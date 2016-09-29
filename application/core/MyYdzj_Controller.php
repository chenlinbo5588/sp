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
	
	
	/**
	 * 导航相关
	 */
	protected function _navs(){
		
		$navs = $this->uri->segments;
        $pathStr = implode('/',$navs);
		
		$currentUri = $_SERVER['REQUEST_URI'];
		if(preg_match("/^\/index.php\/admin\//",$currentUri,$match)){
			$currentUri = substr($currentUri,17);
		}
		
		$configNav = config_item('navs');
		$this->_subNavs = $configNav['sub'][$navs[1]];
		///print_r($this->_subNavs);
		
		$this->assign('uri_string',$this->uri->uri_string);
		$this->assign('currentURL',$currentUri);
        $this->assign('pathStr',$pathStr);
        $this->assign('fnKey',$navs[1]);
        
        $this->assign('navs',$configNav);
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



