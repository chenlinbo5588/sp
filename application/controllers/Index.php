<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Team_Service');
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
		
		$this->display();
	}
	

}
