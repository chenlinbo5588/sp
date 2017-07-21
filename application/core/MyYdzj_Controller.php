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
	 * 记录谁操作得
	 */
	public function addWhoHasOperated($action = 'add'){
		return array(
			"{$action}_uid" => $this->_profile['basic']['uid'],
			"{$action}_username" => $this->_profile['basic']['username']
		);
	}
	
    
}



