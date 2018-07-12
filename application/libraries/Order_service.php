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
	 * 微信签名算法
	 * 
	 * @todo 可能需要改造
	 */
	public function makeSignature($param,$signKey)
	{
		//签名步骤一：按字典序排序参数
		ksort($param);
		$string = $this->ToUrlParams($param);
		
		
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".$signKey;
		
		//签名步骤三：MD5加密
		$string = md5($string);
		
		
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}
	
	/**
	 * 转换成 请求字符串
	 */
	public function ToUrlParams($param)
	{
		$buff = "";
		foreach ($param as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	
	
	
	/**
	 * 创建业务订单
	 */
	public function createBussOrder($param){
		$order = array();
		
		//这两个不需要填写
		//$order['app_id'] = $this->_appConfig['appid'];
		//$order['mch_id'] = $this->_appConfig['mch_id'];
		
		$order['order_id'] = $this->_appConfig['mch_id'].date("YmdHis").mt_rand(100,999);
		
		if($this->_paymentConfig['channel'][$param['pay_channel']]){
			$order['pay_channel'] = $this->_paymentConfig['channel'][$param['pay_channel']];
			
			if($this->_paymentConfig['method'][$param['pay_channel']][$param['pay_method']]){
				$order['pay_method'] = $this->_paymentConfig['method'][$param['pay_channel']][$param['pay_method']];
			}
		}
		
		
		if($param['product_id']){
			$order['goods_id'] = $param['product_id'];
		}
		
		if($param['goods_tag']){
			$order['goods_tag'] = $param['goods_tag'];
		}
		
		$order['order_typename'] = $param['order_typename'];
		
		$order['time_start'] = date("YmdHis");
		$order['time_expire'] = date("YmdHis", time() + 600);
		
		$order['openid'] = $param['openid'];
		$order['extra_info'] = $param['extra_info'];
		
		$order['uid'] = empty($param['uid']) ? 0 : $param['uid'];
		$order['add_uid'] = empty($param['uid']) ? 0 : $param['uid'];
		$order['add_username'] = empty($param['add_username']) ? '' : $param['add_username'];
		
		$this->_orderModel->_add($order);
		$error = $this->_orderModel->getError();
		
		if(QUERY_OK != $error['code']){
			return false;
			
		}else{
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
		
		$input = new WxPayUnifiedOrder();
		
		
		$input->SetBody($this->_appConfig['name'].'-'.$param['order_typename']);
		//$input->SetAttach("test");
		
		$input->SetOut_trade_no($param['order_id']);
		
		//测试阶段 始终用 1分
		$input->SetTotal_fee("1");
		
		$input->SetTime_start($localOrder['time_start']);
		$input->SetTime_expire($localOrder['time_expire']);
		
		
		if($localOrder['goods_tag']){
			$input->SetGoods_tag($param['goods_tag']);
		}
		
		$input->SetNotify_url($param['notify_url']);
		
		$input->SetTrade_type("JSAPI");
		
		if($localOrder['goods_id']){
			$input->SetProduct_id($param['goods_id']);
		}
		
		$input->SetOpenid($weixinUser['openid']);
		
		$weixinOrder = WxPayApi::unifiedOrder($input);
		
		file_put_contents('wuye.txt',print_r($weixinOrder,true),FILE_APPEND);
		
		if('SUCCESS' == $weixinOrder['return_code'] && 'SUCCESS' == $weixinOrder['result_code']){
			$callPayJson = array(
				'timeStamp' => time(),
				'nonce_str' => WxPayApi::getNonceStr(10),
				'package' => 'prepay_id='.$weixinOrder['prepay_id'],
				'signType' => 'MD5'
			);
			
			$callPayJson['paySign'] = $this->makeSignature(array_merge(
				$callPayJson,array('appId' => $this->_appConfig['appid'])
			),$this->_appConfig['mch_key']);
			
			return $callPayJson;
			
		}else{
			
			return false;
		}
		
	}
	
	
}
