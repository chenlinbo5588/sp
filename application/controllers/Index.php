<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
	
		
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->seoTitle('首页');
		$this->display();
	}
	
	public function gsjstk()
	
	{
		
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->display();
	}
	

}
