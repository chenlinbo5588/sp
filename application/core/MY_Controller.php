<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $_inApp = false;
	public $_currentLang = '';
	public $_verifyName = 'verify';
	public $_lastVisit = 'lastvisit';
	public $_reqtime ;
	public $_navigation = array();
	
	protected $_siteSetting = array();
	public $_seoSetting = array();
	
	
	public $_seo = array(
		'SEO_title' => '',
		'SEO_description' => '',
		'SEO_keywords' => ''
	);
	
	public $_smarty = null;
	
	public function __construct(){
		parent::__construct();
		$this->_reqtime = $this->input->server('REQUEST_TIME');
		
		
		$this->_initLanguage();
		
		$this->_initLibrary();
		$this->_initApp();
		
		$this->_initSmarty();
		$this->_security();
		$this->_initMobile();
		
		
		
		
		$this->_initSiteSetting();
		$this->_initSeoSetting();
		
		$this->smartyConfig();
	}
	
	
	public function _initLanguage(){
		
		$lang = $this->input->cookie('lang');
		$lang2 = $this->input->get('lang');
		
		if(in_array($lang2,array('chinese','english'))){
			$this->_currentLang = $lang2;
		}
		
		if(empty($this->_currentLang) && in_array($lang,array('chinese','english'))){
			$this->_currentLang = $lang;
		}
		
		if(empty($this->_currentLang)){
			$this->_currentLang = 'chinese';
		}
		
		$this->input->set_cookie('lang',$this->_currentLang,CACHE_ONE_MONTH);
		$this->config->set_item('language',$this->_currentLang);
		
	}
	


	protected function _verify($type = 'alnum' ,$len = 5 , $seconds = 120){
		$string = random_string($type,$len);
		$expire = $this->input->server('REQUEST_TIME') + $seconds;
		$text = "{$string}\t{$expire}";
		$encrypted_string = $this->encrypt->encode($text, $this->config->item('encryption_key'));
		$this->input->set_cookie($this->_verifyName,$encrypted_string, $seconds);
		
		return $string;
	}
	
	
	public function getFormHash(){
		return array('formhash' => $this->security->get_csrf_hash(), $this->_verifyName => $this->_verify());
	}
	
	/**
	 * @todo for iOS api , need to revamp
	 */
	public function formhash(){
		$this->jsonOutput('',$this->getFormHash());
	}
	
	
	protected function _checkVerify(){
		if($this->isPostRequest()){
			$verfiycode = $this->input->cookie($this->_verifyName);
			if($verfiycode){
				$string = $this->encrypt->decode($verfiycode,$this->config->item('encryption_key'));
				$info = explode("\t",$string);
				if($this->input->post($this->_verifyName) == $info[0] && $this->input->server('REQUEST_TIME') < $info[1]){
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function isGetRequest(){
        return 'get' == strtolower($_SERVER['REQUEST_METHOD']) ? 1 : 0;
    }
    public function isPostRequest(){
        return 'post' == strtolower($_SERVER['REQUEST_METHOD']) ? 1 : 0;
    }
    
    
    private function _initMobile(){
    	//print_r($this->agent);
    	
    	
    	$this->assign('isMobile',$this->agent->is_mobile());
    }
    
    private function _initApp(){
    	if($this->input->server('HTTP_APP_SP') == 'iOS'){
			$this->_inApp = true;
		}
    }
    
    private function _initSmarty(){
    	$this->load->file(APPPATH.'third_party/smarty/Smarty.class.php');
    	
    	$this->_smarty = new Smarty();
        if(ENVIRONMENT == 'production'){
            //运行一段时间后再修改
            $this->_smarty->compile_check = false;
            //$this->_smarty->compile_check = true;
        }else{
            $this->_smarty->compile_check = true;
        }
        
    	//$this->smartyConfig();
    }
    
    protected function _getSiteSetting($key){
    	if($key){
    		return $this->_siteSetting[$key];
    	}else{
    		
    		return $this->_siteSetting;
    	}
    }
    	
    /**
     * 返回缓存类型
     */
    public function getCacheObject(){
    	$driverName = config_item('cache_driver');
    	
    	if($driverName == 'redis'){
    		$isSupport = $this->cache->redis->is_supported();
	    	if($isSupport){
	    		return $this->cache->redis;
	    	}else{
	    		
	    		return $this->cache->file;
	    	}
    		
    	}else{
    		return $this->cache->file;
    	}
    }
    
    private function _initSiteSetting(){
    	$settingList = $this->getCacheObject()->get(CACHE_KEY_SiteSetting);
    	if(empty($settingList)){
    		$temp = $this->Setting_Model->getList();
    		//print_r($list);
	    	$settingList = array();
	    	foreach($temp as $item){
	    		$settingList[$item['name']] = $item['value'];
	    	}
	    	
	    	$this->getCacheObject()->save(CACHE_KEY_SiteSetting,$settingList,CACHE_ONE_DAY);
    	}
    	
    	$this->_siteSetting = $settingList;
    	
    	$this->assign('siteSetting',$this->_siteSetting);
    	
    	
    	$siteNameKey = 'site_name';
    	if($this->_currentLang == 'english'){
    		$siteNameKey .= '_en';
    	}
    	
    	$this->config->set_item('site_name',$this->_siteSetting[$siteNameKey]);
    	$this->config->set_item('image_max_filesize',$this->_siteSetting['image_max_filesize']);
    	$this->config->set_item('background_image_allow_ext',$this->_siteSetting['background_image_allow_ext']);
    	$this->config->set_item('forground_image_allow_ext',$this->_siteSetting['forground_image_allow_ext']);
    	
    }
    
    
    private function _initSeoSetting(){
    	
    	$seoList = $this->getCacheObject()->get(CACHE_KEY_SeoSetting);
    	if(empty($seoList)){
    		$temp = $this->Seo_Model->getList();
    		//print_r($list);
	    	$seoList = array();
	    	
	    	$siteNameKey = 'site_name';
	    	if($this->_currentLang == 'english'){
	    		$siteNameKey .= '_en';
	    	}
	    	
	    	foreach($temp as $item){
	    		$item['title'] = str_replace(array('{sitename}'),array($this->_siteSetting[$siteNameKey]),$item['title']);
	    		$item['keywords'] = str_replace(array('{sitename}'),array($this->_siteSetting[$siteNameKey]),$item['keywords']);
	    		$item['description'] = str_replace(array('{sitename}'),array($this->_siteSetting[$siteNameKey]),$item['description']);
	    		
	    		$seoList[$item['type']] = $item;
	    	}
	    	
	    	$this->getCacheObject()->save(CACHE_KEY_SeoSetting,$seoList,CACHE_ONE_DAY);
    	}
    	
    	$this->_seoSetting = $seoList;
    	
    }
    
    
    public function getAppTemplateDir(){
    	return 'default';
    }
    
    
    public function smartyConfig(){
    	$dir = $this->getAppTemplateDir();
    	
    	$this->_smarty->setTemplateDir(SMARTY_TPL_PATH.$dir.DS.'templates'.DS);
        $this->_smarty->setCompileDir(SMARTY_TPL_PATH.$dir.DS.'templates_c'.DS);
        $this->_smarty->addPluginsDir(SMARTY_TPL_PATH.$dir.DS.'plugins'.DS);
        $this->_smarty->setCacheDir(SMARTY_TPL_PATH.$dir.DS.'cache'.DS);
        $this->_smarty->setConfigDir(SMARTY_TPL_PATH.$dir.DS.'config'.DS);
    	
    }
    
    
    protected function _initLibrary(){
		$this->load->helper(array('form','directory','file', 'url','string'));
		$this->load->driver('cache');
		$this->load->model(array('Member_Model','Setting_Model','Seo_Model'));
		$this->load->library(array('user_agent','Form_validation','encrypt','PHPTree','Base_service'));
		
		$this->load->config('site');
		
		
		$this->base_service->initStaticVars();
		
    }
    
    
    private function _security(){
    	
    	$this->assign('formhash',$this->security->get_csrf_hash());
    	
    	/*
    	if($this->input->cookie($this->_lastVisit) != ''){
			$elapsed_time = number_format(microtime(TRUE) -  $this->input->cookie($this->_lastVisit), 2);
			if($elapsed_time < 0.2){
				if($this->input->is_ajax_request() || $this->_inApp == true){
					$this->responseJSON('请求过于频繁');
				}else{
					show_error('请求过于频繁',200);
				}
			}
		}
		
		$this->input->set_cookie($this->_lastVisit,microtime(TRUE),time() + 86400);
		
		if($this->isPostRequest() && !$this->_checkVerify()){
			if($this->input->is_ajax_request() || $this->_inApp == true){
				$this->responseJSON('请求失效');
			}else{
				show_error('请求失效',500);
			}
		}
		*/
    }
    
    
    public function assign($name , $value = ''){
        if(is_array($name)){
            $this->_smarty->assign($name);
        }else{
            $this->_smarty->assign($name,$value);
        }
    }
    
    
    public function breadcrumb(){
    	
    	if($this->_navigation){
    		
    		$temp = array();
    		
    		$i = 0;
    		
    		foreach($this->_navigation as $key => $item){
    			
    			$key = htmlspecialchars($key);
    			
    			if($i == 0){
    				$temp[] = "<a class=\"first breadlink\" href=\"{$item}\">{$key}</a>";
    			}else{
    				$temp[] = "<a class=\"breadlink\" href=\"{$item}\">{$key}</a>";
    			}
    			
    			$i++;
    		}
    		
    		return implode('<em>&gt;</em>',$temp);
    	}else{
    		return '';
    	}
    }
    
    
    public function display($viewname = ''){
    	//echo $this->uri->uri_string();
    	
    	if($viewname == ''){
    		$tempPath = array();
    		
    		$paramAr = $this->uri->rsegment_array();
    		//print_r($paramAr);
    		$startIndex = 1;
    		
    		if($paramAr[1] == 'admin'){
    			$startIndex = 2;
    		}
    		
    		$tempPath[] = empty($paramAr[$startIndex]) ? 'index' :  $paramAr[$startIndex];
    		$startIndex++;
    		$tempPath[] =  (empty($paramAr[$startIndex]) ? 'index' :  $paramAr[$startIndex]);
    		
    		$viewname = implode('/',$tempPath);
    	}
    	
    	//修改前
    	$unchangeTplName = $viewname;
    	$tplDir = $this->_smarty->getTemplateDir(0);
    	
    	$this->assign($this->_seo);
    	$this->assign('currentLang',$this->_currentLang);
    	$this->loadPageLang();
    	
    	
    	if($this->input->is_ajax_request()){
    		$viewname = $viewname.'_ajax';
    	}else{
    		if($this->agent->is_mobile()){
    			$viewname = $viewname . '_mobile';
    		}
    	}
    	
    	$realPath = $tplDir.$viewname.'.tpl';
    	//echo $realPath;
    	
    	if(file_exists($realPath)){
    		$this->output->set_output($this->_smarty->fetch($realPath));
    	}else{
    		$this->output->set_output($this->_smarty->fetch($tplDir.$unchangeTplName.'.tpl'));
    	}
    	
    }
    
    
    /**
     * 
     */
    public function loadPageLang(){
    	$page = $this->uri->rsegment_array();
    	
    	$langArray = array();
    	if(file_exists(APPPATH."language/{$this->_currentLang}/common_lang.php")){
    		$langArray = $this->lang->load('common','',true);
    	}
    	
    	
    	if(file_exists(APPPATH."language/{$this->_currentLang}/{$page[1]}_lang.php")){
    		$langArray = array_merge($langArray,$this->lang->load($page[1],'',true));
   		}
   		
   		$this->assign($langArray);
    }
    
    
    
    public function seoTitle($title){
    	$this->_seo['SEO_title'] = $title;
    }
    
    public function seo($title = '',$keyword = '', $desc = ''){
    	//print_r($this->_seoSetting);
    	$siteNameKey = 'site_name';
    	if($this->_currentLang == 'english'){
    		$siteNameKey .= '_en';
    	}
	    	
	    	
    	if($title){
    		$this->_seo['SEO_title'] = $title;
    	}else{
    		$this->_seo['SEO_title'] = $this->_siteSetting[$siteNameKey];
    	}
    	
    	if($keyword){
    		$this->_seo['SEO_keywords'] = $keyword . ','.$this->_seoSetting['index']['keywords'];
    	}else{
    		$this->_seo['SEO_keywords'] = $this->_seoSetting['index']['keywords'];
    	}
    	
    	if($desc){
    		$this->_seo['SEO_description'] = $desc. ','.$this->_seoSetting['index']['keywords'];
    	}else{
    		$this->_seo['SEO_description'] = $this->_seoSetting['index']['description'];
    	}
    }
    
    /**
     * 
     */
    public function responseJSON($message = '' , $data = array()){
    	$this->output
		    	->set_status_header(200)
		    	->set_content_type('application/json')
		    	->set_output(json_encode(array('message' => $message,'data' => $data)));
		    	
		$this->output->_display();
		
		exit;
    }
    
    /**
     * 设置
     */
    public function jsonOutput($message = '' , $data = array() , $cacheTime = 0 ){
    	if(!$this->output->_display_cache($this->config,$this->URI)){
			// 缓存文件不存在或者是已经失效，设置数据
			$data = array('message' => $message,'data' => $data);
			//print_r($data);
			$this->output
		    	->set_status_header(200)
		    	->set_content_type('application/json')
		    	->set_output(json_encode($data));
		    	
		    if($cacheTime > 0){
		    	$this->output->cache($cacheTime);
		    }
		}
    }
}



include APPPATH.'core/Ydzj_Controller.php';
include APPPATH.'core/Ydzj_Admin_Controller.php';
