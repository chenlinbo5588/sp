<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function welcome(){
		
		$this->display('index/welcome');
	}
	
	
	public function index()
	{
		
		//print_r($this->session->all_userdata());
		$this->display('index/index');
	}
	
	
	public function logout()
	{
		$this->session->unset_userdata('manage_profile');
		redirect(site_url('member/admin_login'));
	}
}
