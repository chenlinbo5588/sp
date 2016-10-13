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
		$pageParam = array(
			'page_size' => config_item('page_size'),
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
		
		$list = $this->inventory_service->getColorList($condition,$this->_loginUID);
		$this->assign('page',$list['pager']);
		$this->assign('list',$list['data']);
		$this->display();
	}
	
	
	
	public function colorcheck($colorname){
		$flag = $this->inventory_service->isColorExists($colorname,$this->_loginUID);
		
		if($flag == true){
			$this->form_validation->set_message('colorcheck', "{$colorname}已设置");
			return false;
		}else{
			return true;
		}
		
	}
	
	/**
	 * 添加颜色
	 */
	public function add(){
		$username = trim($this->input->post('color_name'));
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->form_validation->set_rules('color_name','required|min_length[1]|max_length[30]|callback_colorcheck');
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_string('',''),$this->getFormHash());
					break;
				}
				
				$this->inventory_service->addColor($username,$this->_loginUID);
				
				$this->jsonOutput('添加成功');
			}
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	
	/**
	 * 检查颜色名称是否存在
	 */
	public function colorname_check(){
		
		$colorname = trim($this->input->get_post('color_name'));
		$flag = $this->inventory_service->isColorExists($colorname,$this->_loginUID);
		
		if($flag){
			echo 'false';
		}else{
			echo 'true';
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
