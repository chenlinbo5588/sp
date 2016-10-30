<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suggestion extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model(array('Suggestion_Model'));
	}
	
	public function index(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array(),
			'order' => 'sug_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$username = $this->input->get_post('username');
		$company_name = $this->input->get_post('company_name');
		
		if($username){
			$condition['like']['username'] = $username;
		}
		
		if($company_name){
			$condition['like']['company_name'] = $company_name;
		}
		
		$status =  $this->input->get_post('status');
		switch($status){
			case '未处理':
			case '处理中':
			case '已处理':
				$condition['where']['status'] = $status;
				break;
			default:
				break;
		}
		
		
		//print_r($condition);
		$list = $this->Suggestion_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
	
	
	
	private function _getRules(){
		$this->form_validation->set_rules('username','姓名','required|min_length[1]|max_length[30]');
		$this->form_validation->set_rules('company_name','公司名称','required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		
		$this->form_validation->set_rules('city','城市','required');
		
		if($this->input->post('tel')){
			$this->form_validation->set_rules('tel','固定电话','required|max_length[20]');
		}
		
		if($this->input->post('email')){
			$this->form_validation->set_rules('email','联系邮箱','required|valid_email');
		}
		
		if($this->input->post('weixin')){
			$this->form_validation->set_rules('weixin','微信号','required');
		}
		
		$this->form_validation->set_rules('doc_no','合同号','required');
		$this->form_validation->set_rules('remark','备注','required|max_length[200]');
		
		$this->form_validation->set_rules('status','状态','required|in_list[未处理,处理中,已处理]');
		
	}
	
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Article_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'article_id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	private function _prepareData(){
		$info = array(
			'username' => $this->input->post('username'),
			'company_name' => $this->input->post('company_name'),
			'mobile' => $this->input->post('mobile'),
			'city' => $this->input->post('city'),
			'tel' => $this->input->post('tel') ? $this->input->post('tel') : '',
			'email' => $this->input->post('email') ? $this->input->post('email') : '',
			'weixin' => $this->input->post('weixin') ? $this->input->post('weixin') : '',
			'doc_no' => $this->input->post('doc_no'),
			'remark' => strip_tags($this->input->post('remark')),
			'status' => $this->input->post('status')
			
		);
		
		return $info;
	}
	
	/*
	public function add(){
		$feedback = '';
		
		$treelist = $this->article_service->getArticleClassTreeHTML();
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareArticleData();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if(($newid = $this->Article_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Article_Model->getFirstByKey($newid,'article_id');
			}
		}else{
			$info['uid'] = $this->_adminProfile['basic']['uid'];
			$info['article_show'] = 1;
			$info['article_author'] = $this->_adminProfile['basic']['username'];
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('articleClassList',$treelist);
		$this->display();
	}
	*/
	
	public function edit(){
		$feedback = '';
		$id = $this->input->get_post('sug_id');
		$info = $this->Suggestion_Model->getFirstByKey($id,'sug_id');
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData();
				$info['sug_id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Suggestion_Model->update($info,array('sug_id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				
				$info = $this->Suggestion_Model->getFirstByKey($id,'sug_id');
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
}