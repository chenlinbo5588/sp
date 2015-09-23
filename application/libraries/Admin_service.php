<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Service {

	protected $CI ;
	protected $_userModel;
	protected $_districtModel;
	
	
	public function __construct(){
		$this->CI = & get_instance();
		$this->CI->load->model('Adminuser_Model');
		$this->CI->load->model('Common_District_Model');
		
		$this->_userModel = $this->CI->Adminuser_Model;
		$this->_districtModel = $this->CI->Common_District_Model;
		
	}
    
	protected function successRetun($data = array()){
		return array(
			'code' => 'success',
			'message' => '成功',
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
    
    
    public function do_adminlogin($param){
		
		$result = $this->formatArrayReturn();
		$result['message'] = '登陆失败';
		
		$userInfo = $this->_userModel->getFirstByKey($param['email'],'email');
		
		if(!empty($userInfo)){
			if($userInfo['password'] == $param['password']){
				unset($userInfo['password']);
				
				$result = $this->successRetun(array('basic' => $userInfo));
				
			}else{
				$result['message'] = '密码错误';
			}
		}else{
			$result['message'] = '用户不存在';
		}
		
		return $result;
	}
}
