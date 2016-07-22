<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sports_cate extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Sports_service','Attachment_service'));
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
		$list = $this->Sports_Category_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
	
	
	
	private function _getRules(){
		$this->form_validation->set_rules('status','是否显示','required|in_list[0,1]');
		
		$this->form_validation->set_rules('teamwork','参与形势','required|in_list[1,2,3]');
		if($this->input->post('cate_sort')){
			$this->form_validation->set_rules('cate_sort','排序',"is_natural|less_than[256]");
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
			
			$this->jsonOutput('删除成功');
		}else{
			$this->jsonOutput('请求非法');
			
		}
	}
	
	
	private function _prepareData($action = 'add'){
		$fileInfo = $this->attachment_service->addImageAttachment('sports_logo',array(),FROM_BACKGROUND,'sports_cate');
		
		$info = array(
			'name' => $this->input->post('name'),
			'status' => $this->input->post('status') ? $this->input->post('status') : 0,
			'cate_sort' => $this->input->post('cate_sort') ? $this->input->post('cate_sort') : 255,
			'aid'  => $this->input->post('aid') ? $this->input->post('aid') : 0,
			'logo_url'  => $this->input->post('logo_url') ? $this->input->post('logo_url') : '',
			'teamwork'  => $this->input->post('teamwork'),
		);
		
		if($fileInfo){
			//添加阶段 
			if($action == 'add' && $info['logo_url']){
				$this->attachment_service->deleteByFileUrl($info['logo_url']);
			}
			
			$info['logo_url'] = $fileInfo['file_url'];
			$info['aid'] = $fileInfo['id'];
		}
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		$info = array();
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','名称','required|min_length[1]|max_length[30]|is_unique['.$this->Sports_Category_Model->getTableRealName().'.name]');
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareData('add');
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Sports_Category_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				$feedback = getSuccessTip('保存成功');
				$info = $this->Sports_Category_Model->getFirstByKey($newid);
			}
		}else{
			
			$info['status'] = 1; 
			
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		$info = $this->Sports_Category_Model->getFirstByKey($id);
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','名称','required|min_length[1]|max_length[30]|is_unique_not_self['.$this->Sports_Category_Model->getTableRealName().'.name.id.'.$id.']');
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$infoPost = $this->_prepareData('edit');
				$infoPost['id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					$info = $infoPost;
					break;
				}
				
				//确定是否要删除文件
				$deleteFile = '';
				if($info['aid'] != $infoPost['aid']){
					$deleteFile = $info['logo_url'];
				}
				
				$info = array_merge($infoPost,$this->addWhoHasOperated('edit'));
				
				if($this->Sports_Category_Model->update($info,array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				if($deleteFile){
					$this->attachment_service->deleteByFileUrl($deleteFile);
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display('sports_cate/add');
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
				
				$this->Sports_Category_Model->update($upInfo,array('id' => $this->input->post('id')));
				
				$this->jsonOutput('保存成功');
				
			}else{
				$this->jsonOutput('保存失败 '.$this->form_validation->error_string());
			}
		}
	}
	
}