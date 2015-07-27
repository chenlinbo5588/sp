<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{	
		$this->email->from('tdkc_of_cixi@163.com', '运动之家');
		$this->email->to('104071152@qq.com');
		//$this->email->cc('another@another-example.com');
		//$this->email->bcc('them@their-example.com');
		
		$this->email->subject('【运动之家 邮件激活】');
		$this->email->message('尊敬的'.$this->input->post('nickname').'用户,欢迎你加入运动之家， 点击以下链接进行邮件激活,链接2小时内有效');
		if($this->email->send()){
			$this->assign('tip_email',true);
		}
		
	}
	
	
	/**
	 * 前台 登陆
	 */
	public function login()
	{
		
		if($this->isPostRequest()){
			
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('email','用户名', 'required|valid_email');
			$this->form_validation->set_rules('password','密码','required|alpha_numeric');
			
			$this->form_validation->set_rules('returnUrl','返回地址','valid_url');
			
			if($this->form_validation->run() !== FALSE){

				$this->load->library('Member_Service');
				
				$result = $this->member_service->do_login(array(
					'email' => $this->input->post('email'),
					'password' => $this->input->post('password')
				));
				
				
				if($result['code'] == 'success'){
					$this->session->set_userdata(array(
						'profile' => $result['data']
					));
					
					if($this->input->post('returnUrl') && preg_match("/^https?\:\/\/".config_item('site_domain').'/',$this->input->post('returnUrl'))){
						redirect($this->input->post('returnUrl'));
					}else{
						redirect('team');
					}
					
					
					//$this->jsonOutput($result['message'],array('memberinfo' => $result['memberinfo']));
				}else{
					$this->assign('feedback',$result['message']);
				}
			}
		}else{
			
			
			$this->assign('returnUrl', $this->input->get('returnUrl'));
		}
		
		$this->seoTitle('登陆');
		$this->display("member/login");
	}
	
	
	
	
	public function admin_login(){
		
		if($this->isPostRequest()){
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('email','用户名', 'required|valid_email');
			$this->form_validation->set_rules('password','密码','required|alpha_numeric');
			
			if($this->form_validation->run() !== FALSE){
				
				$this->load->library('Member_Service');
				
				$result = $this->member_service->do_login(array(
					'email' => $this->input->post('email'),
					'password' => $this->input->post('password')
				));
				
				
				if($result['code'] == 'success'){
					$this->session->set_userdata(array(
						'manage_profile' => $result['data']
					));
					
					redirect(admin_site_url('stadium/index'));
					//$this->jsonOutput($result['message'],array('memberinfo' => $result['memberinfo']));
				}else{
					
					$this->assign('feedback',$result['message']);
				}
			}
		}
		
		$this->seoTitle('后台登陆');
		$this->display('member/admin_login');
	}
	
	
	public function register()
	{
		
		$registerOk = false;
		
		if($this->isPostRequest()){
			
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('email','用户名',array(
						'required',
						'valid_email',
						array(
							'email_callable',
							array(
								$this->Member_Model,'checkEmailNoExist'
							)
						)
					),
					array(
						'email_callable' => '%s已经存在'
					)
				);
				
			$this->form_validation->set_rules('nickname','昵称', 'required');
			$this->form_validation->set_rules('psw','密码','required|alpha_dash|min_length[6]|max_length[12]');
			$this->form_validation->set_rules('psw_confirm','密码确认','required|matches[psw]');
			$this->form_validation->set_rules('agreee_licence','同意注册条款','required');
			
			if($this->form_validation->run() !== FALSE){
				
				$this->load->library('Member_Service');
				$this->load->library('Register_Service');
				
				$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
				
				if($todayRegistered < 3){
					$result = $this->register_service->createMemberByEmail(array(
						'email' => $this->input->post('email'),
						'nickname' => $this->input->post('nickname'),
						'password' => $this->input->post('psw'),
						'regip' => $this->input->ip_address(),
						'regdate' => $this->input->server('REQUEST_TIME')
					));
				
				
					if($result['code'] == 'success'){
						
						$userInfo = $this->member_service->getUserInfoByEmail($this->input->post('email'));
						$this->session->set_userdata(array(
							'profile' => array('memberinfo' => $userInfo)
						));
						
						
						//@todo mail 模版,邮件链接
						$this->email->from('tdkc_of_cixi@163.com', '运动之家');
						$this->email->to('104071152@qq.com');
						//$this->email->cc('another@another-example.com');
						//$this->email->bcc('them@their-example.com');
						
						$this->email->subject('【运动之家 邮件激活】');
						$this->email->message('尊敬的'.$this->input->post('nickname').'用户,欢迎你加入运动之家， 点击以下链接进行邮件激活,链接2小时内有效');
						if($this->email->send()){
							$this->assign('mailed',true);
						}
						
						
						
						$registerOk = true;
					}else{
						
						$this->assign('feedback',$result['message']);
					}
				}else{
					$this->assign('feedback','很抱歉，您今日注册数量已经用完');
				}
			}
		
		}
		
		
		if($registerOk){
			
			$this->load->library('Common_District_Service');
			$this->assign('d1',$this->common_district_service->getDistrictByPid(0));
		
			$this->seoTitle('设置您的所在地');
			$this->display('my/set_city');
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
