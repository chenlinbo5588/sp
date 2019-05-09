<?php
defined('BASEPATH') OR exit('No direct script access allowed');





class Order extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Order_service');
        $this->order_service->setWeixinAppConfig(config_item('mp_xcxCswy'));
		
    	
    	//print_r(Order_service::$orderType);
    	
    	$this->postJson['pay_channel'] = '微信支付';
		$this->postJson['pay_method'] = '小程序支付';
		
		
	}
	
	
	
	/**
	 * 获得保洁配置
	 */
	public function getStaffConfig(){
		
		$data = array(
			'amount' => intval($this->_getSiteSetting('service_prepay_amount')),
		);
		
		$this->jsonOutput2(RESP_SUCCESS,$data);
	}
	
	
	/**
	 * 创建 保洁订单
	 */
	public function createBaojieOrder(){
	
		if($this->memberInfo){
			
			for($i = 0; $i < 1; $i++){
				$isEnable = $this->_getSiteSetting('service_booking_status');
				if('关闭' == $isEnable){
					$this->jsonOutput2('该功能暂时关闭，不能预约');
					break;
				}
				
				$orderCountLimit = intval($this->_getSiteSetting('service_order_limit'));
				
				if($orderCountLimit){
					$todayStart = strtotime(date('Y-m-d'));
					$currentCount = $this->Order_Model->getCount(array(
						'where' => array(
							'gmt_create >=' => $todayStart,
							'gmt_create <' => $todayStart + CACHE_ONE_DAY
						)
					));
					
					if($currentCount >= $orderCountLimit){
						$this->jsonOutput2("今日预约单数量已经达到{$orderCountLimit}");
						break;
					}
				}
				
				
				if($this->postJson['order_id']){
					
					$this->postJson['uid'] = $this->memberInfo['uid'];
					$this->form_validation->set_data($this->postJson);
					
					$this->_setIsUserOrderRules();
					
					if(!$this->form_validation->run()){
						$this->jsonOutput2($this->form_validation->error_first_html());
						break;
					}
					
					
					$orderInfo = $this->Order_Model->getFirstByKey($this->postJson['order_id'],'order_id','id,time_expire');
					
					if($this->_reqtime >= strtotime($orderInfo['time_expire'])){
						$this->order_service->updateOrderStatusByIds(array($orderInfo['id']),OrderStatus::$closed,OrderStatus::$unPayed);
						$this->jsonOutput2('订单已过期');
						break;
					}
					
				}else{
					
					$this->form_validation->set_data($this->postJson);
					
					$this->form_validation->set_rules('order_typename','订单类型', 'in_db_list['.$this->Order_Type_Model->getTableRealName().'.name]');
					
					//上门 时间
					$this->form_validation->set_rules('visit_time', '上门时间', 'required|valid_datetime');
					
					//上门地址
					$this->form_validation->set_rules('address','地址','required');
					
					//联系方式
					$this->form_validation->set_rules('mobile','联系方式','required|valid_mobile');
					
					//联系人名称
					$this->form_validation->set_rules('username','联系人名称', 'required');
					
					
					if(!$this->form_validation->run()){
						$this->jsonOutput2($this->form_validation->error_first_html());
						break;
					}
					
					
					$this->postJson['order_type'] = Order_service::$orderType['nameKey'][$this->postJson['order_typename']]['id'];
					
					$this->postJson['uid'] = $this->memberInfo['uid'];
					$this->postJson['add_username'] = $this->memberInfo['username'];
					
					if($this->postJson['mobile'] == $this->yezhuInfo['mobile']){
						//业主自己
						$this->postJson['add_username'] = $this->yezhuInfo['name'];
					}
					
					//异步回调
					$this->postJson['notify_url'] = site_url(Order_service::$orderType['nameKey'][$this->postJson['order_typename']]['order_url']);
					
					$message = '订单创建失败';
					
					$this->postJson['goods_name'] = $this->postJson['address'];
					
					//附加信息
					$this->postJson['extra_info'] = array(
						'address' => $this->postJson['address'],
						'username' => $this->postJson['username'],
						'booking_time' => $this->postJson['visit_time'],
						'bz' => $this->postJson['remark'] ? $this->postJson['remark'] : ''
					);
					$houseInfo = $this->House_Model->getFirstByKey($this->postJson['address'],'address','id,resident_id');
					//物业对应小区标识,如果是某个小区的业主
					if($houseInfo){
						$this->postJson['resident_id'] = $this->$houseInfo['resident_id'];
						
						$this->load->model('Resident_Model');
						
						$residentInfo = $this->Resident_Model->getFirstByKey($houseInfo['resident_id'],'id','name');
						
						$this->postJson['attach'] = $residentInfo['name'];
					}
					
					if(ENVIRONMENT == 'development'){
						//@todo 修改金额
						$this->postJson['amount'] = mt_rand(1,3);
					}else{
						$this->postJson['amount'] = mt_rand(1,3);
						
						//$this->postJson['amount'] = intval($this->_getSiteSetting('service_prepay_amount')) * 100;
					}
				}
				
				$callPayJson = $this->order_service->createWeixinOrder($this->postJson);
				
				if($callPayJson){
					$this->jsonOutput2(RESP_SUCCESS,$callPayJson);
				}else{
					$this->jsonOutput2("预约单创建失败");
				}
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	
	
	/**
	 * 创建 月嫂、保姆、护工订单
	 */
	public function createStaffOrder(){
		
		if($this->memberInfo){
			
			for($i = 0; $i < 1; $i++){
				$isEnable = $this->_getSiteSetting('service_booking_status');
				if('关闭' == $isEnable){
					$this->jsonOutput2('该功能暂时关闭，不能预约');
					break;
				}
				
				$orderCountLimit = intval($this->_getSiteSetting('service_order_limit'));
				
				if($orderCountLimit){
					$todayStart = strtotime(date('Y-m-d'));
					$currentCount = $this->Order_Model->getCount(array(
						'where' => array(
							'gmt_create >=' => $todayStart,
							'gmt_create <' => $todayStart + CACHE_ONE_DAY
						)
					));
					
					if($currentCount >= $orderCountLimit){
						$this->jsonOutput2("今日预约单数量已经达到{$orderCountLimit}");
						break;
					}
				}
				
				$this->load->library('Cart');
				if($this->postJson['order_id']){
					
					$this->postJson['uid'] = $this->memberInfo['uid'];
					$this->form_validation->set_data($this->postJson);
					
					$this->_setIsUserOrderRules();
					
					if(!$this->form_validation->run()){
						$this->jsonOutput2($this->form_validation->error_first_html());
						break;
					}
					
					$orderInfo = $this->Order_Model->getFirstByKey($this->postJson['order_id'],'order_id','id,time_expire');
					
					if($this->_reqtime >= strtotime($orderInfo['time_expire'])){
						$this->order_service->updateOrderStatusByIds(array($orderInfo['id']),OrderStatus::$closed,OrderStatus::$unPayed);
						$this->jsonOutput2('订单已过期');
						break;
					}
					
					
				}else{
					
					$list = $this->cart->contents();
					if(empty($list)){
						$this->jsonOutput2("预约单记录不能为空");
						break;
					}
					
					$maxCount = intval($this->_getSiteSetting('service_staff_maxcnt'));
					if($maxCount && count($list) > $maxCount){
						$this->jsonOutput2("预约单预约人数一次不能超过{$maxCount}个");
						break;
					}
					
					$firstCartItem = array();
					foreach($list as $rowKey => $cartItem){
						$firstCartItem = $cartItem;
						break;
					}
					
					$serviceTypeInfo = $this->Basic_Data_Model->getFirstByKey($firstCartItem['options']['service_type'],'id','show_name');
					
					$this->postJson['order_typename'] = $serviceTypeInfo['show_name'].'预约单';
					$this->form_validation->set_data($this->postJson);
					
					//新创订单
					$this->form_validation->set_rules('order_typename','订单类型','required|in_db_list['.$this->Order_Type_Model->getTableRealName().'.name]');
					
					//面谈时间
					$this->form_validation->set_rules('meet_time','面谈时间','required|valid_datetime');
					
					//面谈地点
					$this->form_validation->set_rules('address','面谈地址', 'required');
					
					
					//$this->form_validation->set_rules('amount','缴费金额','required|is_numeric|greater_than_equal_to[0]');
					
					if(!$this->form_validation->run()){
						$this->jsonOutput2($this->form_validation->error_first_html());
						break;
					}
					
					
					$this->postJson['order_type'] = Order_service::$orderType['nameKey'][$this->postJson['order_typename']]['id'];
					$this->postJson['uid'] = $this->memberInfo['uid'];
					$this->postJson['add_username'] = $this->memberInfo['username'];
					$this->postJson['mobile'] = $this->memberInfo['mobile'];
					
					if($this->yezhuInfo){
						$this->postJson['add_username'] = $this->yezhuInfo['name'];
					}
					
					$this->postJson['username'] = $this->postJson['add_username'];
					
					
					//异步回调
					$this->postJson['notify_url'] = site_url(Order_service::$orderType['nameKey'][$this->postJson['order_typename']]['order_url']);
					
					$message = '订单创建失败';
					
					
					$this->postJson['goods_id'] = $firstCartItem['id'];
					
					$nameStr = array();
					foreach($list as $rowKey => $cartItem){
						$nameStr[] = $cartItem['name'];
					}
					
					$this->postJson['goods_name'] = implode(' ',$nameStr);
					
					//附加信息
					$this->postJson['extra_info'] = array(
						'cart' => $list,
						'booking_time' => $this->postJson['meet_time'],
						'address' => $this->postJson['address'],
					);
					
					$houseInfo = $this->House_Model->getFirstByKey($this->postJson['address'],'address','id,resident_id');
					//物业对应小区标识,如果是某个小区的业主
					if($houseInfo){
						$this->postJson['resident_id'] = $this->$houseInfo['resident_id'];
						
						$this->load->model('Resident_Model');
						
						$residentInfo = $this->Resident_Model->getFirstByKey($houseInfo['resident_id'],'id','name');
						
						$this->postJson['attach'] = $residentInfo['name'];
					}
					
					
					if(ENVIRONMENT == 'development'){
						//@todo 修改金额
						$this->postJson['amount'] = mt_rand(1,3);
					}else{
						//@TODO 打开
						$this->postJson['amount'] = mt_rand(1,3);
						//$this->postJson['amount'] = intval($this->_getSiteSetting('service_prepay_amount')) * 100;
					}
				}
				
				
				$callPayJson = $this->order_service->createWeixinOrder($this->postJson);
				
				if($callPayJson){
					$this->jsonOutput2(RESP_SUCCESS,$callPayJson);
					
					//清空购物车
					$this->cart->destroy();
					
				}else{
					$this->jsonOutput2("预约单创建失败");
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
		if($this->memberInfo){
			$message = '';
			
			if('物业费' == $this->postJson['order_typename']){
				$this->postJson['end_month'] = 12;
			}
			
			$callPayJson = $this->order_service->createWuyeOrder('house_id',$this->postJson,$this->memberInfo,$message);
			
			if($callPayJson){
				
				$this->jsonOutput2(RESP_SUCCESS,$callPayJson);
				
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