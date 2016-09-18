<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_service extends Base_service {

	private $_msgTemplateModel = null;
	private $_email = null;

	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Msg_Template_Model');
		self::$CI->load->library('email');
		
		$this->_email = self::$CI->email;
		$this->_msgTemplateModel = self::$CI->Msg_Template_Model;
	}
	
	
	public function sendEmail($to,$subject,$content){
		
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
		
		
		return true;
		
	}
	
	
	
	public function getMsgTemplateByCode($code){
		return $this->_msgTemplateModel->getFirstByKey($code,'code');
	}
	
	/**
	 * 
	 */
	public function getListByCondition($condition){
		return $this->toEasyUseArray($this->_msgTemplateModel->getList($condition),'code');
	}
	
	
	
	public function updateMsgTemplate($param){
		
		return $this->_msgTemplateModel->update(array(
			'title' => $param['title'],
			'content' => $param['content'],
		
		),array('code' => $param['code']));
		
	}
	
		
	/**
	 * 
	 */
	public function switchMsgTemplateStatus($switchValue,$ids){
		
		$data = array();
		foreach($ids as $id){
			$data[] = array(
				'code' => $id,
				'is_off' => $switchValue == 'mail_switchON' ? 0 : 1
			);
		}
		
		if($data){
			return $this->_msgTemplateModel->batchUpdate($data,'code');
		}
		
		return false;
	}
}
