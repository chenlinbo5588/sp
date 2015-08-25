<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Service {

	protected $CI ;
	protected $_userModel;
	protected $_districtModel;
	
	
	public function __construct(){
		$this->CI = & get_instance();
		$this->CI->load->model('Member_Model');
		$this->CI->load->model('Common_District_Model');
		
		$this->_userModel = $this->CI->Member_Model;
		$this->_districtModel = $this->CI->Common_District_Model;
		
	}
    
	protected function successRetun($data = array()){
		return array(
			'code' => 'success',
			'data' => $data
		);
	}
	
	protected function formatArrayReturn($code = 'failed' , $message = '失败' , $data = array()){
		return array(
			'code' => $code,
			'message' => $message,
			'data' => $data
		);
	}
	
	public function getCityById($city_id){
    	return  $this->_districtModel->getFirstByKey($city_id);
    }
}
