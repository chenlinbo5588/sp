<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 登陆
	 */
	public function login()
	{
		if($this->isPostRequest()){
			
			$this->load->library('Member_Service');
			
			$result = $this->member_service->do_login(array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password')
			));
			
			if($result['code'] == SUCCESS_CODE){
				$this->session->set_userdata(array(
					'memberinfo' => $result['memberinfo']
				));
				
				$this->jsonOutput($result['message'],array('memberinfo' => $result['memberinfo']));
			}else{
				$this->jsonOutput($result['message'],array());
			}
			
		}else{
			$this->jsonOutput('',array('formhash' => $this->security->get_csrf_hash()));
		}
		
	}
	
	
	/**
	 * 登出
	 */
	public function logout(){
		$this->session->sess_destroy();
		$this->jsonOutput('',array());
	}
	
}
