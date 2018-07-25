<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once dirname(__FILE__)."/Weixin_refund.php";


/**
 * 物业费 、能耗费 退款
 */
class Order_wuye_refund extends Weixin_refund {
	
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 业务处理
	 */
	public function process($pRefundOrder,$refundResp){
		
		$this->_ci->load->library(array('Wuye_service'));
		
		
		$updateFiled = 'wuye_expire';
		
		$houseWuyeInfo = json_decode($pRefundOrder['extra_info'],true);
		switch($pRefundOrder['order_typename']){
			case '物业费退款':
				$updateFiled = 'wuye_expire';
				break;
			case '能耗费退款':
				$updateFiled = 'nenghao_expire';
				break;
			default:
				return false;
				break;
		}
		
		//还原缴费时间
		$this->_ci->House_Model->updateByWhere(array(
			$updateFiled => $houseWuyeInfo['fee_start'],
		),array('id' => $houseWuyeInfo['house_id']));
		
	
	}
	

}
