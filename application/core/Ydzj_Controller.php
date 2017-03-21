<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 运动之家 控制器
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
		
		$this->getSiteNavs();
		
		$this->_initLogin();
	}
	
	
	/**
	 * 获得导航
	 */
	public function getSiteNavs(){
		$this->_siteNavs = $this->navigation_service->getClassTree();
		
		$this->assign('siteNavs',$this->_siteNavs);
		$this->assign('idReplacement','{ID}');
		//print_r($navTree);
		
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
	
	
	protected function prepareSideNav($navId){
		
		$navigationInfo = $this->Navigation_Model->getFirstByKey($navId,'id');
		
		$currentTopIndex = 0;
		$findNavId = $navId;
		
		if($navigationInfo['pid'] != 0){
			$findNavId =  $navigationInfo['pid'];
		}
		
		foreach($this->_siteNavs as $topIndex => $topNav){
			$currentTopIndex = $topIndex;
			
			if($findNavId == $topNav['id']){
				break;
			}
			if(!empty($topNav['children'])){
				foreach($topNav['children'] as $subNav){
					if($findNavId == $subNav['id']){
						break;
					}
				}
			}
		}
		
		return array($this->_siteNavs[$currentTopIndex],$navigationInfo);
		
	}
	
	
	/**
	 * 通用网站文章方法
	 */
	public function article(){
		
		$navId = $this->uri->rsegment(3);
		$articleId = $this->uri->rsegment(4);
		
		$article = $this->Article_Model->getFirstByKey($articleId,'article_id');
		$articleClassInfo = $this->Article_Class_Model->getFirstByKey($article['ac_id'],'ac_id');
		
		list($currentSideNav,$navigationInfo) = $this->prepareSideNav($navId);
		
		//print_r($this->uri);
		//print_r($this->_siteNavs);
		//$this->sideNavs = $tempAr[$this->modKey]['sideNav'];
		$moduleUrl = str_replace('{ID}',$currentSideNav['id'],$currentSideNav['url_cn']);
		
		$homeKey = '首页';
		$urlKey = 'url_cn';
		$nameKey = 'name_cn';
		
		if($this->_currentLang == 'english'){
			$homeKey = 'Home';
			$urlKey = 'url_en';
			$nameKey = 'name_en';
		}
		
		
		$this->_navigation = array(
			$homeKey => base_url('/'),
			$currentSideNav[$nameKey] => $moduleUrl,
		);
		
		if($navigationInfo['pid'] != 0){
			$this->_navigation[$navigationInfo[$nameKey]] = str_replace('{ID}',$navigationInfo['id'],$navigationInfo[$urlKey]);
		}
		
		$this->assign(
			array(
				'currentModule' => $this->uri->rsegment(1),
				'currentSideUrl' => base_url($this->uri->uri_string()),
				'sideTitleUrl' => $moduleUrl,
				'sideNavs' => $currentSideNav['children'],
				'sideTitle' => $currentSideNav[$nameKey],
				'nameKey' => $nameKey,
				'urlKey' => $urlKey,
				'article' => $article,
				'breadcrumb'=>$this->breadcrumb()
			)
		);
		
		$this->seo($article['article_title'].' '.$currentSideNav[$nameKey]);
		
		$this->display('common/art');
	}
	
	
	
	/**
	 * 左快捷导航
	 */
	public function setLeftNavLink($link){
		$this->_smarty->assign('LEFT_BUTTON',$link);
	}
	
	/**
	 * 右快捷导航
	 */
	public function setRightNavLink($link){
		$this->_smarty->assign('RIGHT_BUTTON',$link);
	}
	
	
	public function setTopNavTitle($title,$css = ''){
		$this->_smarty->assign('TOP_NAV_TITLE',$title);
		$this->_smarty->assign('TOP_NAV_CSS',$css);
	}
	
	protected function _getCity(){
		
		$city_id = $this->input->cookie('city');
		if($city_id == NULL){
			if($this->_profile['basic']['district_bind'] != 0){
				$city_id = $this->_profile['basic']['d2'];
			}else{
				$city_id = 176; //默认宁波市;
			}
		}
		
		$this->input->set_cookie('city',$city_id,2592000);
		
		return $city_id;
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



