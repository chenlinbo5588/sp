<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class WorkCategory{
	//房产确权
	public static $houseRight = 1;
	
	//土地测量
	public static $landSurvey = 2;
	
	public static $typeName = array(
		1 => '房产确权',
		2 => '土地测量',
	);
}


/**
 * 物业核心服务
 */
class Yewu_service extends Base_service {
	
	
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'User_Model','Yewu_Model','Work_Group_Model',
		));
		self::$CI->load->library(array('Basic_data_service','Admin_pm_service'));
		$this->_userModel = self::$CI->User_Model;
		$this->_workGroupModel = self::$CI->Work_Group_Model;

		

	}
	public function getUserInfoById($pId,$key = 'uid'){
		
		$uesrList = $this->_userModel->getList(array(
			'where' => array(
				$key => $pId
			),
			'limit' => 1
		));
		
		if($uesrList[0]){		
			return $uesrList[0];
		}else{
			return array();
		}
		
	}
	/**
	 * 根据会话 初始话 相关数据
	 * 
	 */
	public function initUserInfoBySession($pSession,$idKey = 'openid'){
		
		$idVal = $pSession[$idKey];
		
		return self::$memberModel->getFirstByKey($idVal,$idKey);
		
	}
	
	public function getGroupInfo($serviceArea){
		$groupList = $this->_workGroupModel->getList();
		foreach($groupList as $key => $item){
			if(is_array(json_decode($item['service_area'],true))){
				if(in_array($serviceArea,json_decode($item['service_area'],true))){
					return $groupList[$key];
				}
			}
		
		}
		return array('id' =>0);
		
	}
	
}
