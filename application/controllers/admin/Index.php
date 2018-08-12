<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function nopermission(){
		$this->display('common/nopermission');
	}
	
	
	public function index()
	{
		$this->display();
	}
	
}
