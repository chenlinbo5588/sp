<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verify_service extends Base_service {

	protected $_verifyCodeLogModel;
	protected $_expiredSeconds = 60;

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('VerifyCode_Log_Model');
		$this->_verifyCodeLogModel = self::$CI->VerifyCode_Log_Model;
	}
	
	/**
	 * 
	 */
	public function getPhoneVerifyCodeBeforeExpire($phone){
		$info = $this->_verifyCodeLogModel->getList(array(
			'where' => array(
				'phone' => $phone,
				'expire_time >=' => self::$CI->input->server('REQUEST_TIME')
			),
			'order' => 'id DESC',
			'limit' => 1
		));

		if($info){
			return $info[0];
		}else{
			return '';
		}
		
	}
	
	/**
	 * 用统一个 IP 不能 ，同时多次发送不同的手机号码 来请求手机验证码
	 * 60 秒内，不能大于大于 3 条
	 */
	public function getIpCount($ip, $seconds = 60){
		
		$count = $this->_verifyCodeLogModel->getCount(array(
			'where' => array(
				'ip' => $ip,
				'gmt_create >=' => self::$CI->input->server('REQUEST_TIME') - $seconds,
				'gmt_create <=' => self::$CI->input->server('REQUEST_TIME')
			)
		));
		
		return $count;
	}
	
	/**
	 * 更新发送标志
	 */
	public function updateSendFlag($param,$where){
		return $this->_verifyCodeLogModel->increseOrDecrease($param,$where);
	}
	
	
	/**
	 * 创建验证码
	 */
	public function createVerifyCode($param,$type = 'numeric', $length = '6'){
		if(empty($param['expire_time'])){
			$param['expire_time'] = time() + $this->_expiredSeconds;
		}
		
		$param['ip'] = self::$CI->input->ip_address();
		$param['code'] = random_string($type,$length);
		
		$id = $this->_verifyCodeLogModel->_add($param);
		
		if($id > 0){
			return array('id' => $id, 'code' => $param['code']);
		}
		
		return false;
	}
	
	
	/**
	 * 验证验证码是否有效
	 */
	public function validateAuthCode($code,$phone = ''){
		//echo $code;
		//echo $phone;
		
		if($this->isAuthCodeValidate($phone,$code) > 0){
			return true;
		}
		
		return false;
		
	} 
	
	/**
	 * 执行验证
	 */
	public function isAuthCodeValidate($phone , $code){
		
		$count = $this->_verifyCodeLogModel->getCount(array(
			'where' => array(
				'phone' => $phone,
				'code' => $code,
				'gmt_create >=' => self::$CI->input->server('REQUEST_TIME') - $this->_expiredSeconds,
				'gmt_create <=' => self::$CI->input->server('REQUEST_TIME')
			)
		));
		
		return $count;
	}
}
