<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Operation{
	//发起业务,未受理
	public static $submit = 1;
	
	//业务受理
	public static $accept = 2;
	
	//作业中
	public static $operation = 3;
		
	//已完成
	public static $complete = 4;
	
	//审核
	public static $examine = 5;
	
	//结款
	public static $payment = 6;
	
	//开票
	public static $invoice = 7;
	
	//转让中
	public static $transfer = 10;
	
	//已撤销
	public static $revoke = 20;
	
	public static $typeName = array(
		1 => '发起业务',
		2 => '业务受理',
		3 => '作业中',
		4 => '已完成',
		5 => '审核',
		6 => '结款',
		7 => '开票',
		10 => '转让中',
		20 => '已撤销',
	);
}


/**
 * 物业核心服务
 */
class Yewu_service extends Base_service {
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'Member_Model','Yewu_Model','Work_Group_Model','Yewu_Transfer_Model','Evaluate_Model','Member_Extend_Model',
			'Company_Model','Yewu_Detail_Model','Invoice_Model'
		));
		self::$CI->load->library(array('Basic_data_service','Admin_pm_service'));
		$this->_memberModel = self::$CI->Member_Model;
		$this->_yewuModel = self::$CI->Yewu_Model;
		$this->_workGroupModel = self::$CI->Work_Group_Model;
		$this->_companyModel = self::$CI->Company_Model;
		$this->_yewuTransferModel = self::$CI->Yewu_Transfer_Model;
		$this->_memberExtendModel = self::$CI->Member_Extend_Model;
		$this->_yewuDetailModel = self::$CI->Yewu_Detail_Model;
		$this->_basicDataServiecObj = self::$CI->basic_data_service;

	}
	public function getMenberInfoById($pId,$key = 'uid'){
		
		$uesrList = $this->_memberModel->getList(array(
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
	public function initMemberInfoBySession($pSession,$idKey = 'uid'){
		
		$idVal = $pSession['openid'];
		return $this->_memberExtendModel->getFirstByKey($idVal,$idKey);
		
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
		if($money < 5000){
			$result = $this->_yewuModel->updateByCondition(array(
				'plan_money' => $money * 100,
				'receivable_money' => $money * 100,
				'edit_uid' => $user['uid'],
				'edit_username' => $user['name'],
				'gmt_modify' => time(),
			),array('where' => array('id' => $yewuId)));
		}else{
			$result = $this->_yewuModel->updateByCondition(array(
				'planmoney' => $money,
				//'status' => 5,
				'edit_uid' => $user['uid'],
				'edit_username' => $user['name'],
				'gmt_modify' => time(),
			),array('where' => array('id' => $yewuId)));	
		}
		return $result;
	}
	
	
	
	public function addCompany($companyInfo,$userInfo){
		$insertData = $companyInfo;
		$insertData['add_uid'] = $userInfo['uid'];
		$insertData['add_name'] = $userInfo['name'];
		$insertData['gmt_create'] = time();
		$result = $this->_companyModel->_add($insertData);
		return $result;	
	}
	
	
	public function addYewuDetail($userInfo,$operation,$yewu_id){
		$addInfo = array(
			'yewu_id' => $yewu_id,
			'operation' => $operation,
			'time' => time(),
			'name' => $userInfo['name'],
			'mobile' => $userInfo['mobile'],
		);
		$result = $this->_yewuDetailModel->_add($addInfo);
		return $result;
		
	}
	
	
	public function getYewuList($id,$status = null,$groupId = null,$search,$userType){
		$ids[] = $id;
		$groupIds[] = $groupId;
		//不是区域领导
		if(4 != $userType){		
			if(is_array($status) && count($status) >0){
				$condition['where_in'][] = array('key' => 'status', 'value' => $status);
			}
			if($groupId){
				$condition['where_in'][] = array('key' => 'group_id', 'value' => $groupIds);
			}else{
				$condition['where_in'][] = array('key' => 'user_id', 'value' => $ids);
			}
			if($search){
				$condition['like']['yewu_name'] = $search;
			}
		}
		$condition['order'] = 'status ASC';
		$data = $this->_yewuModel->getList($condition);
		$basicData = $this->_basicDataServiecObj->getBasicDataList();
		foreach($data  as $key => $item){
			if($data[$key]['status'] != Operation::$revoke){
				$data[$key]['time'] =date("Y-m-d H:i",$data[$key]['gmt_create']) ;
				$data[$key]['mobile'] =mask_mobile($data[$key]['mobile']);
				$data[$key]['real_name'] =mask_name($data[$key]['real_name']);
				$data[$key]['user_name'] =mask_name($data[$key]['user_name']);
				$data[$key]['user_mobile'] =mask_mobile($data[$key]['user_mobile']);
				$data[$key]['work_category'] = $basicData[$item['work_category']]['show_name'];
				
				
				if($data[$key]['status'] == Operation::$transfer){
					$transfer = $this->_yewuTransferModel->getList(array('yewu_id' => $item['id'],'status' => '1'));
				}
				
				if($transfer){
					$data[$key]['group_name_from'] = $transfer[0]['group_name_from'];
					$data[$key]['group_name_to'] = $transfer[0]['group_name_to'];
				}
	

				$yewuDetailInfo = $this->_yewuDetailModel->getList(array('where' => array('operation' => $data[$key]['status'],'yewu_id' =>$key)));
				$data[$key]['worker_name'] =mask_name($yewuDetailInfo[0]['name']);
				$data[$key]['worker_mobile'] =mask_mobile($yewuDetailInfo[0]['mobile']);
			}
			
		}
		return $data;
	}
	
	/**
	 * 生成流水号
	 */
	public function generateSerialNumber($year,$area,$areaName){
		$countYear = $this->_yewuModel->getCount(array(
			'where' => array(
				'year' => $year,
			)
		));
		$countYear = str_pad($countYear,4,"0",STR_PAD_LEFT);
		$countArea = $this->_yewuModel->getCount(array(
			'where' => array(
				'year' => $year,
				'service_area' => $area,
			)
		));
		$countArea = str_pad($countArea,4,"0",STR_PAD_LEFT);
		$area = str_pad($area,3,"0",STR_PAD_LEFT);
		$acceptNumber = $year.$countYear.$area.$countArea;
		
		return array('accept_number' => $acceptNumber , 'encryption' => md5($acceptNumber));
	}
	
}
