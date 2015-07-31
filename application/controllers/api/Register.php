<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->_inApp){
			//$this->output->set_status_header(400,'Page Not Found');
		}
	}
	
	
	public function index(){
		
	}
	
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
							'nickname_callable[nickname]',
							array(
								$this->Member_Model,'isUnqiueByKey'
							)
						)
					),
					array(
						'nickname_callable' => '%s已经被占用'
					)
				);
				
				$this->load->library('Register_Service');
				$this->load->library('Verify_Service');
				
				$phoneNo = $this->input->post('phoneNo');
				
				$rt = array(
					'code' => 'failed',
					'message' => '请求失败'
				);
				
				if($this->form_validation->run()){
					/**
					 * @todo 同一个IP 不能进行连续注册
					 */
					
					$result = $this->register_service->createHalfRegisterMemebr(array(
						'mobile' => $phoneNo,
						'regip' => $this->input->ip_address()
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
					 * 再检查 在验证码有效期内，同一个Ip ，同一个号码只能, 不能超过3次
					 */
					if($codeInfo['send_normal'] >= 3){
						$rt['message'] = "攻击行为，请求失败";
						break;
					}
				}
				
				$rt['code'] = 'success';
				$this->load->library('ShortMessage_Service');
				if($this->shortmessage_service->sendMessage($phoneNo)){
					$this->verify_service->sendSuccessAddon($codeInfo['id']);
				}else{
					$this->verify_service->sendFailedAddon($codeInfo['id']);
				}
			}
			
			if($rt['code'] == 'success'){
				$this->jsonOutput('成功',$this->getFormHash());
			}else{
				$this->jsonOutput($rt['message'],$this->getFormHash());
			}
			
		}else{
			$this->formhash();
		}
	}
	
	
	public function step_authcode(){
		if($this->isPostRequest()){
			
			$this->load->library('Register_Service');
			$this->load->library('Verify_Service');
				
			if($this->verify_service->isAuthCodeValidate($this->input->post('phoneNo'),$this->input->post('code'))){
				$this->jsonOutput('成功',$this->getFormHash());
			}else{
				$this->jsonOutput('验证码错误',$this->getFormHash());
			}
			
			
		}
	}
}
