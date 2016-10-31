<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Admin_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']);
	}
	
	
	private function _clearCache(){
		$this->getCacheObject()->delete(CACHE_KEY_SiteSetting);
	}
	
	public function base(){
		
		$feedback = '';
		$settingKey = array(
			'site_name',
			'site_logo',
			'icp_number',
			'site_phone',//客服电话
			'site_email',
			'statistics_code',
			'time_zone',
			'site_status',
			'closed_reason',
			'company_address',
			'site_mobile',
			'site_tel', //固定电话
			'site_faxno',
			'site_qq',
			'site_weixin',
		);
		
		$currentSetting = $this->admin_service->getSettingList(array(
			'where_in' => array(
				array('key' => 'name' , 'value' => $settingKey)
			)
		));
		
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('site_name','网站名称','required');
			$this->form_validation->set_rules('time_zone','默认时区','required');
			$this->form_validation->set_rules('site_status','站点状态','required|in_list[0,1]');
			
			$fileData = $this->attachment_service->addImageAttachment('site_logo',array(),FROM_BACKGROUND);
			
			//print_r($fileData);
			//print_r($_POST);
			
			if($this->form_validation->run()){
				$data = array();
				foreach($settingKey as $oneKey){
					$temp = array();
					$temp['name'] = $oneKey;
					
					if('site_logo' == $oneKey){
						
						if($fileData){
							$temp['value'] = $fileData['file_url'];
						}else{
							continue;
						}
						
						
					}else{
						$temp['value'] = $this->input->post($oneKey);
					}
					
					$data[] = $temp;
				}
				
				//print_r($data);
				
				if($this->admin_service->updateSetting($data) >= 0){
					$feedback = getSuccessTip('保存成功');
					
					$this->_clearCache();
					if($fileData){
						//更新成功了，则删除原先的图片
						$this->attachment_service->deleteByFileUrl($currentSetting['site_logo']);
					}
					
					$currentSetting = $this->admin_service->getSettingList(array(
						'where_in' => array(
							array('key' => 'name' , 'value' => $settingKey)
						)
					));
					
				}else{
					$feedback = getErrorTip('保存失败');
				}
			}else{
				$feedback = getErrorTip($this->form_validation->error_string());
			}
		}
		
		$this->assign('currentSetting',$currentSetting);
		$this->assign('feedback',$feedback);
		
		$timezone = $this->_getTimeZone();
		$showZone = array();
		foreach($timezone as $key => $value){
			
			$hour = floor($key);
			$minites = ($key - $hour)*60;
			
			if($hour > 0) {
				$hour = "+{$hour}";
			}
			
			if($minites == 0){
				$minites = '00';
			}
			
			$showZone[$value] = "GMT {$hour}:{$minites} {$value}";
		}
		
		
		$this->assign('timezone',$showZone);
		$this->display();
	}
	
	
	public function dump(){
		
		$feedback = '';
		
		$settingKey = array(
			'guest_comment',
			'captcha_status_login',
			'captcha_status_register',
			'captcha_status_goodsqa',
		);
		
		$currentSetting = $this->admin_service->getSettingList(array(
			'where_in' => array(
				array('key' => 'name' , 'value' => $settingKey)
			)
		));
		
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('guest_comment','允许游客咨询', 'required|in_list[0,1]');
			
			
			if($this->form_validation->run()){
				$data = array();
				foreach($settingKey as $oneKey){
					$temp = $this->input->post($oneKey);
					
					if(empty($temp)){
						$temp = 0;
					}
					
					$data[] = array(
						'name' => $oneKey,
						'value' => $temp
					);
				}
				
				//print_r($data);
				
				if($this->admin_service->updateSetting($data) >= 0){
					
					$this->_clearCache();
					
					$feedback = getSuccessTip('保存成功');
					
					$currentSetting = $this->admin_service->getSettingList(array(
						'where_in' => array(
							array('key' => 'name' , 'value' => $settingKey)
						)
					));
					
				}else{
					$feedback = getErrorTip('保存失败');
				}
			}else{
				
				$feedback = getErrorTip($this->form_validation->error_string());
				
			}
		}
		
		//print_r($currentSetting);
		$this->assign('currentSetting',$currentSetting);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	public function seoset(){
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
	
	
	
	
	
	private function _getTimeZone(){
		return array(
		'-12' => 'Pacific/Kwajalein',
		'-11' => 'Pacific/Samoa',
		'-10' => 'US/Hawaii',
		'-9' => 'US/Alaska',
		'-8' => 'America/Tijuana',
		'-7' => 'US/Arizona',
		'-6' => 'America/Mexico_City',
		'-5' => 'America/Bogota',
		'-4' => 'America/Caracas',
		'-3.5' => 'Canada/Newfoundland',
		'-3' => 'America/Buenos_Aires',
		'-2' => 'Atlantic/St_Helena',
		'-1' => 'Atlantic/Azores',
		'0' => 'Europe/Dublin',
		'1' => 'Europe/Amsterdam',
		'2' => 'Africa/Cairo',
		'3' => 'Asia/Baghdad',
		'3.5' => 'Asia/Tehran',
		'4' => 'Asia/Baku',
		'4.5' => 'Asia/Kabul',
		'5' => 'Asia/Karachi',
		'5.5' => 'Asia/Calcutta',
		'5.75' => 'Asia/Katmandu',
		'6' => 'Asia/Almaty',
		'6.5' => 'Asia/Rangoon',
		'7' => 'Asia/Bangkok',
		'8' => 'Asia/Shanghai',
		'9' => 'Asia/Tokyo',
		'9.5' => 'Australia/Adelaide',
		'10' => 'Australia/Canberra',
		'11' => 'Asia/Magadan',
		'12' => 'Pacific/Auckland'
		);		
	}
	
}
