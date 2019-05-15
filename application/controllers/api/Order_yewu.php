<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(WEIXIN_PAY_PATH.'WxPay.Api.php');
require_once(WEIXIN_PAY_PATH.'WxPay.Notify.php');


/**
 * 物业费,能耗费  支付回调
 */
class PayNotifyCallBack extends WxPayNotify
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
		
		$orderInfo = $this->_ci->order_service->getOrderInfoById($data['out_trade_no'],'order_id');
		if($orderInfo['amount'] != $data['total_fee']){
			$msg = "订单金额错误";
			return false;
		}
		
		if($orderInfo['status'] != OrderStatus::$unPayed){
			$msg = "订单状态错误";
			return false;
		}
		
		//启用事务
		$this->_ci->Order_Model->beginTrans();
		
		$affectRow = $this->_ci->Order_Model->updateByWhere(
			array('ref_order' => $data['transaction_id'],'status' => OrderStatus::$payed , 'pay_time_end' => $data['time_end']),
			array('order_id' => $data['out_trade_no'],'status' => OrderStatus::$unPayed)
		);

		
		if($affectRow > 0 ){
			
			//@TODO 可能出现跨年订单引起的问题， 创建时间 和付款时间 跨年
			$orderInfo['year'] = date('Y',$orderInfo['gmt_create']);
			$orderInfo['pay_time'] = time();
			
			$this->_ci->order_service->updateOrderRelation($orderInfo);

			if($this->_ci->Order_Model->getTransStatus() === FALSE){
				$this->_ci->Order_Model->rollBackTrans();
				
				$msg = "订单数据更新失败";
				
				return false;
			}else{
				$this->_ci->Order_Model->commitTrans();
				
				$this->_ci->weixin_service->wuyeOrderNotify($orderInfo);
				
				$this->_ci->load->library('Admin_pm_service');
				
				$this->_ci->admin_pm_service->addOrderMessage($orderInfo);
				
				return true;
			}
			
		}else if($affectRow == 0){
			
			$shortInfo = $this->_ci->Order_Model->getFirstByKey($data['out_trade_no'],'order_id','status');
			if($shortInfo['status'] == OrderStatus::$payed){
				
				if($this->_ci->Order_Model->getTransStatus() === FALSE){
					return false;
				}
				
				$this->_ci->Order_Model->commitTrans();
				
				return true;
			}else{
				
				$this->_ci->Order_Model->rollBackTrans();
				
				$msg = "订单数据更新失败";
				return false;
			}
		}
		
	}
}


class Order_yewu extends Wx_Tdkc_Controller {
	
	public function __construct(){
		parent::__construct();
        
        $this->load->library(array('Order_service'));
    	
    	
    	
	}
	
	/**
	 * 订单异步回调通知
	 */
	public function notify(){
		
		try {
			
			$notify = new PayNotifyCallBack();
			$notify->setController(get_instance());
			
			$notify->Handle();
			
		}catch(WxPayException $e1){
			$message = $e1->getMessage();
		}catch(Exception $e){
			$message = $e1->getMessage();
		}
		
	}
	

}
