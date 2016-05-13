<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends Ydzj_Controller {
	
	private $sideNavs = null;
	private $modKey = '产品中心';
	
	
	public function __construct(){
		parent::__construct();
		$this->assign('pgClass',strtolower(get_class()).'Pg');
		$this->assign('sideTitle',$this->modKey);
		
		$this->load->library('Goods_Service');
		
		$topClass = $this->Goods_Class_Model->getList(array(
			'where' => array(
				'gc_name' => $this->modKey,
				'gc_parent_id' => 0
			)
		));
		
		if($topClass[0]){
			$this->topClassId = $topClass[0]['gc_id'];
			
			$sideNavs = $this->Goods_Class_Model->getList(array(
				'where' => array(
					'gc_parent_id' => $this->topClassId
				)
			));
			
			if($sideNavs){
				foreach($sideNavs as $nav){
					$this->sideNavs[$nav['gc_name']] = site_url('product/plist/?gc_id=').$nav['gc_id'];
				}
			}
		}
		
		
		$this->assign('sideNavs',$this->sideNavs);
	}
	
	
	
	public function plist()
	{
		$keyword = $this->input->get_post('keyword') ? $this->input->get_post('keyword') : '';
		$currentGcId = $this->input->get_post('gc_id');
		
		if(empty($currentGcId)){
			$currentGcId = $this->topClassId;
		}
		
		$this->_breadCrumbLinks($currentGcId);
		$childIds = $this->goods_service->getAllChildGoodsClassByPid($currentGcId);
		$childIds[] = $currentGcId;
		
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
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'form_id' => '#listForm',
				'anchor' => 'listmao',
				'base_link' => site_url('product/plist/?')."gc_id={$currentGcId}&keyword={$keyword}"
			)
		);
		
		if($keyword){
			$condition['like']['goods_name'] = $keyword;
		}
		
		$list = $this->Goods_Model->getList($condition);
		
		
		if($list['data']){
			$count = 0;
			foreach($list['data'] as $key => $product){
				if($product['goods_pic']){
					$product['goods_pic'] = resource_url($product['goods_pic']);
				}else{
					$product['goods_pic'] = resource_url('img/default.jpg');
				}
				$product['digest'] = cutText(html_entity_decode(strip_tags($product['goods_intro'])),300);
				$list['data'][$key] = $product;
				
			}
		}
		
		
		//print_r($list);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		$this->assign('currentGcId',$currentGcId);
		$this->assign('keyword',$keyword);
		
		$this->assign('breadcrumb',$this->breadcrumb());
		$this->display();
	}
	
	
	private function _breadCrumbLinks($currentAcId){
		
		if(empty($currentAcId)){
			$currentAcId = $this->topClassId;
		}
		
		if($currentAcId){
			$parents = $this->goods_service->getParentsById($currentAcId);
			if($parents){
				$parents = array_reverse($parents);
			}
			
			foreach($parents as $pitem){
				$this->_navigation[$pitem['gc_name']] = site_url('product/plist?id='.$pitem['gc_id']);
			}
		}
		
	}
	
	
	
	public function detail(){
		
		$id = $this->input->get_post('id');
		$gc_id = $this->input->get_post('gc_id');
		$info = $this->Goods_Model->getFirstByKey($id,'goods_id');
		if($info){
			$this->_breadCrumbLinks($info['gc_id']);
			$nextProduct = $this->goods_service->getNextByProduct($info);
			$preProduct = $this->goods_service->getPreByProduct($info);
			
			$this->assign('nextProduct',$nextProduct);
			$this->assign('preProduct',$preProduct);
		}else{
			$this->_breadCrumbLinks($gc_id);
		}
		
		$this->assign('breadcrumb',$this->breadcrumb());
		$this->assign('info',$info);
		
		$this->display();
		
		
	}
	
}
