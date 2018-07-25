<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once dirname(__FILE__)."/Weixin_refund.php";


/**
 * 家政服务退款
 */
class Order_staff_refund extends Weixin_refund {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 业务处理
	 */
	public function process($pRefundOrder,$refundResp){
		
		$this->_ci->load->library(array('Staff_service'));
		
		//家政服务订单 退款
		
		
	}
	

}
