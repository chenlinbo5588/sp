<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $_verifyName;
	
	
	public $_lastVisit;
	public $_lastVisitKey;
	
	//请求时间间隔
	public $_reqInterval = 0;
	
	public $_reqtime;
	
	protected $_subNavs;
	protected $_breadCrumbs;
	
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
		$this->_subNavs = array();
		$this->_breadCrumbs = array();
		$this->_verifyName = 'verify';
		
		$this->_initLibrary();
		$this->_initSmarty();
		
		$this->_initMobile();
		$this->_initSiteSetting();
		$this->_initSeoSetting();
		
		$this->seo();
		
		$this->smartyConfig();
	}
	
	protected function _verify($type = 'alnum' ,$len = 5 , $seconds = 600){
		$string = random_string($type,$len);
		$expire = $this->_reqtime + $seconds;
		$text = "{$string}\t{$expire}";
		$encrypted_string = $this->encrypt->encode($text);
		//$this->input->set_cookie($this->_verifyName,$encrypted_string, $seconds);
		
		return $string;
	}
	
	
	
	public function getFormHash(){
		return array(config_item('csrf_token_name') => $this->security->get_csrf_hash());
	}
	
	public function isGetRequest(){
        return 'get' == strtolower($_SERVER['REQUEST_METHOD']) ? 1 : 0;
    }
    public function isPostRequest(){
        return 'post' == strtolower($_SERVER['REQUEST_METHOD']) ? 1 : 0;
    }
    
    
    private function _initMobile(){
    	$this->assign('isMobile',$this->agent->is_mobile());
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
    	//print_r($settingList);
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
    	$this->config->set_item('site_name',$this->_siteSetting['site_name']);
    }
    
    
    private function _initSeoSetting(){
    	
    	$seoList = $this->getCacheObject()->get(CACHE_KEY_SeoSetting);
    	
    	
    	if(empty($seoList)){
    		$temp = $this->Seo_Model->getList();
    		//print_r($list);
	    	$seoList = array();
	    	foreach($temp as $item){
	    		$item['title'] = str_replace(array('{sitename}'),array($this->_siteSetting['site_name']),$item['title']);
	    		$item['keywords'] = str_replace(array('{sitename}'),array($this->_siteSetting['site_name']),$item['keywords']);
	    		$item['description'] = str_replace(array('{sitename}'),array($this->_siteSetting['site_name']),$item['description']);
	    		
	    		$seoList[$item['type']] = $item;
	    	}
	    	
	    	//print_r($seoList);
	    	$this->getCacheObject()->save(CACHE_KEY_SeoSetting,$seoList,CACHE_ONE_DAY);
    	}
    	
    	$this->_seoSetting = $seoList;
    	///print_r($this->_seoSetting);
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
    	/* @todo 需要更改为 lazy connection */
    	$this->load->helper(array('form','directory','file', 'url','string'));
    	$this->load->config('site');
    	
    	$this->load->database();
    	
		
		$this->load->driver('cache');
		
		$this->load->model(array('Setting_Model','Member_Model','Seo_Model'));
		$this->load->library(array('session', 'user_agent','Form_validation','encrypt','PHPTree','Base_service'));
		
		$this->base_service->initStaticVars();
		
    }
    
    public function assign($name , $value = ''){
        if(is_array($name)){
            $this->_smarty->assign($name);
        }else{
            $this->_smarty->assign($name,$value);
        }
    }
    
    
    public function display($viewname = ''){
    	//echo $this->uri->uri_string();
    	if($viewname == ''){
    		$tempPath = array();
    		
    		$paramAr = $this->uri->segment_array();
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
    	
    	$this->_smarty->assign($this->_seo);
    	
    	if($this->input->is_ajax_request()){
    		$viewname = $viewname.'_ajax';
    	}else{
    		if($this->agent->is_mobile()){
    			$viewname = $viewname . '_mobile';
    		}
    	}
    	
    	$realPath = $tplDir.$viewname.'.tpl';
    	//echo $realPath;
    	$this->assign(array('subNavs' => $this->_subNavs,'breadCrumbs' => $this->_breadCrumbs));
    	
		if(file_exists($realPath)){
    		$this->output->set_output($this->_smarty->fetch($realPath));
    	}else{
    		$this->output->set_output($this->_smarty->fetch($tplDir.$unchangeTplName.'.tpl'));
    	}
    	
    }
    
    public function seoTitle($title = ''){
    	if($title){
    		$this->_seo['SEO_title'] = $title . ' - '.$this->_siteSetting['site_name'];
    	}else{
    		$this->_seo['SEO_title'] = $this->_siteSetting['site_name'];
    	}
    }
    
    public function seo($title = '',$keyword = '', $desc = ''){
    	if($title){
    		$this->_seo['SEO_title'] = $title;
    	}else{
    		$this->_seo['SEO_title'] = $this->_siteSetting['site_name'];
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
     * json format
     */
    private function _buildJsonFormat($message = '' , $data = array()){
    	if(is_array($message)){
    		if (array_key_exists('code', $message)) {
    			$d = array_merge($message,array('data' => $data));
    		}else {
    			$d = array('code' => $message[0],'message' =>$message[1], 'data' => $data);
    		}
			
		}else{
			$d = array('message' => $message,'data' => $data);
		}
		
		if(config_item('csrf_protection') && $this->isPostRequest()){
			$d['data'] = array_merge($d['data'],$this->getFormHash());
		}
		
		return $d;
    }
    
    
    /**
     * 
     */
    public function responseJSON($message = '' , $data = array()){
    	$this->output
		    	->set_status_header(200)
		    	->set_content_type('application/json')
		    	->set_output(json_encode($this->_buildJsonFormat($message,$data)));
		    	
		$this->output->_display();
		
		exit;
    }
    
    /**
     * 设置
     */
    public function jsonOutput($message = '' , $data = array() , $cacheTime = 0 ){
    	if(!$this->output->_display_cache($this->config,$this->URI)){
			// 缓存文件不存在或者是已经失效，设置数据
			$d = $this->_buildJsonFormat($message,$data);
			$this->output
		    	->set_status_header(200)
		    	->set_content_type('application/json')
		    	->set_output(json_encode($d));
		    	
		    if($cacheTime > 0){
		    	$this->output->cache($cacheTime);
		    }
		}
    }
}


require_once(APPPATH.'libraries'.DIRECTORY_SEPARATOR.'Http_client.php');
require_once(APPPATH.'libraries'.DIRECTORY_SEPARATOR.'Flexihash.php');
include_once APPPATH.'core/Ydzj_Controller.php';
include_once APPPATH.'core/MyYdzj_Controller.php';
include_once APPPATH.'core/Ydzj_Admin_Controller.php';


