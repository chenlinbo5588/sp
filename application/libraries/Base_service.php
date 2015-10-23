<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Service {

	protected $CI ;
	protected $_memberModel;
	protected $_districtModel;
	public $form_validation;
	
	
	public function __construct(){
		$this->CI = & get_instance();
		$this->CI->load->model('Member_Model');
		$this->CI->load->model('Common_District_Model');
		$this->CI->load->library('form_validation');
		
		$this->_memberModel = $this->CI->Member_Model;
		$this->_districtModel = $this->CI->Common_District_Model;
		
		$this->form_validation = $this->CI->form_validation;
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
    
    
    /**
     * 转换成更容易使用的数组
     */
    public function toEasyUseArray($list, $primaryKey = 'id'){
    	$temp = array();
		
		if($list){
			if(isset($list['data'])){
				foreach($list['data'] as $item){
					$temp[$item[$primaryKey]] = $item;
				}
				
				return array('data' => $temp, 'pager' =>$list['pager'] );
			}else{
				foreach($list as $item){
					$temp[$item[$primaryKey]] = $item;
				}
				
				return $temp;
			}
		}
		
		return $temp;
    }
}
