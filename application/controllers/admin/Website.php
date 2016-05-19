<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Website_Model');
	}
	
	public function index(){
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			//'where' => array('site_status' => 1),
			'order' => 'site_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
			
		);
		
		$search_map['search_field'] = array('site_name' => '网站名称','site_url' => '网站地址');
		
		$search['search_field_name'] = $this->input->get_post('search_field_name');
		$search['search_field_value'] = $this->input->get_post('search_field_value');
		
		if(!empty($search['search_field_value']) && in_array($search['search_field_name'], array_keys($search_map['search_field']))){
			$condition['like'][$search['search_field_name']] = $search['search_field_value'];
		}
		
		$list = $this->Website_Model->getList($condition);
		
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
			
			$affectRows = 0;
			
			/*
			$updateData = array();
			
			foreach($delId as $del){
				$updateData[] = array(
					'site_id' => $del,
					'site_status' => 0
				);
			}
			
			if($updateData){
				$affectRows = $this->Website_Model->batchUpdate($updateData,'site_id');
			}
			*/
			$affectRows = $this->Website_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'site_id' , 'value' => $delId)
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
		
		
		$action = 'add';
		
		$feedback = '';
		if($this->isPostRequest()){
			$this->_getRules('add');
			
			$this->form_validation->set_rules('site_url','站点网址',"required|valid_starthttp|valid_url|is_unique[{$this->Website_Model->_tableRealName}.site_url]");
		
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData('add');
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				
				if(($newid = $this->Website_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$action = 'edit';
				$info = $this->Website_Model->getFirstByKey($newid,'site_id');
				
				$this->cache->file->delete(Cache_Key_SiteList);
			}
		}
		
		
		$this->assign('action',$action);
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);

		$this->display();
	}
	
	
	
	
	
	private function _getRules($action = 'add'){
		
		$this->form_validation->set_rules('site_name','站点名称',"required");
		
		
		if($this->input->post('site_sort')){
			$this->form_validation->set_rules('site_sort','排序',"is_natural|less_than[256]");
		}
		
		
		
	}
	
	
	private function _prepareData($action){
		
		$info = array(
			'site_name' => $this->input->post('site_name'),
			'site_url' => $this->input->post('site_url'),
			'site_sort' => $this->input->post('site_sort') ? $this->input->post('site_sort') : 255,
			'seo_title' => $this->input->post('seo_title') ? $this->input->post('seo_title') : '',
			'seo_keywords' => $this->input->post('seo_keywords') ? $this->input->post('seo_keywords') : '',
			'seo_description' => $this->input->post('seo_description') ? $this->input->post('seo_description') : '',
			'statistics_code' => $this->input->post('statistics_code') ? $this->input->post('seo_description') : '',
			'icp_number' => $this->input->post('icp_number') ? $this->input->post('icp_number') : '',
		);
		
		$urls = parse_url($info['site_url']);
		$info['site_domain'] = $urls['host'] ? $urls['host'] : '';
		
		$info = array_merge($info,$this->addWhoHasOperated($action));
		
		return $info;
	}
	
	/**
	 * 信息浏览
	 */
	public function detail(){
		
		$site_id = $this->input->get_post('site_id');
		$info = $this->Website_Model->getFirstByKey($site_id,'site_id');
		
		$this->assign('action','detail');
		$this->assign('info',$info);
		$this->display('website/add');
	}
	
	
	public function edit(){
		
		$this->assign('action','edit');
		
		$feedback = '';
		$site_id = $this->input->get_post('site_id');
		
		$info = $this->Website_Model->getFirstByKey($site_id,'site_id');
		
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('site_url','站点网址',"required|valid_starthttp|valid_url|is_unique_not_self[{$this->Website_Model->_tableRealName}.site_url.site_id.{$site_id}]");
			
			$this->_getRules('edit');
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData('edit');
				$info['site_id'] = $site_id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->Website_Model->update($info,array('site_id' => $site_id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$this->cache->file->delete(Cache_Key_SiteList);
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		
		$this->display('website/add');
	}
	
}
