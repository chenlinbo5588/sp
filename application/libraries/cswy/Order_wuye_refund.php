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
	public function customHandle($pRefundOrder,$refundResp){
		
		try {
			
			$this->commonOrderUpdate($pRefundOrder['order_id'],$refundResp);
			
			$this->_ci->load->library(array('Wuye_service'));
			
			
			if('车位费' != $pRefundOrder['order_typename']){
				
				$updateFiled = 'wuye_expire';
				
				
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
				
				
				//还原缴费时间
				$this->_ci->House_Model->updateByWhere(array(
					$updateFiled => $pRefundOrder['fee_old_expire'],
				),array('id' => $pRefundOrder['goods_id']));
				
			}else{
				
				$this->_ci->Parking_Model->updateByWhere(array(
					'expire' => $pRefundOrder['fee_old_expire'],
				),array('id' => $pRefundOrder['goods_id']));
				
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
