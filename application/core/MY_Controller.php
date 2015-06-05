<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $_uriParam ;
	public $_inApp = false;
	public $_verifyName = 'verify';
	public $_lastVisit = 'lastvisit';	
	
	public function __construct(){
		parent::__construct();
		
		if($this->input->server('HTTP_APP_SP') == 'iOS'){
			$this->_inApp = true;
		}
		
		$this->load->model('Member_Model');
		$this->load->helper(array('form', 'url','string'));
		$this->load->library(array('form_validation','encrypt','Base_Service'));
		
		$this->_uriParam = $this->uri->uri_to_assoc();
		
		if($this->input->cookie($this->_lastVisit) != ''){
			$elapsed_time = number_format(microtime(TRUE) -  $this->input->cookie($this->_lastVisit), 2);
			if($elapsed_time < 0.3){
				if($this->input->is_ajax_request() || $this->_inApp == true){
					$this->responseJOSN('请求过于频繁',array());
				}else{
					show_error('请求过于频繁',200);
				}
			}
		}
		
		$this->input->set_cookie($this->_lastVisit,microtime(TRUE),time() + 86400);
		
		if($this->isPostRequest() && !$this->_checkVerify()){
			if($this->input->is_ajax_request() || $this->_inApp == true){
				$this->responseJOSN('请求失效',array());
			}else{
				show_error('请求失效',500);
			}
		}
		
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
    
    /**
     * 
     */
    public function responseJOSN($message = '' , $data = array()){
    	$this->output
		    	->set_status_header(200)
		    	->set_content_type('application/json')
		    	->set_output(json_encode($data));
		    	
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
