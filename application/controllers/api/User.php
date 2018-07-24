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
		$code = $this->input->get_post('code');
		
		if($code){
			$xcxConfig = config_item('mp_xcxCswy');
			
			$this->weixin_service->setConfig($xcxConfig);
			$weixinUser = $this->weixin_service->getWeixinUserByCode($code);
			
			file_put_contents('weixin.txt',print_r($weixinUser,true));
			if($weixinUser){
				$this->session->set_userdata(array(
					'weixin_user' =>  $weixinUser
				));
				
				$bindInfo = $this->weixin_service->checkUserBind($weixinUser);
				
				//业务 会话
				$bindInfo['sessionId'] = $this->session->session_id;
				
				$this->jsonOutput2(RESP_SUCCESS,$bindInfo);
			}else{
				$this->jsonOutput2("微信登陆失败",array('sessionId' => $this->session->session_id));
			}
			
		}else{
			$this->jsonOutput2('参数错误');
		}
	}

}
