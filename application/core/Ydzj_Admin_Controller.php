<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 管理控制器
 * 
 * 
 */
class Ydzj_Admin_Controller extends Ydzj_Controller {
	
	public $_adminProfile = array();
	private $_permission = array();
	private $_allowSites = array();
	private $_roleAllowSites = '';
	
	public function __construct(){
		parent::__construct();
		
		
		$this->form_validation->set_error_delimiters('<label class="error">','</label>');
		$this->_adminProfile = $this->session->userdata('manage_profile');
		
		if(empty($this->_adminProfile)){
			$this->_adminProfile = array();
		}
		
		if(!$this->isLogin()){
			if($this->input->is_ajax_request()){
				$this->responseJSON('您尚未登陆',array('redirectUrl' => site_url('member/admin_login')));
			}else{
				redirect(site_url('member/admin_login'));
			}
		}else{
			$this->assign('manage_profile',$this->_adminProfile);
		}
		
		$this->load->Model('Role_Model');
		
		$this->_checkPermission();
		$this->_getAllowSites();
		//print_r($this->session->all_userdata());
	}
	
	
	public function getAllowChannelCondition(){
		
		$condition = array();
		
		if($this->_allowSites){
			$condition['where_in'] = array(
				array('key' => 'channel', 'value' => $this->_allowSites)
			);
		}
		
		
		return $condition;
		
		
	}
	
	
	private function _getAllowSites(){
		
		$userSites = trim($this->_adminProfile['basic']['site_ids']);
		
		//print_r($this->_adminProfile['basic']);
		$temp = array();
		
		if($userSites){
			$temp = explode(',',$userSites);
		}
		
		//print_r($temp);
		if($this->_roleAllowSites){
			$temp1 = explode(',',$this->_roleAllowSites);
			$temp = array_merge($temp,$temp1);
			$temp = array_unique($temp);
		}
		
		$this->_allowSites = $temp;
		
		if($this->_adminProfile['basic']['uid'] == WEBSITE_FOUNDER){
			//没有来源的，防止数据看不到
			$this->_allowSites[] = 0;
		}
		
		//print_r($temp);
	}
	
	
	
	private function _checkPermission(){
		//print_r($this->_adminProfile);
		
		$currentUri = $this->uri->uri_string();
		
		//echo $currentUri;
		
		if($this->_adminProfile['basic']['group_id']){
			$roleInfo = $this->Role_Model->getFirstByKey($this->_adminProfile['basic']['group_id'],'id');
			$currentPer = $this->encrypt->decode($roleInfo['permission'],config_item('encryption_key').md5($roleInfo['name']));
			
			if(trim($currentPer)){
				$this->_permission = array_flip(explode('|',$currentPer));
			}
			
			$this->_roleAllowSites = trim($roleInfo['site_ids']);
			
			//print_r($currentPer);
		}
		
		//公共权限
		$this->_permission['admin'] = 1;
		$this->_permission['admin/index'] = 1;
		$this->_permission['admin/index/profile'] = 1;
		$this->_permission['admin/dashboard/welcome'] = 1;
		$this->_permission['admin/dashboard/aboutus'] = 1;
		$this->_permission['admin/index/logout'] = 1;
		$this->_permission['admin/index/index'] = 1;
		$this->_permission['admin/index/welcome'] = 1;
		
		$this->assign('permission',$this->_permission);
		
		
		if(!isset($this->_permission[$currentUri])){
			//echo $currentUri;
			//file_put_contents("deb.txt",$currentUri,FILE_APPEND);
			if($this->input->is_ajax_request()){
				$this->responseJSON('没有足够的权限,请联系管理员');
			}else{
				redirect(site_url('common/nopermission'));
			}
			
		}
		
	}
	
	
	
	public function isLogin(){
		
		if($this->_adminProfile && ($this->_reqtime - $this->session->userdata('lastvisit') < 86400)){
			return true;
		}
		
		return false;
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj_admin';
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
}

