<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_service extends Base_service {

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Security_Control_Model');
	}
	
	
	/**
	 * 手机号码 半注册
	 * @tod 后续加入 暂时未用
	 */
	public function createHalfRegisterMemebr($regParam){
		$return = $this->formatArrayReturn();
		
		$regParam['status'] = -2;
		
		$uid = self::$memberModel->_add(array(
			'nickname' => $regParam['mobile'],
			'mobile' => $regParam['mobile'],
			'reg_ip' => $regParam['reg_ip'],
			'status' => -2,
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
		$now = self::$CI->input->server('REQUEST_TIME');
		
		$count = self::$CI->Security_Control_Model->getCount(array(
			'where' => array(
				'ip' => $ip,
				'gmt_create >=' => $now - $time,
				'gmt_create <=' => $now
			)
		));
		
		return $count;
	}
	
	
	/**
	 * 创建会员
	 */
	public function createMember($regParam){
		$return = $this->formatArrayReturn();
		
		$regParam['reg_ip'] = self::$CI->input->ip_address();
		$regParam['reg_date'] = self::$CI->input->server('REQUEST_TIME');
				
		$uid = self::$memberModel->_add($regParam);
		
		if($uid > 0){
			$return = $this->successRetun(array('uid' => $uid));
			self::$CI->Security_Control_Model->_add(array(
				'ip' => $regParam['reg_ip'],
				'action' => 'register',
				'extra' => $uid
			));
		}
		
		return $return;
		
	}
	
	
	
}
