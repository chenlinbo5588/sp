<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Budc extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Building_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']);
		
		
		
		$this->assign('id_type',config_item('id_type'));
		$this->assign('sex_type',config_item('sex_type'));
		
		
		
		
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
	
	public function index()
	{
		$condition = $this->_prepareCondition();
		
		$where = array();
		
		$where['qlrName'] = $this->input->get_post('qlr_name');
		$where['idNo'] = $this->input->get_post('id_no');
		$where['sex'] = $this->input->get_post('sex');
		$where['yhdz'] = $this->input->get_post('yhdz');
		
		
		
		if($where['qlrName']){
			$condition['like']['qlr_name'] = $where['qlrName'];
		}
		
		if($where['idNo']){
			$condition['like']['id_no'] = $where['idNo'];
		}
		
		if($where['sex']){
			$condition['where']['sex'] = $where['sex'];
		}
		
		if($where['yhdz']){
			$condition['where']['yhdz'] = 1;
		}
		
		
		$results = $this->Person_Model->getList($condition);
		
		$this->assign('list',$results['data']);
		$this->assign('page',$results['pager']);
		
		
		$this->display();
	}
	
	
	
}
