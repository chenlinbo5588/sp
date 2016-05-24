<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doc extends Ydzj_Controller {
	
	private $sideNavs = null;
	private $modKey = '';
	private $topClassId = 0;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Article_service','Goods_Service'));
		
		$this->assign('pgClass',strtolower(get_class()).'Pg');
		$this->modKey = $this->input->get_post('catname');
		
		if(empty($this->modKey)){
			$this->modKey = '服务中心';
		}
		
		$this->assign('sideTitle',$this->modKey);
		
		
		$tempAr = config_item('pageConf');
		
		$this->sideNavs = $tempAr[$this->modKey]['sideNav'];
		$this->assign('sideNavs',$this->sideNavs);
		
		$articleClass = $this->Article_Class_Model->getList(array(
			'where' => array(
				'ac_name' => '产品资料',
				'ac_parent_id' => 0
			)
		));
		
		if($articleClass[0]){
			$this->topClassId = $articleClass[0]['ac_id'];
			
			
			/*
			$sideNavs = $this->Article_Class_Model->getList(array(
				'where' => array(
					'ac_parent_id' => $this->topClassId
				)
			));
			
			if($sideNavs){
				foreach($sideNavs as $nav){
					$this->sideNavs[$nav['ac_name']] = site_url('doc/product_list/?ac_id=').$nav['ac_id'];
				}
			}
			*/
		}
		
		
		//$this->assign('sideNavs',$this->sideNavs);
		
		
		$goodsList = $this->goods_service->getCommandGoodsList();
		$this->assign('goodsList',$goodsList);
	}
	
	
	
	public function product_list()
	{
		
		$keyword = $this->input->get_post('keyword') ? $this->input->get_post('keyword') : '';
		$currentAcId = $this->input->get_post('ac_id');
		
		
		if(empty($currentAcId)){
			$currentAcId = $this->topClassId;
		}
		
		$this->_breadCrumbLinks($currentAcId);
		$childIds = $this->article_service->getAllChildArticleClassByPid($currentAcId);
		
		$childIds[] = $currentAcId;
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		//echo $currentPage;
		$condition = array(
			'where' => array(
				'article_show' => 1
			),
			'where_in' => array(
				array(
					'key' => 'ac_id','value' => $childIds
				)
			),
			'order' => 'article_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				//'call_js' => 'search_page',
				'form_id' => '#listForm',
				'anchor' => 'listmao',
				'base_link' => site_url('doc/product_list/?')."ac_id={$currentAcId}&keyword={$keyword}"
			)
		);
		
		if($keyword){
			$condition['like']['article_title'] = $keyword;
		}
		
		$list = $this->Article_Model->getList($condition);
		//echo 'aaa';print_r($list);
		if($list['data']){
			foreach($list['data'] as $key => $newsArtile){
				
				
				if($newsArtile['article_pic']){
					$newsArtile['article_pic'] = resource_url($newsArtile['article_pic']);
				}else{
					$newsArtile['article_pic'] = resource_url('img/default.jpg');
				}
				
				if(empty($newsArtile['article_url'])){
					$newsArtile['article_url'] = site_url('news/detail?id=') . $newsArtile['article_id'].'&ac_id='.$newsArtile['ac_id'];
				}
				
				if(trim($newsArtile['article_digest'])){
					$newsArtile['article_digest'] = cutText(html_entity_decode(strip_tags($newsArtile['article_content'])),120);
				}
				
				$list['data'][$key] = $newsArtile;
			}
		}
		
		//print_r($list);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		$this->assign('currentAcId',$currentAcId);
		$this->assign('keyword',$keyword);
		
		$this->assign('breadcrumb',$this->breadcrumb());
		$this->display();
		
	}
	
	
	private function _breadCrumbLinks($currentAcId){
		
		if(empty($currentAcId)){
			$currentAcId = $this->topClassId;
		}
		
		if($currentAcId){
			$parents = $this->article_service->getParentsById($currentAcId);
			if($parents){
				$parents = array_reverse($parents);
			}
			
			foreach($parents as $pitem){
				$this->_navigation[$pitem['ac_name']] = site_url('news/news_list?id='.$pitem['ac_id']);
			}
		}
	}
}
