<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(WEIXIN_PAY_PATH.'WxPay.Api.php');


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
	
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library(array('Weixin_service','Wuye_service'));
		
		
		self::$CI->load->model(array(
			'Order_Model',
		));
		
		$this->_orderModel = self::$CI->Order_Model;
		
		$this->_weixinServiceObj = self::$CI->weixin_service;
		$this->_wuyeServiecObj = self::$CI->wuye_service;
		
		$this->_paymentConfig = config_item('payment');
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
		
		$order['app_id'] = $this->_appConfig['appid'];
		$order['mch_id'] = $this->_appConfig['mch_id'];
		
		$order['order_id'] = $this->_appConfig['mch_id'].date("YmdHis").mt_rand(100,999);
		
		if($this->_paymentConfig['channel'][$param['pay_channel']]){
			$order['pay_channel'] = $this->_paymentConfig['channel'][$param['pay_channel']];
			
			if($this->_paymentConfig['method'][$param['pay_channel']][$param['pay_method']]){
				$order['pay_method'] = $this->_paymentConfig['method'][$param['pay_channel']][$param['pay_method']];
			}
		}
		
		if($param['goods_id']){
			$order['goods_id'] = $param['goods_id'];
		}
		
		if($param['goods_name']){
			$order['goods_name'] = $param['goods_name'];
		}
		
		if($param['goods_tag']){
			$order['goods_tag'] = $param['goods_tag'];
		}
		
		$order['order_typename'] = $param['order_typename'];
		
		$order['time_start'] = date("YmdHis");
		$order['time_expire'] = date("YmdHis", time() + 600);
		
		$order['amount'] = $param['amount'];
		
		
		if($param['extra_info']){
			$order['extra_info'] = $param['extra_info'];
		}
		
		$order['uid'] = empty($param['uid']) ? 0 : $param['uid'];
		$order['add_uid'] = empty($param['uid']) ? 0 : $param['uid'];
		$order['add_username'] = empty($param['add_username']) ? '' : $param['add_username'];
		
		$order['ip'] = self::$CI->input->ip_address();
		
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
	 * 创建订单
	 */
	public function createWeixinOrder($param,$weixinUser){
		
		$param['openid'] = $weixinUser['openid'];
		
		
		if($param['order_id']){
			$localOrder = $this->_orderModel->getFirstByKey($param['order_id'],'order_id');
		}else{
			$localOrder = $this->createBussOrder($param);
		}
		
		if(empty($localOrder)){
			return false;
		}
		
		file_put_contents('wuye.txt',print_r($localOrder,true),FILE_APPEND);
		
		if($param['order_id']){
			//已有订单重新签发
			return $this->genPaymentParam($localOrder['prepay_id']);
			
		}else{
			
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
			
			file_put_contents('wuye.txt',print_r($weixinOrder,true),FILE_APPEND);
			
			if('SUCCESS' == $weixinOrder['return_code'] && 'SUCCESS' == $weixinOrder['result_code']){
				
				//将 prepay_id 保存起来, 用来在用户取消订单后，后续可以再次进行下发 换起支付的参数
				$this->_orderModel->updateByWhere(array(
					'prepay_id' => $weixinOrder['prepay_id']
				),array(
					'id' => $localOrder['id']
				));
				
				return $this->genPaymentParam($weixinOrder['prepay_id']);
			}else{
				return false;
			}
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
	
}
