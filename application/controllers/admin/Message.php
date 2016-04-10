<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Message extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function email(){
		$this->display();
	}
	
	public function email_tpl(){
		$this->display();
	}
	
}
