<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Admin_service'));
	}
	
	
	private function _clearCache(){
		$this->cache->file->delete(CACHE_KEY_SiteSetting);
	}
	
	
	public function index(){
		$feedback = '';
		
		$settingKey = array(
			'cms_isuse',
			'cms_submit_verify_flag',
			'cms_comment_flag',
			'cms_seo_description',
			'cms_seo_keywords',
			'cms_seo_title'
		);
		
		$currentSetting = $this->admin_service->getSettingList(array(
			'where_in' => array(
				array('key' => 'name' , 'value' => $settingKey)
			)
		));
		
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('cms_isuse','CMS开关','required|in_list[0,1]');
			$this->form_validation->set_rules('cms_submit_verify_flag','投稿需要审核','required|in_list[0,1]');
			$this->form_validation->set_rules('cms_comment_flag','允许评论','required|in_list[0,1]');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				$data = array();
				foreach($settingKey as $oneKey){
					$temp = array();
					$temp['name'] = $oneKey;
					$temp['value'] = $this->input->post($oneKey);
					
					$data[] = $temp;
				}
				
				
				if($this->admin_service->updateSetting($data) >= 0){
					$feedback = getSuccessTip('保存成功');
					
					$this->_clearCache();
					
					$currentSetting = $this->admin_service->getSettingList(array(
						'where_in' => array(
							array('key' => 'name' , 'value' => $settingKey)
						)
					));
					
				}else{
					$feedback = getErrorTip('保存失败');
				}
			}
		}
		
		$this->assign('currentSetting',$currentSetting);
		$this->assign('feedback',$feedback);
		
		$this->display();
	}
}
