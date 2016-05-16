<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	
	private $_regLimit = 10;
	
	private $_siteList = array();
	
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Register_Service','Verify_Service'));
		$this->load->model('Captcha_Model');
		
		$this->_regLimit = config_item('maxRegisterIpLimit');
		
		
		$this->_getSiteConfig();
	}
	
	
	
	private function _getSiteConfig(){
		$this->_siteList = array(
			'www.txcf188.com' => array(
					'registeOkText' => 'registerOk_text1',
					'jumUrlType' => 'website',
					'rules' => array('username','mobile','mobile_auth_code'),
				),
			
			's1.txcf188.com' => array(
					'registeOkText' => 'registerOk_text2',
					'jumUrlType' => 'qqchat',
					'rules' => array('mobile'),
				),
			
			's2.txcf188.com' => array(
					'registeOkText' => 'registerOk_text3',
					'jumUrlType' => 'qqgroup',
					'rules' => array('mobile','auth_code'),
				),
			
			's3.txcf188.com' => array(
					'registeOkText' => 'registerOk_text2',
					'jumUrlType' => 'qqchat',
					'rules' => array('username', 'mobile','auth_code'),
				),
				
			's4.txcf188.com' => array(
					'registeOkText' => 'registerOk_text2',
					'jumUrlType' => 'qqchat',
					'rules' => array( 'mobile','auth_code'),
				),
			's5.txcf188.com' => array(
					'registeOkText' => 'registerOk_text2',
					'jumUrlType' => 'qqchat',
					'rules' => array( 'mobile','auth_code'),
				)
		);
		
	}
	
	
	//渠道
	private function _getCurrentServerName(){
		$currentHost = $this->input->server('HTTP_HOST');
		//$currentHost = 'www.txcf188.com';
		//$currentHost = 's1.txcf188.com';
		//$currentHost = 's2.txcf188.com';
		//$currentHost = 's3.txcf188.com';
		//$currentHost = 's4.txcf188.com';
		//$currentHost = 's5.txcf188.com';
		
		$this->assign('currentHost',$currentHost);
		
		return $currentHost;
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
		
		
		return array(
			'channel' => 1,//手机网页版
			'inviter' => $inviter ? intval($inviter) : 0,
			'reg_orig' => empty($inviteFrom) != true ? $inviteFrom : '',
			'channel_name' => $this->input->server('HTTP_HOST'),
			'channel_code' => empty($channel_code) != true ? $channel_code : ''
		);
	}
	
	
	private function _setValidationRules($site){
		$currentConfig = $this->_siteList[$site];
		
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
				
				
				/*
				if(in_array('auth_code',$this->_siteList[$currentHost]['rules'])){
					$this->load->model('Captcha_Model');
					$captcha = $this->Captcha_Model->getList(array(
						'where' => array(
							'ip_address' => $this->input->ip_address(),
							'captcha_time >' => $this->input->server('REQUEST_TIME') - 7200
						),
						'limit' => 1,
						'order' => 'captcha_id DESC'
					));
					
					//print_r($captcha);
					if(strtolower($captcha[0]['word']) != strtolower($this->input->post('auth_code')) ){
						$this->assign('feedback','验证码错误');
	 					break;
					}
					
				}
				*/
				
				
				$addParam = array(
					'mobile' => $this->input->post('mobile'),
					'username' => $this->input->post('username') ? $this->input->post('username') : '' , 
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
					$this->assign('feedback',config_item($this->_siteList[$currentHost]['registeOkText']));
					$this->assign('jumUrlType',$this->_siteList[$currentHost]['jumUrlType']);
					
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