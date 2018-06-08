<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
        
    	$this->load->library(array('Weixin_xcx_api','Member_service'));
    	
    	
    	
	}
	
	public function index()
	{
		

	}
	
	
	/**
	 * 
	 */
	public function login(){
		
		$code = $this->input->get_post('code');
		
		file_put_contents('debug.txt',print_r($_SERVER,true));
		
		if($code){
			
			$xcxConfig = config_item('mp_xcxCswy');
			
			$this->weixin_xcx_api->setConfig($xcxConfig);
			$resp = $this->weixin_xcx_api->getWeixinUserByCode($code);
			
			file_put_contents('debug.txt',print_r($resp,true),FILE_APPEND);
			
			if(!empty($resp['session_key'])){
				
				$sessionData = array(
					'weixinCustomer' => $resp,
					'lastvisit' => $this->_reqtime,
				);
				
				$isBind = false;
				
				$userInfo = $this->weixin_xcx_api->getBindUser($resp);
				
				if(empty($userInfo)){
					$this->weixin_xcx_api->addWxUser($resp);
				}else if($userInfo['uid']){
					
					//已绑定
					$isBind = true;
					
					$yezhuInfo = $this->member_service->getUserInfoById($userInfo['uid']);
					if($yezhuInfo){
						$sessionData['profile'] = array(
							'basic' => $yezhuInfo,
						);
						
						$this->member_service->updateUserInfo(
						array(
							'sid' => $this->session->session_id,
							'last_login' => $this->_reqtime,
							'last_loginip' => $this->input->ip_address()
						),
						$userInfo['uid']);
					};
					
					
				}else{
					//已自动注册微信记录，未绑定
				}
				
				$this->session->set_userdata($sessionData);
				
				$this->jsonOutput2('请求成功',array('sessionId' => $this->session->session_id, 'isBind' => $isBind));
			}else{
				$this->jsonOutput2('服务器请求响应异常');
			}
		}else{
			$this->jsonOutput2('参数错误');
		}
	}
	
	
	public function test1(){
		
		file_put_contents('debug1.txt',print_r($_SERVER,true));
		file_put_contents('debug1.txt',print_r($this->session->all_userdata(),true),FILE_APPEND);
		
		$this->jsonOutput2('请求成功');

		
	}
}
