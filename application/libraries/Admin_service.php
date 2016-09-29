<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_service extends Base_service {
	

	public function __construct(){
		parent::__construct();
		
	}
    
    /**
	 * 更新用户信息
	 */
	public function updateUserInfo($data,$uid){
		return self::$adminUserModel->update($data,array('uid' => $uid));
	}
	
	
    public function do_adminlogin($email,$password){
		$user = self::$adminUserModel->getFirstByKey($email,'email');
		$message = '失败';
		
		for($i = 0; $i < 1; $i++){
			if(empty($user)){
				$message = '用户名不存在';
				break;
			}
			
			if($user['uid'] != WEBSITE_FOUNDER && $user['status'] == '关闭'){
				$message = '用户已被冻结';
				break;
			}
			
			
			//echo self::$CI->encrypt->encode($password,config_item('encryption_key').md5($user['email']));
			
			
			if($password != self::$CI->encrypt->decode($user['password'],config_item('encryption_key').md5($user['email']))){
				$message = '密码不正确';
				break;
			}
			
			unset($user['password']);
			$message = '成功';
		}
		
		
		return $this->formatArrayReturn(0,$message,array('basic' => $user));
	}
	
}
