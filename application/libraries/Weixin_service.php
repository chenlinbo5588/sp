<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weixin_service extends Base_service {
	
	private $_mpApi;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library(array('Weixin_mp_api'));
		
		$this->_mpApi = self::$CI->weixin_mp_api;
	}
	
	
	/**
	 * 初始化 小程序api 对象
	 */
	public function setConfig($tempConfig){
		$this->_mpApi->initSetting($tempConfig);
	}
	
	
	/**
	 * 获得微信
	 */
	public function getWeixinUserByCode($pCode){
		//检查 code 
		$resp = $this->_mpApi->getWeixinUserByCode($pCode);
		
		if(!empty($resp['session_key'])){
			return $resp;
		}else{
			
			return false;
		}
	}
	
	
	/**
	 * 检查绑定状态
	 */
	public function checkUserBind($pWeixinUser){
		
		$bind = array(
			'isBind' => false
		);
		
		$userInfo = $this->getBindUser($pWeixinUser);
		if($userInfo['uid']){
			$bind['isBind'] = true;
		}
		
		return $bind;
		
	}
	
	
	 /**
     * 获得绑定用户
     */
    public function getBindUser($wxResp){
		
		$userInfo = array();
		$mpConfig = $this->_mpApi->getSetting();
		
		if($wxResp['unionid']){
			$userInfo = self::$memberModel->getList(array(
				'where' => array(
					'unionid' => $wxResp['unionid'],
				),
				'limit' => 1
			));
			
		}else if($wxResp['openid']){
			$userInfo = self::$memberModel->getList(array(
				'where' => array(
					'openid' => $wxResp['openid'],
				),
				'limit' => 1
			));
		}
		
		return $userInfo[0];
	}
	
	
	/**
	 * 绑定手机号码
	 */
	public function bindMobile($param,$weixinInfo){
		
		self::$CI->load->library(array('Register_service'));
		$regData = array(
			'username' => $param['phoneNo'],
			'nickname' => $param['phoneNo'],
			'mobile' => $param['phoneNo'],
			'openid' => $weixinInfo['openid'],
			'unionid' => $weixinInfo['unionid'] ? $weixinInfo['unionid'] : '',
			'channel' => 1,   //小程序注册进入的
		);
		
		self::$memberModel->beginTrans();
		$resp = self::$CI->register_service->createMember($regData);
		
		if($resp['data']['uid']){
			self::$CI->load->model('Yezhu_Model');
			//更新业主表中  对应的 uid
			
			$yezhuInfo = self::$CI->Yezhu_Model->getFirstByKey($param['phoneNo'],'mobile');
			
			if($yezhuInfo){
				self::$memberModel->updateByWhere(array(
					'username' => $yezhuInfo['name'],
					'sex' => $yezhuInfo['sex'],
				),array(
					'uid' => $resp['data']['uid']
				));
				
				self::$CI->Yezhu_Model->updateByWhere(array('uid' => $resp['data']['uid']),array('mobile' => $param['phoneNo']));
			}
			
			
		}
		
		if(self::$memberModel->getTransStatus() === FALSE){
			self::$memberModel->rollBackTrans();
			return false;
		}else{
			self::$memberModel->commitTrans();
			return $resp['data']['uid'];
		}
	}
	
}
