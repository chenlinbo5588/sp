<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->_inApp){
			//$this->output->set_status_header(400,'Page Not Found');
		}
	}
	
	
	private function _respone($message){
		echo $message;
		$this->jsonOutput($message,array('formhash' => $this->security->get_csrf_hash(), $this->_verifyName => $this->_verify()));
	}
	
	
	public function index(){
		
	}
	
	public function step_phone()
	{
		if($this->isPostRequest()){
			
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
							'username_callable',
							array(
								$this->Member_Model,'checkUserNameNoExist'
							)
						)
					),
					array(
						'username_callable' => '%s已经存在'
					)
				);
				
				
				if(!$this->form_validation->run()){
					$rt['message'] = $this->form_validation->error('phoneNo');
					break;
				}
				
				$this->load->library('Register_Service');
				$this->load->library('Verfiy_Service');
				
				$rt = $this->register_service->createHalfRegisterMemebr(array(
					'mobile' => $this->input->post('phoneNo'),
					'regip' => $this->input->ip_address()
				));
				
			}
			
			if($rt['code'] != 'success'){
				$this->_respone($rt['message']);
			}else{
				$this->_respone('成功',$rt['data']);
			}
			
		}else{
			$this->_respone('');
		}
		
	}
}
