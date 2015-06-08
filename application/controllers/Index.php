<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
		$this->display('index/index');
	}
	
	
	public function admin_login(){
		if($this->isPostRequest()){
			
			$this->load->library('Member_Service');
			
			$result = $this->member_service->do_login(array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password')
			));
			
			if($result['code'] == SUCCESS_CODE){
				$this->session->set_userdata(array(
					'memberinfo' => $result['memberinfo'],
					'last_activity' => $this->_reqtime
				));
				
				redirect(site_url('sp_admin'));
				
			}else{
				
				$this->assign('message',$result['message']);
				$this->display('index/admin_login');
			}
			
			
		}else{
			$this->display('index/admin_login');
		}
		
		
		
	}
	
	
}
