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
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->seoTitle('新闻中心');
		$this->display();
	}
	



	public function detail(){
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->seoTitle('工程案例');
		$this->display();
		
		
	}

}
