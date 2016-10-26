<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 登陆态
 */
class MyYdzj_Controller extends Ydzj_Controller {
	private $_pushObject;
	protected $_loginUID = 0;
	protected $_currentOid = 0;
	public $_newpm = 0;
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->isLogin()){
			//$this->session->unset_userdata(array($this->_lastVisitKey,$this->_profileKey));
			js_redirect('member/login');
		}
		
		$this->load->library('Lab_service');
		
		
		$this->load->config('member_site');
		$this->_loginUID = $this->_profile['basic']['uid'];
		
		$this->_navs();
		
		
		/*
		$this->_pushObject = $this->base_service->getPushObject();
		$this->assign('pushConfig',config_item('huanxin'));
		*/
		
		$refresh = false;
		$spm = $this->input->get('spm');
		
		if($spm){
			//用于用户登陆后直接刷新站内信，可以避免首次等待
			$refreshIime = $this->encrypt->decode($spm);
			
			if($refreshIime && $refreshIime - $this->_reqtime > 0){
				$refresh = true;
			}
		}
		
		if($refresh || $this->_reqInterval >= config_item('pmcheck_interval')){
			$this->_pmUpdate();
		}
		
		$this->_initUserParam();
	}
	
	
	/**
	 * 初始化用户相关参数
	 */
	private function _initUserParam(){
		$param = $this->lab_service->getMemberOrginationList($this->_loginUID);
		
		
		if($param){
			$this->_profile['lab'] = $param;
			//获得用户当前机构的所用的实验室列表
			
			$this->_currentOid = $param['current']['oid'];
			
			$this->lab_service->setLabTablesByOrgination($this->_currentOid);
			$userJoinedLabs = $this->lab_service->getUserJoinedLabListByOrgination($this->_loginUID,$param['current']['oid']);
			$joinedLabsIds = array();
			
			if($userJoinedLabs){
				foreach($userJoinedLabs as $lab){
					$joinedLabsIds[] = $lab['lab_id'];
				}
			}
			
			
			$this->session->set_userdata(array('lab' => $param, 'user_labs' => $joinedLabsIds));
			
			$this->assign(
				array(
					'user_labs' => json_encode($joinedLabsIds),
					'lab_param' => $param,
					'current_oid' => $this->_currentOid
				)
			);
			
		}
	}
	
	
	/**
	 * 更新用户站内信状态
	 */
	protected function _pmUpdate(){
		$this->_newpm = $this->message_service->getLastestSysPm($this->_profile,$this->_loginUID);
		if($this->_newpm){
			$this->assign('newPm',$this->_newpm);
		}
	}
	
	
	
	/**
	 * 
	 */
	protected function _addBreadCrumbs(){
		
		
		
	}
	
    
}



