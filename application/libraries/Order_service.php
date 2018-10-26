<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(WEIXIN_PAY_PATH.'WxPay.Api.php');





class OrderVerify {
	
	public static $unVerify = 0;
	public static $verifyOK = 1;
	public static $sendBack = 2;
	
	public static $statusName = array(
		'未审核',
		'审核通过',
		'已退回',
	);
	
}


class Order_service extends Base_service {

	
	private $_weixinServiceObj;
	private $_wuyeServiecObj;
	
	private $_orderModel;
	private $_appConfig;
	private $_paymentConfig;
	
	private $_orderTypeModel;
	
	public static $orderType;
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library(array('Weixin_service','Wuye_service','constant/OrderStatus','constant/Utype'));
		
		
		self::$CI->load->model(array(
			'Order_Model','Order_Type_Model','House_Model','Feetype_Model','Plan_Model',
			'Plan_Detail_Model','Parking_Model'
		));
		
		$this->_orderModel = self::$CI->Order_Model;
		$this->_houseModel = self::$CI->House_Model;
		$this->_feetypeModel = self::$CI->Feetype_Model;
		$this->_planModel = self::$CI->Plan_Model;
		$this->_planDetailModel = self::$CI->Plan_Detail_Model;
		
		$this->_parkingModel = self::$CI->Parking_Model;
		
		
		$this->_weixinServiceObj = self::$CI->weixin_service;
		$this->_wuyeServiecObj = self::$CI->wuye_service;
		
		$this->_paymentConfig = config_item('payment');
		
		
		$this->_orderTypeModel = self::$CI->Order_Type_Model;
		
		$tempType =  $this->_orderTypeModel->getList();
		self::$orderType = array(
			'idKey' => $this->toEasyUseArray($tempType,'id'),
			'nameKey' => $this->toEasyUseArray($tempType,'name'),
		);
		
		$this->_dataModule = array(-1);
		
		$this->_objectMap = array(
			'订单' => $this->_orderModel,
			'房屋' => $this->_houseModel,
		);
	}
	
	/**
	 * 设置微信app config
	 */
	public function setWeixinAppConfig($pConfig){
		$this->_appConfig = $pConfig;
		$this->_weixinServiceObj->setConfig($pConfig);
	}
	
	
	/**
	 * 创建业务订单
	 */
	public function createBussOrder($param){
		$order = array();
		
		$ts = date("YmdHis");
		
		$order['app_id'] = $this->_appConfig['appid'];
		$order['mch_id'] = $this->_appConfig['mch_id'];
		$order['order_id'] = $this->_appConfig['mch_id'].$ts.mt_rand(100,999);
		$order['time_start'] = $ts;
		
		if($param['time_expire']){
			$order['time_expire'] = date("YmdHis", $param['time_expire']);
		}else{
			$order['time_expire'] = date("YmdHis", time() + 1800);
		}
		
		$order['add_uid'] = empty($param['uid']) ? 0 : $param['uid'];
		$order['ip'] = self::$CI->input->ip_address();
	
		$order = array_merge($order,$param);
		
		if($order['extra_info']){
			$order['extra_info'] = is_array($order['extra_info']) ? json_encode($order['extra_info']) : $order['extra_info'];
		}else{
			$order['extra_info'] = json_encode(array());
		}
		
		$newid = $this->_orderModel->_add($order);
		$error = $this->_orderModel->getError();
		
		if(QUERY_OK != $error['code']){
			return false;
		}else{
			$order['id'] = $newid;
			return $order;
		}
	}
	
	
	/**
	 * 获得订单详情
	 */
	public function getOrderInfoById($pId,$key = 'id'){
		
		$orderInfo = $this->_orderModel->getFirstByKey($pId,$key);
		
		if($orderInfo){
			$orderInfo['extra_info'] = json_decode($orderInfo['extra_info'],true);
		}
		
		return $orderInfo;
	}
	
	/**
	 * 请求微信退款
	 */
	public function requestWeixinRefund($pRefundOrder,$refundObj,&$message){
		
		try {
			
			$wxPayRefund = new WxPayRefund();
			
			//原交易订单号
			$wxPayRefund->SetOut_trade_no($pRefundOrder['order_old']);
			
			//退款订单号
			$wxPayRefund->SetOut_refund_no($pRefundOrder['order_id']);
			
			//原订单总金额
			$wxPayRefund->SetTotal_fee($pRefundOrder['amount']);
			
			//退款金额
			$wxPayRefund->SetRefund_fee($pRefundOrder['refund_amount']);
			
			
			$wxPayRefund->SetOp_user_id($pRefundOrder['mch_id']);
			
			
			/*
			if($pOrderParam['notify_url']){
				$wxPayRefund->SetNotify_url($pOrderParam['notify_url']);
			}
			*/
			
			$refundResp = WxPayApi::refund($wxPayRefund);
			
			//file_put_contents('weixinRefund.txt',print_r($refundResp,true));
			
			if(!$this->checkWeixinRespSuccess($refundResp)){
				$message = $refundResp['err_code_des'];
				return false;
			}
			
			if(!is_array($pRefundOrder['extra_info'])){
				$pRefundOrder['extra_info'] = json_decode($pRefundOrder['extra_info'],true);
			}
			
			$refundFlag = $refundObj->customHandle($pRefundOrder,$refundResp);
			if($refundFlag){
				$this->_weixinServiceObj->refundOrderNotify($pRefundOrder);
			}
			
			return $refundFlag;
			
		}catch(WxPayException $payException){
			log_message('error',"code=".$payException->getCode().",message=".$payException->errorMessage());
			
		}catch(Exption $e){
			//error
			log_message('error',"code=".$e->getCode().",message=".$e->errorMessage());
		}
		
	}
	
	
	
	/**
	 * 创建退订单
	 * 
	 * @param array $pOrderParam 原订单参数
	 * @param bool  &$isNewCreate 是否新的订单
	 * 
	 * 
	 */
	public function createRefundOrder($pOrderParam,&$isNewCreate){
		
		$tuiOrder = array();
		
		$isNewCreate = false;
		
		if($pOrderParam['refund_id']){
			//退款中订单 继续退款
			$tuiOrder = $this->getOrderInfoById($pOrderParam['refund_id'],'order_id');
			
		}else if($pOrderParam['order_id']){
			//原正常订单
			$oldOrderInfo = $this->getOrderInfoById($pOrderParam['order_id'],'order_id');
			
			if(!$oldOrderInfo['is_refund']){
				//检查一下是否有退款中的退款订单
				$tempOrder = $this->_orderModel->getList(array(
					'where' => array(
						'goods_id' => $oldOrderInfo['goods_id'],
						'order_old' => $oldOrderInfo['order_id'],
						'status' => OrderStatus::$refounding
					),
					'limit' => 1
				));
				
				if($tempOrder[0]){
					$tuiOrder = $tempOrder[0];
				}
			}else{
				//说明本身就是退款单
				$tuiOrder = $oldOrderInfo;
			}
			
		}
		
		if(!empty($tuiOrder)){
			return $tuiOrder;
		}else{
			//创建一个退订单
			$tuiOrder = array(
				'prepay_id' => $oldOrderInfo['prepay_id'],
				'order_type' => $oldOrderInfo['order_type'],
				'order_typename' => $oldOrderInfo['order_typename'],
				'pay_channel' => $oldOrderInfo['pay_channel'],
				'pay_method' => $oldOrderInfo['pay_method'],
				'is_refund' => 1,
				'amount' => $oldOrderInfo['amount'],//原订单金额
				'refund_amount' => $pOrderParam['amount'],//退款金额
				'mobile' => $oldOrderInfo['mobile'],
				'goods_id' => $oldOrderInfo['goods_id'],
				'goods_name' => $oldOrderInfo['goods_name'],
				'status' => OrderStatus::$refounding,
				'order_old' => $oldOrderInfo['order_id'],
				'fee_month' => $oldOrderInfo['fee_month'],
				'fee_old_expire' => $oldOrderInfo['fee_old_expire'],
				'fee_start' => $oldOrderInfo['fee_start'],
				'fee_start' => $oldOrderInfo['fee_expire'],
				'resident_id' => $oldOrderInfo['resident_id'],
				'uid' => $oldOrderInfo['uid'],
				'username' => $oldOrderInfo['username'],
				'add_uid' => empty($pOrderParam['add_uid']) == true ? $oldOrderInfo['add_uid'] : $pOrderParam['add_uid'],
				'add_username' => empty($pOrderParam['add_username']) == true ? $oldOrderInfo['add_username'] : $pOrderParam['add_username'],
			);
			
			if($pOrderParam['extra_info']){
				$tuiOrder['extra_info'] = array_merge($oldOrderInfo['extra_info'],$pOrderParam['extra_info']);
			}else{
				$tuiOrder['extra_info'] = $oldOrderInfo['extra_info'];
			}
			
			$isNewCreate = true;
			
			return $this->createBussOrder($tuiOrder);
			
		}
		
	}
	
	
	
	/**
	 * 检查微信 返回成功状态
	 */
	public function checkWeixinRespSuccess($result){
		if('SUCCESS' == $result['return_code'] && 'SUCCESS' == $result['result_code']){
			return true;
		}
		
		return false;
	}
	
	
	
	/**
	 * 支付方式 名称转代码
	 */
	private function _payChannelInfo($pOrderParam){
		
		$order = array();
		if($this->_paymentConfig['channel'][$pOrderParam['pay_channel']]){
			$order['pay_channel'] = $this->_paymentConfig['channel'][$pOrderParam['pay_channel']];
			
			if($this->_paymentConfig['method'][$pOrderParam['pay_channel']][$pOrderParam['pay_method']]){
				$order['pay_method'] = $this->_paymentConfig['method'][$pOrderParam['pay_channel']][$pOrderParam['pay_method']];
			}
		}
		
		return $order;
	}
	
	
	/**
	 * 创建 物业费 、能耗费 、车位费 订单
	 */
	public function createWuyeOrder($keyId,$pParam,$memberInfo,&$message,$from = 'wx'){
		

		self::$CI->load->library('Wuye_service');
		
		$wuyeService = self::$CI->wuye_service;
		
		$callPayJson = array();
		
		self::$CI->form_validation->reset_validation();
		
		$message = '订单创建失败';
		for($i = 0; $i < 1; $i++){
			
			if($pParam['order_id']){
				
				$pParam['uid'] = $memberInfo['uid'];

				self::$CI->form_validation->set_data($pParam);
				
				$this->setOrderIdRules();
		
				self::$CI->form_validation->set_rules('uid','用户标识', array(
						'required',
						array(
							'checkIsUserOrder_callable['.$pParam['order_id'].']',
							array(
								$this,'checkIsUserOrder'
							)
						)
					)
				);
				
				if(!self::$CI->form_validation->run()){
					$message = self::$CI->form_validation->error_first_html();
					break;
				}
			
				$orderInfo = $this->getOrderInfoById($pParam['order_id'],'order_id');
				
				$whichField = '';
				
				$info = self::$CI->House_Model->getFirstByKey($orderInfo['goods_id'],'id','wuye_expire,nenghao_expire');
				
				switch($orderInfo['order_typename']){
					case '物业费':
						$whichField = 'wuye_expire';
						break;
					case '能耗费':
						$whichField = 'nenghao_expire';
						break;
					default:
						break;
				}
				
				if(empty($whichField)){
					$message = '订单类型非法';
					break;
				}
				
				if(time() >= strtotime($orderInfo['time_expire'])){
					$this->updateOrderStatusByIds(array($orderInfo['id']),OrderStatus::$closed,OrderStatus::$unPayed);
					$message = '订单已过期';
					break;
				}
				
				//fixed 用户先选择一个月份在创建订单付款界面取消后， 重新选择缴费月份，然后付款后一笔交易成功后， 最后我的订单中继续付款前一个交易。
				if($orderInfo['fee_old_expire'] != $info[$whichField]){
					$this->updateOrderStatusByIds(array($orderInfo['id']),OrderStatus::$closed,OrderStatus::$unPayed);
					$message = '该订单缴费信息已过期';
					break;
				}
				
			}else{
				self::$CI->form_validation->set_data($pParam);
				
				self::$CI->form_validation->set_rules('order_typename','订单类型','in_db_list['.$this->_orderTypeModel->getTableRealName().'.name]');
				
				
				if(empty($param['id']) && !empty($param['address'])){
					$tempHouseInfo = $wuyeService->search('房屋',array(
						'select' => 'id',
						'where' => array(
							'address' => $param['address']
						)
					));
					
					if($tempHouseInfo[0]){
						$param[$keyId] = $tempHouseInfo[0]['id'];
					}
				}
				
				
     			self::$CI->form_validation->set_rules($keyId,'数据标识',array(
						'required',
						array(
							'feetype_callable['.$pParam['year'].','.$pParam['order_typename'].']',
							array(
								$wuyeService,'checkFeetype'
							)
						)
					),
					array(
						'feetype_callable' => '该小区尚未配置'.$pParam['order_typename'].'信息'
					)
				);
				
				//获得缴费情况
				$currentFeeExpire = $wuyeService->getCurrentFeeInfo($pParam[$keyId],$pParam['order_typename'],$pParam['end_month']);

				$wuyeService->setFeeTimeRules($currentFeeExpire['year']);
				if(!self::$CI->form_validation->run()){			
					$message = self::$CI->form_validation->error_first_html();
					break;
				}
				
				//再校验  缴费的时间一定要大于已缴费的时间,防止多笔支付更新时的问题
				if($currentFeeExpire['expireTimeStamp'] >= $currentFeeExpire['newEndTimeStamp']){
					$message = '缴费时间只能延长,不能回退';
					break;
				}

				//开始创建订单
				
				$pParam['order_type'] = self::$orderType['nameKey'][$pParam['order_typename']]['id'];
				
				$pParam['uid'] = $memberInfo['uid'];
				
				$yezhuInfo = self::$CI->Yezhu_Model->getById(array(
					'where' => array(
						'resident_id' => $currentFeeExpire['resident_id'],
						'mobile' => $memberInfo['mobile']
					)
				));
				
				if($yezhuInfo){
					$pParam['add_username'] = $yezhuInfo['name'];
					$pParam['username'] = $pParam['add_username'];
					
				}else{
					$pParam['add_username'] = $memberInfo['username'];
					$pParam['username'] = $memberInfo['username'];
					
				}
				
				
				
				//联系方式
				$pParam['mobile'] = $memberInfo['mobile'];
				
				//异步回调
				$pParam['notify_url'] = site_url(self::$orderType['nameKey'][$pParam['order_typename']]['order_url']);
				
				$pParam['goods_id'] = $pParam[$keyId];
				$pParam['goods_name'] = $currentFeeExpire['address'];
				
				//物业对应小区标识
				$pParam['resident_id'] = $currentFeeExpire['resident_id'];
				$pParam['resident_name'] = $currentFeeExpire['resident_name'];
				//附件数据 比如  浅水湾
				$pParam['attach'] = $currentFeeExpire['resident_name'];
				
				//原到期时间戳
				$pParam['fee_old_expire'] = $currentFeeExpire['expireTimeStamp'];
				
				//新的缴费开始和结束
				$pParam['fee_start'] = $currentFeeExpire['newStartTimeStamp'];
				$pParam['fee_expire'] = $currentFeeExpire['newEndTimeStamp'];
				
				//缴费月数

				$pParam['fee_month'] = $currentFeeExpire['fee_month'];

				
				if(ENVIRONMENT == 'development'){
					//@todo 修改金额
					$pParam['amount'] = mt_rand(1,3);
				}else{
					$pParam['amount'] = mt_rand(1,3);
					//计算金额
					//$pParam['amount'] = intval(100 * $this->wuye_service->computeFee($currentFeeExpire));
				}
				
			}
			if('wx' == $from){
				$callPayJson = $this->createWeixinOrder($pParam);
			}else if('Backstage' == $from){		
				$localOrder = $this->createBussOrder($pParam);
				$callPayJson = $this->updateOrderRelation($localOrder);
				$message = '新建成功';
			}

			if(empty($callPayJson)){
				$message = $pParam['order_typename']."订单创建失败";
				break;
			}
			
			$message = RESP_SUCCESS;
		}

		return $callPayJson;
		
	}
	
	
	
	
	/**
	 * 创建订单
	 */
	public function createWeixinOrder($param){
		$param = array_merge($param,$this->_payChannelInfo($param));
		
		if($param['order_id']){
			$localOrder = $this->_orderModel->getFirstByKey($param['order_id'],'order_id');
		}else{
			$localOrder = $this->createBussOrder($param);
		}
		
		if(empty($localOrder)){
			return false;
		}
		
		if($param['order_id']){
			//已有订单重新签发
			return $this->genPaymentParam($localOrder['prepay_id']);
			
		}else{
			try {
				
				$input = new WxPayUnifiedOrder();
			
				$input->SetBody($localOrder['order_typename'].'-'.$localOrder['goods_name']);
				//$input->SetAttach("test");
				
				$input->SetOut_trade_no($localOrder['order_id']);
				
				//测试阶段 始终用 1分
				$input->SetTotal_fee(intval($localOrder['amount']));
				
				$input->SetTime_start($localOrder['time_start']);
				$input->SetTime_expire($localOrder['time_expire']);
				
				
				if($localOrder['goods_tag']){
					$input->SetGoods_tag($localOrder['goods_tag']);
				}
				
				if($localOrder['dev_id']){
					$input->SetDevice_info($localOrder['dev_id']);
				}
				
				if($param['attach']){
					$input->SetAttach($param['attach']);
				}
				
				$input->SetNotify_url($param['notify_url']);
				
				$input->SetTrade_type("JSAPI");
				
				if($localOrder['goods_id']){
					$input->SetProduct_id($localOrder['goods_id']);
				}
				
				$input->SetOpenid($param['openid']);
				
				$weixinOrder = WxPayApi::unifiedOrder($input);
				
				if($this->checkWeixinRespSuccess($weixinOrder)){
					
					//将 prepay_id 保存起来, 用来在用户取消订单后，后续可以再次进行下发 换起支付的参数
					$this->_orderModel->updateByWhere(array(
						'prepay_id' => $weixinOrder['prepay_id']
					),array(
						'id' => $localOrder['id']
					));
					
					return $this->genPaymentParam($weixinOrder['prepay_id']);
				}else{
					log_message('error',"order_id={$localOrder['order_id']},RESP:".json_encode($weixinOrder));
				}
			}catch(WxPayException $e1){
				log_message('error','code='.$e1->getCode().',message='.$e1->getMessage());
			}catch(Exception $e){
				log_message('error','code='.$e->getCode().',message='.$e->getMessage());
			}
			
			
			return false;
		}
		
	}
	
	
	/**
	 * 生成小程序支付参数
	 */
	public function genPaymentParam($pPrepayId){
	
		$callPayJson = array(
			'timeStamp' => (string)time(),
			'nonceStr' => WxPayApi::getNonceStr(10),
			'package' => 'prepay_id='.$pPrepayId,
			'signType' => 'MD5'
		);
		
		$signObj = new WxPayResults();
		$signObj->FromArray(array_merge(
			$callPayJson,array('appId' => $this->_appConfig['appid'])
		));
		
		$callPayJson['paySign'] = $signObj->MakeSign();
		
		return $callPayJson;
	}
	
	
	/**
	 * 获取微信对账单
	 * @param string $pDate  20180602
	 * @param string $pType  ALL,SUCCESS,REFUND,RECHARGE_REFUND
	 */
	public function getWeixinPayBill($pDate,$pType){
		
		$file = false;
		
		try {
			$input = new WxPayDownloadBill();
			$input->SetBill_date($pDate);
			$input->SetBill_type($pType);
			$file = WxPayApi::downloadBill($input);
			
			//file_put_contents('bill.txt',print_r($resp,true));;
			
		}catch(WxPayException $e1){
			log_message('error','code='.$e1->getCode().',message='.$e1->getMessage());
		}catch(Exception $e){
			log_message('error','code='.$e->getCode().',message='.$e->getMessage());
		}
		
		return $file;
		
	}
	
	/**
	 * 获得订单列表
	 */
	public function getOrderListByCondition($pCondition){
		$condition = array(
			'select' => 'order_id,order_type,order_typename,goods_name,status,amount,time_start,time_expire',
		);
		
		$condition = array_merge($condition,$pCondition);
		$orderList = $this->_orderModel->getList($condition);
		
		$statusNameList = OrderStatus::$statusName;
		
		if($condition['pager']){
			if($orderList['data']){
				foreach($orderList['data'] as $index => $orderItem){
					$orderItem['statusName'] = $statusNameList[$orderItem['status']];
					$orderList['data'][$index] = $orderItem;
				}
			}
		}else{
			
			if($orderList){
				foreach($orderList as $index => $orderItem){
					$orderItem['statusName'] = $statusNameList[$orderItem['status']];
					$orderList[$index] = $orderItem;
				}
			}
		}
		
		return $orderList;
	}
	
	
	/**
	 * 设置关闭
	 */
	private function _closeOrder($pId,$key = 'id'){
		return $this->_orderModel->updateByWhere(array(
			'status' => OrderStatus::$closed
		),array($key => $pId));
	}
	
	
	/**
	 * 关闭订单
	 */
	public function closeOrderById($pOrderId){
		
		$orderInfo = $this->_orderModel->getFirstByKey($pOrderId,'order_id','id,order_id,pay_channel,status');
		
		if(empty($orderInfo)){
			return false;
		}
		
		
		if($orderInfo['status'] != OrderStatus::$unPayed){
			return false;
		}
		
		if($orderInfo['pay_channel'] == $this->_paymentConfig['channel']['微信支付']){
			$wxPayCloseObj = new WxPayCloseOrder();
			
			$wxPayCloseObj->SetOut_trade_no($orderInfo['order_id']);
			
			$closeResp = WxPayApi::closeOrder($wxPayCloseObj);
			
			if(!$this->checkWeixinRespSuccess($closeResp)){
				log_message('error',"return_code={$closeResp['return_code']},return_msg={$closeResp['return_msg']},order_id={$orderInfo['order_id']}");
				
				if('ORDERCLOSED' != $closeResp['return_code']){
					return false;
				}
			}
			
			return $this->_closeOrder($orderInfo['id']);
			
		}else if($orderInfo['pay_channel'] == $this->_paymentConfig['channel']['支付宝支付']){
			//@todo 待完成
			return false;
			
		}else{
			//非互联网订单直接关闭
			return $this->_closeOrder($orderInfo['id']);
		}
		
	}
	
	/**
	 * 更新订单状态
	 */
	public function updateOrderStatusByIds($pIds,$newStatus,$oldStatus = -1){
		
		if(empty($pIds)){
			return false;
		}
		
		if(!in_array($newStatus,array_keys(OrderStatus::$statusName))){
			return false;
		}
		
		return $this->_orderModel->updateByCondition(array(
			'status' => $newStatus
		),array(
			'where' => array( 
				'status' => $oldStatus,
			),
			'where_in' => array(
				array('key'=> 'id', 'value' => $pIds )
			)
		));
		
	}
	
	
	/**
	 * 获得预约单 额外信息
	 */
	public function getStaffExtraInfo($extraArray){
		
		$list = '';
		
		foreach ($extraArray as $key => $value) {
			$temp = array();
			$tempStr = '';
			
			if('cart' == $key){
				$temp[] = '预约人姓名';
				
				foreach ($value as $key2 => $value2) {
					$tempStr .= $value2['name']." ";
		 		}
		 		$temp[] = $tempStr;
		 		
			}else if('booking_time' == $key){
				
				$temp[] = '碰面时间';
				$temp[] = $value;
				
			}else if('address' == $key){
				
				$temp[] = '碰面地址';
				$temp[] = $value;
				
			}else if('reason' == $key){
				$temp[] = '退款原因';
				$temp[] = $value;
				
			}else if('remark' == $key){
				
				$temp[] = '备注';
				$temp[] = $value;
			}
			
			
			if($temp){
	 			$list[] = $temp;
	 		}
	 		
	 	}
		
		return $list;
	}
	
	
	/**
	 * 解析物业能耗费自定义数据
	 */
	public function getWuyeOrderExtraInfo($extraArray){
		
		$list = array();
		
		foreach($extraArray as $key => $value ){
			$temp = array();
			
			if('reason' == $key){
				
				$temp[] ="退款原因";
				$temp[] = $value;
	 		 	
	 		}else  if('remark' ==$key){
	 			$temp[] ="备注";
				$temp[] = $value;
	 		}
	 		
	 		if($temp){
	 			$list[] = $temp;
	 		}
	 		
	 	}
	 	
	 	return $list;
	}
	
	
	/**
	 * 保洁
	 */
	public function getBaojieExtraInfo($extraArray){
		
		
		$list = array();
		
		foreach($extraArray as $key => $value ){
			$temp = array();
			
			if('username' == $key){
				$temp[] ="预约人名称：";
				$temp[] = $value;
				
			}else if('visit_time' == $key){
				$temp[] ="上门时间";
				$temp[] = $value;
				
			}else if('address'  == $key){
				$temp[] ="上门地址";
				$temp[] = $value;
				
			}else if('bz'  == $key){
				$temp[] ="用户备注";
				$temp[] = $value;
				
			}else if('reason' == $key){
				$temp[] ="退款原因";
				$temp[] = $value;
	 		 	
	 		}else  if('remark' ==$key){
	 			$temp[] ="备注";
				$temp[] = $value;
	 		}
	 		
	 		if($temp){
	 			$list[] = $temp;
	 		}
	 		
	 	}
	 	
	 	return $list;
	 	
	 	
	}
	
	
	/**
	 * 解析额外信息
	 */
	public function extraInfoToArray($pOrderInfo){
		
		$item = array();
		
		if(strpos($pOrderInfo['order_typename'],'保洁') !== false ){
			$item = $this->getBaojieExtraInfo($pOrderInfo['extra_info']);
			
		}elseif(strpos($pOrderInfo['order_typename'],'预约单') !== false){
			$item = $this->getStaffExtraInfo($pOrderInfo['extra_info']);
		
  		}elseif(in_array($pOrderInfo['order_typename'],array('物业费','能耗费','车位费'))){
			$item = $this->getWuyeOrderExtraInfo($pOrderInfo['extra_info']);
			
		}else{
			//@TODO more here
			
		}
		
		return $item;
		
	}
	
	
	////////////验证规则/////////////////
	
	public function setOrderIdRules(){
		self::$CI->form_validation->set_rules('order_id','订单ID','required|in_db_list['.$this->_orderModel->getTableRealName().'.order_id]');
	}
	
	
	/**
	 * 检查是否用户订单
	 */
	public function checkIsUserOrder($uid,$orderId = ''){
		
		$cnt = $this->_orderModel->getCount(array(
			'where' => array(
				'order_id' => $orderId,
				'uid' => $uid
			)
		));
		
		if($cnt == 0){
			self::$CI->form_validation->set_message('checkIsUserOrder_callable','订单号不正确');
			return false;
		}else{
			return true;
		}
		
		
	}
	
	
	/**
	 * 退款 相关数据回滚
	 */
	public function updateOrderRefundRelation($pParam){
		
		$payYear = date('Y',$pParam['fee_start']);
		
		$this->_planModel->setTableId($pParam['year']);
		$this->_planDetailModel->setTableId($pParam['year']);
		
		if('物业费' == $pParam['order_typename']){
			
			//@TODO 如果用户在付款后 和退款前 更新了该物业绑定的车位 ,可能会引起BUG
			$this->_parkingModel->updateByWhere(array(
				'expire' => $pParam['fee_old_expire'],
			),array('house_id' => $pParam['goods_id']));
			
			
			$this->_houseModel->updateByWhere(array(
				'wuye_expire' => $pParam['fee_old_expire'],
			),array('id' => $pParam['goods_id']));
			
		}else if('能耗费' == $pParam['order_typename']){
			
			$this->_houseModel->updateByWhere(array(
				'nenghao_expire' => $pParam['fee_old_expire'],
			),array('id' => $pParam['goods_id']));
		}
		
		//计划明细 修改为已退款
		$this->_planDetailModel->updateByWhere(array(
			'order_status' => OrderStatus::$refounded,
		),array(
			'order_id' => $pParam['order_old']
		));
		
		//计划记录  修改为已退款
		$this->_planModel->updateByWhere(array(
			'order_status' => OrderStatus::$refounded,
		),array(
			'order_id' => $pParam['order_old']
		));
		
	}
	
	
	
	/**
	 * 创建订单后更新计划表、房屋表、详情表、历史表
	 */
	public function updateOrderRelation($pParam){
		
		if(empty($pParam['year'])){
			$pParam['year'] = date('Y',$pParam['fee_expire']);
		}
		
		$this->_planModel->setTableId($pParam['year']);
		$this->_planDetailModel->setTableId($pParam['year']);
		
		$this->_orderModel->updateByWhere(array(
			'status' => OrderStatus::$payed,
		),array(
			'order_id' => $pParam['order_id'],
		));

		
		$houseItem = $this->_houseModel->getFirstByKey($pParam['goods_id'],'id');
		
		if(empty($pParam['utype'])){
			if($pParam['uid'] == $houseItem['uid'])
			{
				$pParam['utype'] = Utype::$seifpaid;
			}else{
				$houseYezhu = $this->_houseYezhuModel->getList(array('where' => array('house_id' => $pParam['goods_id'],'uid' => $pParam['uid'])));
				if($houseYezhu){
					$pParam['utype'] =Utype::$housepaid;
				}else{
					$pParam['utype'] = Utype::$otherpaid;
				}
				
			}

		}
		$feetypeItem = $this->_feetypeModel->getById(array(
			'where' => array(
				'resident_id' => $houseItem['resident_id'],
				'year' => $pParam['year'],
				'name' => $pParam['order_typename']
			)
		));
		
		$feeRule = json_decode($feetypeItem['fee_rule'],true);
			
		if('物业费' == $pParam['order_typename']){
				
			$this->_houseModel->updateByWhere(array(
				'wuye_expire' => $pParam['fee_expire'],
			),array(
				'id' => $pParam['goods_id'],
			));
			
			$this->_parkingModel->updateByWhere(array(
				'expire' => $pParam['fee_expire']
			),array(
				'house_id' => $pParam['goods_id'],
			));
			
			$this->_planModel->increseOrDecrease(array(
				array('key'  => 'amount_payed', 'value' => $pParam['amount']/100),
				array('key'  => 'order_id', 'value' => $pParam['order_id']),
				array('key'  => 'order_status', 'value' => OrderStatus::$payed),
				array('key'  => 'pay_time', 'value' => $pParam['pay_time']),
				array('key'  => 'uid2', 'value' => $pParam['uid']),
				array('key'  => 'utype', 'value' => $pParam['utype']),
			),array(
				'house_id' => $pParam['goods_id'],
				'feetype_name' => $pParam['order_typename']
			));
			
			$this->_planDetailModel->increseOrDecrease(array(
				array('key'  => 'amount_payed', 'value' => 'amount_real'),
				array('key'  => 'order_id', 'value' => $pParam['order_id']),
				array('key'  => 'order_status', 'value' => OrderStatus::$payed),
				array('key'  => 'pay_time', 'value' => $pParam['pay_time']),
				array('key'  => 'uid2', 'value' => $pParam['uid']),
				array('key'  => 'utype', 'value' => $pParam['utype']),
			),array(
				'house_id' => $pParam['goods_id'],
				'fee_gname' => $pParam['order_typename']
			));
			
		}else if('能耗费' == $pParam['order_typename']){
			
			$this->_houseModel->updateByWhere(array(
				'nenghao_expire' => $pParam['fee_expire'],
			),array(
				'id' => $pParam['goods_id'],
			));
			
	 		$nenghaoDetail = array(
	 			'house_id' => $pParam['goods_id'],
				'address' => $pParam['goods_name'],
				'resident_id' => $pParam['resident_id'],
				'resident_name' => $pParam['resident_name'],
				'wuye_type' => $feeRule[0]['wuyeType'],
				'year' => $pParam['year'],
				'jz_area' => $houseItem['jz_area'],
				'price' => $feeRule[0]['price'],
				'month_payed' => $pParam['fee_month'],
				'fee_gname' => $pParam['order_typename'],
				'feetype_name' => $feeRule[0]['feeName'],
				'wuye_type' => $feeRule[0]['wuyeType'],
				'billing_style' => $feeRule[0]['billingStyle'],
				'amount_plan' => $feeRule[0]['price']*12,
				'amount_real' => $feeRule[0]['price']*12,
				'amount_payed' => $pParam['amount']/100,
				'order_id' => $pParam['order_id'],
				'order_status' => OrderStatus::$payed,
				'pay_time' => $pParam['pay_time'],
				'month' => date('m',$pParam['fee_expire']),
				'stat_date' => $pParam['fee_start'],
				'end_date' => $pParam['fee_expire'],
				'uid2' => $pParam['uid'],
				'utype' => $pParam['utype'],
			);
			
			
			
			$this->_planDetailModel->_add($nenghaoDetail);
			
			$this->_planModel->increseOrDecrease(array(
				array('key'  => 'amount_payed', 'value' => "amount_payed + {$nenghaoDetail['amount_payed']}"),
				array('key'  => 'order_id', 'value' => $pParam['order_id']),
				array('key'  => 'order_status', 'value' => OrderStatus::$payed),
				array('key'  => 'uid2', 'value' => $pParam['uid']),
				array('key'  => 'utype', 'value' => $pParam['utype']),
			),array(
				'house_id' => $pParam['goods_id'],
				'feetype_name' => $pParam['order_typename']
			));
			
		}
		
		return true;
	}
	
	
}
