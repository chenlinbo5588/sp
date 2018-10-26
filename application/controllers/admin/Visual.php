<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visual extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Report_service','Wuye_service','Basic_data_service'));
		
		$this->wuye_service->setDataModule($this->_dataModule);
		$this->_moduleTitle = '报表';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		$this->_subNavs = array(
			array('url' => $this->_className.'/visual','title' => '日报表'),
			array('url' => $this->_className.'/visualweek','title' => '周报表'),
			array('url' => $this->_className.'/visualmonth','title' => '月报表'),
			array('url' => $this->_className.'/visualyear','title' => '年报表'),
		);

	}
	
	
/**
 * 可视化日报表
 */
	public function visual(){	
		$search['date_s'] = $this->input->get_post('date_s');
		$search['date_e'] = $this->input->get_post('date_e');
		
		$residentId = $this->_getResidentId();
		
		$residentName = $this->getResidentNameByid($residentId);
		$condition['where']['resident_id'] = $residentId;
		
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
		$list = $this->report_service->search('每日报表',$condition);

		$sumjson = array();
		foreach($list as $key => $item){
			if('物业费' == $item['feetype_name']){
				$wuyejson[] = $item ['amount_payed'];
				$dateJson[] = date('Y-m-d',$item['date_key']);
			}else if('能耗费' == $item['feetype_name']){
				$nenghaojson[] = $item ['amount_payed'];	
			}
		}
		if($dateJson){
			foreach($dateJson as $key => $item){
				$sumjson[] = $wuyejson[$key] + $nenghaojson[$key];
				$dateEnd = $item;
			}
			$dateStart = $dateJson[0];
		}else{
			$dateStart = $search['date_s'];
			$dateEnd = $search['date_e'];
		}

		$reprotMode = '每日报表';

		$this->assign(array(
			'wuyeDatejson' => json_encode($wuyejson),
			'dateJson' => json_encode($dateJson),
			'nenghaoDatejson' => json_encode($nenghaojson),
			'sumjson' => json_encode($sumjson),
    		'residentList' => $this->wuye_service->getOwnedResidentList(array(
			'select' => 'id,name,address,lng,lat'),'id'),
			'residentId' => $residentId,
			'residentName' => $residentName,
			'start_date' =>$dateStart,
			'end_date' => $dateEnd,
			'report_mode' => $reprotMode,
			'search' => $search
		));

		$this->display();
	}
	
		
/**
 * 可视化周报表
 */
	public function visualweek(){	
		$search['date_s'] = $this->input->get_post('date_s');
		$search['date_e'] = $this->input->get_post('date_e');
		$search['year'] = $this->input->get_post('year');
		if(empty($search['year'])){
			$search['year'] = date('Y');
		}
		$condition['where']['year'] = $search['year'];
		$residentId = $this->_getResidentId();
		
		$residentName = $this->getResidentNameByid($residentId);
		$condition['where']['resident_id'] = $residentId;
		
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
		$list = $this->report_service->search('每周报表',$condition);

		$sumjson = array();
		foreach($list as $key => $item){
			if('物业费' == $item['feetype_name']){
				$wuyejson[] = $item ['amount_payed'];
				$dateJson[] = '第'.$item['week'].'周';
			}else if('能耗费' == $item['feetype_name']){
				$nenghaojson[] = $item ['amount_payed'];	
			}
		}
		if($dateJson){
			foreach($dateJson as $key => $item){
				$sumjson[] = $wuyejson[$key] + $nenghaojson[$key];
				$dateEnd = $search['year'].'年'.$item;
			}
			$dateStart = $search['year'].'年'.$dateJson[0];
		}else{
			$dateStart =$search['year'].'年第1周';
			if($search['year'] == date('Y')){
				$dateEnd = $search['year'].'年第'.date('W').'周';
			}else{
				$dateEnd = $search['year'].'年第53周';
			}
			
			
		}

		$reprotMode = '每周报表';

		$this->assign(array(
			'wuyeDatejson' => json_encode($wuyejson),
			'dateJson' => json_encode($dateJson),
			'nenghaoDatejson' => json_encode($nenghaojson),
			'sumjson' => json_encode($sumjson),
    		'residentList' => $this->wuye_service->getOwnedResidentList(array(
			'select' => 'id,name,address,lng,lat'),'id'),
			'residentId' => $residentId,
			'residentName' => $residentName,
			'start_date' =>$dateStart,
			'end_date' => $dateEnd,
			'report_mode' => $reprotMode,
			'search' => $search
		));

		$this->display($this->_className.'/visual');
	}
	
	/**
 * 可视化月报表
 */
	public function visualmonth(){	
		$search['date_s'] = $this->input->get_post('date_s');
		$search['date_e'] = $this->input->get_post('date_e');
		$search['year'] = $this->input->get_post('year');
		if(empty($search['year'])){
			$search['year'] = date('Y');
		}
		$condition['where']['year'] = $search['year'];		
		$residentId = $this->_getResidentId();
		
		$residentName = $this->getResidentNameByid($residentId);
		$condition['where']['resident_id'] = $residentId;
		
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
		$list = $this->report_service->search('每月报表',$condition);

		$sumjson = array();
		foreach($list as $key => $item){
			if('物业费' == $item['feetype_name']){
				$wuyejson[] = $item ['amount_payed'];
				$dateJson[] = $item['month'].'月';
			}else if('能耗费' == $item['feetype_name']){
				$nenghaojson[] = $item ['amount_payed'];	
			}
		}
		if($dateJson){
			foreach($dateJson as $key => $item){
				$sumjson[] = $wuyejson[$key] + $nenghaojson[$key];
				$dateEnd = $search['year'].'年'.$item;
			}
			$dateStart = $search['year'].'年'.$dateJson[0];
		}else{
			$dateStart = $search['year'].'年'.'1月';
			if($search['year'] == date('Y')){
				$dateEnd = $search['year'].'年'.date('m').'月';
			}else{
				$dateEnd = $search['year'].'年'.'12月';
			}
		}

		$reprotMode = '每月报表';

		$this->assign(array(
			'wuyeDatejson' => json_encode($wuyejson),
			'dateJson' => json_encode($dateJson),
			'nenghaoDatejson' => json_encode($nenghaojson),
			'sumjson' => json_encode($sumjson),
    		'residentList' => $this->wuye_service->getOwnedResidentList(array(
			'select' => 'id,name,address,lng,lat'),'id'),
			'residentId' => $residentId,
			'residentName' => $residentName,
			'start_date' =>$dateStart,
			'end_date' => $dateEnd,
			'report_mode' => $reprotMode,
			'search' => $search
		));

		$this->display($this->_className.'/visual');
	}
	/**
 * 可视化年报表
 */
	public function visualyear(){	

		$search['resident_id'] = $this->input->get_post('resident_id');
		$search['year'] = $this->input->get_post('year');
		if($search['resident_id']){
			$condition['where_in'][] = array('key' => 'resident_id', 'value' => $search['resident_id']);
		}else{
			$condition['where']['id'] = 0;
		}
		if($search['year']){
			$condition['where']['year'] = $search['year'];
		}else{
			$condition['where']['year'] = date('Y');
		}
		$list = $this->report_service->search('每年报表',$condition);
		
		$sumjson = array();
		foreach($list as $key => $item){
			if('物业费' == $item['feetype_name']){
				$wuyejson[] = $item ['amount_payed'];
				$dateJson[] = $item['resident_name'].$item['year'].'年费用详情';
			}else if('能耗费' == $item['feetype_name']){
				$nenghaojson[] = $item ['amount_payed'];	
			}
		}
		if($dateJson){
			foreach($dateJson as $key => $item){
				$sumjson[] = $wuyejson[$key] + $nenghaojson[$key];
			}
		}
		$reprotMode = '每年报表';
		$this->assign(array(
			'wuyeDatejson' => json_encode($wuyejson),
			'dateJson' => json_encode($dateJson),
			'nenghaoDatejson' => json_encode($nenghaojson),
			'sumjson' => json_encode($sumjson),
    		'residentList' => $this->wuye_service->getOwnedResidentList(array(
			'select' => 'id,name,address,lng,lat'),'id'),
			'residentId' => $search['resident_id'],
			'search' => $search,
			'report_mode' => $reprotMode,
		));

		$this->display($this->_className.'/visualyear');
	}

	public function getResidentnameByid($residentId){
		$residentInfo = $this->Resident_Model->getFirstByKey($residentId,'id');
		$residentName = $residentInfo['name'];
		return $residentName;
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
