<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_Service extends Base_Service {

	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Security_Control_Model');
	}
	
	
	/**
	 * 手机号码 半注册
	 */
	public function createHalfRegisterMemebr($regParam){
		$return = $this->formatArrayReturn();
		
		$uid = $this->_userModel->_add(array(
			'username' => $regParam['mobile'],
			'mobile' => $regParam['mobile'],
			'regip' => $regParam['regip'],
			'status' => -1,
		));
		
		if($uid > 0){
			$return = $this->successRetun(array('uid' => $uid));
		}
		
		return $return;
	}
	
	/**
	 * 业务逻辑 用统一个 IP 不能 ，在一天只内最多只能注册 3 个
	 */
	public function getIpLimit($ip, $time = 86400){
		$count = $this->CI->Security_Control_Model->getCount(array(
			'where' => array(
				'ip' => $ip,
				'gmt_create >=' => $this->CI->input->server('REQUEST_TIME') - $time,
				'gmt_create <=' => $this->CI->input->server('REQUEST_TIME')
			)
		));
		
		return $count;
	}
	
	
	
	public function createMemberByEmail($regParam){
		$return = $this->formatArrayReturn();
		
		$uid = $this->_userModel->_add(array(
			'email' => $regParam['email'],
			'nickname' => $regParam['nickname'],
			'password' => $regParam['password'],
			'regip' => $regParam['regip'],
			'status' => -1,
		));
		
		if($uid > 0){
			$return = $this->successRetun(array('uid' => $uid));
			$this->CI->Security_Control_Model->_add(array(
				'ip' => $regParam['regip'],
				'action' => 'register',
				'extra' => $uid
			));
		}
		
		return $return;
		
	}
	
	
	
}
