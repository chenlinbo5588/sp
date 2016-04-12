<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_Service extends Base_Service {
	

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
		
		$userInfo = self::$memberModel->getFirstByKey($param['mobile'],'mobile');
		
		if(!empty($userInfo)){
			if($userInfo['password'] == $param['password']){
				unset($userInfo['password']);
				
				if($userInfo['freeze'] == 'Y'){
					$result['message'] = '您的账号已被冻结,请联系网站客服人员';
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
