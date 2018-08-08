<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 登陆获取 session key
	 */
	public function login(){
		$code = $this->postJson['code'];
		
		if($code){
			$xcxConfig = config_item('mp_xcxCswy');
			
			$this->weixin_service->setConfig($xcxConfig);
			$weixinUser = $this->weixin_service->getWeixinUserByCode($code);
			
			if($weixinUser){
				$this->session->set_userdata(array(
					'weixin_user' =>  $weixinUser
				));
				
				$bindInfo = $this->weixin_service->checkUserBind($weixinUser);
				
				if($this->memberInfo){
					$bindInfo['mobile'] = $this->memberInfo['mobile'];
				}
				
				$this->jsonOutput2(RESP_SUCCESS,$bindInfo);
			}else{
				//$this->jsonOutput2("微信登陆失败",array('sessionId' => $this->session->session_id));
				$this->jsonOutput2("微信登陆失败");
			}
			
		}else{
			$this->jsonOutput2('参数错误');
		}
	}

}
