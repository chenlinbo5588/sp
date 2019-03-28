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
			'User_Model','Yewu_Model','Work_Group_Model','Yewu_Transfer_Model','Evaluate_Model','User_Extend_Model',
			'Company_Model'
		));
		self::$CI->load->library(array('Basic_data_service','Admin_pm_service'));
		$this->_userModel = self::$CI->User_Model;
		$this->_yewuModel = self::$CI->Yewu_Model;
		$this->_workGroupModel = self::$CI->Work_Group_Model;
		$this->_companyModel = self::$CI->Company_Model;
		$this->_yewuTransferModel = self::$CI->Yewu_Transfer_Model;
		$this->_userExtendModel = self::$CI->User_Extend_Model;

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
	public function initUserInfoBySession($pSession,$idKey = 'uid'){
		
		$idVal = $pSession['openid'];
		return $this->_userExtendModel->getFirstByKey($idVal,$idKey);
		
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

	
	public function changeTansfer($param,$user,$fromFroupInfo){
		$this->_yewuModel->beginTrans();
		if('同意' == $param['opinion']){
			$this->_yewuTransferModel->updateByCondition(
				array('status' => 2,),
				array('where' => array(
					'yewu_id' => $param['yewu_id'],
					'status' => 1,
					'group_id_to' => $param['group_id'],
				)));
			 	$this->_yewuModel->updateByCondition(
					array(
						'status' => 2,
						'edit_uid' => $user['uid'],
						'edit_username' => $user['name'],
						'gmt_modify' => time(),
						
					),
					array('where' => array('id' => $param['yewu_id']))
				);
		}else if('不同意' == $param['opinion']){
			$this->_yewuTransferModel->updateByCondition(
				array('status' => 3,),
				array('where' => array(
					'yewu_id' => $param['yewu_id'],
					'status' => 1,
					'group_id_to' => $param['group_id'],
				)));
			 	$this->_yewuModel->updateByCondition(
					array(
						'worker_id' => $fromFroupInfo['group_leaderid'],
						'worker_name' => $fromFroupInfo['group_leader_name'],
						'worker_mobile' => $fromFroupInfo['group_leader_mobile'],
						'current_group' => $fromFroupInfo['id'],
						'status' => 2,
						'edit_uid' => $user['uid'],
						'edit_username' => $user['name'],
						'gmt_modify' => time(),
						
					),
					array('where' => array('id' => $param['yewu_id']))
				);
		}
		if($this->_yewuModel->getTransStatus() === FALSE){
			$this->_yewuModel->rollBackTrans();
			return false;
		}else{
			$this->_yewuModel->commitTrans();
			return true;
		}
	}
	
	public function setYewuMoney($yewuId,$money,$user){
		if($money<5000){
			$result = $this->_yewuModel->updateByCondition(array(
				'plan_money' => $money,
				'receivable_money' => $money,
				'status' => 4,
				'edit_uid' => $user['uid'],
				'edit_username' => $user['name'],
				'gmt_modify' => time(),
			),array('where' => array('id' => $yewuId)));
		}else{
			$result = $this->_yewuModel->updateByCondition(array(
				'planmoney' => $money,
				'status' => 3,
				'edit_uid' => $user['uid'],
				'edit_username' => $user['name'],
				'gmt_modify' => time(),
			),array('where' => array('id' => $yewuId)));	
		}
		return $result;
	}
	public function addCompany($companyInfo,$userInfo){
		$insertData = $companyInfo;
		$insertData['add_uid'] = $userInfo['id'];
		$insertData['add_name'] = $userInfo['name'];
		$insertData['gmt_create'] = time();
		$result = $this->_companyModel->_add($insertData);
		return $result;	
	}
	
}
