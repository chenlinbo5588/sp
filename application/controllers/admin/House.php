<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class House extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service'));
		
		$this->_moduleTitle = '房屋';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
			array('url' => $this->_className.'/import','title' => $this->_moduleTitle.'导入'),
			array('url' => $this->_className.'/yezhu_import','title' => '业主导入'),
		);
		
		
		$this->form_validation->set_error_delimiters('<div>','</div>');
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
		$search['address'] = $this->input->get_post('address');
		
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		
		if($search['address']){
			$condition['like']['address'] = $search['address'];
		}
		
		
		$list = $this->House_Model->getList($condition);
		
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'currentPage' => $currentPage
		));
		
		
		$this->display();
		
	}
	
	
	/**
	 * 验证
	 */
	private function _getRules(){
		$this->form_validation->set_rules('resident_id','小区名称','required|in_db_list['.$this->Resident_Model->getTableRealName().'.id]');
		$this->form_validation->set_rules('address','房屋地址','required|min_length[2]|max_length[200]');
		
		$this->form_validation->set_rules('longitude','经度','is_numeric');
		$this->form_validation->set_rules('latitude','纬度','is_numeric');
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
			
			//@todo 
			
			$this->jsonOutput('删除失败，待开发完善',$this->getFormHash());
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	
	/**
	 * 准备页面数据
	 */
	private function _preparePageData(){
		
		$residentList = $this->Resident_Model->getList(array(
			'select' => 'id,name,address,lng,lat',
			'order' => 'displayorder DESC'
		),'id');
		
		$this->assign(array(
			'residentList' => $residentList,
			'residentJson' => json_encode($residentList)
		));
		
		/*
		$buildingList = $this->Building_Model->getList(array(
			'select' => 'id,name,address,lng,lat',
			'order' => 'displayorder DESC'
		),'id');
		
		$this->assign(array(
			'buildingList' => $buildingList,
			'buildingJson' => json_encode($buildingList)
		));
		*/
		
	}
	
	
	/**
	 * 
	 */
	private function _prepareData(){
		$data['nickname'] = $this->input->post('nickname');
		$data['unit_num'] = $this->input->post('unit_num');
		$data['max_plies'] = $this->input->post('max_plies');
		$data['floor_plies'] = $this->input->post('floor_plies');
		$data['total_num'] = $this->input->post('total_num');
		$data['yezhu_num'] = $this->input->post('yezhu_num');
		$data['displayorder'] = $this->input->post('displayorder');
		
		return array(
			'nickname' => $data['nickname'] ? $data['nickname'] : '',
			'unit_num' => $data['unit_num'] ? $data['unit_num'] : 0,
			'max_plies' => $data['max_plies'] ? $data['max_plies'] : 1,
			'floor_plies' => $data['unit_num'] ? $data['floor_plies'] : 0,
			'total_num' => $data['total_num'] ? $data['total_num'] : 0,
			'yezhu_num' => $data['yezhu_num'] ? $data['yezhu_num'] : 0,
			'displayorder' => $data['displayorder'] ? $data['displayorder'] : 255
		);
	}
	
	
	public function getAddress(){
		
		$searchKey = $this->input->get_post('term');
		
		$return = array();
		
		if($searchKey){
			$houseList = $this->House_Model->getList(array(
				'like' => array(
					'address' => $searchKey
				),
				'limit' => 50
			));

			
			$yezhuIds = array();
			$houseAssocList = array();
			
			foreach($houseList as $houseItem ){
				
				if($houseItem['yezhu_id']){
					$yezhuIds[] = $houseItem['yezhu_id'];
				}

				if(isset($houseAssocList[$houseItem['yezhu_id']])){
					$houseAssocList[$houseItem['yezhu_id']][] = $houseItem;
				}else{
					$houseAssocList[$houseItem['yezhu_id']] = array();
					$houseAssocList[$houseItem['yezhu_id']][] = $houseItem;
				}
				
				/*
				$return[] = array(
					'id' => $houseItem['id'],
					'label' => $houseItem['address'],
					'value' => $houseItem['address'],
					'name'=>$yezhu['name'],
					'mobile'=>$yezhu['mobile']
				);
				*/
			}
			
			
			
			if($yezhuIds){
				$yezhuList = $this->Yezhu_Model->getList(array(
					'select' => 'id,name,mobile',
					'where_in' => array(
						array('key' => 'id','value' => $yezhuIds)
					)
				),'id');
			}
			
			foreach($houseAssocList as $yezhuKey => $houseSubItems){
				foreach($houseSubItems as $subItem){
					$tempYezhuInfo = $yezhuList[$yezhuKey];
					$return[] = array(
						'id' => $houseItem['id'],
						'label' => $houseItem['address'],
						'value' => $houseItem['address'],
						'name'=> empty($tempYezhuInfo) == true ? '' : $yezhuList[$yezhuKey]['name'],
						'mobile'=> empty($tempYezhuInfo) == true ? '' : $yezhuList[$yezhuKey]['mobile'],
					);
				}
			}
			
		}
		
		$this->jsonOutput2('',$return,false);
		
	}
	
	
	/**
	 * @todo 待完善
	 */
	public function add(){
		$feedback = '';
		//print_r($_FILES);
		
		$buildingId = $this->input->get_post('building_id');
		
		if($this->isPostRequest()){
			
			$this->_getAddressRule();
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$newid =$this->House_Model->_add(array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add')));
				$error = $this->House_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('address').'已被占用');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/edit?id='.$newid)));
			}
		}else{
			
			if($buildingId){
				$this->assign('info', array('building_id' => $buildingId));
			}
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
	private function _getAddressRule($id = 0){
		if($id){
			$builingInfo = $this->House_Model->getFirstByKey($id,'id','building_id');
			
			$this->form_validation->set_rules('address','房屋地址','required|min_length[2]|max_length[200]|callback_checkAddress['.$builingInfo['building_id'].']|is_unique_not_self['.$this->House_Model->getTableRealName().".address.id.{$id}]");
		}else{
			$this->form_validation->set_rules('address','房屋地址','required|min_length[2]|max_length[200]|callback_checkAddress|is_unique['.$this->House_Model->getTableRealName().".address]");
		}
		
	}
	
	
	/**
	 * 编辑页面
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		$residentId = $this->input->get_post('resident_id');
		
		$info = $this->House_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getAddressRule($id);
			$this->_getRules();
			for($i = 0; $i < 1; $i++){
				$info = array_merge($info,$_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$this->House_Model->update(array_merge($info,$_POST,$this->addWhoHasOperated('edit')),array('id' => $id));
				$error = $this->House_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('address').'已存在');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
		}else{
			
			$this->assign('info',$info);
			
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
		
		$this->form_validation->set_error_delimiters('','');
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[address,displayorder]');
			
			
			switch($fieldName){
				case 'address':
					$this->_getAddressRule($id);
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
    			
    			if(0 != $_FILES['excelFile']['error']){
    				$feedback = getErrorTip('请上传文件');
	    			break;
	    		}
	    		
	    		$this->form_validation->reset_validation();
	    		$this->form_validation->set_rules('resident_id','所在小区','required|is_natural_no_zero');
	    		
	    		if(!$this->form_validation->run()){
	    			$feedback = getErrorTip($this->form_validation->error_html());
	    			break;
	    		}
	    		
	    		require_once PHPExcel_PATH.'PHPExcel.php';
	    		
	    		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
		        $cacheSettings = array( 'dir'  => ROOTPATH.'/temp' );
		        PHPExcel_Settings::setLocale('zh_CN');
		        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		        
		        
	    		try {
	    			
	    			$excelFile = $_FILES['excelFile']['tmp_name'];
	    			$objPHPexcel = PHPExcel_IOFactory::load($excelFile);
					$objWorksheet = $objPHPexcel->getActiveSheet(0); 
					
					$startRow = 2;
					
					$highestRow = $objWorksheet->getHighestRow();
					
					if($highestRow > 1000){
						$highestRow = 1000;
					}
					
					//选中的小区ID
					$residentId = $this->input->post('resident_id');
					$allHouseList = $this->Building_Model->getList(array(
						'select' => 'id,resident_id,name,lng,lat',
						'where' => array(
							'resident_id' => $residentId
						)
					),'name');
					
					
					$result = array();
					$successCnt = 0;
					
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['building_name'] = getCleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue());
						$tmpRow['room_num'] = getCleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue());
						$tmpRow['jz_area'] = getCleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue());
						$tmpRow['address'] = $tmpRow['building_name'].$tmpRow['room_num'];
						
						$this->form_validation->reset_validation();
						$this->form_validation->set_data($tmpRow);
						
						if(!empty($allHouseList)){
							$this->form_validation->set_rules('building_name','建筑物名称', 'required|in_list['.implode(',',array_keys($allHouseList)).']');
						}else{
							$this->form_validation->set_rules('building_name','建筑物名称','required');
						}
						
						
						$this->form_validation->set_rules('address','地址','required');
						$this->form_validation->set_rules('room_num','房间号码','min_length[1]');
						$this->form_validation->set_rules('jz_area','建筑面积','required|is_numeric');
						
						if(!$this->form_validation->run()){
							//print_r($this->form_validation->error_array());
							
							if('development' == ENVIRONMENT){
								$tmpRow['message'] = $this->form_validation->error_html();
							}else{
								$tmpRow['message'] = '数据校验失败';
							}
							
							$result[] = $tmpRow;
							continue;
						}
						
						
						$insertData = array_merge(array(
							'resident_id' => $allHouseList[$tmpRow['building_name']]['resident_id'],
							'building_id' => $allHouseList[$tmpRow['building_name']]['id'],
							'address' => $tmpRow['address'],
							'jz_area' => $tmpRow['jz_area'],
							'lng' => $allHouseList[$tmpRow['building_name']]['lng'],
							'lat' => $allHouseList[$tmpRow['building_name']]['lat'],
						),$this->addWhoHasOperated('add'));
						
						
						
						$this->House_Model->_add($insertData);
						
						$error = $this->House_Model->getError();
						
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '数据库错误';
							if($error['code'] == MySQL_Duplicate_CODE){
								$tmpRow['message'] = '房屋已经存在';
							}
						}else{
							$tmpRow['message'] = '导入成功';
							$tmpRow['classname'] = 'successText';
							$successCnt++;
						}
						
						$result[] = $tmpRow;
					}
					
					$feedback = getSuccessTip('导入完成');
					
					//print_r($result);
	    			$this->assign(array(
						'result' => $result,
						'successCnt' => $successCnt,
						
					));
					
	    			@unlink($excelFile);
	    		}catch(Exception $e){
	    			$feedback = '导入错误,请检查文件格式是否正确';
	    		}
    		}
    	}
    	
    	$this->assign(array(
    		'feedback' => $feedback,
    		'residentList' => $residentList
    	));
    	
    	$this->display();
    	
    }
    
    
    
    
    /**
     * 导入excel
     */
    public function yezhu_import(){
    	$feedback = '';
    	
    	
    	$this->form_validation->set_error_delimiters('','');
    	
    
    	if($this->isPostRequest()){
       		
    		for($i = 0; $i < 1; $i++){
    			
    			if(0 != $_FILES['excelFile']['error']){
    				$feedback = getErrorTip('请上传文件');
	    			break;
	    		}
	    		
	    		
	    		require_once PHPExcel_PATH.'PHPExcel.php';
	    		
	    		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
		        $cacheSettings = array( 'dir'  => ROOTPATH.'/temp' );
		        PHPExcel_Settings::setLocale('zh_CN');
		        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		        
		        $this->load->library(array('Basic_data_service'));
		        
	    		try {
	    			
	    			$excelFile = $_FILES['excelFile']['tmp_name'];
	    			$objPHPexcel = PHPExcel_IOFactory::load($excelFile);
					$objWorksheet = $objPHPexcel->getActiveSheet(0); 
					
					$startRow = 2;
					
					$highestRow = $objWorksheet->getHighestRow();
					
					if($highestRow > 1000){
						$highestRow = 1000;
					}
					
					$result = array();
					$successCnt = 0;
					
					$idTypeList = $this->basic_data_service->getTopChildList('证件类型');
					
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['address'] = getCleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue());
						$tmpRow['yezhu_name'] = getCleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue());
						$tmpRow['id_type'] = getCleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue());
						$tmpRow['id_no'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['mobile'] = getCleanValue($objWorksheet->getCell('E'.$rowIndex)->getValue());
						$tmpRow['telephone'] = getCleanValue($objWorksheet->getCell('F'.$rowIndex)->getValue());
						
						$this->form_validation->reset_validation();
						$this->form_validation->set_data($tmpRow);
						
						
						$this->form_validation->set_rules('address','地址','required|in_db_list['.$this->House_Model->getTableRealName().'.address]');
						$this->form_validation->set_rules('yezhu_name','业主姓名','required');
						$this->form_validation->set_rules('id_type','证件类型','required|in_list['.implode(',',array_keys($idTypeList)).']');
						$this->form_validation->set_rules('id_no','证件号码','required');
						$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
						$this->form_validation->set_rules('telephone','固定电话','valid_telephone');
						
						
						if(!$this->form_validation->run()){
							//print_r($this->form_validation->error_array());
							$tmpRow['message'] = $this->form_validation->error_html();
							$result[] = $tmpRow;
							continue;
						}
						
						
						$yezhuInfo = $this->Yezhu_Model->getFirstByKey($tmpRow['id_no'],'id_no','id,mobile');
						if(empty($yezhuInfo)){
							$tmpRow['message'] = '无此证件对应的业主';
							$result[] = $tmpRow;
							continue;
						}
						
						$updateData = array_merge(array(
							'yezhu_id' => $yezhuInfo['id'],
							'mobile' => $tmpRow['mobile'],
							'telephone' => $tmpRow['telephone']
						),$this->addWhoHasOperated('edit'));
						
						
						$this->House_Model->updateByWhere($updateData,array(
							'address' => $tmpRow['address']
						));
						
						$error = $this->House_Model->getError();
						
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '导入失败';
						}else{
							$tmpRow['message'] = '导入成功';
							$tmpRow['classname'] = 'successText';
							$successCnt++;
						}
						
						$result[] = $tmpRow;
					}
					
					$feedback = getSuccessTip('导入完成');
					
					//print_r($result);
	    			$this->assign(array(
						'result' => $result,
						'successCnt' => $successCnt,
						
					));
					
	    			@unlink($excelFile);
	    		}catch(Exception $e){
	    			$feedback = '导入错误,请检查文件格式是否正确';
	    		}
    		}
    	}
    	
    	$this->assign(array(
    		'feedback' => $feedback
    	));
    	
    	$this->display();
    	
    }
}
