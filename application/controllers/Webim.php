<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webim extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->assign('hxpsw',md5(config_item('encryption_key').$this->encrypt->decode($this->_profile['basic']['password'])));
		
	}
	
	
	public function index()
	{
		$this->seoTitle('聊天系统');
		$this->display();
	}
	
	
}
