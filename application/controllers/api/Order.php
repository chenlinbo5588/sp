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
			
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	
	/**
	 * 
	 */
	public function createWuyeOrder(){
		
		if($this->yezhuInfo){
		
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_data($this->postJson);
				$this->form_validation->set_rules('house_id','物业标识','required');
				$this->form_validation->set_rules('order_typename','in_db_list['.$this->Feetype_Model->getTableRealName().'.name]');
				$this->form_validation->set_rules('year','缴费年份','required|is_natural_no_zero|greater_than_equal_to['.date('Y').']');
				
				$this->form_validation->set_rules('month','缴费到期月份','required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[12]');
				$this->form_validation->set_rules('amount','缴费金额','required');
				
				
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_html());
					break;
				}
				
				$this->order_service->setWeixinAppConfig(config_item('mp_xcxCswy'));
				
				
				$this->postJson['pay_channel'] = '微信支付';
				$this->postJson['pay_method'] = '小程序支付';
				
				$this->postJson['uid'] = $this->yezhuInfo['uid'];
				$this->postJson['add_username'] = $this->yezhuInfo['name'];
				
				//@todo 修改金额
				$this->postJson['amount'] = 1;
				
				//异步回调
				$this->postJson['notify_url'] = site_url('api/order_wuye/notify');
				
				$message = '订单创建失败';
				
				//strtotime( "2009-01-31 +1 month" )
				$expireTs = strtotime($this->postJson['year'].'-'.str_pad($this->postJson['month'],2,'0',STR_PAD_LEFT).'-01 +1 month');
				
				$this->postJson['extra_info'] = json_encode(array(
					'house_id' => $this->postJson['house_id'],
					'fee_expire' => $expireTs
				));
				
				file_put_contents('wuye.txt',print_r($this->sessionInfo,true));
				file_put_contents('wuye.txt',print_r($this->yezhuInfo,true),FILE_APPEND);
				file_put_contents('wuye.txt',print_r($this->postJson,true),FILE_APPEND);
				
				try {
					$callPayJson = $this->order_service->createWeixinOrder($this->postJson,$this->sessionInfo);
					file_put_contents('wuye.txt',print_r($callPayJson,true),FILE_APPEND);
				}catch(WxPayException $e1){
					$message = $e1->getMessage();
				}catch(Exception $e){
					$message = $e1->getMessage();
				}
				
				if($callPayJson){
					$this->jsonOutput2(RESP_SUCCESS,$callPayJson);
				}else{
					$this->jsonOutput2($message);
				}
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
		
	}
	
	
	
	/**
	 * 获得订单列表
	 */
	public function getList(){
		if($this->memberInfo){
			
			$page = $this->postJson['page'];
			$statusName = $this->postJson['statusName'];
			
			if(empty($page)){
				$page = 1;
			}
			
			$statusNameList = OrderStatus::$statusName;
			$orderStatus = 0;
			
			if (in_array ($statusName, $statusNameList)) {
				$orderStatus = array_search($statusName,$statusNameList);
			}else{
				$orderStatus = -1;
			}
			
			$condition = array(
				'where' => array(
					'uid' => $this->memberInfo['uid'],
					'status' => $orderStatus
				),
				'pager' => array(
					'page_size' => config_item('page_size'),
					'current_page' => $page,
				)
			);
			
			$orderList = $this->order_service->getOrderListByCondition($condition);
			$this->jsonOutput2(RESP_SUCCESS,$orderList);
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	
	/**
	 * 获得订单详情
	 */
	public function getOrderDetail(){
		
		if($this->memberInfo){
			
			for($i = 0; $i < 1; $i++){
				$this->form_validation->set_data($this->postJson);
				$this->form_validation->set_rules('order_id','订单ID','required|in_db_list['.$this->Order_Model->getTableRealName().'.order_id]');
				
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_html());
					break;
				}
				
				$orderInfo = $this->order_service->getOrderDetailByOrderId($this->postJson['order_id'],$this->memberInfo);
				$this->jsonOutput2(RESP_SUCCESS,$orderInfo);
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	
	/**
	 * 取消
	 */
	public function closeOrder(){
		
		
	}
	
	
	/**
	 * 退款订单
	 */
	public function refoundOrder(){
		
		
	}
	
}
