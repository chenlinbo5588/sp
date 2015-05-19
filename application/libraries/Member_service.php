<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_Service extends Base_Service {

	private $_userModel;


	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Member_Model');
		$this->_userModel = $this->CI->Member_Model;
	}
	/*
	public function test(){
		echo 'aa;';
	}
	*/
	
	public function do_login($param){
		$result = array(
			'code' => -1,
			'message' => '登陆失败'
		);
		
		$userInfo = $this->_userModel->getUserByUserName($param['username'],'uid,username,password,status,groupid,credits,freeze');
		
		
		if(!empty($userInfo)){
			if($userInfo['password'] == $param['password']){
				unset($userInfo['password']);
				
				$result = array(
					'code' => SUCCESS_CODE,
					'message' => '登陆成功',
					'memberinfo' => $userInfo
				);
			}else{
				$result['message'] = '密码错误';
			}
		}else{
			$result['message'] = '用户不存在';
		}
		
		return $result;
	}	
	
	
}
