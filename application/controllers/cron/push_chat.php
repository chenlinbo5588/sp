<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push_Chat extends MY_Controller {


	public function __construct(){
		parent::__construct();
		
		if(!is_cli()){
			exit();
		}
	}
	
	public function user(){
		$param = $this->input->server('argv');
		print_r($param);
		
		if(empty($param[3])){
			exit('table param is not given');
		}
		
		$this->load->library('Message_service');
		
		
		
		$this->message_service->getPushObject();
		
		echo 'auser';
	}
	
	
	
	public function index()
	{
		echo '自动推送聊天窗口';
	}
}
