<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/*
	public function map(){
		$key = '在线地图';
		
		$this->_navigation[$key] = base_url('service/map.html');
		$this->assign('breadcrumb',$this->breadcrumb());
		
		$this->seo($key);
		$this->assign('site_name',$this->_siteSetting['site_name']);
		$this->assign('company_address',$this->_siteSetting['company_address']);
		$this->display();
	}
	*/
	
	private function _getRules(){
		
		$this->form_validation->set_rules('username','姓名','required|min_length[1]|max_length[30]');
		$this->form_validation->set_rules('company_name','公司名称','required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		
		$this->form_validation->set_rules('city','城市','required');
		
		
		if($this->input->post('tel')){
			$this->form_validation->set_rules('tel','固定电话','required|max_length[20]');
		}
		
		if($this->input->post('email')){
			$this->form_validation->set_rules('email','联系邮箱','required|valid_email');
		}
		
		if($this->input->post('weixin')){
			$this->form_validation->set_rules('weixin','微信号','required');
		}
		
		$this->form_validation->set_rules('doc_no','合同号','required');
		$this->form_validation->set_rules('remark','备注','required|max_length[200]');
		$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
		
		$info = array(
			'username' => $this->input->post('username'),
			'company_name' => $this->input->post('company_name'),
			'mobile' => $this->input->post('mobile'),
			'city' => $this->input->post('city'),
			'tel' => $this->input->post('tel') ? $this->input->post('tel') : '',
			'email' => $this->input->post('email') ? $this->input->post('email') : '',
			'weixin' => $this->input->post('weixin') ? $this->input->post('weixin') : '',
			'doc_no' => $this->input->post('doc_no'),
			'remark' => strip_tags($this->input->post('remark')),
			'status' => '未处理',
			'ip' => $this->input->ip_address()
		);
		
		return $info;
	}
	
	
	public function suggestion(){
		$feedback = '';
		$itemSide = $this->navigation_service->getInfoByUrl(base_url('service/suggestion.html'));
		
		$message = '您的投诉建议意见已完成提交,我们的工作人员交尽快处理。';
		$homeKey = '首页';
		$urlKey = 'url_cn';
		$nameKey = 'name_cn';
		
		if($this->_currentLang == 'english'){
			$homeKey = 'Home';
			$urlKey = 'url_en';
			$nameKey = 'name_en';
		}
		
		
		if($this->_currentLang == 'english'){
			
			$message = 'We have recevied your suggest, we will keep in touch with you as soon as possible';
		}
		
		if($this->isPostRequest()){
			
			$this->load->model('Suggestion_Model');
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_getRules();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string('','');
					$feedback = getErrorTip($feedback);
					break;
				}
				
				$newid = $this->Suggestion_Model->_add($info);
				unset($info);
				
				
				$this->assign('alertMsg', $message);
			}
		}
		
		list($currentSideNav,$navigationInfo) = $this->prepareSideNav($itemSide['id']);
		$moduleUrl = str_replace('{ID}',$currentSideNav['id'],$currentSideNav[$urlKey]);
		
		
		$this->_navigation = array(
			$homeKey => base_url('/'),
			$currentSideNav[$nameKey] => $moduleUrl,
		);
		$this->_navigation[$itemSide[$nameKey]] = base_url('service/suggestion.html');
		
		$this->assign(
			array(
			    'info' => $info,
			    'feedback'=>$feedback,
				'currentModule' => $this->uri->rsegment(1),
				'currentSideUrl' => base_url($this->uri->uri_string()),
				'sideTitleUrl' => $moduleUrl,
				'sideNavs' => $currentSideNav['children'],
				'sideTitle' => $currentSideNav[$nameKey],
				'nameKey' => $nameKey,
				'urlKey' => $urlKey,
				'breadcrumb'=>$this->breadcrumb()
			)
		);
		
		
		$this->seo($itemSide[$nameKey].' '.$currentSideNav[$nameKey]);
		$this->display();
	}
}
