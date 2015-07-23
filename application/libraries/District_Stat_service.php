<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class District_Stat_Service extends Base_Service {

	private $_districtModel;
	private $_districtStatModel;
	


	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Common_District_Model');
		$this->CI->load->model('District_Stat_Model');
		
		$this->_districtModel = $this->CI->Common_District_Model;
		$this->_districtStatModel = $this->CI->District_Stat_Model;
	}
	
	
	public function getDistrictByPid($upid = 0,$field = 'id,name'){
		return $this->_districtModel->getList(array(
			'select' => $field,
			'where' => array(
				'upid' => $upid
			),
			'order' => 'id ASC,displayorder DESC'
		));
	}
	
	
	public function getAvailableCity($sportsName = ''){
		
		$this->_districtStatModel->distinct(true);
		
		return $this->_districtStatModel->getList(array(
			'select' => 'name',
			'where' => array(
				'category_name' => $sportsName
			)
		));
	}
	
}
