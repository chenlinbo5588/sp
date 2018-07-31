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
			$this->jsonOutput2(UNBINDED,$this->unBind);
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
					$this->jsonOutput2($this->form_validation->error_html());
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
					$this->jsonOutput2($this->form_validation->error_html());
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
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	/**
	 * 设置公共验证规则
	 */
	private function _setCommonHouseFeeRules(){
		
		$this->load->model('Order_Type_Model');
		
		$this->form_validation->set_rules('id','物业标识','required|in_db_list['.$this->House_Model->getTableRealName().'.id]');
		$this->form_validation->set_rules('order_typename','in_db_list['.$this->Order_Type_Model->getTableRealName().'.name]');
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
					$this->jsonOutput2($this->form_validation->error_html());
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
					$this->jsonOutput2($this->form_validation->error_html());
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
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
}
