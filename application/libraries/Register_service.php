<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_Service extends Base_Service {

	private $_userModel;

	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Member_Model');
		$this->_userModel = $this->CI->Member_Model;
	}
	
	
	/**
	 * 半注册
	 */
	public function createHalfRegisterMemebr($regParam){
		$return = $this->formatArrayReturn();
		
		$uid = $this->_userModel->add(array(
			'username' => $regParam['mobile'],
			'mobile' => $regParam['mobile'],
			'regip' => $regParam['regip'],
			'status' => -1,
		));
		
		if($uid > 0){
			$return = $this->successRetun(array('uid' => $uid));
		}
		
		return $return;
	}
	
	
	
}
