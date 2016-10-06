<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 登陆态
 */
class MyYdzj_Controller extends Ydzj_Controller {
	
	protected $_loginUID = 0;
	public $_newpm = array();
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->isLogin()){
			//$this->session->unset_userdata(array($this->_lastVisitKey,$this->_profileKey));
			js_redirect('member/login');
		}
		
		$this->load->config('member_site');
		$this->_loginUID = $this->_profile['basic']['uid'];
		$this->_navs();
		$this->_initUserLabsRelate();
		if($this->_reqInterval >= config_item('pmcheck_interval')){
			$this->_pmUpdate();
		}
		
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
	 * 更新用户站内信状态
	 */
	protected function _pmUpdate(){
		$this->_newpm = $this->message_service->getLastestSysPm($this->_profile,$this->_loginUID);
		if($this->_newpm){
			$this->assign('newPm',count($this->_newpm));
		}
	}
	
	
	
	/**
	 * 
	 */
	protected function _addBreadCrumbs(){
		
		
		
	}
	
    
}



