<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class District_Stat_Service extends Base_Service {

	private $_districtStatModel;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('District_Stat_Model');
		$this->_districtStatModel = self::$CI->District_Stat_Model;
	}
	
	
	public function getDistrictByPid($upid = 0,$field = 'id,name'){
		return self::$districtModel->getList(array(
			'select' => $field,
			'where' => array(
				'upid' => $upid
			),
			'order' => 'id ASC,displayorder DESC'
		));
	}
	
	
	
	/**
	 * @todo 运营起来后再添加
	 */
	public function getAvailableCity($category = 1, $limit = 100){
		$dList = $this->_districtStatModel->getList(array(
			'select' => 'd2',
			'where' => array(
				'category_id' => $category
			),
			'limit' => $limit
		));
		
		$mainCity = array();
		
		foreach($dList as $d){
			$mainCity[] = $d['d2'];
		}
		
		$city = self::$districtModel->getList(array(
			'select' => 'id,name',
			'where_in' => array(
				array('key' => 'id', 'value' => $mainCity)
			),
			'order' => 'id ASC,displayorder DESC'
		));
		
		return $city;
		//print_r($dList);
	}
	
}
