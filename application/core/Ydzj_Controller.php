<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Ydzj_Controller extends MY_Controller {
	
	public $_profile;
	public $_profileKey;
	
	public $_adminProfile;
	public $_adminProfileKey;
	public $_adminLastVisitKey;
	
	
	
	
	public function __construct(){
		parent::__construct();
		
		$this->_lastVisitKey = 'lastvisit';
		$this->_profileKey = 'profile';
		
		$this->_adminProfileKey = 'admin_profile';
		$this->_adminLastVisitKey = 'admin_lastvisit';
		
		$this->_profile = array();
		$this->_adminProfile = array() ;
		
		$this->form_validation->set_error_delimiters('<label class="form_error">','</label>');
		
		$this->_initLogin();
	}
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
	}
	
	private function _initLogin(){
		$userData =  $this->session->get_userdata();
		//print_r($userData);
		$lastVisit = $userData[$this->_lastVisitKey];
		
		if(empty($lastVisit)){
			$lastVisit = 0;
		}
		
		if($userData[$this->_profileKey]){
			$this->_profile = $userData[$this->_profileKey];
		}
		
		/* 如果已登陆 或者 首次登陆 则刷新上次访问时间 */
		if($this->_profile && $lastVisit == 0){
			$this->session->set_userdata(array($this->_lastVisitKey => $this->_reqtime));
		}
		
		$this->assign($this->_profileKey,$this->_profile);
		
	}
	
	
	public function isLogin($session = array()){
		if(!$session){
			$session = $this->session->get_userdata();
		}
		
		$lastVisit = empty($session[$this->_lastVisitKey]) ? 0 : $session[$this->_lastVisitKey];	
		if($session[$this->_profileKey] && ($this->_reqtime - $lastVisit) < CACHE_ONE_MONTH){
			return true;
		}
		
		return false;
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj';
	}
	
	protected function _getCity(){
		
		$city_id = $this->input->get_cookie('city');
		if($city_id == NULL){
			if($this->_profile['basic']['district_bind'] != 0){
				$city_id = $this->_profile['basic']['d2'];
			}else{
				//$city_id = 176; //默认宁波市;
				$city_id = 0; //默认全国;
			}
		}
		
		$this->input->set_cookie('city',$city_id,2592000);
		
		return $city_id;
	}
	
	
	protected function refreshProfile(){
		$this->session->set_userdata(array(
			$this->_profileKey => $this->_profile
		));
	}
	
}
