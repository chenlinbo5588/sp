<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	
	private $_regLimit = 10;
	private $_currentHost = '';
	
	// 用于前台验证,手动配置
	private $_siteRulesList = array();
	
	// 用于获得渠道代码对应的名称
	private $_wordList = array();
	
	// 用户记录 site id
	private $_websiteList = array();
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Register_Service','Verify_Service'));
		$this->load->model(array('Market_Words_Model','Website_Model'));
		$this->_regLimit = config_item('maxRegisterIpLimit');
		
		$this->_getSiteRulesConfig();
		$this->_getWebSiteList();
		$this->_getWordsConfig();
	}
	
	
	/**
	 * 获得后台配置的网站列表
	 */
	private function _getWebSiteList(){
		$siteList = $this->cache->get(Cache_Key_SiteList);
		
		if(empty($siteList)){
			$tempList = $this->Website_Model->getList(array(
				'select' => 'site_id,site_name,site_domain'
			));
			
			foreach($tempList as $key => $item){
				$siteList[$item['site_domain']] = $item;
			}
			
			$this->cache->file->save(Cache_Key_SiteList,$siteList, 86400);
		}
		
		$this->_websiteList = $siteList;
		
	}
	
	/**
	 * 获得关键词列表
	 */
	private function _getWordsConfig(){
		
		
		$wordsList = array();
		$wordsList = $this->cache->get(Cache_Key_WordList);
		
		if(empty($wordsList)){
			$tempList = $this->Market_Words_Model->getList(array(
				'select' => 'word_name,word_code'
			));
			
			foreach($tempList as $key => $item){
				$wordsList[$item['word_code']] = $item['word_name'];
			}
			
			$this->cache->file->save(Cache_Key_WordList,$wordsList, 86400);
		}
		
		$this->_wordList = $wordsList;
	}
	
	
	private function _getSiteRulesConfig(){
		$this->_siteRulesList = config_item('siteRules');
	}
	
	
	//渠道
	private function _getCurrentServerName(){
		$this->_currentHost = $this->input->server('HTTP_HOST');
		
		//$this->_currentHost = 'www.txcf188.com';
		//$this->_currentHost = 's1.txcf188.com';
		//$this->_currentHost = 's2.txcf188.com';
		//$this->_currentHost = 's3.txcf188.com';
		//$this->_currentHost = 's4.txcf188.com';
		//$this->_currentHost = 's5.txcf188.com';
		//$this->_currentHost = 's6.txcf188.com';
		//$this->_currentHost = 's7.txcf188.com';
		//$this->_currentHost = 's8.txcf188.com';
		//$this->_currentHost = 's9.txcf188.com';
		//$this->_currentHost = 's10.txcf188.com';
		//$this->_currentHost = 's11.txcf188.com';
		//$this->_currentHost = 's12.txcf188.com';
		//$this->_currentHost = 's13.txcf188.com';
		//$this->_currentHost = 's14.txcf188.com';
		
		$this->assign('currentHost',$this->_currentHost);
		
		return $this->_currentHost;
	}
	
	
	

	
	/**
	 * 自动获取渠道数据
	 */
	private function _prepageChannelData(){
		
		$inviter = $this->input->get_post('inviter');
		
		if($this->isGetRequest()){
			$inviteFrom = $this->input->server('HTTP_REFERER');
		}else if($this->isPostRequest()){
			$inviteFrom = $this->input->post('refer');
			
		}
		
		
		$channel_code = $this->input->get_post('channel_code');
		$this->assign('channel_code', $channel_code);
		$this->assign('inviter', $inviter);
		$this->assign('refer',$inviteFrom);
		
		
		$d = array(
			'channel' => 0,
			'inviter' => $inviter ? intval($inviter) : 0,
			'reg_orig' => empty($inviteFrom) != true ? $inviteFrom : '',
			'reg_origname' => '',
			'reg_domain' => $this->_currentHost,
			'channel_code' => empty($channel_code) != true ? $channel_code : '',
			'channel_word' => '',
			'channel_name' => ''
		);
		
		if($this->_wordList[$d['channel_code']]){
			$d['channel_word'] = $this->_wordList[$d['channel_code']];
		}
		
		if($this->_websiteList[$d['reg_domain']]){
			$d['channel'] = $this->_websiteList[$d['reg_domain']]['site_id'];
			$d['channel_name'] = $this->_websiteList[$d['reg_domain']]['site_name'];
		}
		
		$referUrl = parse_url($inviteFrom);
		if($referUrl['host']){
			$d['reg_origname'] = $referUrl['host'];
			
			//如果配置网站，则存储成中文
			if($this->_websiteList[$referUrl['host']]){
				$d['reg_origname'] = $this->_websiteList[$referUrl['host']]['site_name'];
			}
		}
		
		
		return $d;
	}
	
	
	private function _setValidationRules($site){
		$currentConfig = $this->_siteRulesList[$site];
		
		foreach($currentConfig['rules'] as $ruleName){
			switch($ruleName){
				case 'username':
					$this->form_validation->set_rules('username','用户名称', 'required|min_length[1]|max_length[20]');
					break;
				case 'mobile':
				
					$this->form_validation->set_rules('mobile','手机号','required|valid_mobile');
					/*
					$this->form_validation->set_rules('mobile','手机号',array(
								'required',
								'valid_mobile',
								array(
									'loginname_callable[mobile]',
									array(
										$this->Member_Model,'isUnqiueByKey'
									)
								)
							),
							array(
								'loginname_callable' => '%s已经被注册'
							)
						);
					*/
					break;
				case 'mobile_auth_code':
					$this->form_validation->set_rules('auth_code','验证码', array(
							'required',
							array(
								'authcode_callable['.$this->input->post('mobile').']',
								array(
									$this->verify_service,'validateAuthCode'
								)
							)
						),
						array(
							'authcode_callable' => '验证码不正确'
						)
					);
					break;
				case 'auth_code':
					$this->form_validation->set_rules('auth_code','验证码', 'required|callback_validateCaptcha');
					break;
				case 'stock':
					$this->form_validation->set_rules('stock','股票名称或代码', 'required|min_length[1]|max_length[50]');
					break;
				default:
					break;
				
			}
		}
		
	}
	
	public function validateCaptcha($code){
		if(strtolower($code) != strtolower($this->session->userdata('captcha')) ){
			$this->form_validation->set_message('validateCaptcha', '验证码错误');
			return false;
		}else{
			return true;
		}
	}
	
	
	/**
	 *
	 */
	public function index()
	{
		$registerOk = false;
		$currentHost = $this->_getCurrentServerName();
		
		$channelData = $this->_prepageChannelData();
		
		if($this->isPostRequest()){
			$this->form_validation->reset_validation();
			$this->form_validation->set_error_delimiters('<label class="error">','</label>');
			
			$this->_setValidationRules($currentHost);
			
			for($i = 0; $i < 1; $i++){
				
				$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
				if($todayRegistered > $this->_regLimit){
					$this->assign('feedback','很抱歉，您今日注册数量已经用完');
					break;
				}
				
				
				if(!$this->form_validation->run()){
					//$feedback = $this->form_validation->error_string();
					break;
				}
				
				
				$addParam = array(
					'mobile' => trim($this->input->post('mobile')),
					'username' => trim($this->input->post('username')) ? trim($this->input->post('username')) : '未提供' , 
					//正常提交
					'status' => 0,
				);
				
				$addParam = array_merge($addParam,$channelData);
				// check
				$result = $this->register_service->createMember($addParam,true);
				
				if($result['code'] == 'success'){
					/*
					$userInfo = $this->member_service->getUserInfoByMobile($this->input->post('mobile'));
					//$this->_rememberLoginName($this->input->post('mobile'));
					
					$this->session->set_userdata(array(
						'profile' => $userInfo
					));
					*/
					//redirect(config_item('dest_website'));
					$registerOk = true;
					$this->assign('feedback',config_item($this->_siteRulesList[$currentHost]['registeOkText']));
					$this->assign('jumUrlType',$this->_siteRulesList[$currentHost]['jumUrlType']);
					
				}else{
					$this->assign('feedback',$result['message']);
				}
			}
			
		}
		//$registerOk = true;
		
		$this->assign('jumUrl',config_item('jumUrl'));
		$this->assign('registerOk',$registerOk);
		$this->display('index/'.$currentHost);
	}
}