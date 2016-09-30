<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 登陆态
 */
class MyYdzj_Controller extends Ydzj_Controller {
	private $_pushObject;
	protected $_loginUID = 0;
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->isLogin()){
			//$this->session->unset_userdata(array($this->_lastVisitKey,$this->_profileKey));
			js_redirect('member/login');
		}
		
		$this->load->config('member_site');
		
		$this->_navs();
		
		$this->_pushObject = $this->base_service->getPushObject();
		$this->assign('pushConfig',config_item('huanxin'));
	}
	
	
	/**
	 * 
	 */
	protected function _addBreadCrumbs(){
		
		
		
	}
	
    
}



