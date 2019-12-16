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
		
		$this->seoTitle('公司简介');
		$this->display();
	}
	



	public function bussiness(){
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->seoTitle('业务介绍');
		$this->display();		
	}
	
	
	
	public function doc(){
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->seoTitle('资质荣誉');
		$this->display();
	}


}
