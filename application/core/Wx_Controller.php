<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 微信api 业务逻辑 基础 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Wx_Controller extends MY_Controller {
	
	public $postJson = array();
	
	public $bussId = '';
	
	public $sessionInfo = array();
	
	public $memberInfo = array();
	
	public $yezhuInfo = array();
	
	public $yezhuHouseList = array();
	
	
	public $unBind = array('isBind' => false);
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Seo_service','Weixin_service','Wuye_service'));
		$this->form_validation->set_error_delimiters('<label class="form_error">','</label>');
		
		$bussId = $this->input->get('sid');
		
		if($this->isPostRequest()){
			$this->postJson = json_decode($GLOBALS["HTTP_RAW_POST_DATA"],true);
		}
		
		if(empty($bussId)){
			if($this->postJson && $this->postJson['sid']){
				$bussId = $this->postJson['sid'];
			}
		}
		
		if(!empty($bussId)){
			
			$this->sessionInfo = $this->weixin_service->getWeixinUserInfoBySession($bussId);
			
			if('development' == ENVIRONMENT){
				$this->memberInfo = $this->wuye_service->initUserInfoBySession($this->sessionInfo,'mobile');
			}else{
				$this->memberInfo = $this->wuye_service->initUserInfoBySession($this->sessionInfo);
			}
			
			if($this->memberInfo){
				$this->yezhuInfo = $this->wuye_service->getYezhuInfoById($this->memberInfo['mobile'],'mobile');
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