<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends Ydzj_Controller {
	
	private $sideNavs = null;
	private $modKey = '走进标度';
	
	public function __construct(){
		parent::__construct();
		
		$this->assign('pgClass',strtolower(get_class()).'Pg');
		
		$this->load->library('Goods_service');
		$this->load->model('Article_Model');
		
		$tempAr = config_item('pageConf');
		
		$this->sideNavs = $tempAr[$this->modKey]['sideNav'];
		$this->assign('sideNavs',$this->sideNavs);
		
		$this->assign('sideTitle',$this->modKey);
		$this->assign('sideTitleUrl',$tempAr[$this->modKey]['url']);
		
		$this->_navigation = array(
			'首页' => site_url('/'),
			$this->modKey => $tempAr[$this->modKey]['url']
		);
		
		$goodsList = $this->goods_service->getCommandGoodsList();
		$this->assign('goodsList',$goodsList);
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
		$this->seo($key);
		
		$this->display('common/art');
	}
	
	
	public function thinking()
	{
		$key = '公司理念';
		$this->_getArticleContent($key);
		
		$this->seo($key);
		
		$this->display('common/art');
	}
	
	
	public function moreintro()
	{
		$key = '企业风采';
		$this->_getArticleContent($key);
		$this->seo($key);
		
		$this->display('common/art');
	}
}