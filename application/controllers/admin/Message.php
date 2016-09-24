<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Message extends Ydzj_Admin_Controller {
	
	private $settingKey = null;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Admin_service','Message_service'));
		
		$this->assign('moduleTitle','消息通知');
		$this->_subNavs = array(
			array('url' => 'message/email', 'title' => '邮件设置'),
			array('url' => 'message/email_tpl', 'title' => '消息模板'),
		);
		
		
		$this->settingKey = array(
			'email_enabled',
			'email_type',
			'email_host',
			'email_port',
			'email_addr',
			'email_id',
			'email_pass',
			'email_pass_len'
		);
	}
	
	
	
	public function email(){
		
		$feedback = '';
		
		
		
		$currentSetting = $this->admin_service->getSettingList(array(
			'where_in' => array(
				array('key' => 'name' , 'value' => $this->settingKey)
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
					
					//$this->form_validation->set_rules('email_pass','SMTP 身份验证密码','required');
				
					if(!$this->form_validation->run()){
						$feedback = getErrorTip($this->form_validation->error_string());
						
						break;
					}
				}
				
				
				foreach($this->settingKey as $oneKey){
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
				
				
				$this->getCacheObject()->delete(CACHE_KEY_SiteSetting);
				
				$feedback = getSuccessTip('保存成功');
				$currentSetting = $this->admin_service->getSettingList(array(
					'where_in' => array(
						array('key' => 'name' , 'value' => $this->settingKey)
					)
				));
				
			}
			
		}
		
		if(!empty($currentSetting['email_pass'])){
			// empty ，密码不显示，防止泄漏信息
		}
		
		//print_r($currentSetting);
		$this->assign('currentSetting',$currentSetting);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	private function _getEmailTpl(){
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		$keywords = $this->input->get_post('keywords');
		if($keywords){
			$condition['like']['name'] = $keywords;
		}
		
		
		$list = $this->Msg_Template_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
	}
	
	
	public function email_tpl(){
		
		
		$ids = $this->input->post('del_id');
		$switchValue = $this->input->post('submit_type');
		
		
		if($this->isPostRequest()){
			if($ids){
				$row = $this->message_service->switchMsgTemplateStatus($switchValue,$ids);
			}
		}
		
		$this->_getEmailTpl();
		
		$this->display();
	}
	
	
	public function email_testing(){
		
		
		$this->load->library('email');
		
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $this->input->post('email_host');
		$config['smtp_port'] = $this->input->post('email_port');
		$config['smtp_user'] = $this->input->post('email_id');
		
		//
		
		$config['smtp_pass'] = $this->input->post('email_pass');
		$config['smtp_timeout'] = 10;
		$config['charset'] = config_item('charset');
		
		$this->email->initialize($config);
		
		$emailTitle = config_item('site_name');
		$emailBody = "你好，这是一封测试邮件，如果您收到该邮件，表示配置已经生效.";
		
		
		$this->email->to($this->input->post('email_test'));
		$this->email->from($this->input->post('email_addr'));
		$this->email->subject($emailTitle);
		$this->email->message($emailBody);
		
		//if($this->ext_email->send($this->input->post('email_test'),$emailTitle,$emailBody,$this->input->post('email_addr'))){
		if($this->email->send()){
			$feedback = '成功';
		}else{
			$feedback = '失败';
		}
		
		//file_put_contents('debug.txt',$this->email->print_debugger());
		$this->jsonOutput('发送'.$feedback);
		
	}
	
	
	public function email_tpl_edit(){
		
		$code = $this->input->get_post('code');
		
		$this->_subNavs[] = array('url' => 'message/email_tpl_edit?code='.$code, 'title' => '编辑消息模板');
		
		$feedback = '';
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('code','消息代码','required');
			$this->form_validation->set_rules('title','标题','required');
			$this->form_validation->set_rules('content','内容','required');
			
			
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				$rows = $this->message_service->updateMsgTemplate($_POST);
				
				if($rows < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				
			}
			
		}
		
		$tplInfo = $this->message_service->getMsgTemplateByCode($code);
		$this->assign('feedback',$feedback);
		$this->assign('info',$tplInfo);
		$this->display();
		
	}
	
}
