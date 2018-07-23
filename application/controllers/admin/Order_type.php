<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Order_type extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Order_service'));
		
		$this->_moduleTitle = '订单类型';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
		);
		
		$this->form_validation->set_error_delimiters('<div>','</div>');
		
	}
	
	
	
	public function index(){
		
		if($this->input->is_ajax_request() && $this->isPostRequest()){
			
			$this->form_validation->set_rules('fieldname','状态字段','required|in_list[enable]');
			$this->form_validation->set_rules('enabled','状态','required|in_list[0,1]');
			
			if($this->form_validation->run()){
				
				$upInfo[$this->input->post('fieldname')] = $this->input->post('enabled');
				
				$this->Order_Type_Model->update($upInfo,array('id' => $this->input->post('id')));
				
				$this->jsonOutput('保存成功', $this->getFormHash());
				
			}else{
				$this->jsonOutput('保存失败 '.$this->form_validation->error_string(),$this->getFormHash());
			}
			
		}else{
			
			$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
			$condition = array(
				'order' => 'id DESC',
				'pager' => array(
					'page_size' => config_item('page_size'),
					'current_page' => $currentPage,
					'call_js' => 'search_page',
					'form_id' => '#formSearch'
				)
			);
			
			
			$search['name'] = $this->input->get_post('name');
			if($search['name']){
				$condition['like']['name'] = $search['name'];
			}
			
			$list = $this->Order_Type_Model->getList($condition);
			
			$this->assign(array(
				'list' => $list,
				'page' => $list['pager'],
				'currentPage' => $currentPage
			));
			
			$this->display();
		}
		
	}
	
	/**
	 * 验证规则
	 */
	private function _getRules($id = 0){
		
		$this->_getNameRule($id);
		
		$this->form_validation->set_rules('enable','启用状态','required|in_list[0,1]');
		
		if($this->input->post('displayorder')){
			$this->form_validation->set_rules('displayorder','排序',"is_natural|less_than[256]");
		}
		
	}
	
	
	
	/**
	 * 获取
	 */
	private function _getNameRule($id = 0){
		
		if($id){
			$this->form_validation->set_rules('name',$this->_moduleTitle.'名称','required|is_unique_not_self['.$this->Order_Type_Model->getTableRealName().".id.{$id}]");
		}else{
			$this->form_validation->set_rules('name',$this->_moduleTitle.'名称','required|is_unique['.$this->Order_Type_Model->getTableRealName().'.id]');
		}
		
	}
	
	
	
	
	/**
	 * 
	 */
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			/*
			$this->Order_Type_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'brand_id','value' => $ids)
				)
			));
			*/
			//@todo 
			$this->jsonOutput('删除失败，待开发完善',$this->getFormHash());
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	/**
	 * 准备数据
	 */
	private function _prepareData(){
		$info = array(
			'displayorder' => $this->input->post('displayorder') ? $this->input->post('displayorder') : 255
		);
		
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData());
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				
				$newid =$this->Order_Type_Model->_add(array_merge($info,$this->addWhoHasOperated('add')));
				$error = $this->Order_Type_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$feedback = getErrorTip($this->input->post('name').'名称已被占用');
					}else{
						$feedback = getErrorTip('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Order_Type_Model->getFirstByKey($newid,'id');
				
			}
		}else{
			$info['enable'] = 1;
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$info = $this->Order_Type_Model->getFirstByKey($id,'id');
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getRules($id);
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData());
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$this->Order_Type_Model->update($info,array('id' => $id));
				$error = $this->Order_Type_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$feedback = getErrorTip($this->input->post('name').'名称已被占用');
					}else{
						$feedback = getErrorTip('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback
		));
		
		$this->display($this->_className.'/add');
	}
	
	
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		
		$this->form_validation->set_error_delimiters('','');
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[name,displayorder]');
			
			
			switch($fieldName){
				case 'name':
					$this->_getNameRule($id);
					break;
				case 'displayorder';
					$this->form_validation->set_rules('displayorder','排序',"required|is_natural|less_than[256]");
					break;
				default:
					break;
			}
			
			$message = '修改失败';
			
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				
				if($this->Order_Type_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
		
	}
	
}
