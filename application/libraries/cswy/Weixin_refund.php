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
	 * 
	 * 业务处理入口
	 * @param bool 
	 */
	final public function customHandle($pRefundOrder,$refundResp){
		
		
		file_put_contents('callback_refund.txt',print_r($pRefundOrder,true),FILE_APPEND);
		file_put_contents('callback_refund.txt',print_r($refundResp,true),FILE_APPEND);
		
		try {
			//启用事务
			$this->_ci->load->library(array('Order_service'));
			
			
			$this->_ci->Order_Model->beginTrans();
			
			//对退款订单做修改
			$this->_ci->Order_Model->updateByWhere(array(
				'ref_order' => $refundResp['transaction_id'],
				'ref_refund' => $refundResp['refund_id'],
				'status' => OrderStatus::$refounded
			),array('order_id' => $pRefundOrder['order_id']));
			
			
			
			$this->process($pRefundOrder,$refundResp);
			
			//对原订单进行处理
			$this->_ci->Order_Model->increseOrDecrease(array(
				array('key'  => 'refund_amount', 'value' => "refund_amount + {$refundResp['refund_fee']}"),
				array('key'  => 'refund_cnt', 'value' => "refund_cnt + 1"),
			),array('order_id' => $pRefundOrder['order_old']));
			
			
			/*
			file_put_contents('callback_refund.txt',print_r(array(
				array('key'  => 'refund_amount', 'value' => "refund_amount + {$refundResp['refund_fee']}"),
				array('key'  => 'refund_cnt', 'value' => "refund_cnt + 1"),
			),true),FILE_APPEND);
			*/
			
			if($this->_ci->Order_Model->getTransStatus() === FALSE){
				$this->_ci->Order_Model->rollBackTrans();
				return false;
			}else{
				$this->_ci->Order_Model->commitTrans();
				return true;
			}
		}catch(Exception $e){
			
			return false;
		}
		
	}
	
	
	/**
	 * 
	 */
	public function process($pRefundOrder,$refundResp){
		//TODO 用户基础该类之后需要重写该方法，
		
		
		return true;
	}
	
}