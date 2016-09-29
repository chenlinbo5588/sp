<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{
		//print_r($this->session->all_userdata());
		$this->display('index/index');
	}
	
	public function logout()
	{
		$this->session->unset_userdata($this->_adminProfileKey);
		js_redirect('member/admin_login');
	}
}
