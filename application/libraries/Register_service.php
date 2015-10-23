<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_Service extends Base_Service {

	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Security_Control_Model');
	}
	
	
	/**
	 * 手机号码 半注册
	 * @tod 后续加入 暂时未用
	 */
	public function createHalfRegisterMemebr($regParam){
		$return = $this->formatArrayReturn();
		
		$regParam['status'] = -2;
		
		$uid = $this->_memberModel->_add(array(
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
		$now = $this->CI->input->server('REQUEST_TIME');
		
		$count = $this->CI->Security_Control_Model->getCount(array(
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
		
		$regParam['reg_ip'] = $this->CI->input->ip_address();
		$regParam['reg_date'] = $this->CI->input->server('REQUEST_TIME');
						
		
		if(empty($regParam['aid'])){
			$avatarIndex = rand(1,3);
			$regParam['avatar_middle'] = 'img/avatar/'.$avatarIndex.'@middle.jpg';
			$regParam['avatar_small'] = 'img/avatar/'.$avatarIndex.'@small.jpg';
		}
		
		$uid = $this->_memberModel->_add($regParam);
		
		if($uid > 0){
			$return = $this->successRetun(array('uid' => $uid));
			$this->CI->Security_Control_Model->_add(array(
				'ip' => $regParam['reg_ip'],
				'action' => 'register',
				'extra' => $uid
			));
		}
		
		return $return;
		
	}
	
	
	
}
