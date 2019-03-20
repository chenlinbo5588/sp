<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends Wx_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	
	/**
	 * 请求手机验证码
	 */
	public function getAuthcode(){
		
		if($this->isPostRequest()){
			//sleep(2);
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->reset_validation();
				
				$this->form_validation->set_data(array(
					'phoneNo' => $this->postJson['phoneNo']
				));
				
				$this->form_validation->set_rules('phoneNo','手机号码',array(
						'required',
						'valid_mobile'
					),
					array(
						'required' => '请输入手机号码',
						'valid_mobile' => '手机号码无效'
					)
				);
				
				
				
				if(!$this->form_validation->run()){
					$rt['message'] = $this->form_validation->error_first_html();
					break;
				}
				
				$this->load->library('Verify_service');
				$rt = array(
					'code' => 'failed',
					'message' => '请求失败'
				);
				
				
				/**
				 * 记录该 ip,同一个IP 60秒内请求手机验证码不能大于3次
				 */
				$count = $this->verify_service->getIpCount($this->input->ip_address());
				if($count > 3){
					$rt['message'] = "疑似攻击行为，请求失败";
					break;
				}
				
				
				$codeInfo = $this->verify_service->getPhoneVerifyCodeBeforeExpire($this->postJson['phoneNo']);
				if(empty($codeInfo)){
					$codeInfo = $this->verify_service->createVerifyCode(array(
						'phone' => $this->postJson['phoneNo']
					));
				}else{
					/**
					 * 再检查 在验证码有效期内，同一个Ip ，同一个号码只能, 不能超过3次
					 */
					if($codeInfo['send_normal'] >= 3){
						$rt['message'] = "疑似攻击行为，请求失败";
						break;
					}
				}
				
				$rt['code'] = 'failed';
				
				
				
			    $this->load->file(LIB_PATH.'Sms_api.php');
			    
				$smsConfig = config_item('aliyun_SMS');
				
				try {
					$sendResult = Sms_api::sendSms(array(
						'phoneNo' => $this->postJson['phoneNo'],
						'templateVar' => array(
							'code' => $codeInfo['code']
						),
						'templateCode' => 'SMS_136055238',
						'signName' => $smsConfig['signName']
					));
					
				}catch(Exception $e){
					$rt['message'] = $e->getMessage();
					break;
				}
				
				
				if('OK' == $sendResult->Code){
					$rt['code'] = 'success';
					
					$this->verify_service->updateSendFlag(array(
			    		array('key' => 'send_normal' , 'value' => 'send_normal + 1')
			    	),array('id' => $codeInfo['id']));
				}else{
					$this->verify_service->updateSendFlag(array(
			    		array('key' => 'send_fail' , 'value' => 'send_fail + 1')
			    	),array('id' => $codeInfo['id']));
				}
			}
			
			
			if($rt['code'] == 'success'){
				$this->jsonOutput2(RESP_SUCCESS);
			}else{
				$this->jsonOutput2($rt['message']);
			}
		}else{
			$this->jsonOutput2(RESP_ERROR);
		}
		
		
	}
	
	
	/**
	 * 绑定
	 */
	public function doRegister()
	{
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				$this->form_validation->reset_validation();
				
				$this->postJson['openid'] = $this->sessionInfo['weixin_user']['openid'];
				
				$this->form_validation->set_data($this->postJson);
				
				//校验重复性, 不允许 多个号码  与一个微信号码 关联
				//$this->form_validation->set_rules('openid','微信用户号','required|is_unique['.$this->Member_Model->getTableRealName().'.openid]');
				
				$this->form_validation->set_rules('phoneNo','手机号码',array(
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
						'loginname_callable' => '%s已经被绑定'
					)
				);
				
				$rt = array(
					'code' => 'failed',
					'message' => '请求失败'
				);
				
				if(!$this->form_validation->run()){
					$rt['message'] = $this->form_validation->error_first_html();
					break;
				}
				
				
				//$this->register_service->getIpLimit($this->input->ip_address());
				
				$uid = $this->weixin_service->bindMobile($this->postJson,$this->sessionInfo['weixin_user']);
				
				if(!$uid){
					$rt['message'] = '绑定失败';
					break;
				}
				
				$rt['code'] = 'success';
			}
			
			if($rt['code'] == 'success'){
				$this->jsonOutput2(RESP_SUCCESS);
			}else{
				$this->jsonOutput2($rt['message']);
			}
			
		}else{
			$this->jsonOutput2('非法请求');
		}
	}
	
	
	/**
	 * 
	 */
	public function checkAuthcode(){
		if($this->isPostRequest()){
			
			$this->load->library(array('Verify_service'));
			
			if($this->verify_service->isAuthCodeValidate($this->postJson['phoneNo'],$this->postJson['code'])){
				$this->jsonOutput2(RESP_SUCCESS);
			}else{
				//$this->jsonOutput2(RESP_SUCCESS);
				$this->jsonOutput2('验证码错误');
			}
		}else{
			$this->jsonOutput2(RESP_ERROR);
		}
	}
}
