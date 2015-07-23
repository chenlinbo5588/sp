<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 运动之家 管理控制器
 */
class Ydzj_Admin_Controller extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->isLogin()){
			if($this->input->is_ajax_request()){
				$this->responseJOSN('您尚未登陆',array('url' => site_url('member/admin_login')));
			}else{
				redirect(site_url('member/admin_login'));
			}
		}else{
			
			$this->assign('manage_profile',$this->session->userdata('manage_profile'));
		}
	}
	
	
	public function isLogin(){
		//print_r($this->session->userdata('memberinfo'));
		//if($this->session->userdata('admin_info') && ($this->_reqtime - $this->session->userdata('last_activity') < 86400)){
		if($this->session->userdata('manage_profile')){
			return true;
		}
		
		return false;
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj_admin';
	}
}

