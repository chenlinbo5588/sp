<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 物业核心服务
 */
class Wuye_service extends Base_service {
	
	private $_residentModel;
	private $_buildingModel;
	private $_houseModel;
	private $_memberModel;
	private $_wxCustomerModel;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'Resident_Model','Building_Model','House_Model',
			'Member_Model','Wx_Customer_Model'
		
		));
		
		$this->_residengtModel = self::$CI->Resident_Model;
		$this->_buildingModel = self::$CI->Building_Model;
		$this->_houseModel = self::$CI->House_Model;
		
	}
	

	// 小区管理
	public function deleteResident($pId){
		//只有悬挂的小区才能被删除
		
	}
	
	/*
	public function saveResident($pResidentParam,$who){
		$returnVal = false;
		if(!isset($pResidentParam['id'])){
			//事务
			$returnVal = $this->_residengtModel->_add(array_merge($pResidentParam,$who));
		}else{
			
			$this->_residengtModel->beginTrans();
			$returnVal = $this->_residengtModel->update(array_merge($pResidentParam,$who),array('id' => $pResidentParam['id']));
			
			
			//修改小区名称，则自动更新对象的幢以及
			
			
			if($this->_residengtModel->getTransStatus() === FALSE){
				$this->_residengtModel->rollBackTrans();
				return false;
			}else{
				$this->_residengtModel->commitTrans();
				return $returnVal;
			}
		}
	}
	*/
	
		
}
