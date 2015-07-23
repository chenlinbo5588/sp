<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{
		$this->display('my/index');
	}
	
	
	public function set_city()
	{
		
		$this->display('my/set_city');
	}
	
}
