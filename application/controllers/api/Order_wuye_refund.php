<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(WEIXIN_PAY_PATH.'WxPay.Api.php');
require_once(WEIXIN_PAY_PATH.'WxPay.Notify.php');


/**
 * 物业费,能耗费  退款支付回调
 */
class RefundNotifyCallBack extends WxPayNotify
{
	
	private $_ci;
	
	public function setController($pController){
		
		$this->_ci = $pController;
	}
	
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		file_put_contents('wuye_callback_refund.txt',print_r($data,true),FILE_APPEND);
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		
		//根据商户退款订单号获得订单信息
		$orderInfo = $this->_ci->Order_Model->getFirstByKey($data['out_refund_no'],'order_id');
		
		if($orderInfo['amount'] != $data['refund_fee']){
			$msg = "订单金额错误";
			return false;
		}
		
		
		if($orderInfo['status'] == OrderStatus::$refounded){
			return true;
		}
		
		if($orderInfo['status'] != OrderStatus::$refounding){
			$msg = "订单状态错误";
			return false;
		}
		
		file_put_contents('wuye_callback_refund.txt',print_r($orderInfo,true),FILE_APPEND);
		
		//启用事务
		$this->_ci->Order_Model->beginTrans();
		
		$this->_ci->Order_Model->updateByWhere(array(
			'ref_order' => $data['transaction_id'],
			'ref_refund' => $data['refund_id'],
			'refund_amount' => $data['refund_fee'],
			'status' => OrderStatus::$refounded
		),array('order_id' => $data['order_id']));
		
		
		$houseWuyeInfo = json_decode($orderInfo['extra_info'],true);
		
		$this->_ci->load->library('Wuye_service');
		
		$updateFiled = 'wuye_expire';
		
		switch($orderInfo['order_typename']){
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
			$updateFiled => $houseWuyeInfo['fee_start'],
		),array('id' => $houseWuyeInfo['house_id']));
		
		
		//
		$this->_ci->House_Fee_Model->updateByWhere(array(
			'order_status' => OrderStatus::$refounded
		),array('house_id' => $houseWuyeInfo['house_id']));
		
		
		$this->_ci->Order_Model->increseOrDecrease(array(
			array('key'  => 'refund_amount', 'value' => "refund_amount + {$data['refund_fee']}"),
			array('key'  => 'refund_cnt', 'value' => "refund_cnt + 1"),
		),array('order_id' => $orderInfo['order_old']));
		
		
		file_put_contents('wuye_callback_refund.txt',print_r(array(
			array('key'  => 'refund_amount', 'value' => "refund_amount + {$data['refund_fee']}"),
			array('key'  => 'refund_cnt', 'value' => "refund_cnt + 1"),
		),true),FILE_APPEND);
		
		
		
		if($this->_ci->Order_Model->getTransStatus() === FALSE){
			$this->_ci->Order_Model->rollBackTrans();
			return false;
		}else{
			$this->_ci->Order_Model->commitTrans();
			return true;
		}
	}
}


class Order_wuye_refund extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
        
        $this->load->library('Order_service','Wuye_service');
    	$this->form_validation->set_error_delimiters('','');
	}
	
	/**
	 * 订单异步回调通知
	 */
	public function notify(){
		
		try {
			
			$notify = new RefundNotifyCallBack();
			$notify->setController(get_instance());
			
			$notify->Handle();
			
		}catch(WxPayException $e1){
			$message = $e1->getMessage();
		}catch(Exception $e){
			$message = $e1->getMessage();
		}
		
	}
	

}
