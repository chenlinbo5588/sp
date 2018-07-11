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
			$bindInfo = $this->weixin_service->checkUserBind($weixinUser);
			
			if(!empty($weixinUser['buss_id'])){
				$this->jsonOutput2(RESP_SUCCESS,$bindInfo);
			}else{
				$this->jsonOutput2('请求微信服务器响应异常');
			}
		}else{
			$this->jsonOutput2('参数错误');
		}
	}

}
