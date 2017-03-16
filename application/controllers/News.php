<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Ydzj_Controller {
	
	private $sideNavs = null;
	private $topClassId = 0;
	private $seoKeys = array();
	
	public function __construct(){
		parent::__construct();
		
		$this->assign('currentModule','news');
		$this->modKey = '新闻资讯';
		$this->assign('sideTitle',$this->modKey);
		
		$this->load->library(array('Cms_service'));
		
		$articleClass = $this->Cms_Article_Class_Model->getList(array(
			'where' => array(
				'pid' => 0
			)
		));
		
		if($articleClass[0]){
			$this->topClassId = $articleClass[0]['id'];
			
			$sideNavs = $this->Cms_Article_Class_Model->getList(array(
				'where' => array(
					'pid' => $this->topClassId
				)
			));
			
			if($sideNavs){
				foreach($sideNavs as $nav){
					$this->sideNavs[$nav['name']] = base_url('news/plist/ac/'.$nav['id'].'.html');
				}
			}
			
		}
		
		
		$this->assign('sideNavs',$this->sideNavs);
		
		$this->_navigation = array(
			'首页' => site_url('/'),
		);
		
	}
	
	
	
	public function plist()
	{
		$param = $this->uri->ruri_to_assoc();
		
		//print_r($param);
		
		$keyword = $param['kw'];
		$currentAcId = $param['ac'];
		
		if(empty($currentAcId)){
			$currentAcId = $this->topClassId;
		}
		
		$this->_breadCrumbLinks($currentAcId);
		$childIds = $this->cms_service->getAllChildArticleClassByPid($currentAcId);
		
		$childIds[] = $currentAcId;
		
		//print_r($childIds);
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		//echo $currentPage;
		$condition = array(
			//'select' => 'article_id,ac_id,article_title,article_click,gmt_create,gmt_modify',
			'where' => array(
				'article_state' => 3
			),
			'where_in' => array(
				array(
					'key' => 'ac_id','value' => $childIds
				)
			),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				//'call_js' => 'search_page',
				'form_id' => '#listForm',
				'base_link' => base_url("news/plist/ac/{$currentAcId}.html?kw={$keyword}")
			)
		);
		
		if($keyword){
			$condition['like']['article_title'] = $keyword;
		}
		
		$list = $this->Cms_Article_Model->getList($condition);
		if($list['data']){
			foreach($list['data'] as $key => $newsArtile){
				if($newsArtile['image_url']){
					
					$imgArr = getImgPathArray($newsArtile['image_url'] , array('m'), $keyName = 'image_url');
					$newsArtile['image_url'] = resource_url($newsArtile['image_url']);
					
					//print_r($imgArr);
					$list['data'][$key] = array_merge($imgArr);
				}else{
					$newsArtile['image_url'] = resource_url('img/default.jpg');
				}
				
				if(empty($newsArtile['jump_url'])){
					$newsArtile['jump_url'] = site_url("news/detail/{$newsArtile['ac_id']}/{$newsArtile['id']}");
				}
				
				if(trim($newsArtile['digest'])){
					$newsArtile['digest'] = cutText(html_entity_decode(strip_tags($newsArtile['content'])),120);
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
		
		
		$tempSeo = array_reverse($this->seoKeys);
		$this->seo($tempSeo[0], implode(',',$tempSeo));
		
		$this->assign('breadcrumb',$this->breadcrumb());
		$this->display();
		
	}
	
	
	private function _breadCrumbLinks($currentAcId){
		
		if(empty($currentAcId)){
			$currentAcId = $this->topClassId;
		}
		
		if($currentAcId){
			$parents = $this->cms_service->getParentsById($currentAcId);
			if($parents){
				$parents = array_reverse($parents);
			}
			
			foreach($parents as $pitem){
				$this->seoKeys[] = $pitem['name'];
				$this->_navigation[$pitem['name']] = site_url("news/plist/{$pitem['ac_id']}/{$pitem['id']}.html");
			}
		}
		
	}
	
	
	
	public function detail(){
		
		$id = $this->input->get_post('id');
		$ac_id = $this->input->get_post('ac_id');
		$info = $this->Cms_Article_Model->getFirstByKey($id,'id');
		
		
		if($info){
			
			if($info['article_click'] == 0){
				$info['article_click']++;
			}
			
			
			$this->_breadCrumbLinks($info['ac_id']);
			$nextArticle = $this->cms_service->getNextByArticle($info);
			$preArticle = $this->cms_service->getPreByArticle($info);
			if($nextArticle && empty($nextArticle['jump_url'])){
				$nextArticle['jump_url'] = site_url("news/detail/{$nextArticle['ac_id']}/{$nextArticle['id']}.html");
			}
			
			if($preArticle && empty($preArticle['article_url'])){
				$preArticle['jump_url'] = site_url("news/detail/{$preArticle['ac_id']}/{$preArticle['id']}.html");
			}
			
			//print_r($preArticle);
			$this->assign('nextArticle',$nextArticle);
			$this->assign('preArticle',$preArticle);
			
			$this->Cms_Article_Model->increseOrDecrease(array(
				array('key' => 'article_click','value'=> 'article_click + 1')
			),array('id' => $id));
			
		}else{
			$this->_breadCrumbLinks($ac_id);
		}
		
		$tempSeo = array_reverse($this->seoKeys);
		$this->seo($info['article_title'] .' - '.$tempSeo[0], implode(',',$tempSeo));
		
		$this->assign('breadcrumb',$this->breadcrumb());
		$this->assign('info',$info);
		
		$this->display();
		
		
	}
	
}
