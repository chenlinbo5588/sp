<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_Article_Class extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Cms_service'));
	}
	
	public function index(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array(),
			'order' => 'id ASC',
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
		$list = $this->Cms_Article_Class_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
	
	
	
	private function _getRules(){
		
		$this->form_validation->set_rules('name','名称','required|min_length[1]|max_length[30]');
		$this->form_validation->set_rules('list_tplname','文章列表模版名称','required|min_length[1]|max_length[30]');
		$this->form_validation->set_rules('detail_tplname','文章详情页模版名称','required|min_length[1]|max_length[30]');
		
		$this->form_validation->set_rules('status','开启状态','required|in_list[0,1]');
		
		if($this->input->post('ac_sort')){
			$this->form_validation->set_rules('ac_sort','排序',"is_natural|less_than[256]");
		}
	}
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Cms_Article_Class_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功');
		}else{
			$this->jsonOutput('请求非法');
			
		}
	}
	
	
	private function _prepareData($action = 'add'){
		$info = array(
			'name' => $this->input->post('name'),
			'list_tplname' => $this->input->post('list_tplname'),
			'detail_tplname' => $this->input->post('detail_tplname'),
			'status' => $this->input->post('status') ? $this->input->post('status') : 0,
			'ac_sort' => $this->input->post('ac_sort') ? $this->input->post('ac_sort') : 255,
		);
		
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		$info = array();
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareData('add');
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Cms_Article_Class_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				$feedback = getSuccessTip('保存成功');
				$info = $this->Cms_Article_Class_Model->getFirstByKey($newid);
			}
		}else{
			$info['status'] = 1; 
			$info['list_tplname'] = 'article_list'; 
			$info['detail_tplname'] = 'article'; 
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		$info = $this->Cms_Article_Class_Model->getFirstByKey($id);
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$infoPost = $this->_prepareData('edit');
				$infoPost['id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					$info = $infoPost;
					break;
				}
				
				$info = array_merge($infoPost,$this->addWhoHasOperated('edit'));
				
				if($this->Cms_Article_Class_Model->update($info,array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display('cms_article_class/add');
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
				
				$this->Cms_Article_Class_Model->update($upInfo,array('id' => $this->input->post('id')));
				
				$this->jsonOutput('保存成功');
				
			}else{
				$this->jsonOutput('保存失败 '.$this->form_validation->error_string());
			}
		}
	}
	
}