<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verify_Service extends Base_Service {

	protected $_verifyCodeLogModel;
	protected $_expiredSeconds = 120;

	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('VerifyCode_Log_Model');
		$this->_verifyCodeLogModel = $this->CI->VerifyCode_Log_Model;
	}
	
	/**
	 * 
	 */
	public function getPhoneVerifyCodeBeforeExpire($phone){
		$info = $this->_verifyCodeLogModel->getList(array(
			'where' => array(
				'phone' => $phone,
				'expire_time >=' => $this->CI->input->server('REQUEST_TIME')
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
	 * 用统一个 IP 不能 ，同事多次发送不同的手机号码 来请求手机验证码
	 * 60 秒内，不能大于大于 3 条
	 */
	public function getIpCount($ip, $seconds = 60){
		
		$count = $this->_verifyCodeLogModel->getCount(array(
			'where' => array(
				'ip' => $ip,
				'gmt_create >=' => $this->CI->input->server('REQUEST_TIME') - $seconds,
				'gmt_create <=' => $this->CI->input->server('REQUEST_TIME')
			)
		));
		
		return $count;
	}
	
	
	public function sendSuccessAddon($id){
		return $this->_verifyCodeLogModel->sendNormalAddup($id);
	}
	
	public function sendFailedAddon($id){
		return $this->_verifyCodeLogModel->sendFailedAddup($id);
	}
	
	/**
	 * 创建验证码
	 */
	public function createVerifyCode($param,$type = 'numeric', $length = '6'){
		if(empty($param['expire_time'])){
			$param['expire_time'] = time() + $this->_expiredSeconds;
		}
		
		$param['ip'] = $this->CI->input->ip_address();
		$param['code'] = random_string($type,$length);
		
		$id = $this->_verifyCodeLogModel->add($param);
		
		if($id > 0){
			return array('id' => $id, 'code' => $param['code']);
		}
		
		return false;
	}
	
	public function isAuthCodeValidate($phone , $code){
		
		$count = $this->_verifyCodeLogModel->getCount(array(
			'where' => array(
				'phone' => $phone,
				'code' => $code,
				'gmt_create >=' => $this->CI->input->server('REQUEST_TIME') - $this->_expiredSeconds,
				'gmt_create <=' => $this->CI->input->server('REQUEST_TIME')
			)
		));
		
		return $count;
	}
}
