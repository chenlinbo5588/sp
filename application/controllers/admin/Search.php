<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Admin_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']);
	}
	
	
	private function _clearCache(){
		$this->getCacheObject()->delete(CACHE_KEY_SiteSetting);
	}
	
	
	
	public function hotword(){
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('hotwords','热门搜索关键词','required');
			
			if($this->form_validation->run()){
				
				if($this->Setting_Model->update(array('value' => $this->input->post('hotwords')),array('name' => 'hotwords')) >= 0){
					$feedback = getSuccessTip('保存成功');
					
					$this->_clearCache();
					
					$currentSetting = $this->admin_service->getSettingList(array(
						'where' => array('name' => 'hotwords' )
					));
							
				}else{
					$feedback = getErrorTip('保存失败');
				}
			}
		}else{
			$currentSetting = $this->admin_service->getSettingList(array(
				'where' => array('name' => 'hotwords' )
			));
		}
		$this->assign('feedback',$feedback);
		$this->assign('currentSetting',$currentSetting);
		$this->display();
		
	}
	
	
	
	
}
