<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function welcome(){
		
		$this->display('index/welcome');
	}
	
	
	
	/**
	 * 
	 */
	public function profile(){
		
		
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('username','真实名称',"required|min_length[1]|max_length[30]");
			
			
			if($this->input->post('old_password')){
				$this->form_validation->set_rules('old_password','原密码','required|min_length[6]|max_length[12]|alpha_dash');
				$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|alpha_dash');
				$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
			}
			
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$oldPsw = trim($this->input->post('old_password'));
				
				
				$updateData = array(
					'username' => trim($this->input->post('username'))
				);
				
				if($oldPsw){
					$adminUser = $this->Adminuser_Model->getFirstByKey($this->_adminProfile['basic']['uid'],'uid');
					if($oldPsw != $this->encrypt->decode($adminUser['password'],config_item('encryption_key').md5($adminUser['email']))){
						$feedback = '原密码不正确';
						break;
					}
					
					$updateData['password'] = $this->encrypt->encode(trim($this->input->post('admin_password')),config_item('encryption_key').md5(trim($adminUser['email'])));
				}
				
				$this->Adminuser_Model->update($updateData,array(
					'uid' => $this->_adminProfile['basic']['uid']
				));
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('feedback',$feedback);
		
		$this->display();
	}
	
	public function index()
	{
		//print_r($this->session->all_userdata());
		$this->display();
	}
	
	
	public function logout()
	{
		$this->session->unset_userdata('manage_profile');
		redirect(site_url('member/admin_login'));
	}
}
