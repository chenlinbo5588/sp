<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends Ydzj_Controller {
	
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
		
		
		$navigationInfo = $this->navigation_service->getInfoByName('产品中心');
		
		$this->load->library('Goods_service');
		$goodsClassTree = $this->goods_service->getGoodClassTree();
		
		$this->_navigation = array(
			$homeKey => base_url('/'),
			$navigationInfo[$nameKey] => base_url('product/plist.html'),
		);
		
		
		$this->assign(array(
			'currentModule' => 'product',
			'pgClass' => 'productPg',
			'sideNavs' => $goodsClassTree
		));
		
	}
	
	
	
	public function plist()
	{
		$currentGcId = $this->uri->rsegment(3);
		$keyword = $this->input->get_post('keyword') ? $this->input->get_post('keyword') : '';
		
		if(empty($currentGcId)){
			$currentGcId = 0;
		}
		
		
		$this->_breadCrumbLinks($currentGcId);
		$childIds = $this->goods_service->getAllChildGoodsClassByPid($currentGcId);
		$childIds[] = $currentGcId;
		
		//print_r($childIds);
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		$condition = array(
			'where' => array('goods_state' => 1, 'goods_verify' => 1),
			'where_in' => array(
				array(
					'key' => 'gc_id','value' => $childIds
				)
			),
			'order' => 'goods_id DESC',
			'pager' => array(
				'page_size' => 16,
				'current_page' => $currentPage,
				'form_id' => '#listForm',
				'base_link' => base_url("product/plist/{$currentGcId}.html?&keyword={$keyword}")
			)
		);
		
		
		
		if($keyword){
			
			if('english' == $this->_currentLang){
				$condition['like']['goods_name_en'] = $keyword;
			}else{
				$condition['like']['goods_name'] = $keyword;
			}
			
		}
		
		$list = $this->Goods_Model->getList($condition);
		
		//print_r($list);
		
		$cutLen = 300;
		if($this->agent->is_mobile()){
			$cutLen = 80;
		}
		
		
		if($list['data']){
			$count = 0;
			foreach($list['data'] as $key => $product){
				if('english' == $this->_currentLang){
					if($product['goods_name_en']){
						$product['goods_name'] = $product['goods_name_en'];
					}
					
					if(trim(strip_tags($product['goods_intro_en']))){
						$product['goods_intro'] = $product['goods_intro_en'];
						
					}
				}
				
				if($product['goods_pic']){
					$product['goods_pic'] = resource_url($product['goods_pic']);
				}else{
					$product['goods_pic'] = resource_url('img/default.jpg');
				}
				
				$list['data'][$key] = $product;
				
			}
		}
		
		
		$tempSeo = array_reverse($this->seoKeys);
		
		$seoTitle = '';
		if(empty($tempSeo)){
			$seoTitle = $this->_currentLang == 'english' ? 'Product Center' : '产品中心';
		}else{
			$seoTitle = implode(' - ',$tempSeo);
		}
		
		$this->seo(str_replace(
				array('{name} -', '{goods_class}'),
				array('',$seoTitle),$this->_seoSetting['product']['title']
			));
			
		$this->assign(
			array(
				'list' => $list,
				'page' => $list['pager'],
				'currentPage' => $currentPage,
				'currentGcId' => $currentGcId,
				'keyword'=>$keyword,
				'breadcrumb' => $this->breadcrumb(),
				'currentSideUrl' => base_url($this->uri->uri_string)
			)
		);
		
		$this->display();
	}
	
	
	private function _breadCrumbLinks($currentAcId){
		
		if(empty($currentAcId)){
			$currentAcId = 0;
		}
		
		if($currentAcId){
			$parents = $this->goods_service->getParentsById($currentAcId);
			if($parents){
				$parents = array_reverse($parents);
			}
			
			$nameKey = 'name_cn';
			if($this->_currentLang == 'english'){
				$nameKey = 'name_en';
			}
			
			
			foreach($parents as $pitem){
				$this->seoKeys[] = $pitem[$nameKey];
				$this->_navigation[$pitem[$nameKey]] = base_url("product/plist/{$pitem['gc_id']}.html");
			}
		}
		
	}
	
	
	
	public function detail(){
		
		//$id = $this->input->get_post('id');
		//$gc_id = $this->input->get_post('gc_id');
		
		$idInfo = $this->uri->rsegment(3);
		
		//print_r($idInfo);
		list($gc_id,$id )  = explode('_',$idInfo);
		
		$info = $this->Goods_Model->getFirstByKey($id,'goods_id');
		
		$nameKey = 'goods_name';
		$introKey = 'goods_intro';
		
		if('english' == $this->_currentLang && !empty($info['goods_name_en'])){
			$nameKey = 'goods_name_en';
		}
		
		if('english' == $this->_currentLang && !empty($info['goods_intro_en'])){
			$introKey = 'goods_intro_en';
		}
		
		if($info){
			if($info['goods_click'] == 0){
				$info['goods_click']++;
			}
			
			$currentFiles = $this->Goods_Images_Model->getList(array(
				'where' => array('goods_id' => $id)
			));
			
			if(empty($currentFiles) && empty($info['goods_pic'])){
				$info['goods_pic_b'] = 'img/default.jpg';
				$info['goods_pic'] = $info['goods_pic_b'];
			}
			
			
			$info['goods_name'] = $info[$nameKey];
			$info['goods_intro'] = $info[$introKey];
			
			$this->_breadCrumbLinks($info['gc_id']);
			
			$nextProduct = $this->goods_service->getNextByProduct($info);
			$preProduct = $this->goods_service->getPreByProduct($info);
			
			if($nextProduct){
				$nextProduct['url'] = base_url("product/detail/{$nextProduct['gc_id']}_{$nextProduct['goods_id']}.html");
				$this->assign('nextProduct',$nextProduct);
			}
			
			if($preProduct){
				$preProduct['url'] = base_url("product/detail/{$preProduct['gc_id']}_{$preProduct['goods_id']}.html");
				$this->assign('preProduct',$preProduct);
			}
			
			$this->assign('imgList',$currentFiles);
			
			$this->_navigation[$info[$nameKey]] = base_url('product/detail/'.$info['gc_id'].'_'.$info['goods_id'].'.html');
			
			$this->Goods_Model->increseOrDecrease(array(
				array('key' => 'goods_click','value'=> 'goods_click + 1')
			),array('goods_id' => $id));
		}else{
			$this->_breadCrumbLinks($gc_id);
		}
		
		$tempSeo = array_reverse($this->seoKeys);
		
		$this->seo(str_replace(
				array('{name}','{goods_class}'),
				array($info[$nameKey],implode(' - ',$tempSeo)),$this->_seoSetting['product']['title']
			));
		
		$this->assign('breadcrumb',$this->breadcrumb());
		$this->assign('info',$info);
		
		$this->display();
		
		
	}
	
}
