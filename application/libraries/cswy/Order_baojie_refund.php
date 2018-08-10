<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once dirname(__FILE__)."/Weixin_refund.php";


/**
 * 保洁退款
 */
class Order_baojie_refund extends Weixin_refund {
	
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 业务处理
	 */
	public function customHandle($pRefundOrder,$refundResp){
		
		file_put_contents('callback_refund.txt',print_r($pRefundOrder,true),FILE_APPEND);
		file_put_contents('callback_refund.txt',print_r($refundResp,true),FILE_APPEND);
		
		try {
			
			$this->commonOrderUpdate($pRefundOrder['order_id'],$refundResp);
			
			if($this->_ci->Order_Model->getTransStatus() === FALSE){
				$this->_ci->Order_Model->rollBackTrans();
				return false;
			}
			
			
			$this->_ci->load->library(array('Staff_service'));
			
			//预约订单 退款
			$affectRow = $this->_ci->Staff_Booking_Model->updateByWhere(array(
				'order_refund' => $pRefundOrder['order_id'],
				'order_status' => OrderStatus::$refounded
			),array(
				'order_id' => $pRefundOrder['order_old'],
				'order_status' => OrderStatus::$payed
			));
			
			if($this->_ci->Order_Model->getTransStatus() === FALSE){
				$this->_ci->Order_Model->rollBackTrans();
				return false;
			}
			
			//更新退款统计信息
			$affectRow = $this->updateOrderRefundStat($pRefundOrder['order_old'],$refundResp['refund_fee']);
			
			if($this->_ci->Order_Model->getTransStatus() === FALSE){
				$this->_ci->Order_Model->rollBackTrans();
				return false;
			}
			
			
			return $this->_ci->Order_Model->commitTrans();
			
		}catch(Exception $e){
			
			$this->_ci->Order_Model->rollBackTrans();
			
			return false;
		}
		
	}
	

}
