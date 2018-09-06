<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 运动之家 管理控制器
 */
class Ydzj_Admin_Controller extends Ydzj_Controller {
	
	protected $_adminProfile = array() ;
	protected $_adminUID = 0;

	protected $_adminNewPm = array();
	
	protected $_permission = array();
	
	
	//数据可见性
	protected $_dataModule = array();
	
	
	public function __construct(){
		parent::__construct();
		
		
		if($this->input->is_ajax_request()){
			$this->form_validation->set_error_delimiters('<div>','</div>');
		}else{
			$this->form_validation->set_error_delimiters('<label class="error">','</label>');
		}
		
		$this->_initAdminLogin();
		
		$this->_checkPermission();
		
		$this->_adminUID = $this->_adminProfile['basic']['uid'];
		
		$seo = $this->_seoSetting['index'];
		$this->seo($seo['title'],$seo['keywords'],$seo['description']);
		
		$refresh = false;
		$refresh = $this->session->userdata('forcePmCheck');
		
		if($refresh || $this->_reqInterval >= config_item('pmcheck_interval')){
			$this->_pmUpdate();
		}
		
		if($refresh){
			$this->session->unset_userdata('forcePmCheck');
		}
		
		$this->session->set_userdata(array('lastvisit' => $this->_reqtime));
		
		$this->assign('manage_profile',$this->_adminProfile);
		
	}
	
	/**
	 * 更新用户站内信状态
	 */
	protected function _pmUpdate(){
		$this->_adminNewPm = $this->admin_pm_service->refreshAdminPm($this->_adminProfile['basic']);
		
		$newCount = 0;
		foreach($this->_adminNewPm as $key => $val){
			$newCount += $val;
		}
		
		///$newCount = 5;
		
		if($newCount){
			$this->assign('newPm',$newCount);
		}
	}
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
		
		$this->load->library(array('Admin_pm_service'));
		$this->load->model(array('Role_Model','Group_Model'));
	}
	
	
	public function _initLanguage(){
		parent::_initLanguage();
		$this->config->set_item('language','chinese');
	}
	
	
	
	
	private function _initAdminLogin(){
		
		//print_r($this->session->all_userdata());
		$this->_adminProfile = $this->session->userdata('manage_profile');
		
		if(empty($this->_adminProfile)){
			$this->_adminProfile = array();
		}
		
		if(!$this->isAdminLogin()){
			
			$this->session->unset_userdata('manage_profile');
			
			if($this->input->is_ajax_request()){
				$this->responseJSON('您尚未登陆',array('url' => site_url('member/admin_login')));
			}else{
				
				redirect(site_url('member/admin_login'),'javascript:top');
			}
			
			
		}
	}
	
	
	private function _checkPermission(){
        //print_r($this->_adminProfile);
        
        if($this->_adminProfile['basic']['role_id']){
            $roleInfo = $this->Role_Model->getFirstByKey($this->_adminProfile['basic']['role_id'],'id','id,name,permission,enable');
            
            if($roleInfo['enable']){
            	$currentPer = $this->encrypt->decode($roleInfo['permission'],config_item('encryption_key').md5($roleInfo['name']));
            
	            if(trim($currentPer)){
	                $this->_permission = array_flip(explode('|',$currentPer));
	            }
            }
            
        }
        
        
        if($this->_adminProfile['basic']['group_id']){
        	$groupInfo = $this->Group_Model->getFirstByKey($this->_adminProfile['basic']['group_id'],'id','id,name,group_data,enable');
        	
        	if($groupInfo['enable']){
        		$this->_adminProfile['basic']['group_name'] = $groupInfo['name'];
        		
        		$this->_dataModule = json_decode($groupInfo['group_data'],true);
        	}else{
        		//设置不可见
        		$this->_dataModule = array(-1);
        	}
        }
        
        //print_r($this->_permission);

        //公共权限
        $this->_permission['admin'] = 1;
        $this->_permission['admin/'] = 1;
        $this->_permission['index'] = 1;
        $this->_permission['index/'] = 1;
        $this->_permission['index/index'] = 1;
        $this->_permission['my/index'] = 1;
        $this->_permission['my/logout'] = 1;
        $this->_permission['my/profile'] = 1;
        $this->_permission['my/check_newpm'] = 1;
        $this->_permission['index/nopermission'] = 1;
        
        $this->_permission['dashboard/welcome'] = 1;
        $this->_permission['dashboard/aboutus'] = 1;
        
        $this->_permission['common/pic_upload'] = 1;
        
        //ajax autocomplete url start
        $this->_permission['worker/getworker'] = 1;
        $this->_permission['house/getaddress'] = 1;
        $this->_permission['yezhu/getyezhuinfo'] = 1;
        $this->_permission['building/getbuildinglist'] = 1;
        $this->_permission['resident/getresidentname'] = 1;
        $this->_permission['staff_booking/getstaffmobile'] = 1;
        
        //ajax autocomplete url end

        $this->assign('permission',$this->_permission);
        
        if(!isset($this->_permission[$this->_checkPermitUrl])){
            if($this->input->is_ajax_request()){
                $this->responseJSON('没有足够的权限,请联系管理员');
            }else{
                redirect(admin_site_url('index/nopermission'));
            }
        }

    }
    
	
	public function isAdminLogin(){
		if($this->_adminProfile && ($this->_reqtime - $this->session->userdata('lastvisit') < 86400)){
			return true;
		}
		
		return false;
	}
	
	/**
	 * 记录谁操作得
	 */
	public function addWhoHasOperated($action = 'add'){
		return array(
			"{$action}_uid" => $this->_adminProfile['basic']['uid'],
			"{$action}_username" => $this->_adminProfile['basic']['username']
		);
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj_admin';
	}
}

