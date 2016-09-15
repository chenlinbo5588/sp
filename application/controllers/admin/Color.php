<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Color extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Color_Model');
		$this->assign('moduleTitle','颜色管理');
		
		$this->_subNavs = array(
			array('url' => 'color/index', 'title' => '管理'),
			array('url' => 'color/add','title' => '添加'),
		);
	}
	
	public function index(){
		
		if($this->input->is_ajax_request() && $this->isPostRequest()){
			$this->form_validation->set_rules('fieldname','状态字段','required|in_list[brand_recommend]');
			$this->form_validation->set_rules('enabled','状态','required|in_list[0,1]');
			
			if($this->form_validation->run()){
				
				$upInfo[$this->input->post('fieldname')] = $this->input->post('enabled');
				
				$this->Color_Model->update($upInfo,array('brand_id' => $this->input->post('id')));
				
				$this->jsonOutput('保存成功');
				
			}else{
				$this->jsonOutput('保存失败 '.$this->form_validation->error_string());
			}
		}else{
			
			$condition = array(
				'order' => 'color_sort DESC',
			);
			
			$name = $this->input->get_post('search_color_name');
			
			if($name){
				$condition['like']['color_name'] = $name;
			}
			
			$list = $this->Color_Model->getList($condition);
			$this->assign('list',$list);
			$this->display();
		}
		
	}
	
	
	private function _getRules(){
		$this->form_validation->set_rules('color_name','颜色名称','required');
		if($this->input->post('color_sort')){
			$this->form_validation->set_rules('color_sort','排序',"is_natural|less_than[256]");
		}
	}
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Color_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'color_id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功');
		}else{
			$this->jsonOutput('请求非法');
			
		}
	}
	
	
	private function _prepareData(){
		
		$info = array(
			'color_name' => $this->input->post('color_name'),
			'color_sort' => $this->input->post('color_sort') ? intval($this->input->post('color_sort')) : 255
		);
		
		if(empty($info['color_sort']) || $info['color_sort'] > 255){
			$info['color_sort'] = 255;
		}
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData();
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated());
				
				if(($newid =$this->Color_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Color_Model->getFirstByKey($newid,'color_id');
				
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		$info = $this->Color_Model->getFirstByKey($id,'color_id');
		
		$this->_subNavs[] = array('url' => 'color/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData();
				$info['color_id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Color_Model->update($info,array('color_id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display('color/add');
	}
}
