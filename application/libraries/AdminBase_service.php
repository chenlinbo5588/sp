<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminBase_Service extends Base_Service {
	
	public static $adminUserModel;

	public function __construct(){
		parent::__construct();
	}
	
	public static function initStaticVars(){
		
		self::$CI->load->model('Adminuser_Model');
		
		self::$adminUserModel = self::$CI->Adminuser_Model;
		
	}
	
	public function getSettingList($condition){
		$list = self::$settingModel->getList($condition);
		
		return $this->toEasyUseArray($list,'name');
		
	}
	
	/**
	 * 更新网站设置
	 */
	public function updateSetting($data){
		return self::$settingModel->batchUpdate($data, 'name');
	}
	
}
