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
	
	/**
	 * 前台 登陆
	 */
	public function login()
	{
		
		if($this->isLogin()){
			redirect('my');
		}
		
		if($this->isPostRequest()){
			
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			$this->form_validation->reset_validation();
			
			$this->form_validation->set_rules('loginname','用户名', 'required|valid_mobile');
			$this->form_validation->set_rules('password','密码','required|alpha_numeric');
			
			//$this->form_validation->set_rules('returnUrl','返回地址','valid_url');
			
			if($this->form_validation->run() !== FALSE){

				$this->load->library('Member_service');
				
				$result = $this->member_service->do_login(array(
					'mobile' => $this->input->post('loginname'),
					'password' => $this->input->post('password')
				));
				
				
				if($result['code'] == 'success'){
					$this->session->set_userdata(array(
						'profile' => $result['data'],
						'lastvisit' => $this->_reqtime
					));
					
				
					
					$this->member_service->updateUserInfo(
						array(
							'sid' => $this->session->session_id,
							'last_login' => $this->_reqtime,
							'last_loginip' => $this->input->ip_address()
						),
						$result['data']['basic']['uid']);
						
						
					$this->_rememberLoginName($this->input->post('loginname'));
					
					$url = $this->input->post('returnUrl');
					if(!empty($url) && isLocalUrl($url)){
						redirect($url);
					}else{
						redirect('team');
					}
					
				}else{
					$this->assign('feedback',$result['message']);
				}
			}
		}else{
			//记住用户点击时  因为需要登录的返回链接
			$teamJoinParam = $this->input->get('teamjoin');
			//$string = $this->encrypt->decode($teamJoinParam,$this->config->item('encryption_key'));
			if($teamJoinParam){
				$this->assign('returnUrl',site_url('team/invite/?param='.$teamJoinParam));
			}else{
				$this->assign('returnUrl', $this->input->get('returnUrl'));
			}
			
			$this->assign('loginname',$this->input->cookie('loginname'));
			
			
		}
		
		$this->seoTitle('登陆'.config_item('site_name'));
		$this->display("member/login");
	}

	/**
	 * 管理后台登陆
	 */
	public function admin_login(){
		
		
		if($this->isPostRequest()){
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('email','用户名', 'required|valid_email');
			$this->form_validation->set_rules('password','密码','required|alpha_numeric');
			$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
			
			
			for($i = 0; $i < 1; $i++){
				
				if($this->form_validation->run() == FALSE){
					break;
				}
				
				$this->load->library('Admin_service');
				$result = $this->admin_service->do_adminlogin($this->input->post('email'),$this->input->post('password'));
				
				if($result['message'] != '成功'){
					$this->assign('feedback','<div class="form_error">' .$result['message'].'</div>');
					break;
				}
				
				$this->session->set_userdata(array(
					'manage_profile' => $result['data'],
					'lastvisit' => $this->_reqtime
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
		$this->display('member/admin_login');
	}
	
	
	
	
	/**
	 * 用户注册
	 * 
	 */
	public function register()
	{
		
		$registerOk = false;
		
		if($this->isPostRequest()){
			$inviter = $this->input->post('inviter');
			$inviteFrom = $this->input->post('inviteFrom');
			
			$this->assign('inviter', $inviter);
			$this->assign('inviteFrom',$inviteFrom);
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			
			
			$this->form_validation->reset_validation();
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
				
			$this->load->library('Verify_service');
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
			
			/*
			$this->form_validation->set_rules('nickname','昵称', array(
						'required',
						array(
							'nickname_callable[nickname]',
							array(
								$this->Member_Model,'isUnqiueByKey'
							)
						)
					),
					array(
						'nickname_callable' => '%s已经被占用'
					)
				);
			*/
			
			
			$this->form_validation->set_rules('psw','密码','required|alpha_dash|min_length[6]|max_length[12]');
			$this->form_validation->set_rules('psw_confirm','密码确认','required|matches[psw]');
			$this->form_validation->set_rules('agreee_licence','同意注册条款','required');
			
			if($this->form_validation->run() !== FALSE){
				
				$this->load->library('Member_service');
				$this->load->library('Register_service');
				
				$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
				
				if($todayRegistered < 3){
					
					$addParam = array(
						'mobile' => $this->input->post('mobile'),
						'nickname' => $this->input->post('mobile'),
						'password' => $this->input->post('psw'),
						'status' => -2,
						'inviter' => empty($inviter) == true ? 0 : intval($inviter)
					);
					
					$this->assign('default_avatar',$addParam['avatar_big']);
					
					
					//来源为队伍邀请链接
					if('teamInvite' == $inviteFrom){
						$inviterInfo = $this->Member_Model->getById(array(
							'where' => array('uid' => $inviter)
						));
						
						$addParam['district_bind'] = 1;
						$addParam['d1'] = $inviterInfo['d1'];
						$addParam['d2'] = $inviterInfo['d2'];
						$addParam['d3'] = $inviterInfo['d3'];
						$addParam['d4'] = $inviterInfo['d4'];
						
						//只需要设置头像
						$addParam['status'] = -1;
					}
					
					$result = $this->register_service->createMember($addParam);
				
					if($result['code'] == 'success'){
						
						$userInfo = $this->member_service->getUserInfoByMobile($this->input->post('mobile'));
						$this->_rememberLoginName($this->input->post('mobile'));
						
						$this->session->set_userdata(array(
							'profile' => array('basic' => $userInfo)
						));
						
						
						$registerOk = true;
					}else{
						
						$this->assign('feedback',$result['message']);
					}
				}else{
					$this->assign('feedback','<div class="warning">很抱歉，您今日注册数量已经用完</div>');
				}
			}
		
		}else{
			$this->assign('inviter', $this->input->get('inviter'));
		}
		
		
		if($registerOk){
			$this->seoTitle('设置头像');
			$this->display('my/set_avatar');
			
		}else{
			
			$this->seoTitle('用户注册');
			$this->display("member/register");
		}
		
	}
	
	
	/**
	 * 登出
	 */
	public function logout(){
		
		$this->session->unset_userdata('profile');
		//$this->session->sess_destroy();
		//$this->jsonOutput('',$this->getFormHash());
		
		redirect('member/login');
	}
	
	
}
