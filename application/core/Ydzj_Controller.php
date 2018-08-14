<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 业务逻辑 基础 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Ydzj_Controller extends MY_Controller {
	
	public $_profile = array();
	
	
	//检查权限路径
	protected $_checkPermitUrl = '';
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Seo_service','Article_service'));
		$this->form_validation->set_error_delimiters('<label class="form_error">','</label>');
		
		$this->_navs();
		
		$this->_userKeepFresh();
		
		$this->_initLogin();
	}
	
	/**
     * 导航相关
     */
    protected function _navs(){
	    
	    //$currentUri = $_SERVER['REQUEST_URI'];
	    $currentUri = $this->uri->uri_string();
	    $this->_checkPermitUrl = $currentUri;
	    
	    
	    /*
	    if(preg_match("/^\/index.php/",$currentUri,$match)){
	    	$this->_checkPermitUrl = substr($currentUri,11);
	    }
	    
	    if(preg_match("/^\/index.php\/admin\//",$currentUri,$match)){
	    	$this->_checkPermitUrl = substr($currentUri,17);
	    }
	    */
	    
	    if('admin' != $this->_checkPermitUrl){
		    if(preg_match("/^admin/",$currentUri,$match)){
		    	$this->_checkPermitUrl = substr($currentUri,6);
		    }
	    }
		
		$this->_checkPermitUrl = strtolower($this->_checkPermitUrl);
		
		$this->assign(array(
			'permitUri' => $this->_checkPermitUrl
		));
		
	}
	
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
	}
	
	
	/**
	 * 后台添加了新的通知  ，由用于触发信息的更新
	 */
	private function _userKeepFresh(){
		$lsk = $this->input->get_cookie('lsk');
		
		if($lsk){
			$this->_reqInterval = $this->_reqtime - $lsk;
		}
		
		//大于更新时间
		if(empty($lsk) || $this->_reqInterval > config_item('pmcheck_interval') ){
			$this->input->set_cookie('lsk',$this->_reqtime,CACHE_ONE_DAY);
		}
		
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