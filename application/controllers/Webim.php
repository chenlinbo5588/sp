<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webim extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
	}
	
	
	public function index()
	{
		
		$this->display();
	}
	
	
}
