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
		return $this->_districtModel->getFirstByKey($id);
	}
	
	public function getDistrictByPid($upid = 0,$field = 'id,name,upid'){
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
