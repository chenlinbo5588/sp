<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_District_Service extends Base_Service {


	public function __construct(){
		parent::__construct();
		
	}
	
	/**
	 * 
	 */
	public function getDistrictInfoById($id){
		return self::$districtModel->getFirstByKey($id);
	}
	
	public function getDistrictByPid($upid = 0,$field = 'id,name,upid'){
		return self::$districtModel->getList(array(
			'select' => $field,
			'where' => array(
				'upid' => $upid
			),
			'order' => 'id ASC,displayorder DESC'
		));
	}
	
	/**
	 * 准备数据
	 */
	public function prepareCityData($ds = array()){
		
		$rt = array();
		$rt['d1'] = $this->getDistrictByPid(0);
		
		if($ds['d1'] > 0){
			$rt['d2'] = $this->getDistrictByPid($ds['d1']);
		}
		
		if($ds['d2'] > 0){
			$rt['d3'] = $this->getDistrictByPid($ds['d2']);
		}
		
		if($ds['d3'] > 0){
			$rt['d4'] = $this->getDistrictByPid($ds['d3']);
		}
		
		return $rt;
	}
	
	
	public function getDistrictByIds($ids,$field = 'id,name'){
		
		if(empty($ids)){
			return array();
		}
		
		$ds = self::$districtModel->getList(array(
			'select' => $field,
			'where_in' => array(
				array('key' => 'id', 'value' => $ids)
			),
			'order' => 'id ASC,displayorder DESC'
		));
		
		if(!empty($ds)){
			$userDs = array();
			foreach($ds as $key => $dv){
				$userDs[$dv['id']] = $dv;
			}
			
			return $userDs;
		}else{
			
			return array();
		}
	}
	
}
