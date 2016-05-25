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
		$this->load->library(array('Goods_service','Article_service'));
		$goodsList = $this->goods_service->getCommandGoodsList();
		$this->assign('goodsList',$goodsList);
		
		
		// 获得 企业新闻
		$qiyeList = $this->article_service->getCommandArticleList(15);
		// 行业动态
		$industryList = $this->article_service->getCommandArticleList(16);
		
		
		$this->seo();
		$this->assign('qiyeList',$qiyeList);
		$this->assign('industryList',$industryList);
		$this->display();
	}
	

}
