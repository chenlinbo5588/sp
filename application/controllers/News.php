<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	
	
	/**
	 * 首页
	 */
	public function index()
	{
		$this->display();
	}
	



	public function detail(){
		$this->display();
		
		
	}

}
