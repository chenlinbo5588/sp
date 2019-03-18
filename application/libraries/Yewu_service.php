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
			'Resident_Model','Building_Model','User_Model'
		));
		
		$this->_residentModel = self::$CI->Resident_Model;
		$this->_userModel = self::$CI->User_Model;

		$this->_dataModule = array(-1);
		
		$this->_objectMap = array(
			'小区' => $this->_residentModel,
			'停车位' => $this->_parkingModel,
		);

	}
	public function getUserInfoById($pId,$key = 'uid'){
		
		$uesrList = $this->$this->_userModel->getList(array(
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
	
	

	

}
