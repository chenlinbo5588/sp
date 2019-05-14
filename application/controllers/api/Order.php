<?php
defined('BASEPATH') OR exit('No direct script access allowed');





class Order extends Wx_Tdkc_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Order_service');
        $this->order_service->setWeixinAppConfig(config_item('mp_xcxCswy'));
		
    	
    	//print_r(Order_service::$orderType);
    	
    	$this->postJson['pay_channel'] = '微信支付';
		$this->postJson['pay_method'] = '小程序支付';
		
		
	}
	
	
	
	/**
	 * 创建物业费、能耗费 订单
	 */
	public function createOrder(){
		if($this->userInfo){
			$message = '';
			
			$callPayJson = $this->order_service->createOrder('yewu_id',$this->postJson,$this->userInfo,$message);
			
			if($callPayJson){
								
				$this->jsonOutput2(RESP_SUCCESS);
				
			}else{
				$this->jsonOutput2($message);
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
				),
				'order' => 'id DESC'
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
				
				$this->form_validation->set_data(array(
					'order_id' => $this->postJson['order_id']
				));
				
				$this->order_service->setOrderIdRules();
				
				if(!$this->form_validation->run()){
					$message = $this->form_validation->error_first_html();
					break;
				}
				
				$condition = array(
					'where' => array(
						'order_id' => $this->postJson['order_id'],
						'uid' => $this->memberInfo['uid']
					)
				);
				
				$orderInfo = $this->Order_Model->getById($condition);
				$statusNameList = OrderStatus::$statusName;
				
				if($orderInfo){
					$orderInfo['statusName'] = $statusNameList[$orderInfo['status']];
					$orderInfo['extra_info'] = json_decode($orderInfo['extra_info'],true);
					
					if(strpos($orderInfo['order_typename'],'预约单') !== false){
						
						$orderInfo['extra_info_translate']['address'] = $orderInfo['extra_info']['address'] ;
						$orderInfo['extra_info_translate']['booking_time'] =$orderInfo['extra_info']['booking_time'] ;
						
					}else{
						$orderInfo['fee_old_expire'] = $orderInfo['fee_old_expire'] == 0 ? '无缴费记录' : date('Y-m-d', $orderInfo['fee_old_expire']);
						$orderInfo['fee_start'] = date('Y-m-d', $orderInfo['fee_start']);
						$orderInfo['fee_expire'] = date('Y-m-d', $orderInfo['fee_expire']);
					}
					
					if(isset($orderInfo['extra_info']['reason'])){
						$orderInfo['extra_info_translate']['退款原因'] = $orderInfo['extra_info']['reason'];
					}
					
				}else{
					$orderInfo = array();
				}
				
				$this->jsonOutput2(RESP_SUCCESS,$orderInfo);
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	
	
	
	/**
	 * 关闭订单
	 */
	public function closeOrder(){
		
		if($this->memberInfo){
			
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_data(array(
					'uid' => $this->memberInfo['uid'],
					'order_id' => $this->postJson['order_id']
				));
				
				
				$this->_setIsUserOrderRules();
		
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_first_html());
					break;
				}
				
				$resp = $this->order_service->closeOrderById($this->postJson['order_id']);
				
				if(!$resp){
					$this->jsonOutput2("关闭订单失败");
					break;
				}
				
				$this->jsonOutput2(RESP_SUCCESS);
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
		
	}
	
	
	/**
	 * 订单申请退款
	 * 
	 * 
	 */
	public function applyRefundOrder(){
		
		if($this->memberInfo){
			for($i = 0; $i < 1; $i++){
				
				$param = array(
					'uid' => $this->memberInfo['uid'],
					'order_id' => $this->postJson['order_id'],
					'amount' => $this->postJson['amount'],
					'reason' => $this->postJson['reason'],
					'remark' => $this->postJson['remark']
				);
				
				$this->form_validation->set_data($param);
				$this->_setIsUserOrderRules();
				
				$orderInfo = $this->order_service->getOrderInfoById($this->postJson['order_id'],'order_id');
				
				$this->form_validation->set_rules('amount','退款金额','required|is_numeric|greater_than[0]|less_than_equal_to['.($orderInfo['amount'] - $orderInfo['refund_amount']).']');
				
				$this->form_validation->set_rules('reason','退款原因','required|min_length[3]|max_length[100]');
				$this->form_validation->set_rules('remark','备注','min_length[3]|max_length[100]');
				
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_first_html());
					break;
				}
				
				
				if(is_array($orderInfo['extra_info'])){
					$param['extra_info'] = array_merge($orderInfo['extra_info'],array(
						'reason' => $this->postJson['reason'],
						'remark' => $this->postJson['remark'],
					));
				}else{
					$param['extra_info'] = array(
						'reason' => $this->postJson['reason'],
						'remark' => $this->postJson['remark'],
					);
				}
				
				//$param['notify_url'] = site_url(Order_service::$orderType['nameKey'][$orderInfo['order_typename']]['refund_url']);
				
				$isNewRefund = true;
				
				$refundOrder = $this->order_service->createRefundOrder($param,$isNewRefund);
				
				if(empty($refundOrder)){
					$this->jsonOutput('服务器发生错误,请稍后重新尝试');
					break;
				}
				
				if(!$isNewRefund){
					$this->jsonOutput('退款申请已经提交,不能重复申请');
					break;
				}
				
				//退款是否需要审核
				$isNeedVerify = Order_service::$orderType['nameKey'][$refundOrder['order_typename']]['refund_verify'];
				
				
				//订单是否需要审核
				if($isNeedVerify){
					$this->jsonOutput2("退款申请提交成功");
					break;
				}
				
				//业务处理
				$filePath = Order_service::$orderType['nameKey'][$refundOrder['order_typename']]['refund_url'];
				
				if($filePath){
					$fullPath = LIB_PATH.$filePath;
					$this->load->file($fullPath);
					$className = basename($fullPath,'.php');
					$refundObj = new $className;
					$refundObj->setController($this);
					
					$message = '退款失败';
					
					$isOk = $this->order_service->requestWeixinRefund($refundOrder,$refundObj,$message);
					
					if(!$isOk){
						$this->jsonOutput2($message);
						break;
					}
				}
				
				$this->jsonOutput2(RESP_SUCCESS);
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	


}
