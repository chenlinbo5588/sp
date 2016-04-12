<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Service {
	
	public static $CI = null;
	public static $memberModel = null;
	public static $districtModel = null;
	public static $form_validation = null;
	public static $settingModel = null;
	
	
	public static function initStaticVars(){
		self::$CI = & get_instance();
		self::$CI->load->model('Member_Model');
		self::$CI->load->model('Common_District_Model');
		self::$CI->load->library('form_validation');
		self::$CI->load->model('Setting_Model');
		
		self::$memberModel = self::$CI->Member_Model;
		self::$districtModel = self::$CI->Common_District_Model;
		self::$form_validation = self::$CI->form_validation;
		self::$settingModel = self::$CI->Setting_Model;
	}
	
	
	public function __construct(){
		//empty here
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
    	return  self::$districtModel->getFirstByKey($city_id);
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
