<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Ydzj_Controller {
	
	private $sideNavs = null;
	private $modKey = '新闻资讯';
	private $topClassId = 0;
	
	
	public function __construct(){
		parent::__construct();
		$this->assign('pgClass',strtolower(get_class()).'Pg');
		$this->assign('sideTitle',$this->modKey);
		
		$this->load->library('Article_service');
		
		$articleClass = $this->Article_Class_Model->getList(array(
			'where' => array(
				'ac_name' => $this->modKey,
				'ac_parent_id' => 0
			)
		));
		
		if($articleClass[0]){
			$this->topClassId = $articleClass[0]['ac_id'];
			
			$sideNavs = $this->Article_Class_Model->getList(array(
				'where' => array(
					'ac_parent_id' => $this->topClassId
				)
			));
			
			if($sideNavs){
				foreach($sideNavs as $nav){
					$this->sideNavs[$nav['ac_name']] = site_url('news/news_list/?ac_id=').$nav['ac_id'];
				}
			}
			
		}
		
		
		$this->assign('sideNavs',$this->sideNavs);
	}
	
	
	
	public function news_list()
	{
		
		$keyword = $this->input->get_post('keyword') ? $this->input->get_post('keyword') : '';
		
		$currentAcId = $this->input->get_post('ac_id');
		
		$this->_breadCrumbLinks($currentAcId);
		
		
		$childIds = $this->article_service->getAllChildArticleClassByPid($currentAcId);
		$childIds[] = $currentAcId;
		
		//print_r($childIds);
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		//echo $currentPage;
		$condition = array(
			//'select' => 'article_id,ac_id,article_title,article_click,gmt_create,gmt_modify',
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
				'base_link' => site_url('news/news_list/?')."ac_id={$currentAcId}&keyword={$keyword}"
			)
		);
		
		if($keyword){
			$condition['like']['article_title'] = $keyword;
		}
		
		$list = $this->article_service->getArticleListByCondition($condition);
		
		if($list['data']){
			$count = 0;
			foreach($list['data'] as $key => $newsArtile){
				$count = preg_match("/<img\s*?src=\"?(.*?)\"?\s+/is",$newsArtile['article_content'],$matchs);
				
				if($count){
					//print_r($matchs);
					$newsArtile['title_img'] = resource_url($matchs[1]);
				}else{
					$newsArtile['title_img'] = resource_url('img/default.jpg');
				}
				
				$newsArtile['url'] = site_url('news/detail?id=') . $newsArtile['article_id'];
				$newsArtile['digest'] = cutText(html_entity_decode(strip_tags($newsArtile['article_content'])),120);
				$list['data'][$key] = $newsArtile;
				
				$count = 0;
			}
		}
		
		//print_r($list);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		$this->assign('currentAcId',$currentAcId);
		$this->assign('keyword',$keyword);
		
		$this->assign('breadcrumb',$this->breadcrumb());
		$this->display('common/art_list');
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
	
	
	
	public function detail(){
		
		$id = $this->input->get_post('id');
		$info = $this->Article_Model->getFirstById($id,'article_id');
		
		$this->_breadCrumbLinks($info['ac_id']);
		
		$this->assign('breadcrumb',$this->breadcrumb());
		
		$this->assign('info',$info);
		$this->display('common/art_detail');
		
		
	}
	
}
