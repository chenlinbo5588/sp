<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Service {

	protected $CI ;
	protected $_userModel;
	
	
	public function __construct(){
		$this->CI = & get_instance();
		$this->CI->load->model('Member_Model');
		$this->_userModel = $this->CI->Member_Model;
		
	}
    
	protected function successRetun($data = array()){
		return array(
			'code' => 'success',
			'date' => $data
		);
	}
	
	protected function formatArrayReturn($code = 'failed' , $message = '失败' , $data = array()){
		return array(
			'code' => $code,
			'message' => $message,
			'date' => $data
		);
	}
}
