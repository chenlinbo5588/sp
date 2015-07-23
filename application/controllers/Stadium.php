<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium extends Ydzj_Controller {
	
	//public $_controllerUrl;
	
	public function __construct(){
		parent::__construct();
        
        $this->load->library('Stadium_Service');
	}
	
	
	public function index()
	{
		$this->seo('体育场馆');
		$this->display('stadium/index');
	}
	
}
