<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $_uriParam ;
	public $_inApp;
	public $_verifyName = 'verify';
	
	
	public function __construct(){
		parent::__construct();
		
		if($this->input->server('HTTP_APP_SP') == 'iOS'){
			$this->_inApp = true;
		}
		
		$this->load->model('Member_Model');
		$this->load->helper(array('form', 'url','string'));
		$this->load->library(array('form_validation','encrypt','Base_Service'));
		
		$this->_uriParam = $this->uri->uri_to_assoc();
		
		if($this->isPostRequest() && !$this->_checkVerify()){
			$this->jsonOutput('请求失效',array());
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
	
	
	public function formhash(){
		$this->jsonOutput('',array('formhash' => $this->security->get_csrf_hash(), $this->_verifyName => $this->_verify()));
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
     * 设置
     */
    public function jsonOutput($message , $data , $cacheTime = 0 ){
    	if(!$this->output->_display_cache($this->config,$this->URI)){
			// 缓存文件不存在或者是已经失效，设置数据
			$this->output
		    	->set_status_header(200)
		    	->set_content_type('application/json')
		    	->set_output(json_encode(array('message' => $message,'data' => $data)));
		    	
		    if($cacheTime > 0){
		    	$this->output->cache($cacheTime);
		    }
		}
    }
}
