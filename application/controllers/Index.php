<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Register_Service'));
		
		
	}
	
	//渠道
	private function _getCurrentServerName(){
		$currentHost = $this->input->server('HTTP_HOST');
		//$currentHost = 'www.txcf188.com';
		//$currentHost = 's1.txcf188.com';
		$currentHost = 's2.txcf188.com';
		
		//$this->assign('currentHost',$currentHost);
		
		return $currentHost;
	}
	
	
	
	/**
	 * 首页
	 */
	public function index()
	{
		$registerOk = false;
		$currentHost = $this->_getCurrentServerName();
		
		$inviter = $this->input->get_post('inviter');
		$inviteFrom = $this->input->get_post('inviteFrom');
		
		$this->assign('inviter', $inviter);
		$this->assign('inviteFrom',$inviteFrom);
		
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
				// 最多10 次
				if($todayRegistered < 10){
					
					$addParam = array(
						'mobile' => $this->input->post('mobile'),
						'username' => $this->input->post('username'),
						'inviter' => empty($inviter) == true ? 0 : intval($inviter),
						//正常提交
						'status' => 0,
						'channel_name' => $currentHost,
						'channel_orig' => $inviteFrom
					);
					
					// check
					$result = $this->register_service->createMember($addParam,true);
				
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
						
					}else{
						
						$this->assign('feedback',$result['message']);
					}
				}else{
					$this->assign('feedback','很抱歉，您今日注册数量已经用完');
				}
			}
		}
		//$registerOk = true;
		$this->assign('registerOk',$registerOk);
		$this->display('index/'.$currentHost);
	}
	
	
	public function site1(){
		
		$currentHost = $this->_getCurrentServerName();
		
		
		$registerOk = false;
		
		$inviter = $this->input->get_post('inviter');
		$inviteFrom = $this->input->get_post('inviteFrom');
		
		$this->assign('inviter', $inviter);
		$this->assign('inviteFrom',$inviteFrom);
		
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('mobile','手机号','required|valid_mobile');
			
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					break;
				}
				
				$addParam = array(
					'mobile' => $this->input->post('mobile'),
					'username' => '',
					'inviter' => empty($inviter) == true ? 0 : intval($inviter),
					'status' => 0,
					'channel_name' => $currentHost,
					'channel_orig' => $inviteFrom
				);
				
				//check
				$result = $this->register_service->createMember($addParam,true);
				
				if($result['code'] == 'success'){
					//redirect(config_item('dest_website'));
					$registerOk = true;
					$this->assign('feedback','注册成功');
				}else{
					$this->assign('feedback',$result['message']);
				}
				
			}
		}
		
		$this->assign('registerOk',$registerOk);
		$this->display('index/'.$currentHost);
		
	}
	
	
	
	public function site2(){
		
		$currentHost = $this->_getCurrentServerName();
		
		$registerOk = false;
		$inviter = $this->input->get_post('inviter');
		$inviteFrom = $this->input->get_post('inviteFrom');
		
		$this->assign('inviter', $inviter);
		$this->assign('inviteFrom',$inviteFrom);
		
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('mobile','手机号','required|valid_mobile');
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
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
					'inviter' => empty($inviter) == true ? 0 : intval($inviter),
					'status' => 0,
					'channel_name' => $currentHost,
					'channel_orig' => $inviteFrom
				);
				
				//check
				$result = $this->register_service->createMember($addParam,true);
				
				if($result['code'] == 'success'){
					//redirect(config_item('dest_website'));
					
					$registerOk = true;
				}else{
					$this->assign('feedback',$result['message']);
				}
				
			}
			
		}
		
		$this->assign('registerOk',$registerOk);
		$this->display('index/'.$currentHost);
		
	}
	
	
}