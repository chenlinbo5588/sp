<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_service extends Base_service {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 刷新信息
	 */
	public function refreshProfile($uid){
		self::$CI->session->set_userdata(array(
			'profile' => array('basic' => self::$memberModel->getUserByUid($uid))
		));
	}
	
	
	/**
	 * 登录判断
	 */
	public function do_login($param){
		
		$result = $this->formatArrayReturn();
		$result['message'] = '登陆失败';
		
		$userInfo = self::$memberModel->getFirstByKey($param['account'],'account');
		
		if(!empty($userInfo)){
			
			if($userInfo['psw'] == md5(config_item('encryption_key').$param['password'])){
				unset($userInfo['psw']);
				
				if($userInfo['locked'] != 0){
					$result['message'] = '您的账号已被冻结,请联系网站管理人员';
				}else{
					$result = $this->successRetun(array('basic' => $userInfo));
				}
				
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
	public function getUserInfoById($id){
		return self::$memberModel->getFirstByKey($id,'id');
	}
	
	public function getUserInfoByKey($value,$key){
		return self::$memberModel->getFirstByKey($value,$key);
	}
	/**
	 * 
	 */
	public function getUserInfoByMobile($mobile){
		return self::$memberModel->getFirstByKey($mobile,'mobile');
	}
	
	/**
	 * 获得用户信息
	 */
	public function getUserInfoByEmail($email){
		return self::$memberModel->getFirstByKey($email,'email');
	}
	
	/**
	 * 更新用户信息
	 */
	public function updateUserInfo($data,$uid){
		return self::$memberModel->update($data,array('id' => $uid));
	}
	
	/**
	 * 
	 */
	public function getListByCondition($condition){
		return $this->toEasyUseArray(self::$memberModel->getList($condition),'id');
	}
}
