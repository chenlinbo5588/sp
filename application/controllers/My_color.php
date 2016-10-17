<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_color extends MyYdzj_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Inventory_service');
	}
	
	
	public function index()
	{
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$colorName = trim($this->input->get_post('color_name',true));
		
		
		$pageParam = array(
			'page_size' => 50,
			'current_page' => intval($currentPage),
			'call_js' => 'search_page',
			'form_id' => '#formSearch',
		);
		
		$condition = array(
			'where' => array(
				'uid' => $this->_loginUID
			),
			'order' => 'gmt_create DESC',
			'pager' => $pageParam
		);
		
		
		if($colorName){
			$condition['where']['color_name'] = $colorName;
		}
		
		$list = $this->inventory_service->getColorList($condition,$this->_loginUID);
		$this->assign('page',$list['pager']);
		$this->assign('list',$list['data']);
		$this->display();
	}
	
	
	
	public function colorcheck($colorname,$id = ''){
		$info = $this->inventory_service->getColorByName($colorname, $this->_loginUID);
		if($info){
			
			if($id && $info['id'] == $id){
				
				//如果是自己就通过
				return true;
			}
			
			
			$this->form_validation->set_message('colorcheck', "名称{$colorname}已经存在");
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * 添加颜色
	 */
	public function add(){
		$name = trim($this->input->post('color_name'));
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_error_delimiters('<label>','</label>');
				
				
				$this->form_validation->set_rules('color_name','颜色名称', 'required|min_length[1]|max_length[30]|callback_colorcheck');
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_string('',''),$this->getFormHash());
					break;
				}
				//print_r($_POST);
				$this->inventory_service->addColor($name,$this->_loginUID);
				
				$this->jsonOutput('添加成功');
			}
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	/**
	 * 添加颜色
	 */
	public function edit(){
		$name = trim($this->input->post('color_name'));
		$id = $this->input->post('id');
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->form_validation->set_error_delimiters('<label>','</label>');
				$this->form_validation->set_rules('color_name','颜色名称','required|min_length[1]|max_length[30]|callback_colorcheck['.$id.']');
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_string('',''),$this->getFormHash());
					break;
				}
				
				$this->inventory_service->editColor($name,$id,$this->_loginUID);
				
				$this->jsonOutput('编辑成功');
			}
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	/**
	 * 删除 颜色
	 */
	public function delete(){
		$id = $this->input->post('id');
		if($id && $this->isPostRequest()){
			$rows = $this->inventory_service->deleteUserColor($id,$this->_loginUID);
			$this->jsonOutput('删除成功',array('id' => $id));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
}
