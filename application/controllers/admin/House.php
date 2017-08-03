<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class House extends Ydzj_Admin_Controller {
	
	private $_mapConfig ;
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Building_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']);
		
		$this->load->config('cljz_config');
		$this->load->config('arcgis_server');
		
		
		$mapGroup = config_item('mapGroup');
		$mapUrlConfig = config_item($mapGroup);
		
		$this->_mapConfig = $mapUrlConfig;
		$this->assign('mapUrlConfig',$this->_mapConfig);
		$this->assign('PHPSID',$this->session->session_id);
		
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
			'order' => 'hid DESC',
		);
		
		return $searchCondition;
	}
	
	public function index()
	{
		$condition = $this->_prepareCondition();
		$where = array();
		
		$where['qlrName'] = $this->input->get_post('qlr_name');
		$where['idNo'] = $this->input->get_post('id_no');
		
		
		if($where['qlrName']){
			$condition['like']['owner_name'] = $where['qlrName'];
		}
		
		if($where['idNo']){
			$condition['like']['id_no'] = $where['idNo'];
		}
		
		$ownerId = $this->input->get_post('owner_id');
		
		if($ownerId){
			$condition['where']['owner_id'] = $ownerId;
		}
		
		$results = $this->House_Model->getList($condition);
		
		$this->assign('list',$results['data']);
		$this->assign('page',$results['pager']);
		
		
		
		$this->assign('illegalList',array(
			'待定','全部合法','部分违法','全部违法'
		));
		$this->assign('dealWayList',array(
			'待定','暂缓','补办','没收','拆除'
		));
		
		
		$this->display();
	}
	
	
	
	
	/**
	 * 编辑
	 */
	public function detail(){
		
		$feedback = '';
		$hid = $this->input->get_post('hid');
		
		$info = $this->building_service->getHouseInfo($hid);
		$villageList = $this->building_service->getTownVillageList(config_item('site_town'));
		
		$this->building_service->setArcgisUrl(config_item('arcgis_server'),$this->_mapConfig['编辑要素']['标准建筑点']);
		$info = $this->building_service->autoSetXY($info);
		
		if($this->isPostRequest()){
			
		}else{
			
			$houseList = $this->House_Model->getList(array(
				'where' => array(
					'owner_id' => $info['owner_id']
				)
			));
			
			$this->assign('fileList',$info['photos']);
			
			$this->assign('villageList',$villageList);
			$this->assign('info',$info);
			$this->assign('feedback',$feedback);
			$this->display();
		}
		
	}
	
	
	
	
	
}
