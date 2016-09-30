<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 登陆态
 */
class MyYdzj_Controller extends Ydzj_Controller {
	
	protected $_loginUID = 0;
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->isLogin()){
			$this->session->unset_userdata(array($this->_lastVisitKey,$this->_profileKey));
			js_redirect('member/login');
		}
		
		$this->load->config('member_site');
		$this->_navs();
		$this->_initUserLabsRelate();
		
	}
	
	
	private function _initUserLabsRelate(){
		$this->load->library('Lab_service');
		$ar = $this->lab_service->getUserLabsAssoc($this->_adminProfile['basic']['id']);
    	$this->session->set_userdata($ar);
	}
	
	
	/**
     *  检查是否是 系统管理员
     */
    protected function _checkIsSystemManager(){
    	
    	if($this->_loginUID == LAB_FOUNDER_ID){
    		return true;
    	}
    	
    	if($this->_adminProfile['basic']['is_manager'] != 'y'){
			return false;
		}else{
			return true;
		}
    }
    
	
	
	
	
	/**
	 * 
	 */
	protected function _addBreadCrumbs(){
		
		
		
	}
	
    
}



