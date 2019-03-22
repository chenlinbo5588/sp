<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UserType{
	//用户
	public static $user = 1;
	
	//普通工作人员
	public static $worker = 2;
	
	//工作组组长
	public static $groupLeader = 3;
	
	//领导
	public static $landSurvey = 4;
	
	//管理员
	public static $admin = 5;
	
	public static $typeName = array(
		1 => '用户',
		2 => '普通工作人员',
		3 => '工作组组长',
		4 => '领导',
		5 => '管理员',
		
	);
}


/**
 * 物业核心服务
 */
class User_service extends Base_service {
	
	
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'User_Model','User_Extend_Model','Company_Model','Yewu_Model','Work_Group_Model','Worker_Member_Model',
			'Company_Member_Model',
		));
		
		$this->_residentModel = self::$CI->Resident_Model;
		$this->_userModel = self::$CI->User_Model;

		$this->_dataModule = array(-1);

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
