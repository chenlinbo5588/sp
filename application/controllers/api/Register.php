<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 请求手机验证码
	 */
	public function authcode(){
		
		if($this->isPostRequest()){
			//sleep(2);
			for($i = 0; $i < 1; $i++){
				$this->form_validation->set_error_delimiters('','');
				$this->form_validation->reset_validation();
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
					$rt['message'] = $this->form_validation->error('phoneNo');
					break;
				}
				
				
				$this->load->library('Verify_service');
				
				$phoneNo = $this->input->post('phoneNo');
				
				$rt = array(
					'code' => 'failed',
					'message' => '请求失败'
				);
				
				
				/**
				 * 记录该 ip,同一个IP 60秒内请求手机验证码不能大于3次
				 */
				$count = $this->verify_service->getIpCount($this->input->ip_address());
				if($count > 3){
					$rt['message'] = "您的请求过于频繁，请稍后尝试";
					break;
				}
				
				$codeInfo = $this->verify_service->getPhoneVerifyCodeBeforeExpire($phoneNo);
				if(empty($codeInfo)){
					$codeInfo = $this->verify_service->createVerifyCode(array(
						'phone' => $phoneNo
					));
				}else{
					/**
					 * 再检查 在验证码有效期内，同一个Ip ，同一个号码只能, 不能超过3次
					 */
					if($codeInfo['send_normal'] >= 3){
						$rt['message'] = "您的请求过于频繁,稍后再次尝试";
						break;
					}
				}
				
				$rt['code'] = 'success';
				
				$this->load->library('Sms_service');
				if($this->sms_service->sendMessage($phoneNo)){
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
				$this->jsonOutput('成功');
			}else{
				$this->jsonOutput($rt['message']);
			}
			
		}else{
			$this->jsonOutput('非法请求');
		}
		
		
	}
	
	
	/**
	 * for ios
	 */
	public function step_phone()
	{
		if($this->isPostRequest()){
			//sleep(2);
			for($i = 0; $i < 1; $i++){
				$this->form_validation->set_error_delimiters('','');
				$this->form_validation->reset_validation();
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
					$rt['message'] = $this->form_validation->error('phoneNo');
					break;
				}
				
				$this->form_validation->reset_validation();
				$this->form_validation->set_rules('phoneNo','手机号码',array(
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
				
				$this->load->library('Register_service');
				$this->load->library('Verify_service');
				
				$phoneNo = $this->input->post('phoneNo');
				
				$rt = array(
					'code' => 'failed',
					'message' => '请求失败'
				);
				
				if($this->form_validation->run()){
					/**
					 * @todo 同一个IP 不能进行连续发送
					 */
					$result = $this->register_service->createHalfRegisterMemebr(array(
						'mobile' => $phoneNo,
						'reg_ip' => $this->input->ip_address()
					));
				}
				
				/**
				 * 记录该 ip,同一个IP 60秒内请求手机验证码不能大于3次
				 */
				$count = $this->verify_service->getIpCount($this->input->ip_address());
				if($count > 3){
					$rt['message'] = "攻击行为，请求失败";
					break;
				}
				
				$codeInfo = $this->verify_service->getPhoneVerifyCodeBeforeExpire($phoneNo);
				if(empty($codeInfo)){
					$codeInfo = $this->verify_service->createVerifyCode(array(
						'phone' => $phoneNo
					));
				}else{
					/**
					 * 再检查 在验证码有效期内，同一个Ip ，同一个号码只能不能超过3次
					 */
					if($codeInfo['send_normal'] >= 3){
						$rt['message'] = "攻击行为，请求失败";
						break;
					}
				}
				
				$rt['code'] = 'success';
				$this->load->library('Sms_service');
				if($this->sms_service->sendMessage($phoneNo)){
					$this->verify_service->sendSuccessAddon($codeInfo['id']);
				}else{
					$this->verify_service->sendFailedAddon($codeInfo['id']);
				}
			}
			
			if($rt['code'] == 'success'){
				$this->jsonOutput('成功');
			}else{
				$this->jsonOutput($rt['message']);
			}
			
		}else{
			$this->jsonOutput('');
		}
	}
	
	
	public function step_authcode(){
		if($this->isPostRequest()){
			
			$this->load->library('Register_service');
			$this->load->library('Verify_service');
				
			if($this->verify_service->isAuthCodeValidate($this->input->post('code'),$this->input->post('phoneNo'))){
				$this->jsonOutput('成功');
			}else{
				$this->jsonOutput('验证码错误');
			}
			
			
		}
	}
}
