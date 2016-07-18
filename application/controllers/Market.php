<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Market extends Ydzj_Controller {
	
	private $sideNavs = null;
	private $modKey = '营销招商';
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Goods_service');
		$this->load->model('Article_Model');
		
		$this->assign('pgClass',strtolower(get_class()).'Pg');
		$this->assign('sideTitle',$this->modKey);
		
		
		$tempAr = config_item('pageConf');
		
		$this->sideNavs = $tempAr[$this->modKey]['sideNav'];
		$this->assign('sideNavs',$this->sideNavs);
		
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
	
	
	public function agency()
	{
		$key = '经销招商';
		$this->_getArticleContent($key);
		
		$this->seo($key);
		$this->display('common/art');
	}
	
	
	public function cooperation()
	{
		$key = '运营特点';
		$this->_getArticleContent($key);
		$this->seo($key);
		$this->display('common/art');
	}
	
}
