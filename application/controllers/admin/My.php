<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{
		//print_r($this->session->all_userdata());
		$this->display('index/index');
	}
	
	public function logout()
	{
		$this->session->unset_userdata('manage_profile');
		redirect(site_url('member/admin_login'));
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
			
			$admin_password = $this->input->post('admin_password');
			$admin_rpassword = $this->input->post('admin_rpassword');
			
			if($admin_password || $admin_password){
				$this->form_validation->set_rules('old_password','原密码','required|min_length[6]|max_length[12]|alpha_dash');
			}
			
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				$oldPsw = trim($this->input->post('old_password'));
				
				$updateData = array(
					'username' => trim($this->input->post('username'))
				);
				
				if($oldPsw){
					$adminUser = $this->Adminuser_Model->getFirstByKey($this->_adminProfile['basic']['uid'],'uid');
					if($oldPsw != $this->encrypt->decode($adminUser['password'],config_item('encryption_key').md5($adminUser['email']))){
						$feedback = getErrorTip('原密码不正确');
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
	
	
	/**
	 * 检查是否有新想消息s
	 */
	public function check_newpm(){
		
		$message = array();
		
		$newCnt = 0;
		
		foreach($this->_adminNewPm as $key => $val){
			$newCnt += $val;
			
			if($val){
				switch($key){
					case 'site_pm':
						$message[] = "您有新的通知消息，请注意查收";
						break;
					case 'trans_pm':
						$message[] = "您有新的交易，请注意查看";
						break;
					case 'user_pm':
						$message[] = "您有新的私信，请注意查收";
						break;
					default:
						break;
				}
			}
		}
		
		$this->jsonOutput('请求成功',array('newPm' => $newCnt,'tip' =>  implode("\n",$message)));
	}
	
}
