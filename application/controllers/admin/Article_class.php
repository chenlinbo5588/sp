<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_Class extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Article_service'));
	}
	
	public function category(){
		
		$id = $this->input->get_post('ac_parent_id') ? $this->input->get_post('ac_parent_id') : 0;
		
		$treelist = $this->article_service->getArticleClassTreeHTML();
		$deep = 0;
		
		
		//print_r($list);
		$parentId = 0;
		foreach($treelist as $item){
			if($id == $item['ac_id']){
				$deep = $item['level'];
				$parentId = $item['ac_parent_id'];
			}
			
		}
		
		$list = $this->article_service->getArticleClassByParentId($id);
		$this->assign('list',$list);
		$this->assign('parentId',$parentId);
		$this->assign('deep',$deep + 1);
		$this->assign('id',$id);
		
		$this->display();
	}
	
	
	public function getNavUrl(){
		$id = $this->input->get_post('ac_id');
		$info = $this->Article_Class_Model->getById(array(
			'where' => array(
				'ac_id' => intval($id)
			
			)
		));
		
		if(empty($info)){
			$this->jsonOutput('',array(
				'name' => '文章列表',
				'url' => base_url('article/plist.html'),
			));
		}else{
			$this->jsonOutput('',array(
				'name' => $info['name'],
				'url' => base_url('article/plist/'.$info['ac_id'].'.html'),
			));
		}
	}
	
	
	
	
	
	
	
	public function delete(){
		
		$delId = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			if(is_array($delId)){
				$delId = $delId[0];
			}
			
			$this->article_service->deleteArticleClass($delId);
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	public function add(){
		
		$feedback = '';
		$treelist = $this->article_service->getArticleClassTreeHTML();
		$ac_parent_id = $this->input->get_post('ac_parent_id');
		
		
		if($ac_parent_id){
			$info['ac_parent_id'] = $ac_parent_id;
		}
		
		if($this->isPostRequest()){
			$this->_getRules('add');
			
			for($i = 0; $i < 1; $i++){
				
				$info = array(
					'name' => $this->input->post('name'),
					'ac_code' => strtolower(trim($this->input->post('ac_code'))),
					'ac_parent_id' => $this->input->post('ac_parent_id') ? $this->input->post('ac_parent_id') : 0,
					'ac_sort' => $this->input->post('ac_sort') ? $this->input->post('ac_sort') : 255
				);
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if(($newid = $this->Article_Class_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Article_Class_Model->getFirstByKey($newid,'ac_id');
			}
		}
		
		
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback,
			'list' => $treelist,
		));
		
		$this->display();
	}
	
	
	/**
	 * 文章分类子目录代码，在相同的父级列表中互不相同
	 */
	public function checkaccode($accode,$extra = ''){
		
		
		/*
		$pid = $this->input->post('ac_parent_id');
		$acid = $this->input->post('ac_id');
		
		
		$data = $this->Article_Class_Model->getList(array(
			'where' => array(
				'ac_code' => strtolower($accode),
			)
		));
		
		if($extra == 'add'){
			if(empty($data)){
				return true;
			}else{
				$this->form_validation->set_message('checkaccode','%s 已经存在');
				return false;
			}
			
		}else{
			if(empty($data)){
				return true;
			}else{
				foreach($data as $item){
					$item['ac_parent_id'] == $pid;
				}
			}
			
			if(!empty($data[0]) && $data[0]['ac_id'] == $acid){
				return true;
			}else{
				$this->form_validation->set_message('checkaccode',' %s 已经存在');
				return false;
			}
		}
		*/
		
	}
	
	
	public function checkpid($pid, $extra = ''){
		//不能是自己，也不能是其下级分类
		$currentGcId = $this->input->post('ac_id');
		
		$deep = $this->article_service->getArticleClassDeepById($pid);
		
		if($deep >=3){
			$this->form_validation->set_message('checkpid','父级只能是一级分类或者二级分类');
			return false;
		}
		
		if($extra == 'add'){
			//如果是增加的不需要再网后面继续执行了
			return true;
		}
		
		
		$list = $this->Article_Class_Model->getList(array(
			'where' => array('ac_parent_id' => $currentGcId)
		));
		
		$subIds = array($currentGcId);
		$hasData = true;
		
		while($list && $hasData){
			
			$ids = array();
			foreach($list as $item){
				$subIds[] = $item['ac_id'];
				$ids[] = $item['ac_id'];
			}
			
			if($ids){
				$hasData = false;
			}else{
				$list = $this->Article_Class_Model->getList(array(
					'where_in' => array(
						array('key' => 'ac_parent_id', 'value' => $ids)
					)
				));
			}
		}
		
		//print_r($subIds);
		if(in_array($pid,$subIds)){
			$this->form_validation->set_message('checkpid','父级不能选择自己和自己的下级分类');
			return false;
		}else{
			
			return true;
		}
		
	}
	
	
	private function _getRules($action = 'add'){
		
		$this->form_validation->set_rules('name','文章分类名称',"required");
		$this->form_validation->set_rules('ac_code','文章分类目录代码',"required|alpha_numeric");
		
		
		if($this->input->post('ac_parent_id')){
			$this->form_validation->set_rules('ac_parent_id','上级分类', "in_db_list[".$this->Article_Class_Model->getTableRealName().".ac_id]|callback_checkpid[{$action}]");
		}
		
		
		if($this->input->post('ac_sort')){
			$this->form_validation->set_rules('ac_sort','排序',"is_natural|less_than[256]");
		}
		
	}
	
	
	public function edit(){
		
		$feedback = '';
		$treelist = $this->article_service->getArticleClassTreeHTML();
		$ac_id = $this->input->get_post('ac_id');
		
		$info = $this->Article_Class_Model->getFirstByKey($ac_id,'ac_id');
		
		if($this->isPostRequest()){
			
			$this->_getRules('edit');
			
			for($i = 0; $i < 1; $i++){
				
				$info = array(
					'ac_id' => $ac_id,
					'name' => $this->input->post('name'),
					'ac_code' => strtolower(trim($this->input->post('ac_code'))),
					'ac_parent_id' => $this->input->post('ac_parent_id') ? $this->input->post('ac_parent_id') : 0,
					'ac_sort' => $this->input->post('ac_sort') ? $this->input->post('ac_sort') : 255
				);
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				if($this->Article_Class_Model->update($info,array('ac_id' => $ac_id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback,
			'list' => $treelist,
		));
		
		
		$this->display('article_class/add');
	}
	
}
