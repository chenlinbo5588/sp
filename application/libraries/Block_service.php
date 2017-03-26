<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Block_service extends Base_service {
	
	private $_blockModel = null;
	private $_blockDataModel = null;
	

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Block_Model', 'Block_Data_Model'));
		
		$this->_blockModel = self::$CI->Block_Model;
		$this->_blockDataModel = self::$CI->Block_Data_Model;
	}
	
	
	public function getDataByCodeId($code_id){
		$codeInfo = $this->_blockDataModel->getById(array(
			'where' => array(
				'code_id' => $code_id
			)
		));
		
		$codeInfo['json_array'] = json_decode($codeInfo['data'],true);;
		
		if(empty($codeInfo['json_array'])){
			$codeInfo['json_array'] = array();
		}
		
		return $codeInfo;
		
	}
	
}
