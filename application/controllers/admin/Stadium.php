<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium extends Ydzj_Admin_Controller {
	
	//public $_controllerUrl;
	
	public function __construct(){
		parent::__construct();
		//$this->_controllerUrl = admin_site_url();
	}
	
	
	public function index()
	{
		$this->seo('体育场馆');
		$this->display('stadium/index');
	}
	
	public function add(){
		$this->seo('添加体育场馆');
		$this->display('stadium/add');
	}
	
}
