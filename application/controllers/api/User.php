<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Wx_Tdkc_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 登陆获取 session key
	 */
	public function login(){
		$code = $this->postJson['code'];
		
		if($code){
			$xcxConfig = config_item('mp_xcxTdkc');
			
			$this->weixin_service->setConfig($xcxConfig);
			$weixinUser = $this->weixin_service->getWeixinUserByCode($code);
			if($weixinUser){
				$this->session->set_userdata(array(
					'weixin_user' =>  $weixinUser
				));
				
				$bindInfo = $this->weixin_service->checkUserBind($weixinUser);

				$this->initMemberInfo();
				if($this->memberInfo){
					if(11 == strlen($this->memberInfo['mobile'])){
						$bindInfo['mobile'] = mask_mobile($this->memberInfo['mobile']);
					}
				}
				$bindInfo['user_type'] = $this->userInfo['user_type'];
				$bindInfo['site_tel'] = $this->_getSiteSetting('site_tel');
				$bindInfo['default_address'] = $this->_getSiteSetting('service_default_address');
				
				$this->jsonOutput2(RESP_SUCCESS,$bindInfo);
			}else{
				//$this->jsonOutput2("微信登陆失败",array('sessionId' => $this->session->session_id));
				$this->jsonOutput2("微信登陆失败");
			}
			
		}else{
			$this->jsonOutput2('参数错误');
		}
	}
	
	public function getUserInfo(){
		if($this->userInfo){
			$userId = $this->postJson('uesr_id');
			if($userId){
				$userInfo = $this->User_Model->getList(array(
					'select' => 'id,mobile,name,company_name,group_name,user_type',
					'where' => array('id' => $userId),
				));
				if($userInfo)
					$this->jsonOutput2(RESP_SUCCESS,$userInfo);
			}
		}
	}

}
