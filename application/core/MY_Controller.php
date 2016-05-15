<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $_inApp = false;
	public $_verifyName = 'verify';
	public $_lastVisit = 'lastvisit';
	public $_reqtime ;
	public $_navigation = array();
	
	
	public $_seo = array(
		'SEO_title' => '',
		'SEO_description' => '',
		'SEO_keywords' => ''
	);
	
	public $_smarty = null;
	
	public function __construct(){
		parent::__construct();
		
		$this->_reqtime = $this->input->server('REQUEST_TIME');
		
		$this->_initLibrary();
		$this->_initApp();
		
		
		$this->_initSmarty();
		$this->_security();
		
		$this->smartyConfig();
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
            //$this->_smarty->compile_check = false;
            $this->_smarty->compile_check = true;
        }else{
            $this->_smarty->compile_check = true;
        }
        
    	//$this->smartyConfig();
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
		$this->load->model('Member_Model');
		$this->load->library(array('user_agent','form_validation','encrypt','PHPTree','Base_Service'));
		
		$this->base_service->initStaticVars();
		
    }
    
    
    private function _security(){
    	
    	$this->assign('formhash',$this->security->get_csrf_hash());
    	
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
		
		/*
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
    		foreach($this->_navigation as $key => $item){
    			$temp[] = "<a href=\"{$item}\">{$key}</a>";
    		}
    		
    		return implode('&gt;',$temp);
    	}else{
    		return '';
    	}
    }
    
    
    public function display($viewname = ''){
    	if($viewname == ''){
    		$paramAr = $this->uri->segment_array();
    		
    		//print_r($paramAr);
    		if($paramAr[1] == 'admin'){
    			$viewname = empty($paramAr[2]) ? 'index' :  $paramAr[2];
    			$viewname .=  '/'. (empty($paramAr[3]) ? 'index' :  $paramAr[3]);
    		}else{
    			$viewname = empty($paramAr[1]) ? 'index' :  $paramAr[1];
    			$viewname .=  '/'. (empty($paramAr[2]) ? 'index' :  $paramAr[2]);
    		}
    		
    	}
    	
    	if($this->input->is_ajax_request()){
    		$viewname = $viewname.'_ajax';
    	}
    	
    	$this->_smarty->assign($this->_seo);
    	
    	
    	$this->output->set_output($this->_smarty->fetch($this->_smarty->getTemplateDir(0).$viewname.'.tpl'));
    }
    
    public function seoTitle($title){
    	$this->_seo['SEO_title'] = $title;
    }
    
    public function seo($title = '',$keyword = '', $desc = ''){
    	
    	if($title){
    		$this->_seo['SEO_title'] = $title;
    	}
    	
    	if($keyword){
    		$this->_seo['SEO_keywords'] = $keyword;
    	}
    	
    	if($desc){
    		$this->_seo['SEO_description'] = $desc;
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
