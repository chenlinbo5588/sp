<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wuye extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
    	
    	$this->load->library('Wuye_service');
	}
	
	
	/**
	 * 获得 微信用户关联  业主信息
	 */
	public function getYezhuHouseList(){
		if($this->yezhuInfo){
			
			$d = $this->wuye_service->getYezhuHouseListByYezhu($this->yezhuInfo);
			
			if($d){
				$this->jsonOutput2(RESP_SUCCESS,$d);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}
			
		}else{
			if($this->memberInfo){
				$this->jsonOutput2(NONOWNER);
			}else{
				$this->jsonOutput2(UNBINDED,$this->unBind);
			}
			
		}
	}
	
	/**
	 * 获得房屋详情
	 */
	public function getYezhuHouseDetail(){
		
		if($this->yezhuInfo){
			$id = $this->input->get('id');
			
			for($i = 0; $i < 1; $i++){
				
				$data = array(
					'id' => $id
				);
				
				$this->form_validation->set_data($data);
				$this->form_validation->set_rules('id','物业ID','required|in_db_list['.$this->House_Model->getTableRealName().'.id]');
				
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_first_html());
					break;
				}
				
				$houseInfo = $this->wuye_service->getYezhuHouseDetail($id,$this->yezhuInfo);
	            if($houseInfo){
	            	$this->jsonOutput2(RESP_SUCCESS,$houseInfo);
	            }else{
	            	$this->jsonOutput2(RESP_ERROR);
	            }
			}
			
		}else{
			if($this->memberInfo){
				$this->jsonOutput2(NONOWNER);
			}else{
				$this->jsonOutput2(UNBINDED,$this->unBind);
			}
		}
	
	}
	

	 /**
	  *  发起维修请求
	 */
	public function createRepair(){
		
		if($this->yezhuInfo){
		
			for($i = 0; $i < 1; $i++){
				
				$condition = array(
					'where' => array(
						'yezhu_id' => $this->yezhuInfo['id']
					),
					'order' => 'id DESC',
					'limit' => 1,
					'status !=' => RepairStatus::$deleted
				);
				
      			
      			$lastRepair = $this->Repair_Model->getList($condition);
      			if($lastRepair){
      				if(($this->_reqtime - $lastRepair[0]['gmt_create']) < 300 ){
	      				$this->jsonOutput2("维修订单5分钟只能创建一次，请先等待原报修处理完成");
	      				break;
	      			}
      			}
      			
      			
      			$repairTypeList = RepairType::$typeName;
      			
      			$repair_type = $this->postJson['repairType'];  			
      			$address = $this->postJson['address'];
				$remark = $this->postJson['remark']; 
				$filePaths = $this->postJson['FilePaths']; 
		
      			$this->form_validation->set_data($this->postJson);
      			
      			$this->form_validation->set_rules('repairType','报修类型','required|in_list['.implode(',',array_keys($repairTypeList)).']');
      			
      			$this->form_validation->set_rules('remark','报修描述','required|min_length[2]|max_length[100]');	
      			$this->form_validation->set_rules('address','地址','required|min_length[2]|max_length[100]');
      			
      			
      			if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_first_html());
					break;
				}
				
      			$repairOrder =	array (
      				'repair_type' => $repair_type,
      				'remark'  => $remark,	
      				'address' => $address,
      				'yezhu_id'	=> $this->yezhuInfo['id'],  				
					'yezhu_name'	=> $this->yezhuInfo['name'],
					'mobile'	=> $this->yezhuInfo['mobile'],
					'add_uid'	=>  $this->yezhuInfo['id'],
					'add_username'	=>  $this->yezhuInfo['name'],
      			);
   		
      			$newRepairId = $this->Repair_Model->_add($repairOrder);
      			
      			$batchInsert = array();
      			foreach($filePaths as $key => $imgUrl){
      				$imgArray = getImgPathArray($imgUrl,array('m','b'),'image');
      				$batchInsert[] = array_merge(array('repair_id' => $newRepairId,'uid' => $this->yezhuInfo['id']),$imgArray);
      			}
      			
      			if($batchInsert){
      				$insertRows = $this->Repair_Images_Model->batchInsert($batchInsert);
      			}
      			
				$this->jsonOutput2(RESP_SUCCESS);
			
			}
			
		}else{
			if($this->memberInfo){
				$this->jsonOutput2(NONOWNER);
			}else{
				$this->jsonOutput2(UNBINDED,$this->unBind);
			}
		}
	
	}
	
	/**
	 * 获取维修订单列表
	 */
	public function getRepairList(){
		
		if($this->yezhuInfo){
			
			$page = $this->postJson['page'];
			$statusName = $this->postJson['statusName'];
			
			if(empty($page)){
				$page = 1;
			}
			
			$statusNameList = RepairStatus::$statusName;
			$repairStatus = 0;
			
			if (in_array ($statusName, $statusNameList)) {
				$repairStatus = array_search($statusName,$statusNameList);
			}else{
				$repairStatus = -1;
			}
					
			$condition = array(
				'where' => array(
					'yezhu_id' => $this->yezhuInfo['id'],
					'status' => $repairStatus
				),
				'pager' => array(
					'page_size' => config_item('page_size'),
					'current_page' => $page,
				),
				'order' => 'id DESC'
			);
			
			$repairList = $this->Repair_Model->getList($condition);
			$this->jsonOutput2(RESP_SUCCESS,$repairList);
			
		}else{
			if($this->memberInfo){
				$this->jsonOutput2(NONOWNER);
			}else{
				$this->jsonOutput2(UNBINDED,$this->unBind);
			}
		}
	}
	
	/**
	 * 取消报修
	 */
	public function closeRepair(){
		
		if($this->yezhuInfo){
			
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_data(array(
					'repair_id' => $this->postJson['repair_id']
				));
				
				
				$this->form_validation->set_rules('repair_id','报修','required|in_db_list['.$this->Repair_Model->getTableRealName().'.id]');
		
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_first_html());
					break;
				}
				
				$id = $this->postJson['repair_id'];
				$resp = $this->Repair_Model->updateByWhere(array(
						'status' => RepairStatus::$deleted
				),array('id' => $id));
				
				if(!$resp){
					$this->jsonOutput2("取消失败,请联系物业");
					break;
				}
				
				$this->jsonOutput2(RESP_SUCCESS);
			}
			
		}else{
			if($this->memberInfo){
				$this->jsonOutput2(NONOWNER);
			}else{
				$this->jsonOutput2(UNBINDED,$this->unBind);
			}
		}
	}

	/**
	 * 文件上传
	 */
	public function repairImgUpload(){
		
		$uploadname = 'image';
		
		if($this->yezhuInfo){
			
			$this->load->library('Attachment_service');
			$json = $this->attachment_service->pic_upload($this->yezhuInfo['uid'],$uploadname,1,'repair');
			$json['message'] = $json['msg'];
			
			@unlink($_FILES[$uploadname]['tmp_name']);
			
			$this->jsonOutput2(RESP_SUCCESS,$json);
			
			
		}else{
			if($this->memberInfo){
				$this->jsonOutput2(NONOWNER);
			}else{
				$this->jsonOutput2(UNBINDED,$this->unBind);
			}
		}
		
	}
	/**
	 * 获取业主信息
	 */
	public function getYezhuDetail(){
		
		if($this->yezhuInfo){
			
			$detail =array(
				'mobile'=>$this->yezhuInfo['mobile'],
				'name' =>$this->yezhuInfo['name'],
				'repairTypeList' => RepairType::$typeName
			);
			
			if($detail){
				$this->jsonOutput2(RESP_SUCCESS,$detail);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}
			
		}else{
			if($this->memberInfo){
				$this->jsonOutput2(NONOWNER);
			}else{
				$this->jsonOutput2(UNBINDED,$this->unBind);
			}
		}
		
		
	}
	
	/**
	 * 获取用户信息
	 */
	public function getMemberDetail(){
		
		if($this->memberInfo){
			
			$detail =array(
				'mobile'=>$this->memberInfo['mobile'],
				'name' =>$this->memberInfo['username'],
			);
			
			if($detail){
				$this->jsonOutput2(RESP_SUCCESS,$detail);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
		
	}
	
	
	
	/**
	 * 缴费,物业参数 以及缴费参数
	 */
	public function getYezhuHouseDetailWithFeeInfo(){
		
		if($this->yezhuInfo){
			$id = $this->input->get('id');
			
			for($i = 0; $i < 1; $i++){
				
				$data = array(
					'id' => $id,
					'order_typename' => $this->input->get('order_typename'),
				);
				
				$this->form_validation->set_data($data);
				
				$this->_setCommonHouseFeeRules();
				
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_first_html());
					break;
				}
				
				$houseInfo = $this->wuye_service->getYezhuHouseDetail($id,$this->yezhuInfo);
				if($houseInfo){
					$currentHouseFeeExpire = $this->wuye_service->getCurrentHouseFeeInfo($houseInfo['id'],$data['order_typename']);
					
					//获得小区的费用配置
					$residentFee = $this->wuye_service->getResidentFeeSetting($houseInfo['resident_id'],$currentHouseFeeExpire['year'],$data['order_typename']);
					
					$this->jsonOutput2(RESP_SUCCESS,array(
						'house' => $houseInfo,
						'feeYear' => $currentHouseFeeExpire['year'],
						'minDate' => $currentHouseFeeExpire['newStartTimeStamp'],
						'feeSetting' => $residentFee ? array($residentFee) : array()
					));
				
				}else{
					$this->jsonOutput2(RESP_ERROR);
				}
			}
			
		}else{
			if($this->memberInfo){
				$this->jsonOutput2(NONOWNER);
			}else{
				$this->jsonOutput2(UNBINDED,$this->unBind);
			}
		}
	}
	
	/**
	 * 设置公共验证规则
	 */
	private function _setCommonHouseFeeRules(){
		
		$this->load->model('Order_Type_Model');
		
		$this->form_validation->set_rules('id','物业标识','required|in_db_list['.$this->House_Model->getTableRealName().'.id]');
		$this->form_validation->set_rules('order_typename','订单类型','in_db_list['.$this->Order_Type_Model->getTableRealName().'.name]');
	}
	
	
	/**
	 * 计算费用
	 */
	public function computeHouseFee(){
		
		if($this->yezhuInfo){
			
			
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_data($this->postJson);
				$this->_setCommonHouseFeeRules();
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_first_html());
					break;
				}
				
				
				$houseInfo = $this->wuye_service->getYezhuHouseDetail($this->postJson['id'],$this->yezhuInfo);
				if(empty($houseInfo)){
					$this->jsonOutput2('找不到物业信息');
					break;
				}
				
				//再次验证
				$this->form_validation->reset_validation();
				
				$this->form_validation->set_data($this->postJson);
				
				$currentHouseFeeExpire = $this->wuye_service->getCurrentHouseFeeInfo($houseInfo['id'],$this->postJson['order_typename'],$this->postJson['end_month']);
				
				$this->wuye_service->setFeeTimeRules($currentHouseFeeExpire['year']);
				
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_first_html());
					break;
				}
				
				//在校验  缴费的时间一定要大于已缴费的时间
				if($currentHouseFeeExpire['expireTimeStamp'] >= $currentHouseFeeExpire['newEndTimeStamp']){
					$this->jsonOutput2("请选择合理的缴费时间");
					break;
				}
				
				$amount = $this->wuye_service->computeHouseFee($currentHouseFeeExpire);
				$this->jsonOutput2(RESP_SUCCESS,array('amount' => $amount));
			}
			
		}else{
			if($this->memberInfo){
				$this->jsonOutput2(NONOWNER);
			}else{
				$this->jsonOutput2(UNBINDED,$this->unBind);
			}
		}
	}
	
}
