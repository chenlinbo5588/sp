<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 物业费 、能耗费 退款
 */
class Order_wuye_refund {
	
	
	private $_ci ;
	
	public function __construct(){
		
	}
	
	
	public function setController($pController){
		$this->_ci = $pController;
	}
	
	
	/**
	 * 业务处理
	 */
	public function process($pRefundOrder,$refundResp){
		
		try {
			
			file_put_contents('wuye_callback_refund.txt',"开始执行业务逻辑1",FILE_APPEND);
			
			
			$this->_ci->load->library(array('Order_service','Wuye_service'));
			
			
			//启用事务
			$this->_ci->Order_Model->beginTrans();
			
			//对退款订单做修改
			$this->_ci->Order_Model->updateByWhere(array(
				'ref_order' => $refundResp['transaction_id'],
				'ref_refund' => $refundResp['refund_id'],
				'status' => OrderStatus::$refounded
			),array('order_id' => $pRefundOrder['order_id']));
			
			
			$updateFiled = 'wuye_expire';
			
			$houseWuyeInfo = json_decode($pRefundOrder['extra_info'],true);
			
			switch($pRefundOrder['order_typename']){
				case '物业费':
					$updateFiled = 'wuye_expire';
					break;
				case '能耗费':
					$updateFiled = 'nenghao_expire';
					break;
				default:
					return false;
					break;
			}
			
			file_put_contents('wuye_callback_refund.txt',"执行业务逻辑2",FILE_APPEND);
			
			
			//还原缴费时间
			$this->_ci->House_Model->updateByWhere(array(
				$updateFiled => $houseWuyeInfo['fee_start'],
			),array('id' => $houseWuyeInfo['house_id']));
			
			
			file_put_contents('wuye_callback_refund.txt',"执行业务逻辑3",FILE_APPEND);
			
			//对原订单进行处理
			$this->_ci->Order_Model->increseOrDecrease(array(
				array('key'  => 'refund_amount', 'value' => "refund_amount + {$refundResp['refund_fee']}"),
				array('key'  => 'refund_cnt', 'value' => "refund_cnt + 1"),
			),array('order_id' => $pRefundOrder['order_old']));
			
			
			file_put_contents('wuye_callback_refund.txt',print_r(array(
				array('key'  => 'refund_amount', 'value' => "refund_amount + {$refundResp['refund_fee']}"),
				array('key'  => 'refund_cnt', 'value' => "refund_cnt + 1"),
			),true),FILE_APPEND);
			
			
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
	

}
