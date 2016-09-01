<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function nopermission(){
		$this->display('common/nopermission');
	}
	
	
	public function logout()
	{
		$this->session->unset_userdata($this->_adminProfileKey);
		redirect(site_url('member/admin_login'));
	}
	
	
	/**
	 * 
	 */
	public function profile(){
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('name','真实名称',"required|min_length[1]|max_length[30]");
			
			
			if($this->input->post('old_password')){
				$this->form_validation->set_rules('old_password','原密码','required|min_length[6]|max_length[12]|alpha_dash');
				$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|alpha_dash');
				$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
			}
			
			$email = trim($this->input->post('email'));
			if($email){
				$this->form_validation->set_rules('email','邮箱','required|valid_email');
			}
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$oldPsw = trim($this->input->post('old_password'));
				
				$updateData = array(
					'name' => trim($this->input->post('name')),
					'email' => $this->input->post('email') ? trim($this->input->post('email')) : ''
				);
				
				if($oldPsw){
					$adminUser = $this->Adminuser_Model->getFirstByKey($this->_adminProfile['basic']['id']);
					if($adminUser['psw'] != md5(config_item('encryption_key').$oldPsw)){
						$feedback = getErrorTip('原密码不正确');
						break;
					}
					
					$updateData['psw'] = md5(config_item('encryption_key').trim($this->input->post('admin_password')));
				}
				
				$this->Adminuser_Model->update($updateData,array(
					'id' => $this->_adminProfile['basic']['id']
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
		//$this->display('index/index');
		
		$this->display('dashboard/aboutus');
	}
}
