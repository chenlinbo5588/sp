<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seo extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Admin_service','Goods_service'));
		
		$this->_subNavs = array(
			'modulName' => 'SEO设置',
			'subNavs' => array(
				'基本设置' => 'seo/index' ,
			),
		);
	}
	
	
	private function _clearCache(){
		$this->getCacheObject()->delete(CACHE_KEY_SiteSetting);
	}
	
	
	public function index(){
		$feedback = '';
		
		$selectedGroup = 'index';
		$this->load->library(array('Seo_service'));
		
		
		if($this->isPostRequest()){
			
			$rows = 0;
			
			if($_POST['SEO']){
				foreach($_POST['SEO'] as $key => $value){
					$selectedGroup = $key;
					break;
				}
			
				$rows = $this->seo_service->updateSeo($_POST['SEO']);
				
				$this->getCacheObject()->delete(CACHE_KEY_SeoSetting);
			}else{
				
				if($this->input->post('category') != '' && $this->input->post('form_name') == 'category'){
					$selectedGroup = $this->input->post('form_name');
					
					$rows = $this->goods_service->getGoodsClassModel()->updateGoodsClassSeoById($this->input->post('category'),array(
						'gc_title' => $this->input->post('cate_title'),
						'gc_keywords' => $this->input->post('cate_keywords'),
						'gc_description' => $this->input->post('cate_description'),
					));
				}
			}
			
			if($rows >= 0){
				$feedback = getSuccessTip('保存成功');
			}else{
				$feedback = getErrorTip('保存失败');
			}
			
			
		}
		
		$currentSetting = $this->seo_service->getCurrentSeoSetting();
		$goodsClassHTML = $this->goods_service->getGoodsClassTreeHTML();
		
		//print_r($currentSetting);
		
		$this->assign('currentSetting',$currentSetting);
		$this->assign('goodsClassHTML',$goodsClassHTML);
		$this->assign('selectedGroup',$selectedGroup);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	public function ajax_category(){
		$goodsClassInfo = $this->goods_service->getGoodsClassModel()->getGoodsClassById($this->input->get('id'));
		$this->jsonOutput('获取成功',$goodsClassInfo);
	}
}
