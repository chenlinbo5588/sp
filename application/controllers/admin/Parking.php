<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Parking extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service','Basic_data_service'));
		
		$this->wuye_service->setDataModule($this->_dataModule);
		
		$this->_moduleTitle = '停车位';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
			array('url' => $this->_className.'/import','title' => '导入'),
			array('url' => $this->_className.'/export','title' => '导出')
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
		
		$search['name'] = $this->input->get_post('name');
		$search['resident_name'] = $this->input->get_post('resident_name');
		$search['yezhu_name'] = $this->input->get_post('yezhu_name');
		$search['mobile'] = $this->input->get_post('mobile');
		$search['expire_s'] = $this->input->get_post('expire_s') ? $this->input->get_post('expire_s') : '';
		$search['expire_e'] = $this->input->get_post('expire_e') ? $this->input->get_post('expire_e') : '';
		
		if($search['expire_s']){
			$condition['where']['expire >='] = strtotime($search['expire_s']);
		}
		if($search['expire_e']){
			$condition['where']['expire <='] = strtotime($search['expire_e'])+86400;
		}
		
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		
		if($search['yezhu_name']){
			$condition['where']['yezhu_name'] = $search['yezhu_name'];
		}
		
		if($search['mobile']){
			$condition['where']['mobile'] = $search['mobile'];
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
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $currentPage
		));
		
		
		$this->display();
		
	}
	
	
	/**
	 * 验证
	 */
	private function _getRules(){
		
		$this->form_validation->set_rules('jz_area','建筑面积','required|is_numeric|greater_than[0]');
	}
	
	
	
	/**
	 * 
	 */
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$canDeleteIds = array();
			
			$parkingList = $this->wuye_service->search($this->_moduleTitle,array(
				'select' => 'id,yezhu_id',
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			),'id');
			
			
			foreach($parkingList as $item){
				if(empty($item['yezhu_id'])){
					$canDeleteIds[] = $item['id'];
				}
			}
			
			if($canDeleteIds){
				$deleteRows = $this->Parking_Model->deleteByCondition(array(
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
				$this->jsonOutput('业主已入驻，暂时不能删除');
			}
			
		}else{
			$this->jsonOutput('请求非法');
			
		}
	}
	
	
	
	/**
	 * 准备页面数据
	 */
	private function _preparePageData(){
		
		$residentList = $this->wuye_service->getOwnedResidentList(array(
			'select' => 'id,name,address,lng,lat',
			'order' => 'displayorder DESC'
		),'id');
		
		
		if(empty($residentList)){
			$residentList = array();
		}
		
		
		$this->assign(array(
			'residentList' => $residentList,
			'parkingTypeList' => $this->basic_data_service->getTopChildList('车位类型'),
		));
		
	}
	
	

	
	
	/**
	 * @param 自动完成
	 * 获得房屋地址
	 */
	public function getName(){
		
		$searchKey = $this->input->get_post('term');
		
		$return = array();
		
		if($searchKey){
			
			$houseList = $this->wuye_service->search($this->_moduleTitle,array(
				'like_after' => array(
					'name' => $searchKey
				),
				'limit' => 50
			));

			foreach($houseList as $houseItem ){
				
				$return[] = array(
					'id' => $houseItem['id'],
					'label' => $houseItem['address'],
					'value' => $houseItem['address'],
					'name'=> $houseItem['yezhu_name'],
					'mobile'=> $houseItem['mobile'],
				);
			}
		}
		
		$this->jsonOutput2('',$return,false);
		
	}
	
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		if($this->isPostRequest()){
			
			$residentId = $this->input->get_post('resident_id');
			
			$residentList = $this->wuye_service->getOwnedResidentList(array(
				'select' => 'id,name,lng,lat'
			),'id');
			
			$this->form_validation->set_rules('resident_id','小区名称','required|in_list['.implode(',',array_keys($residentList)).']',array(
				'in_list' => '小区数据非法'
			));
			$this->form_validation->set_rules('expire','车位费开始时间','required');
			$this->form_validation->set_rules('parking_type','车位类型','required');
			
			//注意验证规则顺序
			$this->_getRules();
			
			$this->_getNameRule(0,$residentId);
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}

				$insertData = array_merge($_POST,$this->addWhoHasOperated('add'));
				
				$houseInfo = $this->House_Model->getById(array(
					'select' => 'id,yezhu_id,yezhu_name,uid,mobile',
					'where' => array(
						'address' =>$_POST['address'],
						'resident_id' => $residentId
					)
				));
				
				if(empty($houseInfo)){
					$this->jsonOutput('物业地址不存在');
					break;
				}
				
				$yezhuList = $this->wuye_service->search('房屋',array(
					'select' => 'id,yezhu_id,yezhu_name,uid,mobile',
					'where' => array(
						'address' => $insertData['address'],
					)
				));
				
				
				if(!empty($yezhuList)){
					$insertData['yezhu_id'] = $yezhuList[0]['yezhu_id'];
					$insertData['yezhu_name'] = $yezhuList[0]['yezhu_name'];
					$insertData['mobile'] = $yezhuList[0]['mobile'];
					$insertData['uid'] = $yezhuList[0]['uid'];
				}
	
				$insertData['resident_id'] = $residentId;
				$insertData['lng'] = $residentList[$residentId]['lng'];
				$insertData['lat'] = $residentList[$residentId]['lat'];
				$insertData['house_id'] = $houseInfo['id'];
				$insertData['expire'] = strtotime($insertData['expire']);
				$this->Plan_Model->beginTrans();
				$newid =$this->Parking_Model->_add($insertData);
				$who = $this->addWhoHasOperated('add');
				$year = date('Y',time())+1;
				$info = $this->wuye_service->search('收费计划',array(
					'where' => array(
						'house_id' => $houseInfo['id'],
						'year' => $year
					)
				));
				if($info){
					$this->wuye_service->creatParkingPlan($residentId,$insertData,$newid,$who);
				}
				if($this->Plan_Model->getTransStatus() === FALSE){
					$this->Plan_Model->rollBackTrans();
					
					$this->jsonOutput('保存失败');
				}else{
					$flag = $this->Plan_Model->commitTrans();
					$this->jsonOutput('保存成功');
				}
				
				$error = $this->Parking_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('name').'已被占用');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			$this->_preparePageData();
			$this->display();
		}
		
	}
	
	
	
	/**
	 * 检查数据合法性
	 * 检查建筑物名称开头文字必须以小区名称开头
	 */
	public function checkName($name,$residentId = 0){
		
		if(empty($residentId)){
			return true;
		}
		
		$residentInfo = $this->Resident_Model->getFirstByKey($residentId,'id','name');
		
		if(!preg_match("/^{$residentInfo['name']}/is",$name)){
			$this->form_validation->set_message('checkName',$this->_moduleTitle.'名称必须包含小区名称并且以小区名称开始');
			return false;
		}
		
		if($residentInfo['name'] == $name){
			$this->form_validation->set_message('checkName',$this->_moduleTitle.'名称不能和小区名称相同');
			return false;
		}
		
		return true;
		
	}
	
	
	/**
	 * 导入校验规则
	 */
	public function checkName2($name,$residentName){
		
		if(!preg_match("/^{$residentName}/is",$name)){
			$this->form_validation->set_message('checkName2',$this->_moduleTitle.'名称必须包含小区名称并且以小区名称开始');
			return false;
		}
		
		if($residentName == $name){
			$this->form_validation->set_message('checkName2',$this->_moduleTitle.'名称不能和小区名称相同');
			return false;
		}
		
		
		return true;
	}
	
	
	
	
	/**
	 * 获取
	 */
	private function _getNameRule($id = 0,$residentId = 0){
		if($id){
			$this->form_validation->set_rules('name','车位名称','required|min_length[2]|max_length[200]|callback_checkName['.$residentId.']|is_unique_not_self['.$this->Parking_Model->getTableRealName().".name.id.{$id}]");
		}else{
			$this->form_validation->set_rules('name','车位名称','required|min_length[2]|max_length[200]|callback_checkName['.$residentId.']|is_unique['.$this->Parking_Model->getTableRealName().".name]");
		}
		
	}
	
	
	/**
	 * 编辑页面
	 */
	public function edit(){
		
		$feedback = '';
		
		$id = $this->input->get_post('id');
		
		$info = $this->Parking_Model->getFirstByKey($id);
		$oldHouseId = $info['house_id'];
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			
			
			$this->_getRules();
			$this->form_validation->set_rules('parking_type','车位类型','required');
			$this->_getNameRule($id,$info['resident_id']);
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$updateInfo = array_merge($info,$_POST,$this->addWhoHasOperated('edit'));


				$yezhuList = $this->wuye_service->search('房屋',array(
					'select' => 'id,yezhu_id,yezhu_name,uid,mobile',
					'where' => array(
						'address' => $updateInfo['address'],
						'resident_id' => $info['resident_id'],
					)
				));
				if(empty($yezhuList[0])){
					$this->jsonOutput('物业地址不存在');
					break;
				}
				$newHouseId = $yezhuList[0]['id'];
				$updateInfo['house_id'] = $yezhuList[0]['id'];
				$updateInfo['yezhu_id'] = $yezhuList[0]['yezhu_id'];
				$updateInfo['yezhu_name'] = $yezhuList[0]['yezhu_name'];
				$updateInfo['mobile'] = $yezhuList[0]['mobile'];
				$updateInfo['uid'] = $yezhuList[0]['uid'];
				$who = $this->addWhoHasOperated('add');
				$this->Plan_Model->beginTrans();
				$this->Parking_Model->update($updateInfo,array('id' => $id));
				$this->wuye_service->transferAssets($oldHouseId,$newHouseId,$id,$who);
				if($this->Plan_Model->getTransStatus() === FALSE){
					$this->Plan_Model->rollBackTrans();
					
					$this->jsonOutput('保存失败');
				}else{
					$flag = $this->Plan_Model->commitTrans();
					$this->jsonOutput('保存成功');
				}
			}
		}else{
			
			$this->_preparePageData();
			
			$this->assign('info',$info);
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
			
			$parkingInfo = $this->Parking_Model->getFirstByKey($id);
			
			if(empty($parkingInfo)){
				$this->jsonOutput('参数错误');
				break;
			}
			
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[name,displayorder]');
			
			switch($fieldName){
				case 'name':
					$this->_getNameRule($id,$parkingInfo['resident_id']);
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
				
				if($this->Parking_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
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
			$str[] = "<tr class=\"{$line['classname']}\"><td>{$line['name']}</td><td>{$line['jz_area']}</td><td>{$line['message']}</td></tr>";
		}
		
		return implode('',$str);
	}
	
	
	/**
     * 导入excel
     */
    public function import(){
    	$feedback = '';
    	
    	$this->form_validation->set_error_delimiters('','');
    	
    	$residentList = $this->Resident_Model->getList(array(
			'order' => 'displayorder DESC'
		),'id');
		
    
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
	    		
    			if(0 != $_FILES['excelFile']['error']){
    				$feedback = getErrorTip('请上传文件');
	    			break;
	    		}
	    		
	    		
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
					$parkingTypeList = $this->basic_data_service->getTopChildList('车位类型'); 
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['classname'] = 'failed';
						
						$tmpRow['name'] = getCleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue());
						$tmpRow['parking_type'] = getCleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue());
						$tmpRow['address'] = getCleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue());
						$tmpRow['jz_area'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['mobile'] = getCleanValue($objWorksheet->getCell('H'.$rowIndex)->getValue());
							
						$this->form_validation->reset_validation();
						$this->form_validation->set_data($tmpRow);
						if(empty($tmpRow['parking_type'])){
							$tmpRow['parking_type'] = '普通车位';
						}
						$this->form_validation->set_rules('name','车位名称','required|min_length[2]|max_length[200]|callback_checkName2['.$residentInfo['name'].']');
						$this->form_validation->set_rules('jz_area','建筑面积','required|is_numeric|greater_than[0]');
						$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
						$this->form_validation->set_rules('parking_type','车位类型','required|in_list['.implode(',',array_keys($parkingTypeList)).']');
						if(!$this->form_validation->run()){
							//print_r($this->form_validation->error_array());
							$tmpRow['message'] = $this->form_validation->error_first_html();
							$result[] = $tmpRow;
							continue;
						}
						
						$insertData = array_merge(array(
							'resident_id' => $residentId,
							'parking_type' => $tmpRow['parking_type'],
							'name' => $tmpRow['name'],
							'address' => $tmpRow['address'],
							'mobile' => $tmpRow['mobile'],
							'jz_area' => $tmpRow['jz_area'],
							'lng' => $residentInfo['lng'],
							'lat' => $residentInfo['lat'],
						),$this->addWhoHasOperated('add'));
						
						
						$houseInfo = $this->House_Model->getById(array(
							'select' => 'id,wuye_expire',
							'where' => array(
								'address' =>$tmpRow['address'],
								'resident_id' => $residentId
							)
						));
						
						if($houseInfo){
							$insertData['house_id'] = $houseInfo['id'];
							$insertData['expire'] = $houseInfo['wuye_expire'];
						}
						
						$yezhuInfo = $this->Yezhu_Model->getById(array(
							'where' => array(
								'resident_id' => $residentId,
								'mobile' => $tmpRow['mobile']
							)
						));
						
						if($yezhuInfo){
							$insertData['yezhu_id'] = $yezhuInfo['id'];
							$insertData['yezhu_name'] = $yezhuInfo['name'];
							$insertData['uid'] = $yezhuInfo['uid'];
						}
						
						/*
						if(empty($insertData['uid'])){
							$memberInfo = $this->Member_Model->getFirstByKey($tmpRow['mobile'],'mobile','uid');
							
							if($memberInfo){
								$insertData['uid'] = $memberInfo['uid'];
							}
						}
						*/
						
						
						$this->Parking_Model->_add($insertData);
						
						$error = $this->Parking_Model->getError();
						
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '数据库错误';
							if($error['code'] == MySQL_Duplicate_CODE){
								$tmpRow['message'] = '车位已经存在';
							}
						}else{
							$tmpRow['message'] = '导入成功';
							$tmpRow['classname'] = 'ok';
							$successCnt++;
						}
						
						$result[] = $tmpRow;
					}
					
					$feedback = getSuccessTip('导入完成,成功'.$successCnt.'条,失败'.(count($result) - $successCnt).'条');
					
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
     * 导出exl表
     */
   public function export(){
    	
    	$message = '';
    	
    	if($this->isPostRequest()){
    		
    		try {
    			
    			$search = $this->input->post(array('resident_id','name','yezhu_name','page'));
    			
    			$condition = array();
    			
    			
    			if($search['resident_id']){
					$condition['where']['resident_id'] = $search['resident_id'];
				}
					
    			if($search['name']){
    				$condition['where']['name'] = $search['name'];
    			}
    			
    			if($search['yezhu_name']){
    				$condition['where']['yezhu_name'] = $search['yezhu_name'];
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
     * 导出数据列
     */
    private function _getExportConfig(){
    	return array(
    		'A' => array('db_key' => 'name','width' => 30 ,'title' => '车位名称'),
    		'B' => array('db_key' => 'address','width' => 30 ,'title' => '房屋地址'),
    		'C' => array('db_key' => 'jz_area','width' => 15 ,'title' => '建筑面积'),
    		'D' => array('db_key' => 'yezhu_name','width' => 15 ,'title' => '业主姓名'),
    		'E' => array('db_key' => 'mobile','width' => 25 ,'title' => '手机号码'),
    		'F' => array('db_key' => 'expire','width' => 25 ,'title' => '车位费到期时间'),
    	);
    	
    }
    
    /**
     * 执行导出动作
     */
    private function _doExport($condition = array()){
    	
    	$this->_initPHPExcel();
    	
        $objPHPExcel = new PHPExcel();
          
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
              
    	foreach($list as $rowId => $house){
    		foreach($colConfig as $colKey => $colItemConfig){
    			$val = $house[$colItemConfig['db_key']];
    			if('expire' == $colItemConfig['db_key']){
    				if(!empty($val)){
						$val = date('Y-m-d',$val);
					}else{
						$val = '无缴费记录';
					}
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
	 * 业主变更
	 */
	public function yezhu_change(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		$info = $this->Parking_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/yezhu_change?id='.$id, 'title' => '业主变更');
		
		
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				
				
				$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
				
				
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				
				$mobile = $this->input->post('mobile');
				
				$yezhuInfo = $this->wuye_service->search('业主',array(
					'where' => array(
						'resident_id' => $info['resident_id'],
						'mobile' => $mobile
					)
				));
				
				if(empty($yezhuInfo[0])){
					$this->jsonOutput('抱歉,该小区无此业主信息');
					break;
				}
				
				$updateData = array_merge(array(
					'yezhu_id' => $yezhuInfo[0]['id'],
					'yezhu_name' => $yezhuInfo[0]['name'],
					'mobile' => $yezhuInfo[0]['mobile'],
					'uid' => $yezhuInfo[0]['uid'],
				),$this->addWhoHasOperated('edit'));
				
				
				$this->Parking_Model->update($updateData,array('id' => $id));
				
				$error = $this->Parking_Model->getError();
			
				if(QUERY_OK != $error['code']){
					$this->jsonOutput('数据库错误,请稍后再次尝试');
					break;
				}
				
				$this->jsonOutput('操作成功');
			}
		}else{
			$this->assign('info',$info);
			$this->display();
			
		}
		
	}
	
	
}
