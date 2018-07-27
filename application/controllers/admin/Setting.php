<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Attachment_service'));
		$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
		
		$this->_moduleTitle = '站点设置';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
		));
		
	}
	
	/**
	 * 
	 */
	private function _baseGroup(){
		$this->_subNavs = array(
			array('url' => $this->_className.'/base','title' => '基本设置'),
			array('url' => $this->_className.'/dump','title' => '防灌水设置'),
		);
	}
	
	
	private function _clearCache(){
		$this->getCacheObject()->delete(CACHE_KEY_SiteSetting);
		$this->getCacheObject()->delete(CACHE_KEY_SeoSetting);
	}
	
	/**
	 * 
	 */
	public function base(){
		
		$feedback = '';
		
		$this->_baseGroup();
		
		$settingKey = array(
			'site_name',
			'site_shortname',
			'site_name_en',
			'site_shorten',
			'site_logo',
			'icp_number',
			'site_tel', //客服电话
			'site_qq',
			'site_email',
			'statistics_code',
			'time_zone',
			'site_status',
			'closed_reason',
			'company_address',
			'site_mobile',
			'site_phone',//招商电话
			'site_faxno',
		);
		
		$currentSetting = $this->base_service->getSettingList(array(
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
				
				if($this->base_service->updateSetting($data) >= 0){
					$feedback = getSuccessTip('保存成功');
					
					$this->_clearCache();
					
					if($fileData){
						//更新成功了，则删除原先的图片
						$this->attachment_service->deleteByFileUrl($currentSetting['site_logo']);
					}
					
					$currentSetting = $this->base_service->getSettingList(array(
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
		
		$this->_baseGroup();
		
		$settingKey = array(
			'guest_comment',
			'captcha_status_login',
			'captcha_status_register',
			'captcha_status_goodsqa',
		);
		
		$currentSetting = $this->base_service->getSettingList(array(
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
				
				if($this->base_service->updateSetting($data) >= 0){
					
					$this->_clearCache();
					
					$feedback = getSuccessTip('保存成功');
					
					$currentSetting = $this->base_service->getSettingList(array(
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
		$this->load->library(array('Seo_service', 'Goods_service'));
		
		
		if($this->isPostRequest()){
			
			$rows = 0;
			
			if($_POST['SEO']){
				foreach($_POST['SEO'] as $key => $value){
					$selectedGroup = $key;
					break;
				}
			
				$rows = $this->seo_service->updateSeo($_POST['SEO']);
				
			}else{
				
				if($this->input->post('category') != '' && $this->input->post('form_name') == 'category'){
					$selectedGroup = $this->input->post('form_name');
					
					$rows = $this->goods_service->getGoodsClassModel()->updateGoodsClassSeoById($this->input->post('category'),array(
						'gc_title' => $this->input->post('cate_title'),
						'gc_keywords' => $this->input->post('cate_keywords'),
						'gc_description' => $this->input->post('cate_description'),
					));
				}
			}
			
			if($rows >= 0){
				$feedback = getSuccessTip('保存成功');
			}else{
				$feedback = getErrorTip('保存失败');
			}
			
			$this->_clearCache();
		}
		
		
		$currentSetting = $this->seo_service->getCurrentSeoSetting();
		$goodsClassHTML = $this->goods_service->getGoodsClassTreeHTML();
		
		//print_r($currentSetting);
		
		$this->assign('currentSetting',$currentSetting);
		$this->assign('goodsClassHTML',$goodsClassHTML);
		$this->assign('selectedGroup',$selectedGroup);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	public function ajax_category(){
		$this->load->library('Goods_service');
		$goodsClassInfo = $this->goods_service->getGoodsClassModel()->getGoodsClassById($this->input->get('id'));
		$this->jsonOutput('获取成功',$goodsClassInfo);
	}
	
	
	public function express(){
		$this->load->model('Express_Model');
		
		if($this->input->is_ajax_request() && $this->isPostRequest()){
			
			$this->form_validation->set_rules('fieldname','状态字段','required|in_list[isfreq,state]');
			$this->form_validation->set_rules('enabled','状态','required|in_list[0,1]');
			
			if($this->form_validation->run()){
				
				$upInfo[$this->input->post('fieldname')] = $this->input->post('enabled');
				
				$this->Express_Model->update($upInfo,array('id' => $this->input->post('id')));
				
				$this->jsonOutput('保存成功', $this->getFormHash());
				
			}else{
				$this->jsonOutput('保存失败 '.$this->form_validation->error_string(),$this->getFormHash());
			}
			
		}else{
			$currentPage = $this->input->get('page') ? $this->input->get('page') : 1;
			$currentLetter = $this->input->get('letter') ? $this->input->get('letter') : '';
			$letter = $this->input->get('letter') ? $this->input->get('letter') : '';
			
			$rangeAZ = range('A','Z');
			
			$where = array();
			if($currentLetter){
				$where['where']['letter'] = $currentLetter;
			}
			
			
			$condition = array(
				'order' => 'id DESC',
				'pager' => array(
					'page_size' => config_item('page_size'),
					'current_page' => $currentPage,
					'call_js' => 'search_page',
					'form_id' => '#formSearch'
				)
			);
			
			$condition = array_merge($condition,$where);
			
			
			$list = $this->Express_Model->getList($condition);
			
			$this->assign('charList',$rangeAZ);
			$this->assign('list',$list);
			$this->assign('page',$list['pager']);
			
			$this->assign('currentPage',$currentPage);
			$this->assign('letter',$letter);
			$this->display();
			
		}
		
	}
	
	public function search(){
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('hotwords','热门搜索中文关键词','required');
			$this->form_validation->set_rules('hotwords_en','热门搜索英文关键词','required');
			
			if($this->form_validation->run()){
				
				if($this->base_service->updateSetting(array(
					array('name' => 'hotwords','value' => $this->input->post('hotwords')),
					array('name' => 'hotwords_en','value' => $this->input->post('hotwords_en')),
				)) >= 0){
				
					$feedback = getSuccessTip('保存成功');
					$this->_clearCache();
							
				}else{
					$feedback = getErrorTip('保存失败');
				}
			}
		}
		
		$currentSetting = $this->base_service->getSettingList(array(
			'where_in' => array(
				array('key' => 'name','value' => array('hotwords','hotwords_en'))
			
			)
		));
		
			
		$this->assign('feedback',$feedback);
		$this->assign('currentSetting',$currentSetting);
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
