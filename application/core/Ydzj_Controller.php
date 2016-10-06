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
		
		$this->form_validation->set_error_delimiters('<label class="error">','</label>');
		
		$this->_userKeepFresh();
		$this->_initLogin();
	}
	
	
	/**
	 * 导航相关
	 */
	protected function _navs(){
		$navs = $this->uri->segments;
		$moduleIndex = 1;
		
		if($navs[1] == 'admin'){
			$navs = array_slice($navs,1,3);
			$moduleIndex = 0;
		}
		
		//功能 url 访问路径
        $funcUrl = implode('/',$navs);
		
		/*
		$currentUri = $_SERVER['REQUEST_URI'];
		if(preg_match("/^\/index.php\/admin\//",$currentUri,$match)){
			$currentUri = substr($currentUri,17);
		}
		*/
		
		$modulName = $navs[$moduleIndex];
		$moduleUrl = $modulName.'/';
		
		$configNav = config_item('navs');
		$topSelect = $configNav['main'][$navs[$moduleIndex]];
		
		/* 由于主菜单 下 有其他子菜单，使用其他菜单功能是，蒋顶部导航选择 */
		if(empty($topSelect)){
			$topSelect = $configNav['main'][$configNav['side'][$navs[$moduleIndex]]];
		}
		
		$this->_subNavs = $configNav['sub'][$modulName];
		$this->_breadCrumbs[] = $topSelect;
		
		$sideMenu = array();
		if(is_string($configNav['side'][$modulName])){
			$sideMenu = $configNav['side'][$configNav['side'][$modulName]];
		}else{
			$sideMenu = $configNav['side'][$modulName];
		}
		
		if($configNav['sub_parent'][$funcUrl]){
			$this->_breadCrumbs[] = $configNav['sub_parent'][$funcUrl];
		}
		
		if($configNav['sub'][$modulName][$funcUrl]){
			$this->_breadCrumbs[] = array('url' => $funcUrl , 'title'=> $configNav['sub'][$modulName][$funcUrl]);
		}
		
		$this->assign('uri_string',$this->uri->uri_string);
		$this->assign('currentTopNav',$topSelect);
		//$this->assign('currentURL',$currentUri);
        
        $this->assign('modulName',$modulName);
        $this->assign('moduleUrl',$moduleUrl);
        $this->assign('funcUrl',$funcUrl);
        
        $this->assign('navs',$configNav);
	}
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
		$this->load->library('Message_service');
	}
	
	
	/**
	 * 后台添加了新的通知  ，由用于触发信息的更新
	 */
	private function _userKeepFresh(){
		$lsk = $this->input->get_cookie('lsk');
		if($lsk){
			$this->_reqInterval = $this->_reqtime - $lsk;
		}
		
		//大于一分钟 这更新
		if(empty($lsk) || $this->_reqInterval > config_item('pmcheck_interval') ){
			$this->input->set_cookie('lsk',$this->_reqtime,CACHE_ONE_DAY);
		}
	}
	
	
	
	private function _initLogin(){
		$userData =  $this->session->get_userdata();
		
		$lastVisit = $userData[$this->_lastVisitKey];
		if(empty($lastVisit)){
			$lastVisit = 0;
		}
		
		if($userData[$this->_profileKey]){
			//保存时间间隔
			//$this->_reqInterval = $this->_reqtime - $lastVisit;
			
			$this->_profile = $userData[$this->_profileKey];
			$this->assign($this->_profileKey,$this->_profile);
		}
		
		/* 如果已登陆 或者 首次登陆 则刷新上次访问时间 */
		if($this->_profile || $lastVisit == 0){
			$this->session->set_userdata(array($this->_lastVisitKey => $this->_reqtime));
		}
		
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
	
	/**
	 * 上传文件成功与否
	 */
	protected function uploadJSONFormat($error = 0, $fileData){
		if($error){
			return array('error' => $error, config_item('csrf_token_name') =>$this->security->get_csrf_hash(),'msg'=> $this->upload->display_errors('','') );
		}else{
			return array('error' => $error, config_item('csrf_token_name') =>$this->security->get_csrf_hash(),'id' => $fileData['id'], 'url'=>base_url($fileData['file_url']));
		}
	}
	
}
