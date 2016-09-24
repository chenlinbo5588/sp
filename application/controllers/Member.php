<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{	
		/*
		$this->email->from('tdkc_of_cixi@163.com', '运动之家');
		$this->email->to('104071152@qq.com');
		//$this->email->cc('another@another-example.com');
		//$this->email->bcc('them@their-example.com');
		
		$this->email->subject('【运动之家 邮件激活】');
		$this->email->message('尊敬的'.$this->input->post('nickname').'用户,欢迎你加入运动之家， 点击以下链接进行邮件激活,链接2小时内有效');
		if($this->email->send()){
			$this->assign('tip_email',true);
		}
		*/
	}
	
	private function _rememberLoginName($loginName, $liveSeconds = 2592000){
		$this->input->set_cookie('loginname',$loginName, $liveSeconds);
		
	}
	
	private function _autologin($profile){
		$this->load->model('Huanxin_Model');
		$chatConfig = $this->Huanxin_Model->getFirstByKey($profile['basic']['uid'],'uid');
		
		$profile['chat'] = empty($chatConfig) ? array() : $chatConfig;
		
		$this->session->set_userdata(array(
			$this->_profileKey => $profile,
			$this->_lastVisitKey => $this->_reqtime
		));
		
		$this->member_service->updateUserInfo(
			array(
				'sid' => $this->session->session_id,
				'last_login' => $this->_reqtime,
				'last_loginip' => $this->input->ip_address()
			),
			$profile['basic']['uid']);

		$this->_rememberLoginName($this->input->post('loginname'));
					
	}
	
	/**
	 * 前台 登陆
	 */
	public function login()
	{
		
		if($this->isLogin()){
			js_redirect('my');
		}
		
		if($this->isPostRequest()){
			
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			$this->form_validation->reset_validation();
			
			$this->form_validation->set_rules('loginname','登陆账号', 'required');
			
			$this->form_validation->set_rules('password','密码','required|valid_password');
			$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
			
			//$this->form_validation->set_rules('returnUrl','返回地址','valid_url');
			
			if($this->form_validation->run() !== FALSE){

				$this->load->library('Member_service');
				
				$result = $this->member_service->do_login(array(
					'loginname' => $this->input->post('loginname'),
					'password' => $this->input->post('password')
				));
				
				
				if($result['code'] == 'success'){
					$this->_autologin($result['data']);
					
					$url = $this->input->post('returnUrl');
					if(!empty($url) && isLocalUrl($url)){
						js_redirect($url);
					}else{
						js_redirect('my/index');
					}
					
				}else{
					$this->assign('feedback',$result['message']);
				}
			}
		}else{
			$this->assign('returnUrl', $this->input->get('returnUrl'));
			$this->assign('loginname',$this->input->get_cookie('loginname'));
		}
		
		$this->seoTitle('登陆');
		$this->display();
	}

	/**
	 * 管理后台登陆
	 */
	public function admin_login(){
		
		
		$adminData = $this->session->get_userdata($this->_adminProfileKey);
		$this->assign($this->_adminProfileKey,$adminData);
		
		
		if($this->isPostRequest()){
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('email','登陆邮箱', 'required|valid_email');
			$this->form_validation->set_rules('password','密码','required|valid_password');
			$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
			
			
			for($i = 0; $i < 1; $i++){
				
				if($this->form_validation->run() == FALSE){
					break;
				}
				
				$this->load->library('Admin_service');
				$result = $this->admin_service->do_adminlogin($this->input->post('email'),$this->input->post('password'));
				
				if($result['message'] != '成功'){
					$this->assign('feedback',getErrorTip($result['message']));
					break;
				}
				
				$this->session->set_userdata(array(
					$this->_adminProfileKey => $result['data'],
					$this->_adminLastVisitKey => $this->_reqtime
				));
				
				$this->admin_service->updateUserInfo(
					array(
						'sid' => $this->session->session_id,
						'last_login' => $this->_reqtime,
						'last_loginip' => $this->input->ip_address()
					),
					$result['data']['basic']['uid']);
				
				js_redirect(admin_site_url('index'),'top');
				
			}
		}
		
		$this->seoTitle('后台登陆');
		$this->display();
	}
	
	
	
	
	/**
	 * 用户注册
	 */
	public function register()
	{
		$registerOk = false;
		$feedback = '';
		
		if($this->isPostRequest()){
			$inviter = $this->input->post('inviter');
			$inviteFrom = $this->input->post('inviteFrom');
			
			$this->assign('inviter', $inviter);
			//$this->assign('inviteFrom',$inviteFrom);
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			
			$this->load->library(array('Register_service'));
			
			for($i = 0; $i < 1; $i++){
				$this->register_service->memberAddRules();
				if(!$this->form_validation->run()){
					break;
				}
				
				$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
				
				if($todayRegistered > 5){
					$feedback = getWarningTip('很抱歉，您今日注册数量已经用完');
					break;
				}
				
				$addParam = array(
					'sid' => $this->session->session_id,
					'mobile' => $this->input->post('mobile'),
					'username' => $this->input->post('username'),
					'email' => $this->input->post('email'),
					'qq' => $this->input->post('qq'),
					'password' => $this->input->post('psw'),
					'status' => -2,
					'inviter' => empty($inviter) == true ? 0 : intval($inviter)
				);
				
				$addParam['nickname'] = $addParam['username'];
				
				$result = $this->register_service->createMember($addParam);
				
				if($result['code'] != 'success'){
					$feedback = getErrorTip($result['message']);
					break;
				}
				
				$this->load->library(array('Message_service'));
				$pushApi = $this->register_service->getPushObject();
				$resp = $pushApi->createId($result['data']['uid'],$addParam['mobile'],$addParam['nickname'],$addParam['password']);
				
				$userInfo = $this->Member_Model->getFirstByKey($addParam['username'],'username');
				$this->_autologin(array('basic' => $userInfo));
				
				$this->_rememberLoginName($this->input->post('username'));
				//$this->message_service->sendEmail('email_active',$addParam['email']);
				$registerOk = true;
				
			}
			
		}else{
			/* 邀请人 */
			$this->assign('inviter', $this->input->get('inviter'));
			$this->assign('returnUrl',$this->input->get('returnUrl'));
		}
		
		$this->assign('feedback',$feedback);
		
		if($registerOk){
			js_redirect('goods/index');
		}else{
			$this->seoTitle('用户注册');
			$this->display();
		}
	}
	
	/**
	 * 登出
	 */
	public function logout(){
		
		if($this->isLogin()){
			$this->session->unset_userdata('profile');
		}
		
		js_redirect('member/login');
	}
	
	
	public function forget(){
		
		$this->display();
	}
	
	
	public function verify_email(){
		
		$this->load->library('Message_service');
		$param = $this->input->get('p');
		
		$isLogin = false;
		$linkURL = site_url('member/login');
		if($this->isLogin()){
			$isLogin = true;
			$linkURL = site_url('my/index');
		}
		
		$this->assign('linkURL',$linkURL);
		$this->assign('isLogin',$isLogin);
		$this->display();
		
	}
	
}
