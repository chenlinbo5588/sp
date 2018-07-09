<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 微信api 业务逻辑 基础 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Wx_Controller extends MY_Controller {
	
	public $postJson = array();
	public $sessionKey = '';
	
	public $sessionInfo = array();
	
	public $weixinInfo = array();
	
	public $memberInfo = array();
	
	public $yezhuInfo = array();
	
	public $yezhuHouseList = array();
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Seo_service','Weixin_service','Wuye_service'));
		$this->form_validation->set_error_delimiters('<label class="form_error">','</label>');
		
		$sessionKey = $this->input->get('session_key');
		
		if($this->isPostRequest()){
			$this->postJson = json_decode($GLOBALS["HTTP_RAW_POST_DATA"],true);
		}
		
		if(empty($sessionKey)){
			if($this->postJson && $this->postJson['session_key']){
				$sessionKey = $this->postJson['session_key'];
			}
		}
		
		if(!empty($sessionKey)){
			
			$this->sessionInfo = $this->weixin_service->getWeixinUserInfoBySession($sessionKey);
			
			if('development' == ENVIRONMENT){
				$this->memberInfo = $this->wuye_service->initUserInfoBySession($this->sessionInfo,'mobile');
				
			}else{
				$this->memberInfo = $this->wuye_service->initUserInfoBySession($this->sessionInfo);
			}
			
			if($this->memberInfo){
				$this->yezhuInfo = $this->wuye_service->getYezhuInfoByMobile($this->memberInfo['mobile']);	
			}
		}
		
	}
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
	}
	
	
	
	public function getAppTemplateDir(){
		return 'ydzj';
	}
	
}