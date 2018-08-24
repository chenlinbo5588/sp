<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_service {
	
	public static $CI = null;
	public static $memberModel = null;
	public static $adminUserModel = null;
	public static $settingModel = null;
	
	
	protected $_dataModule;
	
	protected $_objectMap;
	
	public static function initStaticVars(){
		
		self::$CI = & get_instance();
		self::$CI->load->model(array('Member_Model','Adminuser_Model','Setting_Model'));
		
		self::$CI->load->library('form_validation');
		
		self::$memberModel = self::$CI->Member_Model;
		self::$adminUserModel = self::$CI->Adminuser_Model;
		
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
	
	
	
	/**
	 * 添加ID 校验
	 */
	public function addIDRules($idTypeList,$pIdType,$id = 0,$dbCheck = true,$tableName = ''){
		$idRules = array('required');
		
		if(ENVIRONMENT == 'production'){
			$idName = '';
			
			foreach($idTypeList as $idTypeItem){
				if($idTypeItem['id'] == $pIdType || $idTypeItem['show_name'] == $pIdType){
					$idName = $idTypeItem['show_name'];
				}
			}
			
			if('身份证' == $idName || '驾驶证' == $idName){
				$idRules[] = 'valid_idcard';
			}else{
				$idRules[] = 'max_length[50]';
			}
		}else{
			$idRules[] = 'max_length[50]';
		}
		
		//数据库校验
		if($dbCheck){
			if($id){
				$idRules[] = 'is_unique_not_self['.$tableName.".id_no.id.{$id}]";
			}else{
				$idRules[] = 'is_unique['.$tableName.".id_no]";
			}
		}
		
		self::$CI->form_validation->set_rules('id_no','证件号码',implode('|',$idRules));
	}
	
	
	/**
	 * 设置可见数据模块
	 */
	public function setDataModule($pModule){
		$this->_dataModule = $pModule;
	}
	
	public function getDataModule($condition,$fieldName = 'resident_id'){
		
		if($this->_dataModule){
			$condition['where_in'][] = array('key' => $fieldName, 'value' => $this->_dataModule);
		}
		
		return $condition;
	}
	
	
	
	/**
	 * 获得数据模块的Key
	 */
	private function _getDataModuleKey($pModelName){
		
		$dataModuleKey = 'resident_id';
		if('小区' == $pModelName){
			$dataModuleKey = 'id';
		}
		
		return $dataModuleKey;
	}
	
	/**
	 * 获得计数
	 */
	public function getRecordCount($pModelName,$condition){
		
		if(empty($this->_objectMap[$pModelName])){
			return false;
		}
		
		$dataModuleKey = $this->_getDataModuleKey($pModelName);
		
		return $this->_objectMap[$pModelName]->getCount($this->getDataModule($condition,$dataModuleKey));
		
	}
	
	
	/**
	 * 查询记录
	 */
	public function search($pModelName,$condition,$assocKey = ''){
		
		if(empty($this->_objectMap[$pModelName])){
			return false;
		}
		
		$dataModuleKey = $this->_getDataModuleKey($pModelName);
		
		if($assocKey){
			return $this->_objectMap[$pModelName]->getList($this->getDataModule($condition,$dataModuleKey),$assocKey);
		}else{
			return $this->_objectMap[$pModelName]->getList($this->getDataModule($condition,$dataModuleKey));
		}
			
	}
	
}
