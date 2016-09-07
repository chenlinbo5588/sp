<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_service {
	
	public static $CI = null;
	public static $dbInstance = null;
	public static $memberModel = null;
	public static $adminUserModel = null;
	public static $districtModel = null;
	public static $form_validation = null;
	public static $settingModel = null;
	
	
	public function __construct(){
		//empty here
	}
	
	public static function initStaticVars(){
		
		self::$CI = & get_instance();
		self::$CI->load->model(array(
			'Setting_Model',
			'Adminuser_Model',
			'Common_District_Model'
		));
		
		self::$CI->load->library('form_validation');
		
		self::$form_validation = self::$CI->form_validation;
		
		self::$memberModel = self::$CI->Member_Model;
		self::$adminUserModel = self::$CI->Adminuser_Model;
		self::$districtModel = self::$CI->Common_District_Model;
		self::$settingModel = self::$CI->Setting_Model;
		
		self::$dbInstance = self::$CI->Member_Model->getDb();
		
	}
	
	
	/**
     * 解析 地址名称 反过来得到 id
     * 
     * @param $dnames  array('浙江省','宁波市','慈溪市','浒山街道')
     * 
     * @return $arrayWithIds array(230,400,790,1222);
     */
    public function getDistrictIdByNames($dnames){
    	
    	$dIdList = self::$districtModel->getList(array(
			'select' => 'id,name',
			'where_in' => array(
				array('key' => 'name', 'value' => $dnames)
			)
		));
		
		$ids = array();
		foreach($dIdList as $dn){
			$ids[$dn['name']] = $dn['id'];
		}
		
		$arrayWithIds = array();
		foreach($dnames as $key => $value){
			$arrayWithIds[$key] = empty($ids[$value]) ? 0 : $ids[$value];
		}
		
		return $arrayWithIds;
		
    }
    
	/**
	 * 根据输入的 IDS 数组，返回带名称的数据组
	 * @param $dIDs  array(230,400,790,1222)
     * 
     * @return $arrayWithName array('浙江省','宁波市','慈溪市','浒山街道');
	 */
	public function getDistrictNameByIds($dIDs){
		
		$dNameList = self::$districtModel->getList(array(
			'select' => 'id,name',
			'where_in' => array(
				array('key' => 'id', 'value' => $dIDs)
			)
		));
		
		$dName = array();
		foreach($dNameList as $dn){
			$dName[$dn['id']] = $dn['name'];
		}
		
		$arrayWithName = array();
		$names = array();
		
		foreach($dIDs as $key => $value){
			$arrayWithName[$key] = empty($dName[$value]) ? '' : $dName[$value];
		}
		
		$names['province'] = $arrayWithName[0];
		$names['city'] = $arrayWithName[1];
		$names['district'] = $arrayWithName[2];
		$names['street'] = $arrayWithName[3];
		
		return $names;
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
	
	
	public function getSettingList($condition = array()){
		$list = self::$settingModel->getList($condition);
		return $this->toEasyUseArray($list,'name');
	}
}
