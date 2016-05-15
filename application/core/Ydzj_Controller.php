<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Ydzj_Controller extends MY_Controller {
	
	public $_profile = array();
	
	
	public function __construct(){
		parent::__construct();
		
		$this->form_validation->set_error_delimiters('<div class="form_error">','</div>');
		
		
		$this->session->set_userdata(array('lastvisit' => $this->_reqtime));
		$this->_profile = $this->session->userdata('profile');
		//print_r($this->session->all_userdata());
		if(empty($this->_profile)){
			$this->_profile = array();
		}
		
		if($this->isLogin()){
			$this->assign('profile',$this->session->userdata('profile'));
			//$this->attachment_service->setUid($this->_profile['basic']['uid']);
		}
		
		//$this->initEmail();
		$this->seo('赢金财经直播间','赢金财经直播间', '赢金财经直播间');
	}
	
	protected function _initLibrary(){
		parent::_initLibrary();
	}
	
	
	
	public function isLogin(){
		if($this->_profile && ($this->_reqtime - $this->session->userdata('lastvisit') < 86400)){
			return true;
		}
		
		return false;
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj';
	}
	
	
	/**
	 * @todo modify when online
	 */
	protected function initEmail(){
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = "smtp.163.com";
		$config['smtp_port'] = 25;
		$config['smtp_user'] = "tdkc_of_cixi";
		$config['smtp_pass'] = 'woaitdkc1234';
		$config['smtp_timeout'] = 10;
		$config['charset'] = config_item('charset');
		
		$this->load->library('email');
		$this->email->initialize($config);
	}
}


/**
 * 登陆态
 */
class MyYdzj_Controller extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->isLogin()){
			redirect('member/login');
		}
		
	}
	
}



