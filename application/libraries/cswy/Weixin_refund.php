<?php
/**
 * 
 * 微信退款基础类
 * 
 * @author chenlinbo 
 *
 */
class Weixin_refund 
{
	
	protected $_ci ;
	
	public function __construct(){
		
	}
	
	
	public function setController($pController){
		$this->_ci = $pController;
	}
	
	
	/**
	 * 公共订单信息更新
	 */
	protected function commonOrderUpdate($pOrderId,$refundResp){
		//启用事务
		$this->_ci->load->library(array('Order_service'));
		
		$this->_ci->Order_Model->beginTrans();
		
		//对退款订单做修改
		$this->_ci->Order_Model->updateByWhere(array(
			'ref_order' => $refundResp['transaction_id'],
			'ref_refund' => $refundResp['refund_id'],
			'pay_time_end' => date("YmdHis"),
			'status' => OrderStatus::$refounded
		),array('order_id' => $pOrderId));
		
	}
	
	
	/**
	 * 
	 */
	public function customHandle($pRefundOrder,$refundResp){
		//TODO 用户基础该类之后需要重写该方法，
		
		
		return true;
	}
	
	
	/**
	 * 
	 */
	protected function updateOrderRefundStat($pOldOrderId,$refundFee){
		
		
		file_put_contents('callback_refund.txt',print_r(array(
			array('key'  => 'refund_amount', 'value' => "refund_amount + {$refundResp['refund_fee']}"),
			array('key'  => 'refund_cnt', 'value' => "refund_cnt + 1"),
		),true),FILE_APPEND);
		
		
		//对原订单进行处理
		return $this->_ci->Order_Model->increseOrDecrease(array(
			array('key'  => 'refund_amount', 'value' => "refund_amount + {$refundFee}"),
			array('key'  => 'refund_cnt', 'value' => "refund_cnt + 1"),
		),array('order_id' => $pOldOrderId));
		
		
	}
	
	
}