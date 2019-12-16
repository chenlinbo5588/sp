<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
	
		
		
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->seoTitle('联系方式');
		$this->display();
	}
	


}
