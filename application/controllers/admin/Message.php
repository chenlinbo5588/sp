<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Message extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
		$this->load->library(array('Admin_Service'));
	}
	
	
	
	public function email(){
		
		$feedback = '';
		
		$settingKey = array(
			'email_enabled',
			'email_type',
			'email_host',
			'email_port',
			'email_addr',
			'email_id',
			'email_pass',
			'email_pass_len'
		);
		
		$currentSetting = $this->admin_service->getSettingList(array(
			'where_in' => array(
				array('key' => 'name' , 'value' => $settingKey)
			)
		));
		
		
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				$data = array();
				
				if($this->input->post('email_type') == 1 && $this->input->post('email_enabled') == 1){
					$this->form_validation->set_rules('email_host','SMTP 服务器','required');
					$this->form_validation->set_rules('email_port','SMTP 端口','required|is_natural_no_zero|less_than[65535]');
					$this->form_validation->set_rules('email_addr','发信人邮件地址','required|valid_email');
					$this->form_validation->set_rules('email_id','SMTP 身份验证用户名','required');
					$this->form_validation->set_rules('email_pass','SMTP 身份验证密码','required');
				
					if(!$this->form_validation->run()){
						$feedback = getErrorTip($this->form_validation->error_string());
						
						break;
					}
				}
				
				
				foreach($settingKey as $oneKey){
					$temp = $this->input->post($oneKey);
					
					if($oneKey == 'email_pass'){
						
						if($temp){
							$temp = $this->encrypt->encode($temp);
						}else{
							continue;
						}
						
					}
					
					
					$data[] = array(
						'name' => $oneKey,
						'value' => $temp
					);
				}
				
				if($data && $this->admin_service->updateSetting($data) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				
				$feedback = getSuccessTip('保存成功');
				$currentSetting = $this->admin_service->getSettingList(array(
					'where_in' => array(
						array('key' => 'name' , 'value' => $settingKey)
					)
				));
				
			}
			
		}
		
		if(!empty($currentSetting['email_pass'])){
			
		}
		
		//print_r($currentSetting);
		$this->assign('currentSetting',$currentSetting);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	public function email_tpl(){
		$this->display();
	}
	
}
