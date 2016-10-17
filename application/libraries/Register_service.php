<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_service extends Base_service {

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Security_Control_Model');
		self::$CI->load->library('Verify_service');
	}
	
	
	/**
	 * 手机号码 半注册
	 * @tod 后续加入 暂时未用
	 */
	public function createHalfRegisterMemebr($regParam){
		$return = $this->formatArrayReturn();
		
		$uid = self::$memberModel->_add(array(
			'nickname' => $regParam['mobile'],
			'mobile' => $regParam['mobile'],
			'reg_ip' => $regParam['reg_ip'],
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
		
		/*
		 * do it mannally at first
		self::$CI->Security_Control_Model->deleteByCondition(array(
			'where' => array(
				'gmt_create <= ' => $now - 259200 // clear tree days before
			),
			'limit' => 10
		));
		*/
		
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
	 * 会员增加公共验证规则
	 */
	public function memberAddRules(){
		
		
		self::$form_validation->reset_validation();
		self::$form_validation->set_rules('mobile','手机号',array(
					'required',
					'valid_mobile',
					array(
						'loginname_callable[mobile]',
						array(
							self::$memberModel,'isUnqiueByKey'
						)
					)
				),
				array(
					'loginname_callable' => '%s已经被注册'
				)
			);
			
		
		self::$form_validation->set_rules('mobile_auth_code','手机验证码', array(
					'required',
					array(
						'authcode_callable['.self::$CI->input->post('mobile').']',
						array(
							self::$CI->verify_service,'validateAuthCode'
						)
					)
				),
				array(
					'authcode_callable' => '手机验证码不正确'
				)
			);
		
		self::$form_validation->set_rules('username','登陆账号',array(
					'required',
					'min_length[2]',
					'max_length[8]',
					'valid_username',
					array(
						'username_callable[username]',
						array(
							self::$memberModel,'isUnqiueByKey'
						)
					)
				),
				array(
					'username_callable' => '%s已经被注册'
				)
			);
			
		self::$form_validation->set_rules('qq','用户QQ号码', 'required|numeric|min_length[5]|max_length[12]');
		self::$form_validation->set_rules('email','用户常用邮箱', 'required|valid_email');
		self::$form_validation->set_rules('psw','密码','required|valid_password|min_length[6]|max_length[12]');
		self::$form_validation->set_rules('psw_confirm','密码确认','required|matches[psw]');
		self::$form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
		
		//self::$form_validation->set_rules('agreee_licence','同意注册条款','required');
		
	}
	
	
	
	/**
	 * 创建会员
	 */
	public function createMember($regParam){
		$return = $this->formatArrayReturn();
		
		$regParam['reg_ip'] = self::$CI->input->ip_address();
		$regParam['reg_date'] = self::$CI->input->server('REQUEST_TIME');
		$regParam['password'] = self::$CI->encrypt->encode($regParam['password']);
		
		
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
