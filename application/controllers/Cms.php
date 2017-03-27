<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Ydzj_Controller {
	
	private $topClassId = 0;
	private $seoKeys = array();
	
	public function __construct(){
		parent::__construct();
		
		$homeKey = '首页';
		$urlKey = 'url_cn';
		$nameKey = 'name_cn';
		
		if($this->_currentLang == 'english'){
			$homeKey = 'Home';
			$urlKey = 'url_en';
			$nameKey = 'name_en';
		}
		
		
		$navigationInfo = $this->navigation_service->getInfoByName('新闻资讯');
		
		$this->load->library(array('Cms_service'));
		$articleClassTree = $this->cms_service->getArticleClassTree();
		
		
		$this->_navigation = array(
			$homeKey => base_url('/'),
			$navigationInfo[$nameKey] => base_url('cms/plist.html'),
		);
		
		
		$this->assign(array(
			'currentModule' => 'cms',
			'pgClass' => 'cmsPg',
			'sideNavs' => $articleClassTree,
		));
		
	}
	
	
	
	public function plist()
	{
		$currentAcId = $this->uri->rsegment(3);
		//print_r($param);
		
		if(empty($currentAcId)){
			$currentAcId = $this->topClassId;
		}
		
		$this->_breadCrumbLinks($currentAcId);
		$childIds = $this->cms_service->getAllChildArticleClassByPid($currentAcId);
		
		$childIds[] = $currentAcId;
		
		//print_r($childIds);
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$keyword = $this->input->get_post('keyword');
		
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
				'base_link' => base_url("cms/plist/{$currentAcId}.html?keyword={$keyword}")
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
					$newsArtile['jump_url'] = base_url("cms/detail/{$newsArtile['id']}.html");
				}
				
				if(trim($newsArtile['digest'])){
					$newsArtile['digest'] = cutText(html_entity_decode(strip_tags($newsArtile['content'])),120);
				}
				
				$list['data'][$key] = $newsArtile;
			}
		}
		
		
		
		$tempSeo = array_reverse($this->seoKeys);
		$seoTitle = '';
		if(empty($tempSeo)){
			$seoTitle = '新闻资讯';
		}else{
			$seoTitle = implode(' - ',$tempSeo);
		}
		
		$this->seo(str_replace(
				array('{article_class}'),
				array($seoTitle),$this->_seoSetting['article']['title']
			));
		
		$this->assign(
			array(
				'list' => $list,
				'page' => $list['pager'],
				'currentPage' => $currentPage,
				'currentAcId' => $currentAcId,
				'keyword'=>$keyword,
				'breadcrumb' => $this->breadcrumb(),
				'currentSideUrl' => base_url($this->uri->uri_string)
			)
		);
		
		
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
			
			$nameKey = 'name_cn';
			if($this->_currentLang == 'english'){
				$nameKey = 'name_en';
			}
			
			//print_r($parents);
			foreach($parents as $pitem){
				$this->seoKeys[] = $pitem[$nameKey];
				$this->_navigation[$pitem[$nameKey]] = base_url("cms/plist/{$pitem['id']}.html");
				
			}
		}
		
	}
	
	
	
	public function detail(){
		$id = $this->uri->rsegment(3);
		
		$info = $this->Cms_Article_Model->getFirstByKey($id,'id');
		
		if($info){
			
			if($info['article_click'] == 0){
				$info['article_click']++;
			}
			
			
			$this->_breadCrumbLinks($info['ac_id']);
			$nextArticle = $this->cms_service->getNextByArticle($info);
			$preArticle = $this->cms_service->getPreByArticle($info);
			
			//print_r($preArticle);
			
			if($nextArticle && empty($nextArticle['jump_url'])){
				$nextArticle['article_url'] = base_url("cms/detail/{$nextArticle['id']}.html");
			}
			
			if($preArticle && empty($preArticle['jump_url'])){
				$preArticle['article_url'] = base_url("cms/detail/{$preArticle['id']}.html");
			}
			
			//print_r($preArticle);
			$this->assign('nextArticle',$nextArticle);
			$this->assign('preArticle',$preArticle);
			
			$this->_navigation[$info['article_title']] = base_url('cms/detail/'.$info['id'].'.html');
			
			
			$this->Cms_Article_Model->increseOrDecrease(array(
				array('key' => 'article_click','value'=> 'article_click + 1')
			),array('id' => $id));
			
		}else{
			$this->_breadCrumbLinks($info['ac_id']);
		}
		
		$tempSeo = array_reverse($this->seoKeys);
		
		$this->seo(str_replace(
			array('{name}','{article_class}'),
			array($info['article_title'],implode(' - ',$tempSeo)),$this->_seoSetting['article_content']['title']
		));
		
		$this->assign('breadcrumb',$this->breadcrumb());
		$this->assign('info',$info);
		
		$this->display();
		
		
	}
	
}
