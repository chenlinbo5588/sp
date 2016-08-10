<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
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
			
			$this->form_validation->reset_validation();
			
			$this->form_validation->set_rules('username','用户名', 'required|alpha_dash|max_length[15]');
			$this->form_validation->set_rules('password','密码','required|alpha_dash');
			$this->form_validation->set_rules('captcha','验证码','required|callback_validateAuthCode');
			
			for($i = 0; $i < 1 ; $i++){
				if(!$this->form_validation->run()){
					$this->assign('errorMsg',$this->form_validation->error_array());
					//print_r($this->form_validation->error_string());
					break;
				}

				$this->load->library('Member_service');
				
				$result = $this->member_service->do_login(array(
					'account' => $this->input->post('username'),
					'password' => $this->input->post('password')
				));
				
				
				if($result['code'] != 'success'){
					$this->assign('feedback',$result['message']);
					break;
				}
				
				$this->session->set_userdata(array(
					'profile' => $result['data'],
					$this->_lastVisitKey => $this->_reqtime
				));
				
				$this->member_service->updateUserInfo(
					array(
						'sid' => $this->session->session_id,
						'last_login' => $this->_reqtime,
						'last_loginip' => $this->input->ip_address()
					),
					$result['data']['basic']['uid']);
					
				$this->_rememberLoginName($this->input->post('username'));
				
				redirect('admin/lab_goods');
			}
		}
		
		$this->seoTitle('登陆');
		$this->display("member/login");
	}

	/**
	 * 管理后台登陆
	 */
	public function admin_login(){
		
		if($this->isPostRequest()){
			$this->form_validation->reset_validation();
			
			$this->form_validation->set_rules('username','用户名', 'required|alpha_dash|max_length[15]');
			$this->form_validation->set_rules('password','密码','required|alpha_dash');
			$this->form_validation->set_rules('captcha','验证码','required|callback_validateAuthCode');
			
			
			for($i = 0; $i < 1; $i++){
				
				if($this->form_validation->run() == FALSE){
					$this->assign('errorMsg',$this->form_validation->error_array());
					break;
				}
				
				$this->load->library('Admin_service');
				
				$result = $this->admin_service->do_adminlogin($this->input->post('username'),$this->input->post('password'));
				
				if($result['message'] != '成功'){
					$this->assign('feedback',$result['message']);
					break;
				}
				
				$this->session->set_userdata(array(
					'admin_profile' => $result['data'],
					'lastvisit' => $this->_reqtime
				));
				
				$this->admin_service->updateUserInfo(
					array(
						'sid' => $this->session->session_id,
						'last_login' => $this->_reqtime,
						'last_loginip' => $this->input->ip_address()
					),
					$result['data']['basic']['id']);
				
				//默认货品
				js_redirect(admin_site_url('admin'),'top');
				
			}
		}
		
		$this->seoTitle('登陆');
		$this->display('member/admin_login');
	}
	
	
	
	
	/**
	 * 登出
	 */
	public function logout(){
		$this->session->unset_userdata($this->_profileKey);
		redirect('member/login');
	}
	
	
}
