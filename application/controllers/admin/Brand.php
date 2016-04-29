<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Brand extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model(array('Brand_Model'));
		$this->load->library(array('Goods_service','Attachment_Service'));
	}
	
	
	
	public function index(){
		
		if($this->input->is_ajax_request() && $this->isPostRequest()){
			
			$this->form_validation->set_rules('fieldname','状态字段','required|in_list[brand_recommend]');
			$this->form_validation->set_rules('enabled','状态','required|in_list[0,1]');
			
			if($this->form_validation->run()){
				
				$upInfo[$this->input->post('fieldname')] = $this->input->post('enabled');
				
				$this->Brand_Model->update($upInfo,array('brand_id' => $this->input->post('id')));
				
				$this->jsonOutput('保存成功', $this->getFormHash());
				
			}else{
				$this->jsonOutput('保存失败 '.$this->form_validation->error_string(),$this->getFormHash());
			}
			
		}else{
			
			$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
			$condition = array(
				'order' => 'brand_id DESC',
				'pager' => array(
					'page_size' => config_item('page_size'),
					'current_page' => $currentPage,
					'call_js' => 'search_page',
					'form_id' => '#formSearch'
				)
			);
			
			
			$brandName = $this->input->get_post('search_brand_name');
			
			if($brandName){
				$condition['like']['brand_name'] = $brandName;
			}
			
			$brandClass = $this->input->get_post('search_brand_class');
			if($brandClass){
				$condition['like']['brand_class'] = $brandClass;
			}
			
			$list = $this->Brand_Model->getList($condition);
			
			
			$this->assign('list',$list);
			$this->assign('page',$list['pager']);
			$this->assign('currentPage',$currentPage);
			
			$this->display();
		}
		
	}
	
	
	private function _getRules(){
		$this->form_validation->set_rules('brand_name','品牌名称','required');
		$this->form_validation->set_rules('brand_recommend','是否推线','required|in_list[0,1]');
		
		if($this->input->post('brand_sort')){
			$this->form_validation->set_rules('brand_sort','排序',"is_natural|less_than[256]");
		}
		
	}
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Brand_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'brand_id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	private function _prepareBrandData(){
		$fileInfo = $this->attachment_service->addImageAttachment('brand_logo',array(),FROM_BACKGROUND);
		
		//print_r($fileInfo);
		$info = array(
			'brand_name' => $this->input->post('brand_name'),
			'class_id' => $this->input->post('class_id') ? $this->input->post('class_id') : 0,
			'brand_sort' => $this->input->post('brand_sort') ? $this->input->post('brand_sort') : 255
		);
		
		
		if(empty($info['brand_sort'])){
			$info['brand_sort'] = 0;
		}
		
		if($fileInfo){
			$info['brand_pic'] = $fileInfo['file_url'];
			
			$originalPic = $this->input->post('old_pic');
			
			if($originalPic){
				$this->attachment_service->deleteByFileUrl($originalPic);
			}
		}
		
		
		if($info['class_id']){
			$goodsClassInfo = $this->Goods_Class_Model->getFirstByKey($info['class_id'],'gc_id');
			$info['brand_class'] = $goodsClassInfo['gc_name'];
		}
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		
		//print_r($_FILES);
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareBrandData();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if(($newid =$this->Brand_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Brand_Model->getFirstByKey($newid,'brand_id');
				
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('list',$treelist);
		$this->display();
	}
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('brand_id');
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		
		$info = $this->Brand_Model->getFirstByKey($id,'brand_id');
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareBrandData();
				$info['brand_id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				
				
				if($this->Brand_Model->update($info,array('brand_id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('list',$treelist);
		$this->display('brand/add');
	}
}
