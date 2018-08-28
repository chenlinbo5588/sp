<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Yezhu extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service','Basic_data_service'));
		
		$this->wuye_service->setDataModule($this->_dataModule);
		
		$this->_moduleTitle = '业主';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
			array('url' => $this->_className.'/import','title' => '导入'),
			array('url' => $this->_className.'/export','title' => '导出'),
		);
		
	}
	
	
	/**
	 * 
	 */
	public function index(){
	
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$search['resident_name'] = $this->input->get_post('resident_name');
		$search['name'] = $this->input->get_post('name');
		$search['mobile'] = $this->input->get_post('mobile');
		
		
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		
		if($search['mobile']){
			$condition['like']['mobile'] = $search['mobile'];
		}
		
		if($search['resident_name']){
			$resident_name = $search['resident_name'];
			
			$resident = $this->Resident_Model->getById(array(
				'where' => array(
					'name' => $resident_name
				)
			));
			
			if($resident['id']){
				$condition['where']['resident_id'] = $resident['id'];
			}else{
				$condition['where']['resident_id'] = 0;
			}
		}
		
		$list = $this->wuye_service->search($this->_moduleTitle,$condition);
		
		$this->assign(array(
			'basicData' => $this->basic_data_service->getBasicData(),
			'residentList' => $this->wuye_service->getOwnedResidentList(array(
							'select' => 'id,name',
						),'id'),
			'list' => $list,
			'page' => $list['pager'],
			'currentPage' => $currentPage
			
		));
		
		
		$this->display();
		
	}
	
	
	/**
	 * 删除物业
	 */
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$canDeleteIds = array();
			
			$yezhuList = $this->wuye_service->search($this->_moduleTitle,
				array(
					'select' => 'id,uid',
					'where_in' => array(
					array('key' => 'id', 'value' => $ids)
				)
			),'id');
			
			
			foreach($ids as $id){
				if(0 == $yezhuList[$id]['uid']){
					$canDeleteIds[] = $id;
				}
			}
			
			if($canDeleteIds){
				$deleteRows = $this->Yezhu_Model->deleteByCondition(array(
					'where_in' => array(
						array('key' => 'id','value' => $canDeleteIds)
					)
				));
				
				if($deleteRows){
					$this->jsonOutput('成功删除'.$deleteRows.'条记录',array('deletedIds' => $canDeleteIds));
				}else{
					$this->jsonOutput('删除操作成功,但无记录被删除');
				}
			}else{
				$this->jsonOutput('已绑定用户，暂不能被删除');
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	
	/**
	 * 
	 */
	private function _prepareData(){
		
		
		$data['displayorder'] = intval($this->input->post('displayorder'));
		
		if(empty($data['displayorder'])){
			$data['displayorder'] = 255;
		}
		
		$carNo = array();
		
		for($i = 1; $i <= 3; $i++){
			
			$tmp = trim($this->input->post('car_no'.$i));
			if($tmp){
				$carNo[] = $tmp;
			}
		}
		
		if($carNo){
			$data['car_no'] = implode(',',$carNo);
		}else{
			$data['car_no'] = '';
		}
		
		return $data;
		
	}
	
	
	
	/**
	 * 
	 */
	private function _commonPageData(){
		$basicData = $this->basic_data_service->getAssocBasicDataTree();
		
		$residentList = $this->wuye_service->getOwnedResidentList(array(
			'select' => 'id,name,address,lng,lat',
			'order' => 'displayorder DESC'
		),'id');
		
		
		$this->assign(array(
			'residentList' => $residentList,
			'province_idcard' => json_encode(config_item('province_idcard')),
			'idTypeList' => $this->basic_data_service->getTopChildList('证件类型'),
			'jiguanList' => $this->basic_data_service->getTopChildList('籍贯'),
		));
	}
	
	
	/**
	 * 
	 */
	private function _addYezhuRules($idTypeList,$pIdType,$id = 0){
		
		$residentList = $this->wuye_service->getOwnedResidentList(array(
			'select' => 'id'
		),'id');
		
		$this->form_validation->set_rules('resident_id','所在小区', 'required|in_list['.implode(',',array_keys($residentList)).']');
		
		
		$this->form_validation->set_rules('name','姓名','required|max_length[50]');
		
		$this->wuye_service->addIDRules($idTypeList,$pIdType,$id,false);
		
		$this->form_validation->set_rules('birthday','出生年月','required|valid_date');
		$this->form_validation->set_rules('age','年龄','required|is_natural_no_zero');
		$this->form_validation->set_rules('sex','性别','required|in_list[1,2]');
		
		$this->form_validation->set_rules('jiguan','籍贯',"required|is_natural_no_zero");
		
		
	}
	
	
	/**
	 * 检验手机号码
	 */
	public function checkMobile($pMobile,$pStr = ''){
		list($residentId, $rowId)=explode('.', $pStr);
		
		$rowInfo = $this->Yezhu_Model->getById(array(
			'select' => 'id',
			'where' => array(
				'resident_id' => $residentId,
				'mobile' => $pMobile
			)
		));
		
		
		$flag = false;
		
		if(empty($rowId)){
			if(empty($rowInfo)){
				$flag = true;
			}
		}else{
			//是自己
			if(empty($rowInfo) || $rowInfo['id'] == intval($rowId)){
				$flag = true;
			}
		}
		
		
		if(!$flag){
			$this->form_validation->set_message('checkMobile','当前小区该手机号码业主已存在');
			return false;
		}
		
		return true;
		
	}
	
	/**
	 * 添加校验规则
	 */
	private function _addRules(){
		
		for($i = 1; $i <= 3; $i++){
			
			$tmp = trim($this->input->post('car_no'.$i));
			if($tmp){
				$this->form_validation->set_rules('car_no'.$i,'车牌号'.$i,'required|min_length[5]|max_length[15]');
			}
		}
		
	}
	
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			
			$this->_addYezhuRules($this->basic_data_service->getTopChildList('证件类型'),$this->input->post('id_type'), 0);
			
			$this->_addRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$this->form_validation->reset_validation();
				$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile|callback_checkMobile['.intval($this->input->post('resident_id')).'.0]');
				
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$insertData = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add'));
				
				//自动绑定用户
				$memberInfo = $this->Member_Model->getFirstByKey($insertData['mobile'],'mobile','uid');
				if($memberInfo){
					$insertData['uid'] = $memberInfo['uid'];
				}
				
				$newid =$this->Yezhu_Model->_add($insertData);
				
				$error = $this->Yezhu_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('mobile').'已被占用');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			$this->_commonPageData();
			$this->display();
		}
		
	}
	
	/**
	 * 编辑页面
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$info = $this->Yezhu_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_addYezhuRules($this->basic_data_service->getTopChildList('证件类型'),$this->input->post('id_type'),$info['id']);
			$this->_addRules();
			
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$this->form_validation->reset_validation();
				$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile|callback_checkMobile['.intval($this->input->post('resident_id')).".{$id}]");
				
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$this->Yezhu_Model->beginTrans();
				
				$updateInfo = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				
				if(0 == $updateInfo['uid']){
					//未绑定,自动更新该用户的绑定的uid
					$memberInfo = $this->Member_Model->getFirstByKey($updateInfo['mobile'],'mobile','uid');
					
					//微信已注册
					if($memberInfo){
						$updateInfo['uid'] = $memberInfo['uid'];
					}
				}
				
				$this->Yezhu_Model->update($updateInfo,array('id' => $id));
				
				$error = $this->Yezhu_Model->getError();
				//业主更新手机号码的 话有可能出现 主键冲突
				if(QUERY_OK != $error['code']){
					
					$this->Yezhu_Model->rollBackTrans();
					
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput('该手机号码'.$this->input->post('mobile').'业主已存在');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$relatedUpdate = array(
					'yezhu_name' => $updateInfo['name'],
					'mobile' => $updateInfo['mobile'],
					'car_no' => $updateInfo['car_no']
				);
				
				if($updateInfo['uid']){
					$relatedUpdate['uid'] = $updateInfo['uid'];
				}
				
				//同步更新
				$this->House_Model->updateByWhere($relatedUpdate,array(
					'yezhu_id' => $id,
				));
				
				unset($relatedUpdate['car_no']);
				$this->Parking_Model->updateByWhere($relatedUpdate,array(
					'yezhu_id' => $id,
				));
				
				
				if($this->Yezhu_Model->getTransStatus() === false){
					$this->Yezhu_Model->rollBackTrans();
				}
				
				$flag = $this->Yezhu_Model->commitTrans();
				
				if($flag){
					$this->jsonOutput('保存成功');
				}else{
					$this->jsonOutput('保存失败');
				}
				
			}
		}else{
			
			$this->assign('info',$info);
			
			$this->_commonPageData();
			$this->display($this->_className.'/add');
			
		}
		
	}
	
	
	
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		
		
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[name,displayorder]');
			
			
			switch($fieldName){
				case 'name':
					$this->form_validation->set_rules('name','业主名称','required|min_length[2]|max_length[20]');
					break;
				case 'displayorder';
					$this->form_validation->set_rules('displayorder','排序',"required|is_natural|less_than[256]");
					break;
				default:
					break;
			}
			
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				if($this->Yezhu_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
	}
	
	
	/**
	 * 导入输出
	 */
	private function _importOutput($result){
		
		$str = array();
		foreach($result as $key => $line){
			$str[] = "<tr class=\"{$line['classname']}\"><td>{$line['name']}</td><td>{$line['id_type']}</td><td>{$line['id_no']}</td><td>{$line['mobile']}</td><td>{$line['message']}</td></tr>";
		}
		
		return implode('',$str);
		
	}
	
	
	
	/**
     * 导入excel
     */
    public function import(){
    	$feedback = '';
    	
    	$this->form_validation->set_error_delimiters('','');
    	
    	if($this->isPostRequest()){
       		
    		for($i = 0; $i < 1; $i++){
    			
    			
    			$this->form_validation->reset_validation();
    			
	    		$this->form_validation->set_data(array(
	    			'resident_id' => $this->input->post('resident_id')
	    		));
	    		
	    		$this->form_validation->set_rules('resident_id','所在小区','required|is_natural_no_zero');
	    		
	    		if(!$this->form_validation->run()){
	    			$feedback = getErrorTip($this->form_validation->error_html());
	    			break;
	    		}
	    		
    			
    			if(0 != $_FILES['excelFile']['error']){
    				$feedback = getErrorTip('请上传文件');
	    			break;
	    		}
	    		
	    		
	    		//选中的小区ID
				$residentId = $this->input->post('resident_id');
				
				$tempInfoList = $this->wuye_service->search('小区',array(
					'where' => array(
						'id' => $residentId
					)
				));
				
				if(empty($tempInfoList[0])){
					$feedback = getErrorTip('找不到该小区信息');
	    			break;
				}
				
				$residentInfo = $tempInfoList[0];
	    		
	    		$this->_initPHPExcel();
		        
	    		try {
	    			
	    			$excelFile = $_FILES['excelFile']['tmp_name'];
	    			$objPHPexcel = PHPExcel_IOFactory::load($excelFile);
	    			
					$objWorksheet = $objPHPexcel->getActiveSheet(0); 
					
					$startRow = 2;
					$highestRow = $objWorksheet->getHighestRow();
					
					$importMaxLimit = config_item('excel_import_limit');
					
					if(($highestRow + 1) > $importMaxLimit){
						$highestRow = $importMaxLimit + 1;
					}
					
					
					
					
					$result = array();
					$successCnt = 0;
					
					
					$idTypeList = $this->basic_data_service->getTopChildList('证件类型');
					$jiguanList = $this->basic_data_service->getTopChildList('籍贯');
					
					$provinceIdcard = config_item('province_idcard');
					$currentYear = date('Y');
					
					
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['classname'] = 'failed';
						
						$tmpRow['name'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['id_type'] = getCleanValue($objWorksheet->getCell('E'.$rowIndex)->getValue());
						$tmpRow['id_no'] = getCleanValue($objWorksheet->getCell('F'.$rowIndex)->getValue());
						$tmpRow['mobile'] = getCleanValue($objWorksheet->getCell('G'.$rowIndex)->getValue());
						
						//车牌
						$tmpRow['car_no1'] = getCleanValue($objWorksheet->getCell('H'.$rowIndex)->getValue());
						$tmpRow['car_no2'] = getCleanValue($objWorksheet->getCell('I'.$rowIndex)->getValue());
						$tmpRow['car_no3'] = getCleanValue($objWorksheet->getCell('J'.$rowIndex)->getValue());
						
						$this->form_validation->reset_validation();
						
						if(('身份证' == $tmpRow['id_type'] || '驾驶证' == $tmpRow['id_type']) && strlen($tmpRow['id_no']) >= 15){
							$sex = intval(substr($tmpRow['id_no'],-2,1));
							$tmpRow['sex'] = $sex % 2 == 0 ? '2' : '1';
							
							$birthday = substr($tmpRow['id_no'],6,8);
							$tmpRow['birthday'] = substr($birthday,0,4). '-'.substr($birthday,4,2).'-' .substr($birthday,6,2);
							
							$tmpRow['age'] = $currentYear - intval(substr($birthday,0,4));
							$provinceName = $provinceIdcard[substr($tmpRow['id_no'],0,3)."000"];
							
							$tmpRow['jiguan'] = $provinceName;
						}
						
						$this->form_validation->set_data($tmpRow);
						
						$this->wuye_service->addIDRules($idTypeList,$tmpRow['id_type'],0,false);
						
						$this->form_validation->set_rules('name','姓名','required|max_length[50]');
						$this->form_validation->set_rules('birthday','出生年月','required|valid_date');
						$this->form_validation->set_rules('age','年龄','required|is_natural_no_zero');
						$this->form_validation->set_rules('sex','性别','required|in_list[1,2]');
						$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
						//设置籍贯
						$this->form_validation->set_rules('jiguan','籍贯','required|in_list['.implode(',',array_values($provinceIdcard)).']');
						
						
						if(!$this->form_validation->run()){
							$tmpRow['message'] = $this->form_validation->error_first_html();
							$result[] = $tmpRow;
							continue;
						}
						
						$carNo = array();
						
						for($carIndex = 1; $carIndex <= 5; $carIndex++ ){
							if(!empty($tmpRow['car_no'.$carIndex])){
								$carNo[] = $tmpRow['car_no'.$carIndex];
							}
						}
						
						$insertData = array(
							'resident_id' => $residentInfo['id'],
							'name' => $tmpRow['name'],
							'mobile' => $tmpRow['mobile'],
							'id_type' => $idTypeList[$tmpRow['id_type']]['id'],
							'id_no' => $tmpRow['id_no'],
							'sex' => $tmpRow['sex'],
							'age' => $tmpRow['age'],
							'birthday' => $tmpRow['birthday'],
							'jiguan' => $jiguanList[$provinceName]['id'],
							'car_no' => implode(',',$carNo),
							'car_no1' => $tmpRow['car_no1'],
							'car_no2' => $tmpRow['car_no2'],
							'car_no3' => $tmpRow['car_no3'],
						);
							
						$this->Yezhu_Model->_add(array_merge($insertData,$this->addWhoHasOperated('add')));
						
						$error = $this->Yezhu_Model->getError();
						
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '数据库错误';
							if($error['code'] == MySQL_Duplicate_CODE){
								$tmpRow['message'] = '业主已经存在';
								
								/*
								$affectRow = $this->Yezhu_Model->update(array_merge($insertData,$this->addWhoHasOperated('edit')),array(
									'resident_id' => $residentInfo['id'],
									'mobile' => $insertData['mobile']
								));
								
								if($affectRow){
									$tmpRow['message'] .= ',自动更新记录';
									
									$tmpRow['classname'] = 'ok';
									$successCnt++;
								}
								*/
							}
						}else{
							$tmpRow['message'] = '导入成功';
							$tmpRow['classname'] = 'ok';
							$successCnt++;
						}
						
						$result[] = $tmpRow;
					}
					
					$feedback = getSuccessTip('导入完成,导入'.$successCnt.'条,失败'.(count($result) - $successCnt).'条');
					
	    			$this->assign(array(
						'output' => '<table class="table">'.$this->_importOutput($result).'</table>',
						'successCnt' => $successCnt,
					));
					
	    			@unlink($excelFile);
	    		}catch(Exception $e){
	    			$feedback = '导入错误,请检查文件格式是否正确';
	    		}
    		}
    		
    		
    		$this->assign(array(
    			'feedback' => $feedback,
    		));
    		
    		$this->display('common/import_resp');
    	}else{
    		
    		$residentList = $this->wuye_service->search('小区',array(
				'order' => 'displayorder DESC'
			),'id');
			
    		$this->assign(array(
	    		'residentList' => $residentList
	    	));
    		
	    	$this->display();
    	}
    	
    	
    }
    
    
    
    
    /**
     * 业主数据导出
     */
    public function export(){
    	
    	$message = '';
    	
    	if($this->isPostRequest()){
    		
    		try {
    			
    			$search = $this->input->post(array('resident_id','name','mobile','age_s','age_e','page'));
    			
    			$condition = array();
    			
    			
    			if($search['resident_id']){
    				$condition['where']['resident_id'] = $search['resident_id'];
    			}
    			
    			if($search['name']){
    				$condition['where']['name'] = $search['name'];
    			}
    			
    			if($search['mobile']){
    				$condition['where']['mobile'] = $search['mobile'];
    			}
    			
    			if($search['age_s']){
    				$condition['where']['age >='] = intval($search['age_s']);
    			}
    			
    			if($search['age_e']){
    				$condition['where']['age <='] = intval($search['age_e']);
    			}
    			
    			
    			$search['page'] = intval($search['page']) == 0 ? 1 : intval($search['page']);
    			
    			$dataCnt = $this->wuye_service->getRecordCount($this->_moduleTitle,$condition);
    			
    			
    			$perPageSize = config_item('excel_export_limit');
    			
    			if($dataCnt > $perPageSize){
    				$condition['pager'] = array(
						'page_size' => $perPageSize,
						'current_page' => $search['page'],
						'form_id' => '#formSearch'
	    			);
    			}
    			
    			$this->_doExport($condition);
    		}catch(Exception $e){
    			//出错信息
    			$message = $e->getMessage();
    		}
    		
    	}else{
    		
    		$this->assign(array(
	    		'residentList' => $this->wuye_service->search('小区',array(
    				'select' => 'id,name',
					'order' => 'displayorder DESC'
				),'id')
	    	));
	    	
    		$this->display();
    	}
    	
    }
    
    
    /**
     * 导出数据列
     */
    private function _getExportConfig(){
    	return array(
    		'A' => array('db_key' => 'resident_id','width' => 15 ,'title' => '小区名称'),
    		'B' => array('db_key' => 'name','width' => 15 ,'title' => '姓名'),
    		'C' => array('db_key' => 'id_type','width' => 12 ,'title' => '证件类型'),
    		'D' => array('db_key' => 'id_no','width' => 25 ,'title' => '证件号码'),
    		'E' => array('db_key' => 'mobile','width' => 15 ,'title' => '手机号码'),
    		'F' => array('db_key' => 'sex','width' => 8 ,'title' => '性别'),
    		'G' => array('db_key' => 'age','width' => 8 ,'title' => '年龄'),
    		'H' => array('db_key' => 'birthday','width' => 15 ,'title' => '出生日期'),
    		'I' => array('db_key' => 'jiguan','width' => 20 ,'title' => '籍贯'),
    		'J' => array('db_key' => 'car_no1','width' => 15 ,'title' => '车牌号码1'),
    		'K' => array('db_key' => 'car_no2','width' => 15 ,'title' => '车牌号码2'),
    		'L' => array('db_key' => 'car_no3','width' => 15 ,'title' => '车牌号码3'),
    	);
    	
    }
    
    /**
     * 执行导出动作
     */
    private function _doExport($condition = array()){
    	
    	$this->_initPHPExcel();
    	
        $objPHPExcel = new PHPExcel();

        $residentList =$this->wuye_service->search('小区',array(
        	'select' => 'id,name',
		),'id');
        
        $data = $this->wuye_service->search($this->_moduleTitle,$condition);
    	
    	$colConfig = $this->_getExportConfig();
    	
    	
    	foreach($colConfig as $colKey => $colItemConfig){
    		$objPHPExcel->getActiveSheet()->getCell($colKey.'1')->setValueExplicit($colItemConfig['title'], PHPExcel_Cell_DataType::TYPE_STRING2);
    		$objPHPExcel->getActiveSheet()->getColumnDimension($colKey)->setWidth($colItemConfig['width']);
    	}
    	
    	
    	$colKeys = array_keys($colConfig);
    	
    	$objPHPExcel->getActiveSheet()->getStyle($colKeys[0].'1:'.$colKeys[count($colKeys) - 1].'1')->applyFromArray(
    		array(
                'font'    => array(
                    'bold'      => true,
                    'size'     => 12
                ),

                'fill' => array(
                    'type'       => PHPExcel_Style_Fill::FILL_PATTERN_LIGHTGRAY,
                    'startcolor' => array(
                        'argb' => 'FFC0C0C0'
                    ),
                    'endcolor'   => array(
                        'argb' => 'FFC0C0C0'
                    )
                )
             )
	    );
        
        if($condition['pager']){
        	$list = $data['data'];
        	$objPHPExcel->getActiveSheet()->setTitle('第'.$data['pager']['pageNow'].'页之共'.$data['pager']['pageLastNum'].'页');
        }else{
        	$list = $data;
        }
        
        
        $basicData = $this->basic_data_service->getBasicData();
        
    	foreach($list as $rowId => $yezhu){
    		foreach($colConfig as $colKey => $colItemConfig){
    			
    			$val = $yezhu[$colItemConfig['db_key']];
    			
    			switch($colItemConfig['title']){
    				case '性别':
    					$val = $val == 1 ? '男':'女';
    					break;
    				case '籍贯':
    				case '证件类型':
    					$val = $basicData[$val]['show_name'];
    					break;
    				case '小区名称':
    					$val = $residentList[$yezhu[$colItemConfig['db_key']]]['name'];
    				default:
    					break;
    			}
    			
    			
    			$objPHPExcel->getActiveSheet()->getCell($colKey.($rowId + 2))->setValueExplicit($val, PHPExcel_Cell_DataType::TYPE_STRING2);
    		}
    	}
    	
    	
    	$objPHPExcel->getActiveSheet()->getStyle($colKeys[0].'1:'.$colKeys[count($colKeys) - 1].(count($list) + 1))->applyFromArray(
            array(
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000')
                    )
                )
            )
        );
        
    	$format = $this->input->post('format');
    	
    	$fileRealName = md5(uniqid());
    	$fileExt = '.xlsx';
    	
    	if('Excel2007' == $format){
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    	}else{
    		$fileExt = '.xls';
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	}
    	
    	$downloadName = $this->_moduleTitle.$fileExt;
        $filePath = ROOTPATH.'/temp/'.$fileRealName.$fileExt;
        
        
        $objWriter->save($filePath);
        $objPHPExcel->disconnectWorksheets(); 
        
        unset($objPHPExcel,$objWriter);
        
        force_download($downloadName,  file_get_contents($filePath));
        
    }
    
    
    
    /**
	 * 自动完成接口
	 */
	public function getYezhuInfo(){
		
		$searchKey = $this->input->get_post('term');
		
		$residentId = intval($this->input->get_post('resident_id'));
		
		$return = array();
		
		if($searchKey){
			
			$yezhuList = $this->wuye_service->search('业主',array(
				'select' => 'id,resident_id,name,mobile',
				'where' => array(
					'resident_id' => $residentId
				),
				'like_after' => array(
					'mobile' => $searchKey
				),
				'limit' => 10
			));
			
			/*
			$residentList =$this->wuye_service->search('小区',array(
				'select' => 'id,name'
			),'id');
			*/
			
			foreach($yezhuList as $yuezhuItem ){
				$return[] = array(
					'id' => $yuezhuItem['id'],
					'label' => $yuezhuItem['mobile'].' '.$yuezhuItem['name'],
					'value' => $yuezhuItem['mobile'],
					'name'=> $yuezhuItem['name'],
				);
			}
		}
		
		$this->jsonOutput2('',$return,false);
		
	}
}
