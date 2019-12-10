<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	
	
	/**
	 * 首页
	 */
	public function introduce()
	{
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->display();
	}
	



	public function bussiness(){
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->display();		
	}
	
	
	
	public function doc(){
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->display();
	}


}
