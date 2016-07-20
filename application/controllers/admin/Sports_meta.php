<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sports_meta extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Sports_service'));
	}
	
	
	
	public function index(){
		
		
		$cateList = $this->sports_service->getSportsCategory();
		
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		$where = array();
		$category_name = $this->input->get_post('category_name');
		if($category_name){
			$where['category_name'] = $category_name;
		}
		
		$gname = $this->input->get_post('gname');
		if($gname){
			$where['gname'] = $gname;
		}
		
		
		$condition = array(
			'where' => $where,
			'order' => 'category_name ASC , gname ASC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$search_name = $this->input->get_post('search_name');
		
		if($search_name){
			$condition['like']['name'] = $search_name;
		}
		
		
		//print_r($condition);
		$list = $this->Sports_Meta_Model->getList($condition);
		
		$this->assign('cateList',$cateList);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
	
	
	
	private function _getRules(){
		$this->form_validation->set_rules('category_name','项目分类','required');
		
		$gname = trim($this->input->post('gname'));
		$newgname = trim($this->input->post('newgname'));
		
		if(empty($gname) && empty($newgname)){
			$this->form_validation->set_rules('gname','分组名称','required');
		}else if(empty($gname) && !empty($newgname)){
			//验证 新增加的
			$this->form_validation->set_rules('newgname','新分组名称','required');
			
		}else if(!empty($gname) && empty($newgname)){
			$this->form_validation->set_rules('gname','分组名称','required');
		}
		
		$this->form_validation->set_rules('name','名称','required|min_length[1]|max_length[30]');
		$this->form_validation->set_rules('status','是否显示','required|in_list[0,1]');
		
		if($this->input->post('meta_sort')){
			$this->form_validation->set_rules('meta_sort','排序',"is_natural|less_than[256]");
		}
	}
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Sports_Cate_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	private function _prepareData(){
		$info = array(
			'category_name' => $this->input->post('category_name'),
			'gname' => $this->input->post('gname') ? $this->input->post('gname') : '',
			'newgname' => $this->input->post('newgname') ? $this->input->post('newgname') : '',
			'name' => $this->input->post('name'),
			'status' => $this->input->post('status') ? $this->input->post('status') : 0,
			'meta_sort' => $this->input->post('meta_sort') ? $this->input->post('meta_sort') : 255,
		);
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		$info = array();
		
		
		$groupList = array();
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			$info = $this->_prepareData();
			
			
			
			for($i = 0; $i < 1; $i++){
				if($info['category_name']){
					$groupList = $this->sports_service->getGroupByCategory($info['category_name']);
				}
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($info['newgname']){
					$info['gname'] = $info['newgname'];
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Sports_Meta_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				$feedback = getSuccessTip('保存成功');
				$info = $this->Sports_Meta_Model->getFirstByKey($newid);
				$groupList = $this->sports_service->getGroupByCategory($info['category_name']);
				
			}
		}else{
			$info['status'] = 1; 
		}
		
		$cateList = $this->sports_service->getSportsCategory();
		$this->assign('groupList',$groupList);
		$this->assign('cateList',$cateList);
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	public function getgroup(){
		$categoryName = trim($this->input->get_post('category_name'));
		
		if($categoryName){
			$list = $this->sports_service->getGroupByCategory($categoryName);
			$this->jsonOutput('success',$list);
			
		}else{
			$this->jsonOutput('success');
		}
	}
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$cateList = $this->sports_service->getSportsCategory();
		$info = $this->Sports_Meta_Model->getFirstByKey($id);
		
		if($info['category_name']){
			$groupList = $this->sports_service->getGroupByCategory($info['category_name']);
		}
				
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareData('edit');
				$info['id'] = $id;
		
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Sports_Meta_Model->update($info,array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				//刷新
				$groupList = $this->sports_service->getGroupByCategory($info['category_name']);
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		
		
		$this->assign('groupList',$groupList);
		$this->assign('cateList',$cateList);
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display('sports_meta/add');
	}
	
	
	/**
	 * 
	 */
	public function onoff(){
		if($this->input->is_ajax_request() && $this->isPostRequest()){
			
			$this->form_validation->set_rules('fieldname','状态字段','required|in_list[status]');
			$this->form_validation->set_rules('enabled','状态','required|in_list[0,1]');
			
			if($this->form_validation->run()){
				
				$upInfo[$this->input->post('fieldname')] = $this->input->post('enabled');
				
				$this->Sports_Meta_Model->update($upInfo,array('id' => $this->input->post('id')));
				
				$this->jsonOutput('保存成功', $this->getFormHash());
				
			}else{
				$this->jsonOutput('保存失败 '.$this->form_validation->error_string(),$this->getFormHash());
			}
		}
	}
	
}