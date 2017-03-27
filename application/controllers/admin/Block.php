<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Block extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Block_service'));
	}
	
	
	public function index(){
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		$condition = array(
			'order' => 'block_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$list = $this->Block_Model->getList($condition);
	
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
		
	}
	
	
	
	private function _getRules(){
		$this->form_validation->set_rules('block_name','板块名称','required');
		$this->form_validation->set_rules('style_name','风格名称','required');
		$this->form_validation->set_rules('is_show','是否显示','required|in_list[0,1]');
		
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
			
			$this->Block_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'block_id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	private function _prepareData(){
		
		$info = array(
			'block_name' => $this->input->post('block_name'),
			'style_name' => $this->input->post('style_name'),
			'is_show' => $this->input->post('is_show') ? $this->input->post('is_show') : 1,
			'displayorder' => $this->input->post('displayorder') ? $this->input->post('displayorder') : 255
		);
		
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		//print_r($_FILES);
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if(($newid =$this->Block_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Block_Model->getFirstByKey($newid,'block_id');
				
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('block_id');
		
		$info = $this->Block_Model->getFirstByKey($id,'block_id');
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData();
				$info['block_id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				
				if($this->Block_Model->update($info,array('block_id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display('block/add');
	}
	
	
	
	
}
