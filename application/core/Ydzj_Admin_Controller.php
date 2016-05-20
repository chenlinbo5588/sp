<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 运动之家 管理控制器
 */
class Ydzj_Admin_Controller extends Ydzj_Controller {
	
	public $_adminProfile = array() ;
	
	public function __construct(){
		parent::__construct();
		
		$this->form_validation->set_error_delimiters('<label class="error">','</label>');
		
		$this->_initAdminLogin();
	}
	
	private function _initAdminLogin(){
		
		//print_r($this->session->all_userdata());
		$this->_adminProfile = $this->session->userdata('manage_profile');
		
		if(empty($this->_adminProfile)){
			$this->_adminProfile = array();
		}
		
		if(!$this->isLogin()){
			if($this->input->is_ajax_request()){
				$this->responseJSON('您尚未登陆',array('url' => site_url('member/admin_login')));
			}else{
				redirect(site_url('member/admin_login'));
			}
		}else{
			$this->assign('manage_profile',$this->_adminProfile);
		}
	}
	
	
	private function _checkPermission(){
        //print_r($this->_adminProfile);
        $currentUri = $this->uri->uri_string();

        if($this->_adminProfile['basic']['group_id']){
            $roleInfo = $this->Role_Model->getFirstByKey($this->_adminProfile['basic']['group_id'],'id');
            $currentPer = $this->encrypt->decode($roleInfo['permission'],config_item('encryption_key').md5($roleInfo['name']));

            if(trim($currentPer)){
                $this->_permission = array_flip(explode('|',$currentPer));
            }
        }

        //公共权限
        $this->_permission['admin'] = 1;
        $this->_permission['admin/index'] = 1;
        $this->_permission['admin/dashboard/welcome'] = 1;
        $this->_permission['admin/dashboard/aboutus'] = 1;
        $this->_permission['admin/index/logout'] = 1;
        $this->_permission['admin/index/index'] = 1;
        $this->_permission['admin/index/welcome'] = 1;

        $this->assign('permission',$this->_permission);
        
        if(!isset($this->_permission[$currentUri])){
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

