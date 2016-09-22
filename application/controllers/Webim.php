<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webim extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->assign('chatConfig',$this->_profile['chat']);
	}
	
	
	public function index()
	{
		$this->seoTitle('聊天系统');
		$this->display();
	}
	
	
}
