<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_Service extends Base_Service {

	private $_msgTemplateModel = null;

	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Msg_Template_Model');
		$this->_msgTemplateModel = self::$CI->Msg_Template_Model;
	}
	
	
	public function getMsgTemplateByCode($code){
		return $this->_msgTemplateModel->getFirstByKey($code,'code');
	}
	
	/**
	 * 
	 */
	public function getListByCondition($condition){
		return $this->toEasyUseArray($this->_msgTemplateModel->getList($condition),'code');
	}
	
	
	/**
	 * 
	 */
	public function switchMsgTemplateStatus($switchValue,$ids){
		
		$data = array();
		foreach($ids as $id){
			$data[] = array(
				'code' => $id,
				'is_off' => $switchValue == 'mail_switchON' ? 0 : 1
			);
		}
		
		if($data){
			return $this->_msgTemplateModel->batchUpdate($data,'code');
		}
		
		return false;
		
		
	}
}
