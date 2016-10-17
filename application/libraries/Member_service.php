<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_service extends Base_service {
	private $_memberSellerModel;
	

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Member_Seller_Model');
		
		$this->_memberSellerModel = self::$CI->Member_Seller_Model;
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
		
		/*
		$findField = '';
		if(preg_match('/^\d+$/i',$param['loginname'])){
			$findField = 'mobile';
		}else{
			$findField = 'nickname';
		}
		*/
		//$userInfo = self::$memberModel->getFirstByKey($param['loginname'],$findField);
		$userInfo = self::$memberModel->getFirstByKey($param['loginname'],'username');
		
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
			//echo $newpsw;
			if($param['password'] != $newpsw){
				$result['message'] = '密码错误';
				break;
			}
			
			$result = $this->successRetun(array('basic' => $userInfo));
		}
		
		return $result;
	}
	
	
	public function getUserInfoById($id){
		return self::$memberModel->getFirstByKey($id,'uid');
	}
	
	/**
	 * 更新用户信息
	 */
	public function updateUserInfo($data,$uid){
		return self::$memberModel->update($data,array('uid' => $uid));
	}
	
	
	/**
	 * 更新用户通知方式
	 */
	public function updateNotifyWays($ways,$uid){
		return self::$memberModel->update(array(
			'notify_ways' => implode(',',$ways)
		),array('uid' => $uid));
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
	
	
	/**
	 * 卖家认证
	 */
	public function sellerVerify($uid,$flag,$message = ''){
		file_put_contents('debug.txt',$uid);
		$data = array(
				'verify_result' => $flag,
				'verify_remark' => $message
			);
		
		if(1 == $data['verify_result']){
			//group_id :  2 = 未认证会员  3=认证会员  4=大客户
			$rows = self::$memberModel->updateByWhere(array('group_id' => 3),array('uid' => $uid));
		}
		
		return $this->_memberSellerModel->update($data,array('uid' => $uid));
			
		/*
		if(is_array($uid)){
			$condition = array(
				'where_in' => array(
					array('key' => 'uid','value' => $uid)
				)
			);
			
			if(1 == $data['verify_result']){
				self::$memberModel->updateByCondition(array('group_id' => 3),array('uid' => $uid));
			}
			
			return $this->_memberSellerModel->updateByCondition($data,$condition);
			
		}else{
			if(1 == $data['verify_result']){
				//group_id :  2 = 未认证会员  3=认证会员  4=大客户
				self::$memberModel->updateByWhere(array('group_id' => 3),array('uid' => $uid));
			}
			
			return $this->_memberSellerModel->update($data,array('uid' => $uid));
		}
		*/
	}
}
