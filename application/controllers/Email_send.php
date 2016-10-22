<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 从邮件表中 取出数据自动发送邮件  Email
 */
class Email_send extends MY_Controller {
	
	private $_userEmailNeedUpdate;
	private $_debug;
	private $_queueId;
	
	public function __construct(){
		parent::__construct();
		$this->_debug = false;
		
		if(!is_cli()){
			exit();
		}
		
		$this->benchmark->mark('code_start');
		$this->initParam();
	}
	
	private function initParam(){
		
		$param = $this->input->server('argv');
		print_r($param);
		//$currentDir = pathinfo(__FILE__, PATHINFO_DIRNAME);
		
		//$dir = dirname(ROOTPATH);
		//file_put_contents($dir.'/hp_push.txt',print_r($_SERVER,true));
		$tableId = intval($param[3]);
		
		if(!in_array($tableId,range(0,9))){
			exit('Email queue id 0-9 is allowed');
		}
		
		$this->_queueId = $tableId;
		$this->_userEmailNeedUpdate = array();
	
	}
	
	
	/**
	 * 一分钟发最多发10封邮件
	 */
	public function index(){
		set_time_limit(0);
		
		$this->load->library('Message_service');
		$condition = array(
			'select' => 'id,email,username,retry,title,content',
			'where' => array(
				'is_send' => 0,
				'retry <=' => 2
			),
			'limit' => 10
		);
		
		$emailNeedSendList = $this->message_service->getEmailListByQueueId($condition,$this->_queueId);
		$this->message_service->initEmail($this->_siteSetting);
		
		
		print_r($emailNeedSendList);
		
		if($emailNeedSendList){
			foreach($emailNeedSendList as $aEmail){
				if(empty($aEmail['email'])){
					$this->_userEmailNeedUpdate[] = array(
						'id' => $aEmail['id'],
						'is_send' => 1,
						'retry' => 0,
					);
				}else{
					$flag = $this->message_service->sendEmail($aEmail['email'],str_replace(
							array('{site_name}','{username}'),
							array($this->_getSiteSetting('site_name'), $aEmail['username']),
							$aEmail['title']
						),
						str_replace(
							array('{site_name}','{username}'),
							array($this->_getSiteSetting('site_name'), $aEmail['username']),
							$aEmail['content']
						));
						
					
					if($flag){
						$this->_userEmailNeedUpdate[] = array(
							'id' => $aEmail['id'],
							'is_send' => 1,
							'retry' => 0,
						);
						
					}else{
						$this->_userEmailNeedUpdate[] = array(
							'id' => $aEmail['id'],
							'is_send' => 0,
							'retry' => $aEmail['retry'] + 1,
						);
					}
				}
			}
		}
		
		
		if($this->_userEmailNeedUpdate){
			$this->Push_Email_Model->batchUpdate($this->_userEmailNeedUpdate,'id');
		}
		
		$this->benchmark->mark('code_end');
		echo 'Hp Match Used : '.$this->benchmark->elapsed_time('code_start', 'code_end')." seconds \n";
	}
}
