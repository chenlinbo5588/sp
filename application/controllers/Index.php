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
		$this->load->library(array('Goods_service','Cms_service'));
		$goodsList = $this->goods_service->getCommandGoodsList();
		$this->assign('goodsList',$goodsList);
		
		$this->assign('homeSliderImg',range(1,3));		
		// 获得 企业新闻
		$qiyeList = $this->cms_service->getCommandArticleList(11);
		// 行业动态
		$industryList = $this->cms_service->getCommandArticleList(12);

		$hotkeys = $this->_getSiteSetting('hotwords');
		$hotwords = explode(',',$hotkeys);
		
		$this->seo();
		$this->assign('hotwords',$hotwords);
		$this->assign('qiyeList',$qiyeList);
		$this->assign('industryList',$industryList);
		
		$this->assign('currentModule','index');
		$this->display();
	}
	
	
	/**
	 * 通用文章详情
	 */
	public function article(){
		
		$this->load->model('Article_Model');
		$articleId = $this->uri->rsegment(3);
		
		
		
		
		
		/*
		$this->assign('sideNavs',$this->sideNavs);
		$this->assign('sideTitle',$this->modKey);
		$this->assign('sideTitleUrl',$tempAr[$this->modKey]['url']);
		*/
		
		$this->_navigation = array(
			'首页' => site_url('/'),
			$this->modKey => $tempAr[$this->modKey]['url']
		);
		
		
		
		
		$this->display('common/art');
	}

}
