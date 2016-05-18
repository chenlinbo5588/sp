<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Market_words extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Market_Words_Model');
	}
	
	public function index(){
		
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'word_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
			
		);
		
		
		$search_map['search_field'] = array('word_name' => '关键词','word_code' => '尾巴代码');
		
		$search['search_field_name'] = $this->input->get_post('search_field_name');
		$search['search_field_value'] = $this->input->get_post('search_field_value');
		
		if(!empty($search['search_field_value']) && in_array($search['search_field_name'], array_keys($search_map['search_field']))){
			$condition['like'][$search['search_field_name']] = $search['search_field_value'];
		}
		
		$list = $this->Market_Words_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->assign('search_map',$search_map);
		$this->display();
		
		
	}
	
	
	public function delete(){
		$delId = $this->input->post('id');
		if($this->isPostRequest() && $delId){
			
			if(!is_array($delId)){
				$delId = (array)$delId;
			}
			
			$affectRows = $this->Market_Words_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'word_id' , 'value' => $delId)
				)
			
			));
			
			if($affectRows > 0){
				$this->jsonOutput('删除成功',$this->getFormHash());
			}else{
				$this->jsonOutput('删除失败',$this->getFormHash());
			}
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	public function add(){
		
		$this->assign('action','add');
		
		$feedback = '';
		if($this->isPostRequest()){
			$this->_getRules('add');
			
			$this->form_validation->set_rules('word_code','尾巴代码','required|min_length[1]|max_length[100]|is_unique['.$this->Market_Words_Model->getTableRealName().'.word_code]');
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareData('add');
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if(($newid = $this->Market_Words_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$this->cache->file->delete(Cache_Key_WordList);
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Market_Words_Model->getFirstByKey($newid,'word_id');
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);

		$this->display();
	}
	
	
	
	private function _getRules($action = 'add'){
		
		$this->form_validation->set_rules('word_name','关键字',"required|min_length[1]|max_length[100]");
		
		
		if($this->input->post('word_sort')){
			$this->form_validation->set_rules('word_sort','排序',"is_natural|less_than[256]");
		}
		
	}
	
	
	private function _prepareData($action){
		
		$info = array(
			'word_name' => $this->input->post('word_name'),
			'word_code' => $this->input->post('word_code'),
			'word_sort' => $this->input->post('word_sort') ? $this->input->post('word_sort') : 255,
		);
		
		$info = array_merge($info,$this->addWhoHasOperated($action));
		
		return $info;
	}
	
	
	
	/**
	 * 信息浏览
	 */
	public function detail(){
		$word_id = $this->input->get_post('word_id');
		$info = $this->Market_Words_Model->getFirstByKey($word_id,'word_id');
		
		$this->assign('action','detail');
		$this->assign('info',$info);
		$this->display('market_words/add');
	}
	
	
	
	public function edit(){
		
		$this->assign('action','edit');
		
		$feedback = '';
		$word_id = $this->input->get_post('word_id');
		$info = $this->Market_Words_Model->getFirstByKey($word_id,'word_id');
		
		if($this->isPostRequest()){
			
			$this->_getRules('edit');
			$this->form_validation->set_rules('word_code','尾巴代码','required|min_length[1]|max_length[100]|is_unique_not_self['.$this->Market_Words_Model->getTableRealName().'.word_code.word_id.'.$word_id.']');
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData('edit');
				$info['word_id'] = $word_id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->Market_Words_Model->update($info,array('word_id' => $word_id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$this->cache->file->delete(Cache_Key_WordList);
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		
		$this->display('market_words/add');
	}
	
}
