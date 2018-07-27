<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(WEIXIN_PAY_PATH.'WxPay.Api.php');
require_once(WEIXIN_PAY_PATH.'WxPay.Notify.php');


/**
 * 月嫂、保姆、陪护  支付回调
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
		file_put_contents('staff_callback.txt',print_r($data,true));
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
		
		$orderInfo = $this->_ci->Order_Model->getFirstByKey($data['out_trade_no'],'order_id');
		if($orderInfo['amount'] != $data['total_fee']){
			$msg = "订单金额错误";
			return false;
		}
		
		if($orderInfo['status'] != OrderStatus::$unPayed){
			$msg = "订单状态错误";
			return false;
		}
		
		
		file_put_contents('staff_callback.txt',print_r($orderInfo,true),FILE_APPEND);
		
		//启用事务
		$this->_ci->Order_Model->beginTrans();
		
		$affectRow = $this->_ci->Order_Model->updateByWhere(
			array('ref_order' => $data['transaction_id'],'status' => OrderStatus::$payed , 'pay_time_end' => $data['time_end']),
			array('order_id' => $data['out_trade_no'],'status' => OrderStatus::$unPayed)
		);
		
		
		$extraInfo = json_decode($orderInfo['extra_info'],true);
		$cartList = $extraInfo['cart'];
		
		$staffIds = array();
		foreach($cartList as $cartItem){
			$staffIds[] = $cartItem['id'];
		}
		
		$staffList = $this->_ci->Staff_Model->getList(array(
			'select' => 'id,service_type,sub_type,name,sex,mobile,avatar_s',
			'where_in' => array(
				array('key' => 'id','value' => $staffIds)
			)
		),'id');
		
		$serviceType = self::$CI->basic_data_service->getTopChildList('业务类型');
		
		//以 id 为下标
		$serviceTypeIdAssoc = array();
		foreach($serviceType as $serviceTypKey => $serviceTypeItem){
			$serviceTypeIdAssoc[$serviceTypeItem['id']] = $serviceTypeItem['show_name'];
		}
		
		$batchInsert = array();
		$dateKey = date('Ymd');
		
		$meetTime = strtotime($extraInfo['meet_time']);
		
		foreach($staffList as $staffItem){
			$batchInsert[] = array(
				'meet_time' => $meetTime,
				'address' => $extraInfo['address'],
				'mobile' => $orderInfo['mobile'],
				'username' => $orderInfo['add_username'],
				'staff_id' => $staffItem['id'],
				'staff_name' => $staffItem['name'],
				'staff_sex' => $staffItem['sex'],
				'service_type' => $staffItem['service_type'],
				'service_name' => $serviceTypeIdAssoc[$staffItem['service_type']],
				'avatar_url' => $staffItem['avatar_s'],
				'expire_key' => date('Ymd',strtotime($extraInfo['meet_time'].' -1 day')),
				'order_id' => $orderInfo['order_id'],
				'order_status' => OrderStatus::$payed,
				'add_uid' => $orderInfo['uid'],
				'add_username' => $orderInfo['add_username'],
			);
		}
		
		//加预约记录加入到
		$this->_ci->Staff_Booking_Model->batchInsert($batchInsert);
		
		$this->_ci->Order_Model->commitTrans();
		
		return true;
		
	}
}


class Order_staff extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
        
        $this->load->library('Order_service','Staff_service');
    	$this->form_validation->set_error_delimiters('','');
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