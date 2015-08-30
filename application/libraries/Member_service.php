<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_Service extends Base_Service {
	
	//private $_memberExt;

	public function __construct(){
		parent::__construct();
		
		//$this->CI->load->model('Member_Ext_Model');
		//$this->_memberExt = $this->CI->Member_Ext_Model;
	}
	
	
	/**
	 * 刷新信息
	 */
	public function refreshProfile($uid){
		$this->CI->session->set_userdata(array(
			'profile' => array('basic' => $this->_userModel->getUserByUid($uid))
		));
	}
	
	/**
	 * 登录判断
	 */
	public function do_login($param){
		
		$result = $this->formatArrayReturn();
		$result['message'] = '登陆失败';
		
		$userInfo = $this->_userModel->getFirstByKey($param['mobile'],'mobile');
		
		if(!empty($userInfo)){
			if($userInfo['password'] == $param['password']){
				unset($userInfo['password']);
				
				$result = $this->successRetun(array('basic' => $userInfo));
				
			}else{
				$result['message'] = '密码错误';
			}
		}else{
			$result['message'] = '用户不存在';
		}
		
		return $result;
	}
	
	/**
	 * 
	 */
	public function getUserInfoByMobile($mobile){
		return $this->_userModel->getFirstByKey($mobile,'mobile');
	}
	
	/**
	 * 获得用户信息
	 */
	public function getUserInfoByEmail($email){
		return $this->_userModel->getFirstByKey($email,'email');
	}
	
	/**
	 * 更新用户信息
	 */
	public function updateUserInfo($data,$uid){
		return $this->_userModel->update($data,array('uid' => $uid));
	}
	
	/**
	 * 用户设置所在地
	 */
	public function set_city($param){
		
		return $this->_userModel->update(array(
			'district_bind' => 1,
			'd1' => $param['d1'],
			'd2' => $param['d2'],
			'd3' => $param['d3'],
			'd4' => $param['d4']
		),array('uid' => $param['uid']));
	}
}
