<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_Service extends Base_Service {

	public function __construct(){
		parent::__construct();
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
	
	
}
