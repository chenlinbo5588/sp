<?php
defined('BASEPATH') OR exit('No direct script access allowed');





class Order extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Order_service');
        $this->order_service->setWeixinAppConfig(config_item('mp_xcxCswy'));
		
    	$this->form_validation->set_error_delimiters('','');
    	
    	//print_r(Order_service::$orderType);
    	
    	$this->postJson['pay_channel'] = '微信支付';
		$this->postJson['pay_method'] = '小程序支付';
		
		
	}
	
	
	/**
	 * 创建订单
	 */
	public function createStaffOrder(){
		
		if($this->memberInfo){
			
			$this->load->library('Cart');
			
			for($i = 0; $i < 1; $i++){
				
				if($this->postJson['order_id']){
					
					$this->postJson['uid'] = $this->memberInfo['uid'];
					$this->form_validation->set_data($this->postJson);
					
					$this->_setIsUserOrderRules();
					
					if(!$this->form_validation->run()){
						$this->jsonOutput2($this->form_validation->error_html());
						break;
					}
					
				}else{
					
					$this->form_validation->set_data($this->postJson);
					
					//新创订单
					$this->form_validation->set_rules('order_typename','in_db_list['.$this->Order_Type_Model->getTableRealName().'.name]');
					
					//面谈地点
					$this->form_validation->set_rules('meet_time','required|valid_datetime');
					
					//面谈地点
					$this->form_validation->set_rules('address','required');
					
					$this->form_validation->set_rules('amount','缴费金额','required');
					
					if(!$this->form_validation->run()){
						$this->jsonOutput2($this->form_validation->error_html());
						break;
					}
					
					
					$this->postJson['order_type'] = Order_service::$orderType['nameKey'][$this->postJson['order_typename']]['id'];
					$this->postJson['uid'] = $this->memberInfo['uid'];
					
					//
					$this->postJson['add_username'] = $this->memberInfo['nickname'];
					
					if($this->yezhuInfo){
						$this->postJson['add_username'] = $this->yezhuInfo['name'];
					}
					
					//@todo 修改金额
					$this->postJson['amount'] = 1;
					$this->postJson['mobile'] = $this->yezhuInfo['mobile'];
					
					//异步回调
					$this->postJson['notify_url'] = site_url(Order_service::$orderType['nameKey'][$this->postJson['order_typename']]['order_url']);
					
					$message = '订单创建失败';
					
					$list = $this->cart->contents();
					if(empty($list)){
						$this->jsonOutput2("预约单记录不能为空");
						break;
					}
					
					//附加信息
					$this->postJson['extra_info'] = array(
						'cart' => $list,
						'meet_time' => $this->postJson['meet_time'],
						'address' => $this->postJson['address'],
					);
				}
				
				
				file_put_contents('staff.txt',print_r($this->sessionInfo,true));
				file_put_contents('staff.txt',print_r($this->memberInfo,true),FILE_APPEND);
				file_put_contents('staff.txt',print_r($this->yezhuInfo,true),FILE_APPEND);
				file_put_contents('staff.txt',print_r($this->postJson,true),FILE_APPEND);
				
				try {
					
					$callPayJson = $this->order_service->createWeixinOrder($this->postJson);
					
					file_put_contents('staff.txt',print_r($callPayJson,true),FILE_APPEND);
					
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
	 * 创建物业费、能耗费 订单
	 */
	public function createWuyeOrder(){
		
		if($this->yezhuInfo){
		
			for($i = 0; $i < 1; $i++){
				
				if($this->postJson['order_id']){
					
					$this->postJson['uid'] = $this->memberInfo['uid'];
					$this->form_validation->set_data($this->postJson);
					
					$this->_setIsUserOrderRules();
					
					if(!$this->form_validation->run()){
						$this->jsonOutput2($this->form_validation->error_html());
						break;
					}
					
				}else{
					
					$this->form_validation->set_data($this->postJson);
					
					//新创订单
					$this->form_validation->set_rules('house_id','物业标识','required');
					
					
					$this->form_validation->set_rules('order_typename','in_db_list['.$this->Order_Type_Model->getTableRealName().'.name]');
					$this->form_validation->set_rules('year','缴费年份','required|is_natural_no_zero|greater_than_equal_to['.date('Y').']');
					
					$this->form_validation->set_rules('start_month','缴费起始月份','required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[12]');
					$this->form_validation->set_rules('end_month','缴费到期月份','required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[12]');
					
					$this->form_validation->set_rules('amount','缴费金额','required');
					
					
					if(!$this->form_validation->run()){
						$this->jsonOutput2($this->form_validation->error_html());
						break;
					}
					
					
					$this->postJson['order_type'] = Order_service::$orderType['nameKey'][$this->postJson['order_typename']]['id'];
					
					$this->postJson['uid'] = $this->yezhuInfo['uid'];
					$this->postJson['add_username'] = $this->yezhuInfo['name'];
					
					//@todo 修改金额
					$this->postJson['amount'] = 1;
					
					//联系方式
					$this->postJson['mobile'] = $this->yezhuInfo['mobile'];
					
					//异步回调
					$this->postJson['notify_url'] = site_url(Order_service::$orderType['nameKey'][$this->postJson['order_typename']]['order_url']);
					
					$message = '订单创建失败';
					//strtotime( "2009-01-31 +1 month" )
					
					$startTs = strtotime($this->postJson['year'].'-'.str_pad($this->postJson['start_month'],2,'0',STR_PAD_LEFT).'-01');
					$expireTs = strtotime($this->postJson['year'].'-'.str_pad($this->postJson['end_month'],2,'0',STR_PAD_LEFT).'-01 +1 month');
					
					$this->postJson['extra_info'] = array(
						'house_id' => $this->postJson['house_id'],
						'fee_start' => $startTs,
						'fee_expire' => $expireTs
					);
					
				}
				
				
				file_put_contents('wuye.txt',print_r($this->sessionInfo,true));
				file_put_contents('wuye.txt',print_r($this->yezhuInfo,true),FILE_APPEND);
				file_put_contents('wuye.txt',print_r($this->postJson,true),FILE_APPEND);
				
				try {
					
					$callPayJson = $this->order_service->createWeixinOrder($this->postJson);
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
	 * 设置是否用户订单
	 */
	private function _setIsUserOrderRules(){
		
		$this->order_service->setOrderIdRules();
		
		$this->form_validation->set_rules('uid','用户标识', array(
				'required',
				array(
					'checkIsUserOrder_callable['.$this->postJson['order_id'].']',
					array(
						$this->order_service,'checkIsUserOrder'
					)
				)
			)
		);
		
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
					$message = $this->form_validation->error_html();
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
					$this->jsonOutput2($this->form_validation->error_html());
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
					//'refund_id' => 
					'amount' => $this->postJson['amount'],
					'reason' => $this->postJson['reason'],
					'remark' => $this->postJson['remark']
				);
				
				$this->form_validation->set_data($param);
				
				$this->_setIsUserOrderRules();
				$this->form_validation->set_rules('reason','退款原因','required|min_length[3]|max_length[100]');
				$this->form_validation->set_rules('remark','备注','min_length[3]|max_length[100]');
				
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_html());
					break;
				}
				
				$orderInfo = $this->Order_Model->getFirstByKey($this->postJson['order_id'],'order_id');
				
				$orderInfo['extra_info'] = json_decode($orderInfo['extra_info'],true);
			
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
				file_put_contents('wuye_callback_refund.txt',print_r($param,true));
				$refundOrder = $this->order_service->createRefundOrder($param);
				
				//业务处理
				$filePath = Order_service::$orderType['nameKey'][$orderInfo['order_typename']]['refund_url'];
				$fullPath = LIB_PATH.$filePath;
				$this->load->file($fullPath);
				$className = basename($fullPath,'.php');
				$refundObj = new $className;
				$refundObj->setController($this);
				
				
				$isOk = $this->order_service->requestWeixinRefund($refundOrder,$refundObj);
				
				if(!$isOk){
					$this->jsonOutput2("退款失败");
					break;
				}
				
				$this->jsonOutput2(RESP_SUCCESS);
				
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
}
