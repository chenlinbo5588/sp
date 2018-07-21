<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weixin_service extends Base_service {
	
	private $_mpApi;
	
	private $_weixinSessionModel;
	private $_sessionInfo;
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library(array('Weixin_mp_api'));
		
		self::$CI->load->model(array(
			'Weixin_Session_Model',
		));
		
		
		$this->_mpApi = self::$CI->weixin_mp_api;
		
		$this->_weixinSessionModel = self::$CI->Weixin_Session_Model;
		
		
	}
	
	
	/**
	 * 设置会话参数
	 */
	public function setSession($pSessionInfo){
		$this->_sessionInfo = $pSessionInfo;
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
		
		//file_put_contents('debug.txt',print_r($resp,true));
		
		if(!empty($resp['session_key'])){
			
			$data = array(
				'code' => $pCode,
				'buss_sid' => md5(mt_rand())
			);
			
			$insertData = array_merge($data,$resp);
			
			//file_put_contents('debug.txt',print_r($insertData,true),FILE_APPEND);
			
			$newId = $this->_weixinSessionModel->_add($insertData);
			
			$errorInfo = $this->_weixinSessionModel->getError();			
			
			if(0 == $errorInfo['code']){
				return $insertData;
			}else{
				return false;
			}
			
		}else{
			
			return false;
		}
	}
	
	
	/**
	 * 检查绑定状态
	 */
	public function checkUserBind($pWeixinUser){
		
		$bind = array(
			//自己的业务ID
			'sessionId' => $pWeixinUser['buss_sid'],
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
	 * 根据 Session Key 获得
	 */
	public function getWeixinUserInfoBySession($pSessionId){
		
		$weixinInfo = $this->_weixinSessionModel->getList(array(
			'where' => array(
				'buss_sid' => $pSessionId,
			),
			'order' => 'id DESC',
			'limit' => 1
		));
		
		if(empty($weixinInfo[0])){
			return array();
		}else{
			return $weixinInfo[0];
		}
	}
	
	
	/**
	 * 绑定手机号码
	 */
	public function bindMobile($param){
		$weixinInfo = $this->getWeixinUserInfoBySession($param['sid']);
		
		if(empty($weixinInfo)){
			return false;
		}
		
		self::$CI->load->library(array('Register_service'));
		$regData = array(
			'nickname' => $param['phoneNo'],
			'mobile' => $param['phoneNo'],
			'openid' => $weixinInfo['openid'],
			'unionid' => $weixinInfo['unionid'],
			'channel' => 1,   //小程序注册进入的
		);
		
		
		//开发阶段使用手机号码 跑业务逻辑
		$this->_weixinSessionModel->beginTrans();
		
		$resp = self::$CI->register_service->createMember($regData);
		
		if($resp['data']['uid']){
			
			if('development' == ENVIRONMENT){
				//开发阶段在 session 中保存手机号码
				$this->_weixinSessionModel->updateByWhere(array('mobile' => $param['phoneNo']),array('id' => $weixinInfo['id']));
			}
			
			//更新业主表中  对应的 uid
			self::$CI->load->model('Yezhu_Model');
			self::$CI->Yezhu_Model->updateByWhere(array('uid' => $resp['data']['uid']),array('mobile' => $param['phoneNo']));
		}
		
		if($this->_weixinSessionModel->getTransStatus() === FALSE){
			$this->_weixinSessionModel->rollBackTrans();
			return false;
		}else{
			$this->_weixinSessionModel->commitTrans();
			return $resp['data']['uid'];
		}
	}
	
}
