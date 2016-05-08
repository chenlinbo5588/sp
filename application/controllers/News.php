<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Ydzj_Controller {
	
	private $sideNavs = null;
	
	public function __construct(){
		parent::__construct();
		$this->assign('pgClass',strtolower(get_class()).'Pg');
		$this->assign('sideTitle','新闻资讯');
		
		$this->load->library('Article_service');
		
		$this->sideNavs = array(
			'企业新闻' => site_url('news/list'),
			'行业动态' => site_url('news/thinking'),
		
		);
		
		$this->assign('sideNavs',$this->sideNavs);
		
		$this->_navigation = array(
			'首页' => site_url('/'),
			'走进标度' => site_url('about')
		);
	}
	
	private function _getArticleContent($key){
		
		$this->_navigation[$key] = $this->sideNavs[$key];
		
		$article = $this->Article_Model->getFirstByKey($key,'article_title');
		$this->assign('article',$article);
		
		$this->assign('breadcrumb',$this->breadcrumb());
		
	}
	
	
	public function index()
	{
		$key = '企业简介';
		$this->_getArticleContent($key);
		
		$this->display('common/art');
	}
	
	
	public function thinking()
	{
		$key = '公司理念';
		$this->_getArticleContent($key);
		
		$this->display('common/art');
	}
	
	
	public function moreintro()
	{
		$key = '企业风采';
		$this->_getArticleContent($key);
		
		$this->display('common/art');
	}
	

}
