<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{	
		
	}
	
	public function userinfo(){
		$uid = $this->input->get_post('uid');
		
		if($uid){
			$userInfo = $this->Member_Model->getFirstByKey($uid,'uid','username,mobile,virtual_no');
			$this->assign('userInfo',$userInfo);
		}
		
		$this->display();
	}
	
	
	private function _rememberLoginName($loginName, $liveSeconds = 2592000){
		$this->input->set_cookie('loginname',$loginName, $liveSeconds);
	}
	
	private function _autologin($profile){
		/*
		$this->load->model('Huanxin_Model');
		$chatConfig = $this->Huanxin_Model->getFirstByKey($profile['basic']['uid'],'uid');
		
		$profile['chat'] = empty($chatConfig) ? array() : $chatConfig;
		*/
		
		$this->load->model('Dept_Model');
		
		$deptInfo = $this->Dept_Model->getFirstByKey($profile['basic']['dept_id']);
		$profile['basic']['dept_name'] = $deptInfo['name'];
		$profile['basic']['dept_sname'] = $deptInfo['short_name'];
		$profile['basic']['dept_type'] = $deptInfo['org_type'];
		
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
					$this->_rememberLoginName($this->input->post('loginname'));
					$this->_autologin($result['data']);
					
					$url = $this->input->post('returnUrl');
					if(!empty($url) && isLocalUrl($url)){
						js_redirect($url);
					}else{
						//增加已个站内信立刻刷新标志，用户登陆可立刻看到消息刷新
						$param = $this->encrypt->encode($this->_reqtime + 10);
						js_redirect('my/base/?spm='.urlencode($param));
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
				
				/**
				 * 同步到最新的系统消息,防止新注册用户同步大量的以前的系统消息
				 */
				$this->load->model('Site_Message_Model');
				$sysMessageId = $this->Site_Message_Model->getMaxByWhere('id');
				
				$addParam = array(
					'sid' => $this->session->session_id,
					'mobile' => $this->input->post('mobile'),
					'username' => $this->input->post('username'),
					'email' => $this->input->post('email'),
					'qq' => $this->input->post('qq'),
					'password' => $this->input->post('psw'),
					'msgid' => intval($sysMessageId),
					'inviter_uid' => empty($inviter) == true ? 0 : intval($inviter),
					'last_login' => $this->_reqtime,
					'last_loginip' => $this->input->ip_address()
				);
				$addParam['username'] = trim($addParam['username']);
				$addParam['nickname'] = $addParam['username'];
				
				$result = $this->register_service->createMember($addParam);
				
				if($result['code'] != 'success'){
					$feedback = getErrorTip($result['message']);
					break;
				}
				
				
				$this->load->library(array('Message_service','Member_service'));
				
				/* 
				 * 暂时不用聊天
				$pushApi = $this->register_service->getPushObject();
				$resp = $pushApi->createId($result['data']['uid'],$addParam['mobile'],$addParam['nickname'],$addParam['password']);
				*/
				
				$userInfo = $this->Member_Model->getFirstByKey($addParam['username'],'username');
				
				$this->_rememberLoginName($addParam['username']);
				$this->_autologin(array(
					'basic' => $userInfo
				));
				
				$this->message_service->initEmail($this->_siteSetting);
				$param = $this->message_service->getEncodeParam(array(
					$result['data']['uid'],
					$addParam['email']
				));
				
				$url = site_url('member/verify_email?p=').$param;
				$flag = $this->message_service->sendEmailConfirm($addParam['email'],$url);
				
				$registerOk = true;
				
			}
			
		}else{
			/* 邀请人 */
			$this->assign('inviter', $this->input->get('inviter'));
			$this->assign('returnUrl',$this->input->get('returnUrl'));
		}
		
		$this->assign('feedback',$feedback);
		
		if($registerOk){
			js_redirect('hp/index');
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
	
	
	public function checkEmailCode($emailCode){
		$code = $this->session->userdata('email_code');
		
		$emailCode = trim($emailCode);
		if(strtolower($code) == strtolower($emailCode)){
			return true;
		}else{
			$this->form_validation->set_message('checkEmailCode', '对不起,邮件验证码参数有误');
			return false;
		}
	}
	
	/**
	 * 验证用户名
	 */
	public function check_username(){
		$key = 'username';
		$username = $this->input->get_post($key,true);
		
		if(empty($username)){
			$this->jsonOutput('参数错误');
		}else{
			$this->form_validation->set_rules('username','登陆账号', 'required|in_db_list['.$this->Member_Model->getTableRealName().'.username]');
			$this->form_validation->set_data(array($key => $username));
			if($this->form_validation->run()){
				$this->jsonOutput('用户名已被占用');
			}else{
				$this->jsonOutput('成功');
			}
		}
	}
	
	
	/**
	 * 忘记密码
	 */
	public function forget(){
		
		$feedback = '';
		
		
		if($this->isPostRequest()){
			
			$step = $this->session->userdata('step');
			
			for($i = 0; $i < 1; $i++){
				if(1== $step){
					$this->form_validation->set_rules('username','登陆账号', 'required|in_db_list['.$this->Member_Model->getTableRealName().'.username]');
					$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
				
				
					if(!$this->form_validation->run()){
						break;
					}
					
					
					$username = $this->input->post('username');
					$userInfo = $this->Member_Model->getFirstByKey($username,'username','uid,username,email,mobile');
					$emailUrl = 'http://mail.'.substr($userInfo['email'],strrpos($userInfo['email'],'@') + 1);
					
					$this->assign('userinfo',$userInfo);
					$this->assign('mailurl',$emailUrl);
					
					$step++;
					
					$this->session->set_userdata(array(
						'forget_uid'=>$userInfo['uid']
					));
					
				}else if(2==$step){
					
					$this->load->library('Verify_service');
					
					$findWay = $this->input->get_post('find_way');
					$this->form_validation->set_rules('find_way','找回方式', 'required|in_list[way_email,way_mobile]');
					
					if('way_email' == $findWay){
						$this->form_validation->set_rules('email_code','邮箱地址','required|callback_checkEmailCode');
						
					}else if('way_mobile' == $findWay){
						$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
						$this->form_validation->set_rules('mobile_auth_code','手机验证码', array(
								'required',
								array(
									'authcode_callable['.$this->input->post('mobile').']',
									array(
										$this->verify_service,'validateAuthCode'
									)
								)
							),
							array(
								'authcode_callable' => '手机验证码不正确'
							)
						);
			
			
					}
					
					if(!$this->form_validation->run()){
						break;
					}
					
					$step++;
				
				}elseif(3==$step){
					
					$this->form_validation->set_rules('newpsw','密码','required|valid_password|min_length[6]|max_length[12]');
					$this->form_validation->set_rules('newpsw_confirm','密码确认','required|matches[newpsw]');
					$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
					
					
					if(!$this->form_validation->run()){
						break;
					}
					
					//print_r($this->session->all_userdata());
					$uid = $this->session->userdata('forget_uid');
					$newpsw = $this->input->post('newpsw');
					
					$row = $this->Member_Model->update(array(
						'password' => $this->encrypt->encode($newpsw)
					),array('uid' => $uid));
					
					$step++;
					
					
				}
			}
		}else{
			$step = 1;
		}
		
		
		$this->assign('stepHTML',step_helper(array(
			'请输入您要找回密码的帐号',
			'输入验证信息',
			'重新设置密码'
		),$step));
		
		$this->session->set_userdata('step',$step);
		
		$this->assign('step',$step);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	/**
	 * 发送邮件验证码
	 */
	public function email_code(){
		
		$email = $this->input->post('email');
		$lastEmailSendKey = 'email_sendts';
		
		$lastSend = $this->session->userdata($lastEmailSendKey);
		
		if($email && $this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				if($lastSend && ($this->_reqtime - $lastSend) < 60){
					$this->jsonOutput('60秒内不可重复发送');
					break;
				}
				
				$code = random_string('alnum',6);
				
				$emailData = array(
					'title' => $this->_getSiteSetting('site_name').'邮件验证码',
					'content' => "您的验证码是: <strong>".$code."</strong> ,如非本人操作请。<div>系统邮件请勿回复</div>"
				);
				
				$this->message_service->initEmail($this->_siteSetting);
				$flag = $this->message_service->sendEmail($email,$emailData['title'],$emailData['content']);
				
				$this->session->set_userdata(array(
					$lastEmailSendKey => $this->_reqtime,
					'email_code' => $code
				));
				
				$this->jsonOutput('邮件已发送');
			}
		}else{
			$this->jsonOutput('参数不正确');
		}
	}
	
	
	public function verify_email(){
		
		$isSuccess = false;
		$feedback = '';
		
		$this->load->library('Message_service');
		$param = $this->input->get('p');
		$realParam = $this->encrypt->decode($param);
		$realParamArray = array();
		
		//print_r($realParamArray);
		if($realParam){
			$realParamArray = explode("\t",$realParam);
			if($this->_reqtime > $realParamArray[2]){
				//expired
				$feedback = '很抱歉,链接已过期.';
			}else{
				$row = $this->Member_Model->update(array(
					'email' => $realParamArray[1],
					'email_status' => 1
				
				),array('uid' => $realParamArray[0],'email_status' => 0));
				
				if($row >= 0){
					$isSuccess = true;
				}
				
				$feedback = '恭喜你， 邮箱认证成功';
			}
		}
		
		$isLogin = false;
		$linkURL = site_url('member/login');
		if($this->isLogin()){
			$isLogin = true;
			
			if($isSuccess){
				$this->_profile['basic']['email'] = $realParamArray[1];
				$this->_profile['basic']['email_status'] = 1;
				$this->refreshProfile();
			}
			
			$linkURL = site_url('my/index');
		}
		
		if($isSuccess){
			$imgUrl = resource_url('img/pass.png');
		}else{
			$imgUrl = resource_url('img/warn.png');
		}
		$this->assign('isSuccess',$isSuccess);
		$this->assign('linkURL',$linkURL);
		$this->assign('imgUrl',$imgUrl);
		$this->assign('isLogin',$isLogin);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
}
