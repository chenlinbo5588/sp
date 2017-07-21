<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dept extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Attachment_service'));
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']);
		
		$this->load->model('Dept_Model');
		
		$this->_subNavs = array(
			'modulName' => '办事机构',
			'subNavs' => array(
				'管理' => 'dept/index' ,
				'添加' => 'dept/add' ,
			),
		);
	}
	
	
	
	public function index(){
		
		
		
		if($this->input->is_ajax_request() && $this->isPostRequest()){
			
			$this->form_validation->set_rules('fieldname','状态字段','required|in_list[status]');
			$this->form_validation->set_rules('enabled','状态','required|in_list[0,1]');
			
			if($this->form_validation->run()){
				
				$upInfo[$this->input->post('fieldname')] = $this->input->post('enabled');
				
				$this->Dept_Model->update($upInfo,array('id' => $this->input->post('id')));
				
				$this->jsonOutput('保存成功');
				
			}else{
				$this->jsonOutput('保存失败 '.$this->form_validation->error_string());
			}
			
		}else{
			$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
			$condition = array(
				'order' => 'id DESC',
				'pager' => array(
					'page_size' => 8,
					'current_page' => $currentPage,
					'call_js' => 'search_page',
					'form_id' => '#formSearch'
				)
			);
			
			$name = $this->input->get_post('name');
			
			if($name){
				$condition['like']['name'] = $name;
			}
			
			$list = $this->Dept_Model->getList($condition);
			
			
			$this->assign('list',$list);
			$this->assign('page',$list['pager']);
			$this->assign('currentPage',$currentPage);
			
			$this->display();
		}
		
	}
	
	
	private function _getRules(){
		$this->form_validation->set_rules('name','办事机构全称','required');
		$this->form_validation->set_rules('short_name','办事机构简称','required');
		$this->form_validation->set_rules('status','是否启用','required|in_list[0,1]');
		
		if($this->input->post('displayorder')){
			$this->form_validation->set_rules('displayorder','排序',"is_natural|less_than[256]");
		}
	}
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Dept_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功');
		}else{
			$this->jsonOutput('请求非法');
			
		}
	}
	
	
	private function _prepareData(){
		
		$fileInfo = $this->attachment_service->addImageAttachment('logo_pic',array(
			/*
			'min_width' => 80,
			'min_height' => 80,
			'max_width' => 150,
			'max_height' => 150,*/
		),FROM_BACKGROUND,'dept');
		
		//print_r($fileInfo);
		$info = array(
			'code' => strtoupper($this->input->post('code')),
			'name' => $this->input->post('name'),
			'short_name' => $this->input->post('short_name'),
			'status' => $this->input->post('status'),
			'displayorder' => $this->input->post('displayorder') ? intval($this->input->post('displayorder')) : 255
		);
		
		
		
		if($fileInfo){
			$info['logo_pic'] = $fileInfo['file_url'];
		}
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		//print_r($_FILES);
		if($this->isPostRequest()){
			$this->_getRules();
			
			$this->form_validation->set_rules('code','机构代码','required|alpha|max_length[3]|is_unique['.$this->Dept_Model->getTableRealName().'.code]');
			
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData();
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated());
				
				if(($newid =$this->Dept_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Dept_Model->getFirstByKey($newid,'id');
				
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
		
		$info = $this->Dept_Model->getFirstByKey($id);
		$this->_subNavs['subNavs']['编辑'] = 'dept/edit?id='.$id;
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('code','机构代码','required|alpha|max_length[3]|is_unique_not_self['.$this->Dept_Model->getTableRealName().'.code.id.'.$id.']');
			$this->_getRules();
			
			
			for($i = 0; $i < 1; $i++){
				$orignalImage = $info['logo_pic'];
				$info = $this->_prepareData();
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Dept_Model->update($info,array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				
				if($info['logo_pic'] && $orignalImage){
					//重新上传了
					$this->attachment_service->deleteByFileUrl($orignalImage);
				}else{
					$info['logo_pic'] = $orignalImage;
				}
				
				
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display('dept/add');
	}
}
