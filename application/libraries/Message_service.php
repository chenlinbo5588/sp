<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_service extends Base_service {

	private $_msgTemplateModel = null;
	private $_email = null;
	
	private $_setting ;

	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Msg_Template_Model');
		self::$CI->load->library('email');
		
		$this->_email = self::$CI->email;
		
		$this->_msgTemplateModel = self::$CI->Msg_Template_Model;
	}
	
	
	/**
	 * @todo modify when online
	 */
	public function initEmail($setting){
		
		$this->_setting = $setting;
		
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $setting['email_host'];
		$config['smtp_port'] = $setting['email_port'];
		$config['smtp_user'] = $setting['email_id'];
		
		$psw = self::$CI->encrypt->decode($setting['email_pass']);
		
		$config['smtp_pass'] = $psw;
		$config['smtp_timeout'] = 3;
		$config['charset'] = config_item('charset');
		
		
		//print_r($config);
		
		$this->_email->initialize($config);
	}
	
	
	public function getEncodeParam($param,$expired = 86400){
		$param[] = self::$CI->input->server('REQUEST_TIME') + $expired;
		$str = implode("\t",$param);
		
		return urlencode(self::$CI->encrypt->encode($str));
	}
	
	
	/**
	 * 
	 */
	public function sendEmailConfirm($to,$confirm_url){
		$tpl = $this->getMsgTemplateByCode('email_confirm');
		
		$tpl['title'] = str_replace(array(
			'{site_name}'
		),array(
			$this->_setting['site_name']
		),$tpl['title']);
		
		$tpl['content'] = str_replace(array(
			'{site_name}',
			'{site_logo}',
			'{site_url}',
			'{confirm_url}',
			'{confirm_url_plain}'
			
		),array(
			$this->_setting['site_name'],
			resource_url($this->_setting['site_logo']),
			site_url(),
			$confirm_url,
			htmlentities($confirm_url)
		),$tpl['content']);
		
		return $this->sendEmail($to,$tpl['title'],$tpl['content']);
	}
	
	
	public function sendEmail($to,$subject,$content){
		
		/*
		echo $to;
		echo $subject;
		echo $content;
		print_r($this->_setting);
		*/
		$this->_email->to($to);
		$this->_email->from($this->_setting['email_addr']);
		$this->_email->subject($subject);
		$this->_email->message($content);
		$this->_email->set_mailtype('html');
		
		$flag = $this->_email->send();
		//var_dump($flag);
		//$this->_email->print_debugger();
		//echo 'success ';
		
		return $flag;
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
