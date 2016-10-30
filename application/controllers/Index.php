<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		//$this->load->library('Team_Service');
	}
	
	/**
	 * 首页
	 */
	public function index()
	{	
		
		
		$this->load->library(array('Goods_service','Article_service'));
		$goodsList = $this->goods_service->getCommandGoodsList();
		$this->assign('goodsList',$goodsList);
		
		$this->assign('homeSliderImg',range(2,3));		
		// 获得 企业新闻
		$qiyeList = $this->article_service->getCommandArticleList(17);
		// 行业动态
		$industryList = $this->article_service->getCommandArticleList(18);

		$hotkeys = $this->_getSiteSetting('hotwords');
		$hotwords = explode(',',$hotkeys);
		
		$this->seo();
		$this->assign('hotwords',$hotwords);
		$this->assign('qiyeList',$qiyeList);
		$this->assign('industryList',$industryList);
		$this->display();
		
		$this->display();
		/*
		//$availableCity = $this->district_stat_service->getAvailableCity(1);
		//print_r($availableCity);
		//$sportsCategoryList = $this->team_service->getSportsCategory();
		
		$city_id = $this->_getCity();
		
		if($city_id){
			$cityInfo = $this->team_service->getCityById($city_id);
		}
		
		//print_r($cityInfo);
		
		$title = '篮球队';
		$this->setTopNavTitle($title);
		$this->seoTitle($title);
		
		
		$searchKey = $this->input->get('search_key');
		if($searchKey){
			$condition['like']['title'] = $searchKey;
		}
		
		if($city_id){
			$condition['where'] = array('d'.$cityInfo['level'] => $city_id);
		}else{
			$cityInfo['id'] = 0;
			$cityInfo['level'] = 0;
			$cityInfo['name'] = '全国';
		}
		
		$teamList = $this->team_service->getAllPagerTeam($condition);
		
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('team/switch_city/upid/'.$city_id).'" title="点击切换城市">'.$cityInfo['name'].'</a>');
		$this->setRightNavLink('<a id="rightBarLink" class="bar_button" href="'.site_url('team/create_team').'">+创建球队</a>');
		
		//$this->assign('sportsCategoryList',$sportsCategoryList);
		if($cityInfo['level'] == 4){
			$this->assign('cityLevel','dname4');
		}else{
			$this->assign('cityLevel','dname'.($cityInfo['level'] + 1));
		}
		
		
		$this->assign('teamList',$teamList);
		$this->display('team/index');
		
		*/
	}
	
	
}
