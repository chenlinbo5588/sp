<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 登陆态
 */
class MyYdzj_Controller extends Ydzj_Controller {
	//private $_pushObject;
	
	protected $_loginUID = 0;
	public $_newpm = 0;
	
	
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->isLogin()){
			//$this->session->unset_userdata(array($this->_lastVisitKey,$this->_profileKey));
			js_redirect('member/login');
		}
		
		
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
		
		
		//@待性能优化
		$unread = $this->message_service->getUserUnreadCount($this->_loginUID);
		$this->assign('unreadCount',$unread);
		
		
		//检查是否已经认证
		$this->_checkHasVerify();
		$this->_repubList();
	}
	
	
	/**
	 * 更新用户站内信状态
	 */
	protected function _pmUpdate(){
		$this->_newpm = $this->message_service->getLastestSysPm($this->_profile,$this->_loginUID);
		
		if($this->_newpm){
			$this->assign('newPm',$this->_newpm);
		}
		
		/*
		$pmClick = $this->input->get_cookie('pmclick');
		
		if(empty($pmClick)){
			$unread = $this->message_service->getUserUnreadCount($this->_loginUID);
			
			$this->input->set_cookie('pmclick', $this->_reqtime,CACHE_ONE_DAY);
			$this->assign('unreadCount',200);
		}
		*/
	}
	
	/**
	 * 检查是否已认证
	 */
	protected function _checkHasVerify(){
		//认证卖家
		$navPop = false;
		
		if(2 == $this->_profile['basic']['group_id']){
			//js_redirect('my/seller_verify');
			
			$this->load->library('Member_service');
			$groupId = $this->member_service->getUserGroupId($this->_loginUID);
			
			if(3 == $groupId){
				$this->_profile['basic']['group_id'] = $groupId;
				$this->refreshProfile();
				$this->getCacheObject()->delete($this->member_service->getUserGroupKey($this->_loginUID));
			}
		}
		
		$this->assign('currentGroupId',$this->_profile['basic']['group_id']);
		$remind = $this->input->get_post('remind');
		$sellerRemind = $this->input->get_cookie('seller_remind');
		
		
	}
	
	
	/**
	 * 
	 */
	protected function _addBreadCrumbs(){
		
		
		
	}
	
	
	private function _repubList(){
		$repubIds = $this->input->get_cookie('repub');
		
		$repubIdArray = array();
		
		if($repubIds){
			$repubIdArray = explode('|',$repubIds);
		}
		
		$this->assign('repubList',$repubIdArray);
	}
	
    
}



