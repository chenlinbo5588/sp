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
	
	public function getDistrictByIds($ids,$field = 'id,name'){
		$ds = $this->_districtModel->getList(array(
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
