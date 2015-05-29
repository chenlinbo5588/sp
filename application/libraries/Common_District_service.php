<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_District_Service extends Base_Service {

	private $_districtModel;


	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Common_District_Model');
		$this->_districtModel = $this->CI->Common_District_Model;
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
	
}
