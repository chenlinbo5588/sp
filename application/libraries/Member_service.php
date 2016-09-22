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
		
		$findField = '';
		
		if(preg_match('/^\d+$/i',$param['loginname'])){
			$findField = 'mobile';
		}else{
			$findField = 'nickname';
		}
		
		$userInfo = self::$memberModel->getFirstByKey($param['loginname'],$findField);
		
		for($i = 0; $i < 1; $i++){
			
			if(empty($userInfo)){
				$result['message'] = '用户不存在';
				break;
			}
			
			if($userInfo['freeze'] == 'Y'){
				$result['message'] = '您的账号已被冻结,请联系网站客服人员';
				break;
			}
			
			/*
			if($userInfo['email_status'] == 0){
				$result['message'] = '您的账号尚未验证邮箱,暂时不能登录';
				break;
			}
			*/
			
			$newpsw = self::$CI->encrypt->decode($userInfo['password']);
			if($param['password'] != $newpsw){
				$result['message'] = '密码错误';
				break;
			}
			
			$result = $this->successRetun(array('basic' => $userInfo));
		}
		
		return $result;
	}
	
	
	
	/**
	 * 
	 */
	public function getUserInfoById($id){
		return self::$memberModel->getFirstByKey($id,'uid');
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
		return self::$memberModel->update($data,array('uid' => $uid));
	}
	
	/**
	 * 
	 */
	public function getListByCondition($condition){
		return $this->toEasyUseArray(self::$memberModel->getList($condition),'uid');
	}
	
	/**
	 * 用户设置所在地
	 */
	public function set_city($param){
		
		return self::$memberModel->update(array(
			'district_bind' => 1,
			'd1' => $param['d1'],
			'd2' => $param['d2'],
			'd3' => $param['d3'],
			'd4' => $param['d4']
		),array('uid' => $param['uid']));
	}
	
}
