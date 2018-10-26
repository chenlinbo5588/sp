<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Report_service','Wuye_service','Basic_data_service'));
		
		
		
		$this->wuye_service->setDataModule($this->_dataModule);
		$this->_moduleTitle = '报表';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'residentList' => $this->wuye_service->getOwnedResidentList(array(
				'select' => 'id,name',
			),'id'),
		));
		$this->_subNavs = array(
			array('url' => $this->_className.'/day','title' => '日详情'),
			array('url' => $this->_className.'/week','title' => '周详情'),
			array('url' => $this->_className.'/month','title' => '月详情'),
			array('url' => $this->_className.'/year','title' => '年详情'),
		);

	}
	private function _seedReportData($reportMode){
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$search['feetype_name'] = $this->input->get_post('feetype_name');
		$residentId = $this->_getResidentId();
		$search['date_s'] = $this->input->get_post('date_s');
		$search['date_e'] = $this->input->get_post('date_e');
		if('每日报表' == $reportMode){
			if($search['date_s']){
				$condition['where']['date_key >='] = strtotime($search['date_s']);
			}else{
				$condition['where']['date_key >='] =  strtotime(date('Y').'-1-1');
			}
			if($search['date_e']){
				$condition['where']['date_key <='] = strtotime($search['date_e']);
			}else{
				$condition['where']['date_key <='] = strtotime(date('Y-m-d'));
			}
		}else if('每周报表' == $reportMode){
			if($search['date_s']){
				$condition['where']['week >='] = $search['date_s'];
			}else{
				$condition['where']['week >='] = 1;
			}
			if($search['date_e']){
				$condition['where']['week <='] = $search['date_e'];
			}else{
				$condition['where']['week <='] = date('W');
			}
		}else if('每月报表' == $reportMode){
			if($search['date_s']){
				$condition['where']['month >='] = $search['date_s'];
			}else{
				$condition['where']['month >='] = 1;
			}
			if($search['date_e']){
				$condition['where']['month <='] = $search['date_e'];
			}else{
				$condition['where']['month <='] = date('m');
			}
		}else if('每年报表' == $reportMode){
			if($search['date_s']){
				$condition['where']['year >='] = $search['date_s'];
			}else{
				$condition['where']['year >='] = date('Y')-5;
			}
			if($search['date_e']){
				$condition['where']['year <='] = $search['date_e'];
			}else{
				$condition['where']['year <='] = date('Y');
			}
		}

		
		$condition =  array_merge_recursive($condition,$this->_searchCondition($currentPage,$search,$residentId));
	
		$list = $this->report_service->search($reportMode,$condition);

		$this->assign(array(
			'residentList' => $this->wuye_service->getOwnedResidentList(array(
							'select' => 'id,name',
						),'id'),
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $currentPage,
			'residentId' => $residentId,
			'reportMode' =>$reportMode,
		));
	}
	
	
	/**
	 * 汇总查询
	 */
	public function day(){
		$this->_seedReportData('每日报表');
		
		$this->display($this->_className.'/day');
	}
	
/**
 * 周报表详情
 */
	public function week(){

		$this->_seedReportData('每周报表');
		
		$this->display($this->_className.'/day');
	}
/**
 * 月报表详情
 */
	public function month(){

		$this->_seedReportData('每月报表');
		
		$this->display($this->_className.'/day');
	}
/**
 * 年报表详情
 */
	public function year(){

		$this->_seedReportData('每年报表');
		
		$this->display($this->_className.'/day');
	}

	public function _searchCondition($currentPage,$search,$residentId){
		$condition = array(
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		

		$condition['where']['resident_id'] = $residentId;
		if($search['feetype_name']){
			$condition['where']['feetype_name'] = $search['feetype_name'];
		}
		if($search['year']){
			$condition['where']['year'] = $search['year'];
		}
		return $condition;
	}
	
	
	private function _getResidentId(){
		$residentId = $this->input->get_post('resident_id');
		if(empty($residentId)){
			$residentList = $this->wuye_service->getOwnedResidentList(array());
			$residentId = $residentList[0]['id'];
		}
		return $residentId;
	}

}
