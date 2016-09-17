<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seo extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Admin_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']);
		
		$this->assign('moduleTitle','SEO设置');
		$this->_subNavs = array(
			array('url' => 'seo/index', 'title' => '首页'),
		);
	}
	
	private function _clearCache(){
		$this->getCacheObject()->delete(CACHE_KEY_SiteSetting);
	}
	
	
	
	public function index(){
		$feedback = '';
		
		$selectedGroup = 'index';
		$this->load->library(array('Seo_service'));
		
		
		if($this->isPostRequest()){
			$rows = 0;
			if($_POST['SEO']){
				foreach($_POST['SEO'] as $key => $value){
					$selectedGroup = $key;
					break;
				}
			
				$rows = $this->seo_service->updateSeo($_POST['SEO']);
				$this->getCacheObject()->delete(CACHE_KEY_SeoSetting);
			}
			
			if($rows >= 0){
				$feedback = getSuccessTip('保存成功');
			}else{
				$feedback = getErrorTip('保存失败');
			}
		}
		
		$currentSetting = $this->seo_service->getCurrentSeoSetting();
		
		//print_r($currentSetting);
		$this->assign('currentSetting',$currentSetting);
		$this->assign('selectedGroup',$selectedGroup);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
}
