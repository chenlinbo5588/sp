<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budongchan_service extends Base_service {
	
	private $_deptModel;
	
	// 不动产登记
	private $_bdcModel ;
	private $_bdcFileModel;
	
	
	//不动产信息流程日志
	private $_bdcLogModel;
	
	private $_bdcWorkflow;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Dept_Model', 'Bdc_Model', 'Bdc_File_Model','Bdc_Log_Model'));
		
		$this->_deptModel = self::$CI->Dept_Model;
		$this->_bdcModel = self::$CI->Bdc_Model;
		$this->_bdcLogModel = self::$CI->Bdc_Log_Model;
		$this->_bdcFileModel = self::$CI->Bdc_File_Model;
		
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
			'status' => $this->_bdcWorkflow['不动产业务'][$status_name]['statusValue'],
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
				'status' => $stValue,
				'status_name' => $stName,
				'remark' => '已受理',
				'add_uid' => $who['uid'],
				'add_username' => $who['username'],
				'gmt_create' => $when,
			);
		}
		
		$rowsCnt = $this->_bdcLogModel->batchInsert($logData);
		
		
		
		
		
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
					'bdc_id' => $bdcId,
					'fileinfo' => json_encode($fileList),
				));
				
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
	 * 进入到下一步
	 */
	public function gonext($param,$who){
		
		$param['edit_uid'] = $who['uid'];
		$param['edit_username'] = $who['username'];
		
		$toDeptId = intval($param['to_dept']);
		
		$deptInfo = $this->_deptModel->getFirstByKey($toDeptId,'id','short_name');
		
		//下一个状态
		$nextStep = $this->_bdcWorkflow['不动产业务'][$param['status_name']]['nextStep'];
		
		$this->_bdcModel->beginTrans();
		//修改到下一个状态
		$affectRow = $this->_bdcModel->update(array_merge($param,array(
			'cur_dept_id' => $toDeptId,
			'cur_dept_sname' => $deptInfo['short_name'],
			'cur_uid' => 0,
			'cur_username' => '',
			'status' => $this->_bdcWorkflow['不动产业务'][$nextStep]['statusValue'],
			'status_name' => $nextStep
		)),array('id' => $param['id']));
		
		
		$nextStepCnt = $this->_bdcLogModel->getCount(array(
			'where' => array(
				'bdc_id' => $param['id'],
				'status' => $this->_bdcWorkflow['不动产业务'][$nextStep]['statusValue']
			)
		));
		
		if(!$nextStepCnt && $toDeptId){
			$this->_bdcLogModel->_add(array(
				'lsno' => $param['lsno'],
				'dept_id' => $param['to_dept'],
				'dept_sname' => $deptInfo['short_name'],
				'bdc_id' => $param['id'],
				'status' => $this->_bdcWorkflow['不动产业务'][$nextStep]['statusValue'],
				'status_name' => $nextStep,
				'add_uid' => $who['uid'],
				'add_username' => $who['username'],
			));
		}
		
		if(FALSE === $this->_bdcModel->getTransStatus()){
			$this->_bdcModel->rollBackTrans();
			return false;
		}else{
			return $this->_bdcModel->commitTrans();
		}
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
		return $this->_deptModel->getList($this->_bdcWorkflow['不动产业务'][$bdcInfo['status_name']]['nextDeptCondition']);
	}
	
}
