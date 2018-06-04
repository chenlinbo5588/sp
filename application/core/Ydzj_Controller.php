<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 业务逻辑 基础 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Ydzj_Controller extends MY_Controller {
	
	public $_profile = array();
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Seo_service','Article_service'));
		$this->form_validation->set_error_delimiters('<label class="form_error">','</label>');
		
		$this->_navs();
		$this->_initLogin();
	}
	
	/**
     * 导航相关
     */
    protected function _navs(){
		/*
		$navs = $this->uri->rsegments;
		
		if($navs[1] == 'admin'){
			$navs = array_slice($navs,1,3);
		}
		
		//功能 url 访问路径
	    $funcUrl = implode('/',$navs);
	    
	    print_r($_SERVER);
	    */
	    
	    $currentUri = $_SERVER['REQUEST_URI'];
	    if(preg_match("/^\/index.php\/admin\//",$currentUri,$match)){
	    	$currentUri = substr($currentUri,17);
	    }
		
		/*
		if(strpos($currentUri,'?') !== false){
			$currentUri = substr($currentUri,0,strpos($currentUri,'?'));
		}
		*/
		
	    $this->assign('currentUri',strtolower($currentUri));
	}
	
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
	}
	
	
	private function _initLogin(){
		
		$this->session->set_userdata(array('lastvisit' => $this->_reqtime));
		$this->_profile = $this->session->userdata('profile');
		//print_r($this->session->all_userdata());
		if(empty($this->_profile)){
			$this->_profile = array();
		}
		
		if($this->isLogin()){
			$this->assign('profile',$this->session->userdata('profile'));
			$this->attachment_service->setUid($this->_profile['basic']['uid']);
		}
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