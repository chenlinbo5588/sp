<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	
	private $_regLimit = 10;
	
	//页面
	private $_currentPage = '';
	
	//页面模板
	// http://s1.txcf188.com/s1.html  模板名称为 s1.txcf188.com
	// http://s1.txcf188.com/s2.html  模板名称为 s2.txcf188.com
	// http://s1.txcf188.com/s3.html  模板名称为 s3.txcf188.com
	// 依次类推
	private $_tplName = '';
	
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
				'select' => 'site_id,site_name,site_url,site_domain'
			));
			
			foreach($tempList as $key => $item){
				$siteList[$item['site_url']] = $item;
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
				'select' => 'word_name,word_url'
			));
			
			foreach($tempList as $key => $item){
				$wordsList[$item['word_url']] = $item['word_name'];
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
		$reSegment = $this->uri->rsegment_array();
		if(empty($reSegment[3])){
			$reSegment[3] = "s1";
		}
		
		$this->_currentPage = $reSegment[3];
		$domain = explode('.',$this->input->server('HTTP_HOST'));
		$domain[0] = $this->_currentPage;
		
		$this->_tplName = implode('.',$domain);
		
		$this->assign('siteIndex',$this->_currentPage);
		
		return $this->_tplName;
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
		
		$subPage = $this->input->get_post('subpage');
		if(empty($subPage)){
			$subPage = $this->input->server('REQUEST_URI');
		}
		
		$channel_code = $this->input->get_post('channel_code');
		
		$this->assign('subpage',$subPage);
		$this->assign('channel_code', $channel_code);
		$this->assign('inviter', $inviter);
		$this->assign('refer',$inviteFrom);
		
		
		$d = array(
			'channel' => 0,
			'inviter' => $inviter ? intval($inviter) : 0,
			'reg_orig' => empty($inviteFrom) != true ? $inviteFrom : '',
			'reg_origname' => '',
			'reg_domain' => base_url(),
			'channel_code' => empty($channel_code) != true ? $channel_code : '',
			'channel_word' => '',
			'channel_name' => ''
		);
		
		$subPageInfo = parse_url(base_url($subPage));
		$subPageUrl = $subPageInfo['scheme'].'://'.$subPageInfo['host'].$subPageInfo['path'];
		$d['page_url'] = $subPageUrl;
		
		
		//找到推广链接 后台配置的对应关键字
		if($this->_websiteList[$d['page_url']]){
			$d['page_name'] = $this->_websiteList[$d['page_url']]['site_name'];
		}
		
		//找到推广链接 后台配置的对应关键字
		if($this->_wordList[base_url($subPage).$d['channel_code']]){
			$d['channel_word'] = $this->_wordList[base_url($subPage).$d['channel_code']];
		}
		
		//找到当前站点 在后台配置的站点名称
		if($this->_websiteList[$d['reg_domain']]){
			$d['channel'] = $this->_websiteList[$d['reg_domain']]['site_id'];
			$d['channel_name'] = $this->_websiteList[$d['reg_domain']]['site_name'];
		}
		
		$referUrl = parse_url($inviteFrom);
		if($referUrl['host']){
			$d['reg_origname'] = $referUrl['scheme'].'://'.$referUrl['host'] .'/';
			
			//如果配置网站，则存储成中文
			if($this->_websiteList[$d['reg_origname']]){
				$d['reg_origname'] = $this->_websiteList[$d['reg_origname']]['site_name'];
			}
		}
		
		return $d;
	}
	
	
	private function _setValidationRules($site,$isMutilRule = '',$ruleGroupName = ''){
		if($isMutilRule == 'yes'){
			$validateRules = $this->_siteRulesList[$site]['rules'][$ruleGroupName];
		}else{
			$validateRules = $this->_siteRulesList[$site]['rules'];
		}
		//print_r($validateRules);
		
		foreach($validateRules as $ruleName){
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
		$tplPageName = $this->_getCurrentServerName();
		$channelData = $this->_prepageChannelData();
		
		$mutiRule = $this->input->get_post('muti_rule') ? $this->input->get_post('muti_rule') : '';
		$ruleName = $this->input->get_post('rule') ? $this->input->get_post('rule') : '';
		
		if($this->isPostRequest()){
			$this->form_validation->reset_validation();
			$this->form_validation->set_error_delimiters('<label class="error">','</label>');
			
			$this->_setValidationRules($tplPageName,$mutiRule,$ruleName);
			
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
				$result = $this->register_service->createMember($addParam);
				
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
					
					if($mutiRule == 'yes'){
						$this->assign('feedback',config_item($this->_siteRulesList[$tplPageName]['registeOkText'][$ruleName]));
						$this->assign('jumUrlType',$this->_siteRulesList[$tplPageName]['jumUrlType'][$ruleName]);
					}else{
						$this->assign('feedback',config_item($this->_siteRulesList[$tplPageName]['registeOkText']));
						$this->assign('jumUrlType',$this->_siteRulesList[$tplPageName]['jumUrlType']);
					}
					
				}else{
					$this->assign('feedback',$result['message']);
				}
			}
			
		}
		//$registerOk = true;
		
		$this->assign('jumUrl',config_item('jumUrl'));
		$this->assign('registerOk',$registerOk);
		$this->display('index/'.$tplPageName);
	}
}