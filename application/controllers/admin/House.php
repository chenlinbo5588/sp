<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class House extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service','Basic_data_service'));
		
		$this->wuye_service->setDataModule($this->_dataModule);
		
		$this->_moduleTitle = '房屋';
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
		
		$search['address'] = $this->input->get_post('address');
		$search['resident_name'] = $this->input->get_post('resident_name');
		$search['yezhu_name'] = $this->input->get_post('yezhu_name');
		$search['mobile'] = $this->input->get_post('mobile');
		
		foreach(array('wuye','nenghao') as $expireName){
			foreach(array('s','e') as $se){
				$tempVal = $this->input->get_post($expireName.'_expire_'.$se);
				if(!empty($tempVal)){
					
					if('s' == $se){
						$condition['where'][$expireName.'_expire >='] = strtotime($tempVal);
					}else{
						$condition['where'][$expireName.'_expire <='] = strtotime($tempVal);
					}
				}
			}
		}
	
		
		if($search['address']){
			$condition['like']['address'] = $search['address'];
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
		
		$this->form_validation->set_rules('building_id','所在建筑物名称','required|in_db_list['.$this->Building_Model->getTableRealName().'.id]');
		$this->form_validation->set_rules('jz_area','建筑面积','required|is_numeric|greater_than[0]');
		
		$this->form_validation->set_rules('longitude','经度','is_numeric');
		$this->form_validation->set_rules('latitude','纬度','is_numeric');
		$this->form_validation->set_rules('mobile[]','手机号码','valid_mobile');
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
			
			$houseList = $this->wuye_service->search($this->_moduleTitle,array(
				'select' => 'id,yezhu_id',
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			),'id');
			
			foreach($houseList as $item){
				if(0 == $item['yezhu_id']){
					$canDeleteIds[] = $item['id'];
				}
			}
			
			if($canDeleteIds){
				$deleteRows = $this->House_Model->deleteByCondition(array(
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
		$redidentId = 0;
		$residentList = $this->wuye_service->getOwnedResidentList(array(
			'select' => 'id,name,address,lng,lat',
			'order' => 'displayorder DESC'
		),'id');
		if(empty($residentList)){
			$residentList = array();
		}
		if(count($residentList)<=1){
			foreach($residentList as $key => $item){
				$redidentId = $item['id'];
			}
			
			$buildingList = $this->Building_Model->getList(array(
				'where' => array(
					'resident_id' => $redidentId
				)
			));
			$this->assign(array(
				'buildingList' => $buildingList
			));
		}
		$this->assign(array(
			'residentList' => $residentList,
			'residentJson' => json_encode($residentList),
			'wuyeTypeList' => $this->basic_data_service->getTopChildList('物业类型'),
		));
		
	}
	
	
	/**
	 * 
	 */
	private function _prepareData(){
		$info['displayorder'] = intval($this->input->post('displayorder'));
		
		
		foreach(array('wuye_expire','nenghao_expire') as $fieldName){
			
			$expire = $this->input->post($fieldName);
		
			if(empty($expire)){
				$info[$fieldName] = 0;
			}else{
				$info[$fieldName] = strtotime($expire);
				
				if($info[$fieldName]){
					$info[$fieldName] = strtotime(date('Y-m',$info[$fieldName]).'  last day of this month');
				}
			}
		}
		
		return $info;
	}
	
	
	/**
	 * @param 自动完成
	 * 获得房屋地址
	 */
	public function getAddress(){
		
		$searchKey = $this->input->get_post('term');
		$residentId = $this->input->get_post('resident_id');
		
		$return = array();
		
		if($searchKey){
			
			$condition = array(
				'like_after' => array(
					'address' => $searchKey
				),
				'limit' => 20
			);
			
			if($residentId){
				$condition['where']['resident_id'] = intval($residentId);
			}
			
			$houseList = $this->wuye_service->search($this->_moduleTitle,$condition);

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
			
			$buildingId = $this->input->get_post('building_id');
			
			$residentList = $this->wuye_service->getOwnedResidentList(array(
				'select' => 'id,name'
			),'id');
			
			
			$this->form_validation->set_rules('resident_id','小区名称','required|in_list['.implode(',',array_keys($residentList)).']',array(
				'in_list' => '小区数据非法'
			));
			$this->form_validation->set_rules('month','起缴月份','required|less_than[12]|greater_than[1]');
			
			//注意验证规则顺序
			
			$this->_getRules();
			$this->_getAddressRule(0,$buildingId);
			$month = $this->input->get_post('month');
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				if(!$this->_validateMobiles($_POST)){
					$this->jsonOutput('同一个业主不能重复出现');
					break;
				}
				
				$buildingInfo = $this->Building_Model->getFirstByKey($_POST['building_id']);
				
				$insertData = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add'));
				
				$insertData['resident_id'] = $buildingInfo['resident_id'];
				$insertData['building_id'] = $buildingInfo['id'];
				
				if(empty($insertData['lng'])){
					$insertData['lng'] = $buildingInfo['lng'];
					$insertData['lat'] = $buildingInfo['lat'];
				}
				if($_POST['mobile'][0]){
					$yezhuInfo = $this->wuye_service->search('业主',array('where' => array('mobile' => $_POST['mobile'][0])));
					$insertData['yezhu_name'] = $yezhuInfo[0]['name'];
					$insertData['yezhu_id'] = $yezhuInfo[0]['id'];
					$insertData['mobile'] = $yezhuInfo[0]['mobile'];
					$insertData['uid'] = $yezhuInfo[0]['uid'];
					$insertData['car_no'] = $yezhuInfo[0]['car_no'];
				}else{
					$insertData['mobile'] = null;
				}
				
				$message = '';
				$insertYezhuList = $this->_checkYezhu($_POST,$message);
				if($message){
					$this->jsonOutput($message);
					break;
				}
				
				$this->House_Model->beginTrans();
				$newid =$this->House_Model->_add($insertData);
				$_POST['id'] = $newid;
				if($insertYezhuList){
					$this->_addHouseYezhu($insertYezhuList,$newid);
				}
				$houseInfo  = $this->House_Model->getById(array(
					'where' => array(
						'id' => $newid
					)
				));
				$this->wuye_service->generationPlan($buildingInfo['resident_id'],$this->addWhoHasOperated('add'),date('Y'),$month,$houseInfo);
				$error = $this->House_Model->getError();
				$message = $this->_getMessage($error);
				if($this->House_Model->getTransStatus() === FALSE){
					$this->House_Model->rollBackTrans();
					
					$this->jsonOutput($message);
				}else{
					$flag = $this->House_Model->commitTrans();
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}				

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
	public function checkAddress($name,$buildingId = 0){
		
		if(empty($buildingId)){
			return true;	
		}
		
		$buildingInfo = $this->Building_Model->getFirstByKey($buildingId,'id','name');
		
		if(!preg_match("/^{$buildingInfo['name']}/is",$name)){
			$this->form_validation->set_message('checkAddress',$this->_moduleTitle.'地址必须包含所在建筑名称并且以所在建筑名称开始');
			return false;
		}
		
		return true;
		
	}
	
	
	
	/**
	 * 获取
	 */
	private function _getAddressRule($id = 0,$building_id = 0){
		if($id){
			$this->form_validation->set_rules('address','房屋地址','required|min_length[2]|max_length[200]|callback_checkAddress['.$building_id.']|is_unique_not_self['.$this->House_Model->getTableRealName().".address.id.{$id}]");
		}else{
			$this->form_validation->set_rules('address','房屋地址','required|min_length[2]|max_length[200]|callback_checkAddress['.$building_id.']|is_unique['.$this->House_Model->getTableRealName().".address]");
		}
		
	}
	
	
	/**
	 * 编辑页面
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$info = $this->House_Model->getFirstByKey($id);
		
		$residentidInfo =  $this->wuye_service->search('房屋',array(
			'select' => 'resident_id', 
			'where' => array(
				'id' => $id
			)
		));
		$residentId = $residentidInfo[0]['resident_id'];
		$yezhuHouseList = $this->wuye_service->search('房屋业主',array(
			'select' => 'yezhu_id', 
			'where' => array(
				'house_id' => $id,
				'resident_id' =>$residentId
			)
		));
		foreach ($yezhuHouseList as $key =>$item){
			$yezhuId[] = $item['yezhu_id'];
		}
		if($yezhuId){
			$yezhuList = $this->wuye_service->search('业主',array(
				'select' =>'name,mobile',
				'where_in' =>array(
					array('key' => 'id' ,'value' => $yezhuId,),
				)
			));
		}
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			$this->_getAddressRule($id,$this->input->post('building_id'));
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$updateInfo = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				
				foreach(array('wuye_expire','nenghao_expire') as $fieldName){
					if($info[$fieldName] > $updateInfo[$fieldName]){
						$this->jsonOutput('缴费日期只能延长,不能回退');
						break;
					}
				}
				if(!$this->_validateMobiles($_POST)){
					$this->jsonOutput('同一个业主不能重复出现');
					break;
				}
				
				
				$_POST['resident_id'] = $residentId;
				$message = '';
				$insertYezhuList = $this->_checkYezhu($_POST,$message);
				if($message){
					$this->jsonOutput($message);
					break;
				}
				
				if($_POST['mobile'][0]){
					$yezhuInfo = $this->wuye_service->search('业主',array('where' => array('mobile' => $_POST['mobile'][0])));
					$updateInfo['yezhu_name'] = $yezhuInfo[0]['name'];
					$updateInfo['yezhu_id'] = $yezhuInfo[0]['id'];
					$updateInfo['mobile'] = $yezhuInfo[0]['mobile'];
					$updateInfo['uid'] = $yezhuInfo[0]['uid'];
					$updateInfo['car_no'] = $yezhuInfo[0]['car_no'];
				}else{
					$updateInfo['mobile'] = '';
				}
				$this->House_Model->beginTrans();

				if($insertYezhuList){
					$this->_addHouseYezhu($insertYezhuList,$id);
				}

				$this->House_Model->update($updateInfo,array('id' => $id));
				
				if($this->House_Model->getTransStatus() === FALSE){
					$this->House_Model->rollBackTrans();
					
					$this->jsonOutput('保存失败');
				}else{
					$flag = $this->House_Model->commitTrans();
					$this->jsonOutput('保存成功');
				}
				
			}
		}else{
			
			$this->assign('info',$info);
			$buildingList = $this->Building_Model->getList(array(
				'where' => array(
					'resident_id' => $info['resident_id']
				)
			));
			$this->assign('yezhuList',$yezhuList);
			$this->assign(array(
				'buildingList' => $buildingList
			));
			
			$this->_preparePageData();
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
			
			$houseInfo = $this->House_Model->getFirstByKey($id);
			
			if(empty($houseInfo)){
				$this->jsonOutput('参数错误');
				break;
			}
			
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[address,displayorder]');
			
			switch($fieldName){
				case 'address':
					$this->_getAddressRule($id,$houseInfo['building_id']);
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
				
				if($this->House_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
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
			$str[] = "<tr class=\"{$line['classname']}\"><td>{$line['building_name']}</td><td>{$line['room_num']}</td><td>{$line['jz_area']}</td><td>{$line['message']}</td></tr>";
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
					
					$allBuildingList = $this->wuye_service->search('建筑物',array(
						'select' => 'id,resident_id,name,lng,lat',
						'where' => array(
							'resident_id' => $residentId
						)
					),'name');
					$feetypeList = $this->wuye_service->search('费用类型',array(),'id');
					
					if(empty($allBuildingList)){
						$feedback = getErrorTip('该小区尚未配置建筑物信息,请先配置建筑物');
	    				break;
					}
					
					
					$result = array();
					$successCnt = 0;
					//$houseList = $this->wuye_service->search('房屋',array(),'id');
					$wuyeTypeList = $this->basic_data_service->getTopChildList('物业类型');
					
					
					//已经插入过
					$insertedHouseList = array();
					
					
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['classname'] = 'failed';
						
						$tmpRow['building_name'] = getCleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue());
						$tmpRow['room_num'] = getCleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue());
						$tmpRow['wuye_type'] = getCleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue());
						$tmpRow['jz_area'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['address'] = $tmpRow['building_name'].$tmpRow['room_num'];
						
						$tmpRow['mobile'] = getCleanValue($objWorksheet->getCell('H'.$rowIndex)->getValue());
						
						//费用到期日期
						$tmpRow['wuye_expire'] = getCleanValue($objWorksheet->getCell('L'.$rowIndex)->getValue());
						$tmpRow['nenghao_expire'] = getCleanValue($objWorksheet->getCell('M'.$rowIndex)->getValue());
						
						$this->form_validation->reset_validation();
						$this->form_validation->set_data($tmpRow);
						
						$this->form_validation->set_rules('building_name','建筑物名称', 'required|in_list['.implode(',',array_keys($allBuildingList)).']',array(
							'in_list' => '该小区没有该建筑物.'
				        ));
				        
				        
						$this->form_validation->set_rules('wuye_type','物业类型','required|in_list['.implode(',',array_keys($wuyeTypeList)).']');
						$this->form_validation->set_rules('address','地址','required');
						$this->form_validation->set_rules('room_num','房间号码','min_length[1]');
						$this->form_validation->set_rules('jz_area','建筑面积','required|is_numeric|greater_than[0]');
						
						
						if(!$this->form_validation->run()){
							
							$tmpRow['message'] = $this->form_validation->error_first_html();
							$result[] = $tmpRow;
							continue;
						}
						
						$insertData = array_merge(array(
							'resident_id' => $residentId,
							'building_id' => $allBuildingList[$tmpRow['building_name']]['id'],
							'address' => $tmpRow['address'],
							'jz_area' => $tmpRow['jz_area'],
							'lng' => $allBuildingList[$tmpRow['building_name']]['lng'],
							'lat' => $allBuildingList[$tmpRow['building_name']]['lat'],
							'wuye_type' => $tmpRow['wuye_type'],
						),$this->addWhoHasOperated('add'));
						
						
						$ts1 = strtotime($tmpRow['wuye_expire']);
						if($ts1){
							$insertData['wuye_expire'] = strtotime(date('Y-m',$ts1).'  last day of this month');
						}
						
						$ts2 = strtotime($tmpRow['nenghao_expire']);
						if($ts2){
							$insertData['nenghao_expire'] = strtotime(date('Y-m',$ts2).'  last day of this month');
						}
						
						$yezhuInfo = array();
						
						if($tmpRow['mobile']){
							//找对应的业主
							$yezhuInfo = $this->Yezhu_Model->getById(array(
								'select' => 'id,name,mobile,uid,car_no',
								'where' => array(
									'resident_id' => $residentId,
									'mobile' => $tmpRow['mobile']
								)
							));
							
							if($yezhuInfo){
								//准备更新数据
								$insertData = array_merge($insertData,array(
									'yezhu_id' => $yezhuInfo['id'],
									'yezhu_name' => $yezhuInfo['name'],
									'mobile' => $yezhuInfo['mobile'],
									'car_no' => $yezhuInfo['car_no'],
									'uid' => $yezhuInfo['uid']
								));
							}
						}
						
						
						$newHouseId = $this->House_Model->_add($insertData);
						
						$error = $this->House_Model->getError();
						
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '数据库错误';
							if($error['code'] == MySQL_Duplicate_CODE){
								$tmpRow['message'] = '房屋已经存在';
								
								if($insertedHouseList[$insertData['address']]){
									$newHouseId = $insertedHouseList[$insertData['address']];
								}
								
							}
						}else{
							$insertedHouseList[$insertData['address']] = $newHouseId;
							
							$tmpRow['message'] = '导入成功';
							$tmpRow['classname'] = 'ok';
							$successCnt++;
						}
						
						if($newHouseId && $yezhuInfo){
							$this->House_Yezhu_Model->_add(array_merge(array(
								'yezhu_id' => $yezhuInfo['id'],
								'house_id' => $newHouseId,
								'resident_id' =>$residentId,
							),$this->addWhoHasOperated('add')));
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
    			
    			$search = $this->input->post(array('resident_id','address','yezhu_name','page'));
    			
    			$condition = array();
    			
    			
    			if($search['resident_id']){
					$condition['where']['resident_id'] = $search['resident_id'];
				}
				
    			if($search['address']){
    				$condition['where']['address'] = $search['address'];
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
    		'A' => array('db_key' => 'address','width' => 30 ,'title' => '地址'),
    		'B' => array('db_key' => 'jz_area','width' => 15 ,'title' => '建筑面积'),
    		'C' => array('db_key' => 'wuye_type','width' => 15 ,'title' => '物业类型'),
    		'D' => array('db_key' => 'yezhu_name','width' => 15 ,'title' => '业主姓名'),
    		'E' => array('db_key' => 'mobile','width' => 25 ,'title' => '联系方式'),
    		'F' => array('db_key' => 'car_no','width' => 15 ,'title' => '车牌号码'),
    		'G' => array('db_key' => 'wuye_expire','width' => 25 ,'title' => '物业费到期时间'),
    		'H' => array('db_key' => 'nenghao_expire','width' => 25 ,'title' => '能耗费到期时间'),	
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
    			if('wuye_expire' == $colItemConfig['db_key'] || 'nenghao_expire' == $colItemConfig['db_key']){
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
	 //未操作house_yezhu表
	public function yezhu_change(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		$info = $this->House_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/yezhu_change?id='.$id, 'title' => '业主变更');
		
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
				$changeStatus = $this->input->get_post('change_status');
				
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
				$updateInfo = array_merge(array(
					'uid' => $yezhuInfo[0]['uid'],
					'yezhu_id' => $yezhuInfo[0]['id'],
					'yezhu_name' => $yezhuInfo[0]['name'],
					'mobile' => $yezhuInfo[0]['mobile'],
					'car_no' =>  $yezhuInfo[0]['car_no'],
				),$this->addWhoHasOperated('edit'));
				
				$parkingUpdateInfo = array_merge(array(
					'uid' => $yezhuInfo[0]['uid'],
					'yezhu_name' => $yezhuInfo[0]['name'],
					'yezhu_id' => $yezhuInfo[0]['id'],
					'mobile' => $yezhuInfo[0]['mobile'],
				),$this->addWhoHasOperated('edit'));
				
				$addInfo = array_merge(array(
					'house_id' => $id,
					'yezhu_id' => $yezhuInfo[0]['id'],
					'resident_id' => $info['resident_id'],
				),$this->addWhoHasOperated('add'));					
				
				$this->House_Model->beginTrans();

				$this->House_Model->update($updateInfo,array('id' => $id));
				
				$this->Parking_Model->update($parkingUpdateInfo,array('house_id' => $id));
				

				if(!$this->House_Yezhu_Model->deleteAndAdd($yezhuInfo[0]['id'],$info['resident_id'],$id,$changeStatus,$this->addWhoHasOperated('add'))){
					$this->House_Yezhu_Model->_add($addInfo);
				}
		    	if($this->House_Model->getTransStatus() === FALSE){
					$this->House_Model->rollBackTrans();
					
					log_message('error','业主添加错误' );
					
				}else{
				 	$this->House_Yezhu_Model->commitTrans();
				 	$this->jsonOutput('更改成功');
				}
			}
		}else{
			$this->assign('info',$info);
			$this->display();
			
		}
		
	}
	public function delete_yezhu(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$canDeleteIds = array();
			
			$houseList = $this->wuye_service->search($this->_moduleTitle,array(
				'select' => 'id,yezhu_id',
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			),'id');
			
			foreach($houseList as $item){
				if(0 != $item['yezhu_id']){
					$canDeleteIds[] = $item['id'];
				}
			}
			
			if($canDeleteIds){
				$deleteRows = $this->House_Yezhu_Model->deleteByCondition(array(
					'where_in' => array(
						array('key' => 'house_id','value' => $canDeleteIds)
					)
				));
				
				$this->House_Model->updateByCondition(
					array(
						'yezhu_id' => 0,
						'yezhu_name' => '',
						'mobile' => '',
						'uid' => 0,
					),
					array('where_in' => array(array('key' => 'id', 'value' => $canDeleteIds)))
				);
				if($deleteRows){
					$this->jsonOutput('成功删除'.$deleteRows.'条记录',array('deletedIds' => $canDeleteIds));
				}else{
					
					$this->jsonOutput('删除操作成功,但无记录被删除');
				}
			}else{
				$this->jsonOutput('该房屋暂时没有业主，暂时不能删除');
			}
			
		}else{
			$this->jsonOutput('请求非法');
			
		}
	}
	
    private function _checkYezhu($data,&$message){

    	if(!empty($data['mobile'][0])){
	    	foreach($data['mobile'] as $key =>$item){
	    		$yezhuInfo = $this->wuye_service->search('业主',array(
					'where' => array(
						'mobile' => $item,
						'resident_id' => $data['resident_id']
					)
				));
				$yezhuList[] = $yezhuInfo[0];;
				if(empty($yezhuList[$key])){
					$message = '抱歉,该小区无此业主信息';

					break;
				}
				
	    	}
	    	return $yezhuList;
    	}
		return false;
    }
    
    private function _addHouseYezhu($yezhuList,$houseId){
    	$this->House_Yezhu_Model->deleteByCondition(array(
			'where' => array(
				'house_id' => $houseId
			)
		));

    	foreach($yezhuList  as $key => $item){
    		$insert = array(
    			'resident_id' => $item['resident_id'],
    			'yezhu_id' => $item['id'],
    			'house_id' => $houseId
    		);
    		$insertData[] = array_merge($insert,$this->_prepareData(),$this->addWhoHasOperated('add'));
    	}
    	
    	$this->House_Yezhu_Model->batchInsert($insertData);

    }
	
	private function _validateMobiles($validateMobile){
		
		$mobileList = array();
		$judgement = true ;
		foreach($validateMobile['mobile'] as $key => $value){
			$onlyMobile = $validateMobile['mobile'][$key];

			if(in_array($onlyMobile,$mobileList)){
				$judgement = false;
				
				break;
			}else{
				$mobileList[] = $onlyMobile;
			}
		}
		return $judgement;
	}
	
	private function _getMessage($error){
		if(QUERY_OK != $error['code']){
			if($error['code'] == MySQL_Duplicate_CODE){
				$message = ($this->input->post('address').'已被占用');
			}else{
				$message = ('数据库错误,请稍后再次尝试');
			}
			
			return $message;
		}
	}
}
