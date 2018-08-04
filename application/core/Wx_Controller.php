<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 微信api 业务逻辑 基础 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Wx_Controller extends MY_Controller {
	
	
	public $postJson = array();
	
	public $sessionInfo = array();
	
	public $memberInfo = array();
	
	public $yezhuInfo = array();
	
	public $yezhuHouseList = array();
	
	
	public $unBind = array('isBind' => false);
	
	//版本
	public $version = '';
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Seo_service','Weixin_service','Wuye_service'));
		$this->form_validation->set_error_delimiters('<label class="form_error">','</label>');
		
		
		$this->version = $this->input->get_post('version');
		
		
		if($this->isPostRequest()){
			$this->postJson = json_decode($GLOBALS["HTTP_RAW_POST_DATA"],true);
		}
		
		if(empty($this->postJson)){
			$this->postJson = array();
		}
		
		$this->sessionInfo = $this->session->all_userdata();
		
		//微信用户
		$weixinUser = $this->sessionInfo['weixin_user'];
		
		//file_put_contents('session.txt',$this->session->session_id."\n");
		//file_put_contents('session.txt',print_r($this->session->all_userdata(),true),FILE_APPEND);
		
		if($weixinUser){
			
			/*
			if($weixinUser['unionid']){
				//开放平台 ，统一用户
				$this->memberInfo = $this->wuye_service->initUserInfoBySession($weixinUser,'unionid');
			}else{
				$this->memberInfo = $this->wuye_service->initUserInfoBySession($weixinUser,'openid');
			}
			*/
			
			$this->memberInfo = $this->wuye_service->initUserInfoBySession($weixinUser,'openid');
			
			if($this->memberInfo){
				$this->yezhuInfo = $this->wuye_service->getYezhuInfoById($this->memberInfo['mobile'],'mobile');
			}
			
			$this->postJson = array_merge($this->postJson,$weixinUser);
			
			//file_put_contents('session.txt',print_r($this->memberInfo,true),FILE_APPEND);
			//file_put_contents('session.txt',print_r($this->yezhuInfo,true),FILE_APPEND);
		}
		
	}
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
	}
	
	
	
	public function getAppTemplateDir(){
		return 'ydzj';
	}
	
}