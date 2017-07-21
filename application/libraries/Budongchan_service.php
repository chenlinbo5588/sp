<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budongchan_service extends Base_service {
	
	private $_deptModel;
	
	// 不动产登记
	private $_dbcdjModel ;
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Dept_Model', 'Bdcdj_Model'));
		
		$this->_deptModel = self::$CI->Dept_Model;
		$this->_bdcdjModel = self::$CI->Bdcdj_Model;
	}
	
	
	/***
	 * 
	 */
	public function getSerialNo($dept_id,$date){
		$howManyToday = $this->_bdcdjModel->getBussCount($dept_id,$date);
		
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
	public function addBdc($param,$who,$when){
		$dateKey = date('Ymd',$when);
		
		$param['lsno'] = $this->getSerialNo($who['dept_id'],$dateKey);
		$param['dept_id'] = $who['dept_id'];
		$param['date_key'] = $dateKey;
		
		
		return $this->_bdcdjModel->_add($param);
		
	}
	
}
