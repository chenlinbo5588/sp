<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_service {
	
	public static $CI = null;
	public static $memberModel = null;
	public static $adminUserModel = null;
	public static $districtModel = null;
	public static $form_validation = null;
	public static $settingModel = null;
	
	
	
	public static function initStaticVars(){
		
		self::$CI = & get_instance();
		self::$CI->load->model(array('Member_Model','Adminuser_Model','Setting_Model'));
		
		self::$CI->load->model('Common_District_Model');
		
		self::$CI->load->library('form_validation');
		
		self::$memberModel = self::$CI->Member_Model;
		self::$adminUserModel = self::$CI->Adminuser_Model;
		
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
    
    /**
	 * 更新网站设置
	 */
	public function updateSetting($data){
		return self::$settingModel->batchUpdate($data, 'name');
	}
	
	/**
	 * 获得设置
	 */
	public function getSettingList($condition = array()){
		return self::$settingModel->getList($condition,'name');
	}
	
	
}
