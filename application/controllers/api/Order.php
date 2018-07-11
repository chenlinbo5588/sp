<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
        
        
        $this->load->library('Order_service');
        
    	$this->form_validation->set_error_delimiters('','');
	}
	
	
	/**
	 * 创建订单
	 */
	public function createStaffOrder(){
		
		if($this->memberInfo){
			
			
		}
	}
	
	
	/**
	 * 
	 */
	public function createWuyeOrder(){
		
		
		if($this->yezhuInfo){
		
			for($i = 0; $i < 1; $i++){
				
				
				$this->form_validation->set_data($this->postJson);
				
				$this->form_validation->set_rules('id','物业标识','required');
				$this->form_validation->set_rules('order_typename','in_db_list['.$this->Feetype_Model->getTableRealName().'.name]');
				$this->form_validation->set_rules('start_month','缴费开始月份','required');
				$this->form_validation->set_rules('end_month','缴费结束月份','required');
				$this->form_validation->set_rules('amount','缴费金额','required');
				
				
				if(!$this->form_validation->run()){
					$this->jsonOutput2('数据校验错误');
					break;
				}
				
				
				$this->order_service->setWeixinAppConfig(config_item('mp_xcxCswy'));
				
				$orderInfo = $this->order_service->createOrder($this->postJson);
				if($orderInfo){
					$this->jsonOutput2(RESP_SUCCESS,$orderInfo);
				}else{
					
					$this->jsonOutput2('订单创建失败');
				}
			}
			
			
		}else{
			
			$this->jsonOutput2('尚未绑定',array('isBind' => false));
		}
		
		
	}
	
	

}
