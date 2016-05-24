<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leavemsg extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model(array('Leavemsg_Model'));
	}
	
	public function index(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array(),
			'order' => 'leave_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$mobile = $this->input->get_post('mobile');
		
		if($mobile){
			$condition['like']['mobile'] = $mobile;
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
		$list = $this->Leavemsg_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
	
	
	
	private function _getRules(){
		$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		$this->form_validation->set_rules('remark','备注','required|max_length[200]');
		
		$this->form_validation->set_rules('status','状态','required|in_list[未处理,处理中,已处理]');
	}
	
	
	private function _prepareData(){
		$info = array(
			'mobile' => $this->input->post('mobile'),
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
		$id = $this->input->get_post('leave_id');
		$info = $this->Leavemsg_Model->getFirstByKey($id,'leave_id');
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData();
				$info['leave_id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Leavemsg_Model->update($info,array('leave_id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				
				$info = $this->Leavemsg_Model->getFirstByKey($id,'leave_id');
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
}