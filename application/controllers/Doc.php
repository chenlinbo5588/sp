<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doc extends Ydzj_Controller {
	
	private $sideNavs = null;
	private $modKey = '';
	private $topClassId = 0;
	private $seoKeys = array();
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Article_service','Goods_service'));
		
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
	
	public function download(){
		
		$this->load->helper('download');
		
		
		//force_download();
		
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
		
		$articleIds = array();
		$fileAssoc = array();
		
		if($list['data']){
			foreach($list['data'] as $key => $newsArtile){
				$articleIds[] = $newsArtile['article_id'];
			}
			
			
			if($articleIds){
				$fileList = $this->Article_File_Model->getList(array(
					'where_in' => array(
						array('key' => 'article_id', 'value' => $articleIds)
					)
				));
				
				
				foreach($fileList as $afile){
					if(!isset($fileAssoc[$afile['article_id']])){
						$fileAssoc[$afile['article_id']] = array();
					}
					
					$fileAssoc[$afile['article_id']][] = $afile;
				}
			}
			
		}
		
		
		$this->load->helper('number');
		
		//print_r($fileAssoc);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('fileAssoc',$fileAssoc);
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
			$parents = $this->article_service->getParentsById($currentAcId);
			if($parents){
				$parents = array_reverse($parents);
			}
			
			foreach($parents as $pitem){
				$this->seoKeys[] = $pitem['ac_name'];
				$this->_navigation[$pitem['ac_name']] = site_url('doc/product_list?id='.$pitem['ac_id']);
			}
		}
	}
}
