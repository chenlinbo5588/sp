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
		
		$this->_checkPermission();
		
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
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
		
		$this->load->model('Member_Role_Model');
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
	
	
	private function _checkPermission(){
        //print_r($this->_adminProfile);
        $currentUri = $this->uri->uri_string();
        
        if($this->_profile['basic']['group_id']){
            $roleInfo = $this->Member_Role_Model->getFirstByKey($this->_profile['basic']['group_id'],'id');
            $currentPer = $this->encrypt->decode($roleInfo['permission'],config_item('encryption_key').md5($roleInfo['name']));
            if(trim($currentPer)){
                $this->_permission = array_flip(explode('|',$currentPer));
            }
        }

        //公共权限
        $this->_permission['my'] = 1;
        $this->_permission['my/index'] = 1;
        $this->_permission['my/base'] = 1;
        $this->_permission['my/logout'] = 1;
        $this->_permission['my/change_psw'] = 1;
        $this->_permission['my/verify_email'] = 1;
        $this->_permission['my/set_email'] = 1;
        $this->_permission['my/upload_avatar'] = 1;
        $this->_permission['my/set_avatar'] = 1;
        $this->_permission['my/nopermission'] = 1;
        
        //消息中心
        $this->_permission['my_pm'] = 1;
        $this->_permission['my_pm/index'] = 1;
        $this->_permission['my_pm/sendpm'] = 1;
        $this->_permission['my_pm/username_check'] = 1;
        $this->_permission['my_pm/delete'] = 1;
        $this->_permission['my_pm/setread'] = 1;
        $this->_permission['my_pm/detail'] = 1;
        $this->_permission['my_pm/check_newpm'] = 1;
       
        $this->assign('permission',$this->_permission);
        
        if(!isset($this->_permission[$currentUri])){
            if($this->input->is_ajax_request()){
                $this->responseJSON('没有足够的权限,请联系管理员');
            }else{
                redirect(site_url('my/nopermission'));
            }
        }
    }
    
}



