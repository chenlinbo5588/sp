<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_Service extends Base_Service {
	
	private $_memberExt;
	


	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Member_Ext_Model');
		$this->_memberExt = $this->CI->Member_Ext_Model;
	}
	
	public function do_login($param){
		
		$result = $this->formatArrayReturn();
		$result['message'] = '登陆失败';
		
		$userInfo = $this->_userModel->getUserByEmail($param['email']);
		
		if(!empty($userInfo)){
			if($userInfo['password'] == $param['password']){
				unset($userInfo['password']);
				
				$result = $this->successRetun(array('memberinfo' => $userInfo));
				
			}else{
				$result['message'] = '密码错误';
			}
		}else{
			$result['message'] = '用户不存在';
		}
		
		return $result;
	}
	
	
	public function getUserInfoByEmail($email){
		return $this->_userModel->getUserByEmail($email);
	}
	
	/**
	 * 用户设置所在地
	 */
	public function set_city($param){
		$rows = $this->_memberExt->_add($param,true);
		if($rows){
			$this->_userModel->update(array(
				'district_bind' => 1
			),array('uid' => $param['uid']));
			
			return $rows;
		}else{
			return false;
		}
		
	}
}
