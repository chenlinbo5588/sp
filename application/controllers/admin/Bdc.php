<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Bdc extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Budongchan_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']);
		
		
		$this->assign('id_type',config_item('id_type'));
		$this->assign('sex_type',config_item('sex_type'));
		
		$this->_subNavs = array(
			'modulName' => '办事机构',
			'subNavs' => array(
				'管理' => 'bdc/index',
				'待初审' => 'bdc/first_sh',
				'待复审' => 'bdc/second_sh',
				
			),
		);
		
		
		
	}
	
	
	//@todo 自动加入所在村条件
	private function _prepareCondition(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$pageParam = array(
			'page_size' => config_item('page_size'),
			'current_page' => $currentPage,
			'call_js' => 'search_page',
			'form_id' => '#formSearch'
		);
		
		$searchCondition = array(
			'pager' => $pageParam,
			'order' => 'id DESC',
		);
		
		return $searchCondition;
	}
	
	private function _getPagerData($moreWhere = array()){
		
		$condition = $this->_prepareCondition();
		
		$where = array();
		
		$where['name'] = $this->input->get_post('name');
		$where['id_no'] = $this->input->get_post('id_no');
		
		if($where['name']){
			$condition['like']['name'] = $where['name'];
		}
		if($where['id_no']){
			$condition['like']['id_no'] = $where['id_no'];
		}
		
		if($condition['where']){
			$condition['where'] = array_merge($condition['where'],$moreWhere);
		}else{
			if($moreWhere){
				$condition['where'] = $moreWhere;
			}
		}
		
		
		$results = $this->Bdc_Model->getList($condition);
		
		$this->assign('list',$results['data']);
		$this->assign('page',$results['pager']);
		
	}
	
	
	public function index()
	{
		
		$this->_getPagerData();
		$this->display();
	}
	
	
	/**
	 * 初审
	 */
	public function first_sh(){
		
		$this->_getPagerData(array(
			'status' => '已提交初审'
		));
		
		
		$this->display();
	}
	
	
	/**
	 * 复审
	 */
	public function second_sh(){
		
		$this->_getPagerData(array(
			'status' => '已提交复审'
		));
		
		
		$this->display();
	}
	
}
