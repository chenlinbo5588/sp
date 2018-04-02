<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 业务逻辑 基础 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Ydzj_Controller extends MY_Controller {
	
	public $_profile = array();
	public $_siteNavs = array();
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Seo_service','Navigation_service','Article_service'));
		$this->form_validation->set_error_delimiters('<label class="form_error">','</label>');
		
		//$this->getSiteNavs();
		
		$this->_initLogin();
	}
	
	protected function _initLibrary(){
		parent::_initLibrary();
	}
	
	/**
	 * 获取 网站导航 
	 */
	public function getSiteNavs(){
		$this->_siteNavs = $this->navigation_service->getClassTree();
		
		$this->assign('siteNavs',$this->_siteNavs);
		$this->assign('idReplacement','{ID}');
		//print_r($navTree);
		
	}
	
	
	/**
	 * 加载微信支持文件
	 */
	public function loadWeixinSupportFiles(){
		require_once(WEIXIN_PATH.'errorCode.php');
		require_once(WEIXIN_PATH.'sha1.php');
		require_once(WEIXIN_PATH.'xmlparse.php');
		require_once(WEIXIN_PATH.'pkcs7Encoder.php');
		require_once(WEIXIN_PATH.'wxBizMsgCrypt.php');
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