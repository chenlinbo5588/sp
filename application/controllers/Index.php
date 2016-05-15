<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
		//渠道
		$currentHost = $this->input->server('HTTP_HOST');
		$this->assign('cssName','css/'.$currentHost.'.css');
		
		$registerOk = false;
		
		if($this->isPostRequest()){
			$inviter = $this->input->post('inviter');
			$inviteFrom = $this->input->post('inviteFrom');
			
			$this->assign('inviter', $inviter);
			$this->assign('inviteFrom',$inviteFrom);
			
			
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
				
				$this->load->library(array('Member_Service','Register_Service'));
				
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
						$userInfo = $this->member_service->getUserInfoByMobile($this->input->post('mobile'));
						//$this->_rememberLoginName($this->input->post('mobile'));
						
						$this->session->set_userdata(array(
							'profile' => $userInfo
						));
						
						redirect('http://zhibo.ddy168.com');
						
						$registerOk = true;
					}else{
						
						$this->assign('feedback',$result['message']);
					}
				}else{
					$this->assign('feedback','很抱歉，您今日注册数量已经用完');
				}
			}
		
		}else{
			$this->assign('inviter', $this->input->get('inviter'));
			$this->assign('inviteFrom',$this->input->server('HTTP_REFERER'));
		}
		
		
		
		$this->display('index/'.$currentHost);
	}
	
}