<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(WEIXIN_PAY_PATH.'WxPay.Api.php');


/**
 * 订单状态
 */
class OrderStatus
{
	//未支付
	public static $unPayed = 0;
	
	//已支付
	public static $payed = 1;
	
	//退款中
	public static $refounding = 2;
	
	//退款完成
	public static $refounded = 3;
	
	//关闭
	public static $closed = 4;
	
	public static $statusName = array(
		'未支付',
		'已支付',
		'退款中',
		'退款完成',
		'关闭',
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
		
		self::$CI->load->library(array('Weixin_service','Wuye_service'));
		
		
		self::$CI->load->model(array(
			'Order_Model','Order_Type_Model'
		));
		
		$this->_orderModel = self::$CI->Order_Model;
		
		$this->_weixinServiceObj = self::$CI->weixin_service;
		$this->_wuyeServiecObj = self::$CI->wuye_service;
		
		$this->_paymentConfig = config_item('payment');
		
		
		$this->_orderTypeModel = self::$CI->Order_Type_Model;
		
		self::$orderType = array(
			'idKey' => $this->_orderTypeModel->getList(array(),'id'),
			'nameKey' => $this->_orderTypeModel->getList(array(),'name'),
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
	 * 请求微信退款
	 */
	public function requestWeixinRefund($pRefundOrder,$refundObj){
		
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
			
			file_put_contents('weixin_refund.txt',print_r($pRefundOrder,true));
			file_put_contents('weixin_refund.txt',print_r($refundResp,true),FILE_APPEND);
			
			
			if(!$this->checkWeixinRespSuccess($refundResp)){
				return false;
			}
			
			return $refundObj->customHandle($pRefundOrder,$refundResp);
			
		}catch(WxPayException $payException){
			log_message('error',"code=".$payException->getCode().",message=".$payException->errorMessage());
			
		}catch(Exption $e){
			//error
			log_message('error',"code=".$e->getCode().",message=".$e->errorMessage());
		}
		
	}
	
	
	
	/**
	 * 创建退订单
	 */
	public function createRefundOrder($pOrderParam,$foreceNew = false){
		
		$tuiOrder = array();
		
		if($pOrderParam['refund_id']){
			//退款中订单 继续退款
			$tuiOrder = $this->getOrderDetailByOrderId($pOrderParam['refund_id']);
			
		}else if($pOrderParam['order_id']){
			//正常订单退款
			$oldOrderInfo = $this->getOrderDetailByOrderId($pOrderParam['order_id']);
			
			if(strpos($oldOrderInfo['order_typename'],'退款') === false){
				//检查一下是否有退款中的退款订单
				$tempOrder = $this->_orderModel->getList(array(
					'where' => array(
						'order_old' => $oldOrderInfo['order_id'],
						'status' => OrderStatus::$refounding
					),
					'limit' => 1
				));
				
				if($tempOrder[0]){
					$tuiOrder = $tempOrder[0];
				}
			}else{
				//本身就是退款单
				$tuiOrder = $oldOrderInfo;
			}
			
		}
		
		if(!empty($tuiOrder)){
			return $tuiOrder;
		}else{
			//创建一个退订单
			$tuiOrderType = self::$orderType['nameKey'][$oldOrderInfo['order_typename'].'退款'];
			
			$tuiOrder = array(
				'order_type' => $tuiOrderType['id'],
				'order_typename' => $tuiOrderType['name'],
				'pay_channel' => $oldOrderInfo['pay_channel'],
				'pay_method' => $oldOrderInfo['pay_method'],
				'amount' => $oldOrderInfo['amount'],//原订单金额
				'refund_amount' => $pOrderParam['amount'],//退款金额
				'uid' => $pOrderParam['uid'],
				'add_uid' => $pOrderParam['add_uid'], //@todo 需要后增加退款审核,这里人就不一样
				'add_username' => $pOrderParam['add_username'],
				'mobile' => $pOrderParam['mobile'],
				'status' => OrderStatus::$refounding,
				'order_old' => $pOrderParam['order_id'],
			);
			
			if($pOrderParam['extra_info']){
				$tuiOrder['extra_info'] = $pOrderParam['extra_info'];
			}
			
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
		
		file_put_contents('weixinOrder.txt',print_r($localOrder,true));
		
		if($param['order_id']){
			//已有订单重新签发
			return $this->genPaymentParam($localOrder['prepay_id']);
			
		}else{
			try {
				
				$input = new WxPayUnifiedOrder();
			
				$input->SetBody($this->_appConfig['name'].'-'.$param['order_typename']);
				//$input->SetAttach("test");
				
				$input->SetOut_trade_no($localOrder['order_id']);
				
				//测试阶段 始终用 1分
				$input->SetTotal_fee($localOrder['amount']);
				
				$input->SetTime_start($localOrder['time_start']);
				$input->SetTime_expire($localOrder['time_expire']);
				
				
				if($localOrder['goods_tag']){
					$input->SetGoods_tag($localOrder['goods_tag']);
				}
				
				$input->SetNotify_url($param['notify_url']);
				
				$input->SetTrade_type("JSAPI");
				
				if($localOrder['goods_id']){
					$input->SetProduct_id($localOrder['goods_id']);
				}
				
				$input->SetOpenid($param['openid']);
				
				$weixinOrder = WxPayApi::unifiedOrder($input);
				
				file_put_contents('weixinOrder.txt',print_r($weixinOrder,true),FILE_APPEND);
				
				if($this->checkWeixinRespSuccess($weixinOrder)){
					
					//将 prepay_id 保存起来, 用来在用户取消订单后，后续可以再次进行下发 换起支付的参数
					$this->_orderModel->updateByWhere(array(
						'prepay_id' => $weixinOrder['prepay_id']
					),array(
						'id' => $localOrder['id']
					));
					
					return $this->genPaymentParam($weixinOrder['prepay_id']);
				}else{
					log_message('error',"return_code={$weixinOrder['return_code']},return_msg={$weixinOrder['return_msg']},order_id={$localOrder['order_id']}");
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
	 * 获得订单列表
	 */
	public function getOrderListByCondition($pCondition){
		$condition = array(
			'select' => 'order_id,order_typename,status,amount,time_start,time_expire',
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
	 * 根据订单号码获得订单详情
	 */
	public function getOrderDetailByOrderId($pOrderId,$pUser = array()){
		
		if(empty($pOrderId)){
			return false;
		}
		
		$condition = array(
			'where' => array(
				'order_id' => $pOrderId
			)
		);
		
		if($pUser){
			$condition['where']['uid'] = $pUser['uid'];
		}
		
		$orderInfo = $this->_orderModel->getById($condition);
		
		$statusNameList = OrderStatus::$statusName;
		
		if($orderInfo){
			$orderInfo['statusName'] = $statusNameList[$orderInfo['status']];
		}
		
		return $orderInfo;
		
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

	
	
	
	
	////////////验证规则/////////////////
	
	public function setOrderIdRules(){
		self::$CI->form_validation->set_rules('order_id','订单ID','required|in_db_list['.$this->_orderModel->getTableRealName().'.order_id]');
	}
	
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
	
}
