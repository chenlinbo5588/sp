<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_service extends Base_service {

	
	private $_weixinServiceObj;
	private $_wuyeServiecObj;
	
	private $_orderModel;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library(array('Weixin_service','Wuye_service'));
		
		
		self::$CI->load->model(array(
			'Order_Model',
		));
		
		$this->_orderModel = self::$CI->Order_Model;
		
		$this->_weixinServiceObj = self::$CI->weixin_service;
		$this->_wuyeServiecObj = self::$CI->wuye_service;
		
		
	}
	
	
	/**
	 * 设置微信app config
	 */
	public function setWeixinAppConfig($pConfig){
		
		$this->_weixinServiceObj->setConfig($pConfig);
		
	}
	
	
	
	/**
	 * 创建订单
	 */
	public function createOrder($param,$who){
		
		$param['body'] = '城市物业-'.$param['order_typename'];
		
		
		//创建订单
		
		$param['out_trade_no'] = 'aaaaa';

		
		$this->_weixinServiceObj->makeOrder($param);
		
		
	}
	
	
	
	
}
