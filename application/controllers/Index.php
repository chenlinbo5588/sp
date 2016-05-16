<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	
	private $_regLimit = 10;
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Register_Service'));
		$this->_regLimit = config_item('maxRegisterIpLimit');
	}
	
	//渠道
	private function _getCurrentServerName(){
		$currentHost = $this->input->server('HTTP_HOST');
		//$currentHost = 'www.txcf188.com';
		//$currentHost = 's1.txcf188.com';
		//$currentHost = 's2.txcf188.com';
		//$currentHost = 's3.txcf188.com';
		
		//$this->assign('currentHost',$currentHost);
		
		return $currentHost;
	}
	
	
	
	/**
	 * 自动获取渠道数据
	 */
	private function _prepageChannelData(){
		
		$inviter = $this->input->get_post('inviter');
		
		if($this->isGetRequest()){
			$inviteFrom = $this->input->server('HTTP_REFERER');
		}else if($this->isPostRequest()){
			$inviteFrom = $this->input->post('refer');
			
		}
		
		
		$channel_code = $this->input->get_post('channel_code');
		$this->assign('channel_code', $channel_code);
		$this->assign('inviter', $inviter);
		$this->assign('refer',$inviteFrom);
		
		
		return array(
			'channel' => 1,//手机网页版
			'inviter' => $inviter ? intval($inviter) : 0,
			'reg_orig' => empty($inviteFrom) != true ? $inviteFrom : '',
			'channel_name' => $this->input->server('HTTP_HOST'),
			'channel_code' => empty($channel_code) != true ? $channel_code : ''
		);
	}
	
	
	
	/**
	 * 首页
	 */
	public function index()
	{
		$registerOk = false;
		$currentHost = $this->_getCurrentServerName();
		
		$channelData = $this->_prepageChannelData();
		
		if($this->isPostRequest()){
			$this->form_validation->reset_validation();
			
			/*
			$this->form_validation->set_rules('mobile','手机号',array(
						'required',
						'valid_mobile',
						array(
							'loginname_callable[mobile]',
							array(
								$this->Member_Model,'isUnqiueByKey'
							)
						)
					),
					array(
						'loginname_callable' => '%s已经被注册'
					)
				);
			*/
			$this->form_validation->set_rules('username','用户名称', 'required|min_length[1]|max_length[20]');
			$this->form_validation->set_rules('mobile','手机号','required|valid_mobile');
			
			$this->load->library('Verify_Service');
			$this->form_validation->set_rules('auth_code','验证码', array(
						'required',
						array(
							'authcode_callable['.$this->input->post('mobile').']',
							array(
								$this->verify_service,'validateAuthCode'
							)
						)
					),
					array(
						'authcode_callable' => '验证码不正确'
					)
				);
			
			
			if($this->form_validation->run() !== FALSE){
				$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
				// 最多30 次
				if($todayRegistered < $this->_regLimit){
					$addParam = array(
						'mobile' => $this->input->post('mobile'),
						'username' => $this->input->post('username'),
						//正常提交
						'status' => 0,
					);
					
					$addParam = array_merge($addParam,$channelData);
					// check
					$result = $this->register_service->createMember($addParam,true);
					
					//print_r($result);
					if($result['code'] == 'success'){
						/*
						$userInfo = $this->member_service->getUserInfoByMobile($this->input->post('mobile'));
						//$this->_rememberLoginName($this->input->post('mobile'));
						
						$this->session->set_userdata(array(
							'profile' => $userInfo
						));
						*/
						//redirect(config_item('dest_website'));
						$registerOk = true;
						$this->assign('feedback',config_item('registerOk_text1'));
						$this->assign('jumUrlType','website');
						
					}else{
						$this->assign('feedback',$result['message']);
					}
				}else{
					$this->assign('feedback','很抱歉，您今日注册数量已经用完');
				}
			}
		}
		//$registerOk = true;
		
		$this->assign('jumUrl',config_item('jumUrl'));
		
		$this->assign('registerOk',$registerOk);
		$this->display('index/'.$currentHost);
	}
	
	
	public function site1(){
		
		$currentHost = $this->_getCurrentServerName();
		$registerOk = false;
		
		$channelData = $this->_prepageChannelData();
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('mobile','手机号','required|valid_mobile');
			
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					break;
				}
				
				$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
				if($todayRegistered > $this->_regLimit){
					$this->assign('feedback','很抱歉，您今日注册数量已经用完');
					break;
				}
				
				$addParam = array(
					'mobile' => $this->input->post('mobile'),
					'username' => '',
					'status' => 0,
				);
				$addParam = array_merge($addParam,$channelData);
				
				//check
				$result = $this->register_service->createMember($addParam,true);
				if($result['code'] == 'success'){
					//redirect(config_item('dest_website'));
					$registerOk = true;
					$this->assign('jumUrlType','qqchat');
					$this->assign('feedback',config_item('registerOk_text2'));
				}else{
					$this->assign('feedback',$result['message']);
				}
				
			}
		}
		
		
		$this->assign('jumUrl',config_item('jumUrl'));
		
		$this->assign('registerOk',$registerOk);
		$this->display('index/'.$currentHost);
		
	}
	
	
	
	public function site2(){
		
		$currentHost = $this->_getCurrentServerName();
		
		$registerOk = false;
		$channelData = $this->_prepageChannelData();
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('mobile','手机号','required|valid_mobile');
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					break;
				}
				
				$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
				if($todayRegistered > $this->_regLimit){
					$this->assign('feedback','很抱歉，您今日注册数量已经用完');
					break;
				}
				
				$this->load->model('Captcha_Model');
				$captcha = $this->Captcha_Model->getList(array(
					'where' => array(
						'ip_address' => $this->input->ip_address(),
						'captcha_time >' => $this->input->server('REQUEST_TIME') - 7200
					),
					'limit' => 1,
					'order' => 'captcha_id DESC'
				));
				
				//print_r($captcha);
				if(strtolower($captcha[0]['word']) != strtolower($this->input->post('auth_code')) ){
					$this->assign('feedback','<label for="authcode_text" class="error">验证码错误</label>');
					break;
				}
				
				$addParam = array(
					'mobile' => $this->input->post('mobile'),
					'username' => '',
					'status' => 0
				);
				$addParam = array_merge($addParam,$channelData);
				//check
				$result = $this->register_service->createMember($addParam,true);
				
				if($result['code'] == 'success'){
					$this->assign('feedback',config_item('registerOk_text3'));
					$this->assign('jumUrlType','qqgroup');
					$registerOk = true;
				}else{
					$this->assign('feedback',$result['message']);
				}
			}
		}
		
		$this->assign('jumUrl',config_item('jumUrl'));
		$this->assign('registerOk',$registerOk);
		$this->display('index/'.$currentHost);
		
	}
	
	
	
	public function site3(){
		
		$currentHost = $this->_getCurrentServerName();
		
		$registerOk = false;
		$channelData = $this->_prepageChannelData();
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('username','姓名','required|min_length[1]|max_length[20]');
			$this->form_validation->set_rules('mobile','手机号','required|valid_mobile');
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					break;
				}
				
				$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
				if($todayRegistered > $this->_regLimit){
					$this->assign('feedback','很抱歉，您今日注册数量已经用完');
					break;
				}
				
				$this->load->model('Captcha_Model');
				$captcha = $this->Captcha_Model->getList(array(
					'where' => array(
						'ip_address' => $this->input->ip_address(),
						'captcha_time >' => $this->input->server('REQUEST_TIME') - 7200
					),
					'limit' => 1,
					'order' => 'captcha_id DESC'
				));
				
				//print_r($captcha);
				if(strtolower($captcha[0]['word']) != strtolower($this->input->post('auth_code')) ){
					$this->assign('feedback','<label for="authcode_text" class="error">验证码错误</label>');
					break;
				}
				
				$addParam = array(
					'mobile' => $this->input->post('mobile'),
					'username' => '',
					'status' => 0
				);
				$addParam = array_merge($addParam,$channelData);
				//check
				$result = $this->register_service->createMember($addParam,true);
				
				if($result['code'] == 'success'){
					$this->assign('feedback',config_item('registerOk_text2'));
					$this->assign('jumUrlType','qqchat');
					$registerOk = true;
				}else{
					$this->assign('feedback',$result['message']);
				}
			}
		}
		
		$this->assign('jumUrl',config_item('jumUrl'));
		$this->assign('registerOk',$registerOk);
		$this->display('index/'.$currentHost);
		
	}
	
	
}