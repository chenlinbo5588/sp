<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 运动之家 管理控制器
 * 
 * 
 */
class Ydzj_Admin_Controller extends Ydzj_Controller {
	
	public $_adminProfile = array() ;
	
	public function __construct(){
		parent::__construct();
		
		$this->form_validation->set_error_delimiters('<label class="error">','</label>');
		
		$this->_adminProfile = $this->session->userdata('manage_profile');
		
		if(empty($this->_adminProfile)){
			$this->_adminProfile = array();
		}
		
		if(!$this->isLogin()){
			if($this->input->is_ajax_request()){
				$this->responseJSON('您尚未登陆',array('url' => site_url('member/admin_login')));
			}else{
				redirect(site_url('member/admin_login'));
			}
		}else{
			$this->assign('manage_profile',$this->_adminProfile);
		}
		
		//print_r($this->session->all_userdata());
	}
	
	
	public function isLogin(){
		
		if($this->_adminProfile && ($this->_reqtime - $this->session->userdata('lastvisit') < 86400)){
			return true;
		}
		
		return false;
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj_admin';
	}
	
	
	/**
	 * no need any more
	protected function _initLibrary(){
		parent::_initLibrary();
		$this->load->library('AdminBase_Service');
		
		$this->adminbase_service->initStaticVars();
	}
	*/
}

