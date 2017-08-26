<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budongchan_service extends Base_service {
	
	private $_deptModel;
	
	// 不动产登记
	private $_bdcModel ;
	private $_bdcFileModel;
	
	
	//不动产信息流程日志
	private $_bdcLogModel;
	
	
	//机构不动产信息可见性表
	private $_deptBdcModel;
	
	
	private $_bdcWorkflow;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Dept_Model', 'Bdc_Model', 'Bdc_File_Model','Bdc_Log_Model','Dept_Bdc_Model'));
		
		$this->_deptModel = self::$CI->Dept_Model;
		$this->_bdcModel = self::$CI->Bdc_Model;
		$this->_bdcLogModel = self::$CI->Bdc_Log_Model;
		$this->_bdcFileModel = self::$CI->Bdc_File_Model;
		$this->_deptBdcModel = self::$CI->Dept_Bdc_Model;
		
		
		self::$CI->config->load('bdc');
		$this->_bdcWorkflow = config_item('bdc_workflow');
	}
	
	
	public function getBdcWorkFlow(){
		return $this->_bdcWorkflow;
	}
	
	/***
	 * 
	 */
	public function getSerialNo($dept_id,$date){
		$howManyToday = $this->_bdcModel->getBussCount($dept_id,$date);
		
		if($howManyToday == 0){
			$howManyToday = 1;
		}else{
			$howManyToday++;
		}
		
		$dept = $this->_deptModel->getFirstByKey($dept_id,'id','code');
		
		return $date.$dept['code'].str_pad($howManyToday,4,'0',STR_PAD_LEFT);
	}
	
	/**
	 * 添加不动产登记信息
	 */
	public function addBdc($param,$who,$when,$pFiles = array()){
		$dateKey = date('Ymd',$when);
		
		//如果是测绘单位增加的登记,则直接跳到测绘流程
		$status_name = '新增登记';
		if('测绘单位' == $who['dept_type']){
			$status_name = '测绘';
		}
		
		$param = array_merge($param,array(
			'lsno' => $this->getSerialNo($who['dept_id'],$dateKey),
			'date_key' => $dateKey,
			'dept_id' => $who['dept_id'],
			'status' => $this->_bdcWorkflow[$status_name]['statusValue'],
			'status_name' => $status_name,
			'add_uid' => $who['uid'],
			'add_username' => $who['username'],
			'cur_dept_id' => $who['dept_id'],
			'cur_dept_sname' => $who['dept_sname'],
			'cur_uid' => $who['uid'],
			'cur_username' => $who['username'],
		));
		
		$this->_bdcModel->beginTrans();
		$newId = $this->_bdcModel->_add($param);
		
		
		$logStatus = array();
		if('测绘单位' == $who['dept_type']){
			$logStatus = array('新增登记' => 1,'测绘' => 2);
		}else{
			$logStatus = array('新增登记' => 1);
		}
		
		$logData = array();
		foreach($logStatus as $stName => $stValue){
			$logData[] = array(
				'lsno' => $param['lsno'],
				'dept_id' => $who['dept_id'],
				'dept_sname' => $who['dept_sname'],
				'user_id' => $who['uid'],
				'username' => $who['username'],
				'bdc_id' => $newId,
				'is_complete' => 1,
				'status' => $stValue,
				'status_name' => $stName,
				'add_uid' => $who['uid'],
				'add_username' => $who['username'],
				'gmt_create' => $when,
			);
		}
		
		if($logData){
			$logData[count($logData) - 1]['is_complete'] = 0;
			$rowsCnt = $this->_bdcLogModel->batchInsert($logData);
		}
		
		
		$this->_deptBdcModel->_add(array(
			'dept_id' => $who['dept_id'],
			'bdc_id' => $newId,
			'lsno' => $param['lsno'],
			'name' => $param['name'],
		));
		
		$affectRow = $this->_bdcFileModel->_add(array(
			'bdc_id' => $newId,
			'fileinfo' => json_encode(array($who['dept_id'] => $pFiles)),
			'add_uid' => $who['uid'],
			'add_username' => $who['username'],
		),true);
		
		
		if($pFiles){
			//把附件过期时间戳取消
			$updateData = array();
			foreach($pFiles as $file){
				$updateData[] = array('id' => $file['id'],'expire_time' => 0);
			}
			
			self::$attachmentModel->batchUpdate($updateData);
		}
		
		
		if(FALSE === $this->_bdcModel->getTransStatus()){
			$this->_bdcModel->rollBackTrans();
			return false;
		}else{
			$this->_bdcModel->commitTrans();
			return $newId;
		}
	}
	
	
	/**
	 * 
	 */
	public function addBdcFile($bdcId,$pFile,$who){
		
		$file = $this->_bdcFileModel->getFirstByKey($bdcId,'bdc_id','fileinfo');
		$fileList = json_decode($file['fileinfo'],true);
		
		$fileList[$who['dept_id']][$pFile['id']] = array(
			'id' => $pFile['id'],
			'orig_name'  => $pFile['orig_name'],
			'file_size'  => $pFile['file_size'],
			'is_image'  => $pFile['is_image'],
			'file_url'  => $pFile['file_url'],
			'uid'  => $pFile['uid'],
			'username'  => $pFile['username'],
			'gmt_create'  => $pFile['gmt_create'],
		);
		
		$affectRow = $this->_bdcFileModel->update(array(
			'fileinfo' => json_encode($fileList),
		),array('bdc_id' => $bdcId));
		
		return $affectRow;
	}
	
	/**
	 * 
	 */
	public function deleteBdcFileById($bdcId,$fileId,$who){
		
		$file = $this->_bdcFileModel->getFirstByKey($bdcId,'bdc_id','fileinfo');
		$fileList = json_decode($file['fileinfo'],true);
		
		$fileIdsList = array();
		
		if($fileList){
			foreach($fileList as $deptId => $deptFileList){
				foreach($deptFileList as $oneFile){
					$fileIdsList[] = $oneFile['id'];
				}
			}
		}
		
		//在文件里列表中
		if(in_array($fileId,$fileIdsList)){
			if($fileList[$who['dept_id']][$fileId]){
				//只可以删除在自己的机构下的文件
				
				@unlink(dirname(ROOTPATH).DIRECTORY_SEPARATOR.'filestore'.DIRECTORY_SEPARATOR.$fileList[$who['dept_id']][$fileId]['file_url']);
				unset($fileList[$who['dept_id']][$fileId]);
				
				$affectRow = $this->_bdcFileModel->update(array(
					'fileinfo' => json_encode($fileList),
				),array('bdc_id' => $bdcId,));
				
				return 0;
			}else{
				//不在自己的机构范围内,不能删除
				return 1;
			}
		}else{
			//不在当前文件列表中
			return -1;
		}
	}
	
	
	
	
	/**
	 * 编辑不动产信息
	 */
	public function editBdc($param,$who){
		
		$param['edit_uid'] = $who['uid'];
		$param['edit_username'] = $who['username'];
		
		return $this->_bdcModel->update($param,array('id' => $param['id']));
	}
	
	/**
	 * 保存不动产号码
	 */
	public function storeBdcNo($id,$param){
		return $this->_bdcModel->update($param,array('id' => $id));
	}
	
	/**
	 * 进入到下一步
	 */
	public function gonext($pId,$param,$who){
		$bdcInfo = $this->_bdcModel->getFirstByKey($pId);
		
		$param['edit_uid'] = $who['uid'];
		$param['edit_username'] = $who['username'];
		
		$toDeptId = intval($param['to_dept']);
		
		if($toDeptId){
			//部门单位
			$deptInfo = $this->_deptModel->getFirstByKey($toDeptId,'id','short_name');
		}
		
		//下一个状态
		$nextStep = $this->_bdcWorkflow[$bdcInfo['status_name']]['nextStep'];
		
		
		if($nextStep){
			$this->_bdcModel->beginTrans();
		
			//如果有下一个状态 则修改到下一个状态
			$affectRow = $this->_bdcModel->update(array_merge($param,array(
				'cur_dept_id' => $toDeptId,
				'cur_dept_sname' => $deptInfo['short_name'],
				'cur_uid' => 0,
				'cur_username' => '',
				'status' => $this->_bdcWorkflow[$nextStep]['statusValue'],
				'status_name' => $nextStep
			)),array('id' => $pId));
			
			
			//修改当前状态为已完成
			$this->_bdcLogModel->update(array(
				'is_complete' => 1,
				'remark' => strlen($param['remark']) == 0 ? '' : $param['remark'],
				'edit_uid' => $who['uid'],
				'edit_username' => $who['username']
			),array('bdc_id' => $pId,'status' => $bdcInfo['status']));
			
			
			//下一个步骤状态
			$nextStepCnt = $this->_bdcLogModel->getCount(array(
				'where' => array(
					'bdc_id' => $pId,
					'status' => $this->_bdcWorkflow[$nextStep]['statusValue']
				)
			));
			
			if(!$nextStepCnt && $toDeptId){
				$this->_bdcLogModel->_add(array(
					'lsno' => $bdcInfo['lsno'],
					'dept_id' => $toDeptId,
					'dept_sname' => $deptInfo['short_name'],
					'bdc_id' => $bdcInfo['id'],
					'status' => $this->_bdcWorkflow[$nextStep]['statusValue'],
					'status_name' => $nextStep,
					'add_uid' => $who['uid'],
					'add_username' => $who['username'],
				));
			}else if($nextStepCnt && $toDeptId){
				$this->_bdcLogModel->update(array(
					'dept_id' => $toDeptId,
					'dept_sname' => $deptInfo['short_name'],
					'edit_uid' => $who['uid'],
					'edit_username' => $who['username'],
				),array('bdc_id' => $toDeptId,'status' => $this->_bdcWorkflow[$nextStep]['statusValue']));
			}
			
			if($toDeptId){
				$this->_deptBdcModel->_add(array(
					'dept_id' => $toDeptId,
					'bdc_id' => $bdcInfo['id'],
					'lsno' => $bdcInfo['lsno'],
					'name' => $bdcInfo['name'],
				),true);
			}
			
			
			if(FALSE === $this->_bdcModel->getTransStatus()){
				$this->_bdcModel->rollBackTrans();
				return false;
			}else{
				return $this->_bdcModel->commitTrans();
			}
		
		}else{
			//如果是最后一个步骤,则更新最后日志状态即可
			$affectRow = $this->_bdcLogModel->update(array(
				'is_complete' => 1,
				'remark' => strlen($param['remark']) == 0 ? '' : $param['remark'],
				'edit_uid' => $who['uid'],
				'edit_username' => $who['username']
			),array('bdc_id' => $pId,'status' => $bdcInfo['status']));
			
			if($affectRow >=0){
				return true;
			}else{
				
				return false;
			}
		}
	}
	
	
	/**
	 * 撤销提交
	 */
	public function revert($bdcInfo,$who,&$lastStatusInfo){
		
		$currentStatus = $this->_bdcWorkflow[$bdcInfo['status_name']];
		$preStatus = $this->_bdcWorkflow[$currentStatus['preStep']];
		
		$this->_bdcModel->beginTrans();
		
		//经过的部门历史
		$bdcDeptList = $this->_bdcLogModel->getList(array(
			'where' => array(
				'bdc_id' => $bdcInfo['id'],
			)
		),'status_name');
		
		
		$currentDeptHistoryCnt = 0;
		//如果流程中 有两处以上 经过同一个部门，则不能删除该条不动产信息的对该部门的可见性
		foreach($bdcDeptList as $logDeptItem){
			if($bdcInfo['cur_dept_id'] == $logDeptItem['dept_id']){
				$currentDeptHistoryCnt++;
			}
		}
		
		//如果有前一个步骤
		if($currentStatus['preStep'] && $bdcDeptList[$currentStatus['preStep']]){
			//回退一步,获得上个状态 的部门 和用户
			$lastStatusInfo = $bdcDeptList[$currentStatus['preStep']];
			
			//上一个状态设置为未完成
			$this->_bdcLogModel->update(array(
				'edit_uid' => $who['uid'],
				'edit_username' => $who['username'],
				'is_complete' => 0,
			),array('id' => $lastStatusInfo['id']));
		}
		
		
		//删除当前状态日志
		$this->_bdcLogModel->deleteByWhere(array(
			'bdc_id' => $bdcInfo['id'],
			'status' => $currentStatus['statusValue']
		));
		
		/*
		//收回对部门机构的可见兴
		if($currentDeptHistoryCnt <= 1){
			$this->_deptBdcModel->deleteByWhere(array(
				'dept_id' => $bdcInfo['cur_dept_id'],
				'bdc_id' => $bdcInfo['id'],
			));
		}
		*/
		
		//修改当前不动产信息的状态
		$affectRow = $this->_bdcModel->update(array(
			'cur_dept_id' => $who['dept_id'],
			'cur_dept_sname' => $who['dept_sname'],
			'cur_uid' => $who['uid'],
			'cur_username' => $who['username'],
			'edit_uid' => $who['uid'],
			'edit_username' => $who['username'],
			'status' => $preStatus['statusValue'],
			'status_name' => $currentStatus['preStep']
		),array('id' => $bdcInfo['id'],'status' => $currentStatus['statusValue']));
		
		
		if($affectRow == 0){
			$this->_bdcModel->rollBackTrans();
			return false;
		}
		
		
		if(FALSE === $this->_bdcModel->getTransStatus()){
			$this->_bdcModel->rollBackTrans();
			return false;
		}else{
			return $this->_bdcModel->commitTrans();
		}
		
	}
	
	
	/**
	 * 退回
	 */
	public function sendback($pId,$param,$who, &$lastStatusInfo){
		
		$bdcInfo = $this->_bdcModel->getFirstByKey($pId);
		
		$currentStatus = $this->_bdcWorkflow[$bdcInfo['status_name']];
		$preStatus = $this->_bdcWorkflow[$currentStatus['preStep']];
		
		
		$this->_bdcModel->beginTrans();
		
		
		//不动产部门信息历史
		$bdcDeptList = $this->_bdcLogModel->getList(array(
			'where' => array(
				'bdc_id' => $bdcInfo['id'],
			)
		),'status_name');
		
		
		if($currentStatus['preStep'] && $bdcDeptList[$currentStatus['preStep']]){
			//回退一步,获得上个状态 的部门 和用户
			$lastStatusInfo = $bdcDeptList[$currentStatus['preStep']];
			
			//上一个状态设置为未完成
			$this->_bdcLogModel->update(array(
				'edit_uid' => $who['uid'],
				'edit_username' => $who['username'],
				'is_complete' => 0,
			),array('id' => $lastStatusInfo['id']));
			
			
			//将退回原因写上
			$this->_bdcLogModel->update(array(
				'is_complete' => 0,
				'reason' => $param['reason'],
			),array(
				'bdc_id' => $bdcInfo['id'],
				'status' => $currentStatus['statusValue']
			));
			
			
			$affectRow = $this->_bdcModel->update(array(
				'is_done' => 0,
				'cur_dept_id' => $lastStatusInfo['dept_id'],
				'cur_dept_sname' => $lastStatusInfo['dept_sname'],
				'cur_uid' => $lastStatusInfo['user_id'],
				'cur_username' => $lastStatusInfo['username'],
				'edit_uid' => $who['uid'],
				'edit_username' => $who['username'],
				'status' => $lastStatusInfo['status'],
				'status_name' => $lastStatusInfo['status_name']
				
			),array('id' => $bdcInfo['id'],'cur_dept_id' => $who['dept_id']));
			
			if($affectRow == 0){
				$this->_bdcModel->rollBackTrans();
				return false;
			}
		}
		
		
		if(FALSE === $this->_bdcModel->getTransStatus()){
			$this->_bdcModel->rollBackTrans();
			return false;
		}else{
			return $this->_bdcModel->commitTrans();
		}
		
	}
	
	
	
	
	/**
	 * 获得可见性
	 */
	public function getBdcVisibleByDept($pId,$deptId){
		return $this->_deptBdcModel->getCount(array(
			'where' => array(
				'dept_id' => $deptId,
				'bdc_id' => $pId
			)
		));
		
	}
	
	
	/**
	 * 获得不动产信息
	 */
	public function getBdcInfoById($id){
		$info = $this->_bdcModel->getFirstByKey($id);
		
		//按照状态
		$statusList = $this->_bdcLogModel->getList(array(
			'where' => array(
				'bdc_id' => $id
			)
		),'status_name');
		
		$file = $this->_bdcFileModel->getFirstByKey($id,'bdc_id','fileinfo');
		
		$info['statusLog'] = $statusList;
		$info['fileList'] = json_decode($file['fileinfo'],true);
		
		return $info;
	}
	
	
	/**
	 * 获得一个目标机构
	 */
	public function getNextOrgList($bdcInfo){
		return $this->_deptModel->getList($this->_bdcWorkflow[$bdcInfo['status_name']]['nextDeptCondition']);
	}
	
	
	/**
	 * 不动产受理
	 */
	public function shouli($pId,$statusValue,$who){
		
		$this->_bdcModel->beginTrans();
		
		$rows = $this->_bdcModel->update(array(
			'cur_uid' => $who['uid'],
			'cur_username' => $who['username'],
			'edit_uid' => $who['uid'],
			'edit_username' => $who['username'],
		),array('id' => $pId, 'cur_dept_id' => $who['dept_id'],'cur_uid' => 0));
		
		
		if(!$rows){
			$this->_bdcModel->rollBackTrans();
			return false;
		}
		
		$this->_bdcLogModel->update(array(
			'user_id' => $who['uid'],
			'username' => $who['username'],
			'edit_uid' => $who['uid'],
			'edit_username' => $who['username'],
		),array('bdc_id' => $pId,'status' => $statusValue));
		
		
		if(FALSE === $this->_bdcModel->getTransStatus()){
			$this->_bdcModel->rollBackTrans();
			return false;
		}else{
			return $this->_bdcModel->commitTrans();
		}
	
	}
	
	
	/**
	 * 通知所有的流程机构,除去自己
	 */
	public function notifyAll($pId,$url,$who){
		$bdcInfo = $this->getBdcInfoById($pId);
		
		$messageService = $this->getMessageService();
		
		foreach($bdcInfo['statusLog'] as $statusName => $statusLog){
			if($statusLog['user_id'] != $who['uid']){
				$messageService->sendPrivatePm(array(
					'uid' => $statusLog['user_id'],
				),$who['uid'],'<span>【不动产资料通过'.$bdcInfo['status_name'].'】'.$bdcInfo['name'].'</span>','<span>您的不动产资料已通过'.$bdcInfo['status_name'].',点击流水号查看详情</span> <a class="link" href="'.$url.'">'.$bdcInfo['lsno'].'</a>',false,false);
			}
		}
		
		return true;
	}
	
	
	public function notifyLastUser($pId,$url,$who,$lastUser,$moreText){
		if($who['uid'] == $lastUser['user_id']){
			return false;
		}
		
		$bdcInfo = $this->_bdcModel->getFirstByKey($pId);
		$messageService = $this->getMessageService();
		
		$messageService->sendPrivatePm(array(
			'uid' => $lastUser['user_id'],
		),$who['uid'],'<span class="error">【不动产资料退回】'.$bdcInfo['name'].'</span>','<span>您的不动产资料被退回,点击流水号查看详情 </span> <a class="link" href="'.$url.'">'.$bdcInfo['lsno'].'</a>'.$moreText,false,false);
		
		return true;
	}
	
}
