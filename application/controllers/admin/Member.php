<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		
		$this->display('member/index');
	}
	
	
}
