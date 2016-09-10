<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 运动之家 管理控制器
 */
class Ydzj_Admin_Controller extends Ydzj_Controller {
	
	
	
	public function __construct(){
		parent::__construct();
		
		$this->_initAdminLogin();
		
		//@todo 打开检查权限
		//$this->_checkPermission();
		$this->_navs();
		
	}
	
	/**
     * 导航相关
     */
    protected function _navs(){
        $navs = array_slice($this->uri->segments,1,3);
        $pathStr = implode('/',$navs);

        $this->assign('pathStr',$pathStr);
        $this->assign('fnKey',$navs[0]);
        $this->assign('navs',config_item('navs'));
    }
	
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
		$this->load->model('Role_Model');
	}
	
	private function _initAdminLogin(){
		//print_r($this->session->all_userdata());
		
		$alldata = $this->session->get_userdata();
		$adminLastVisit = $alldata[$this->_adminLastVisitKey];
		
		if(!$this->isAdminLogin($alldata)){
			if($this->input->is_ajax_request()){
				$this->responseJSON('您尚未登陆',array('url' => site_url('member/admin_login')));
			}else{
				$this->session->unset_userdata($this->_adminProfileKey);
				redirect(site_url('member/admin_login'));
			}
		}else{
			$this->_adminProfile = $alldata[$this->_adminProfileKey];
			if($this->_adminProfile){
				$this->session->set_userdata(array(
					$this->_adminProfileKey => $this->_adminProfile,
					$this->_adminLastVisitKey => $this->_reqtime
				));
			}
			
			$this->assign($this->_adminProfileKey,$this->_adminProfile);
		}
		
	}
	
	
	public function isAdminLogin($session = array()){
		if(!$session){
			$session = $this->session->get_userdata();
		}
		
		$lastVisit = empty($session[$this->_adminLastVisitKey]) ? 0 : $session[$this->_adminLastVisitKey];
		if(!empty($session[$this->_adminProfileKey]) && ($this->_reqtime - $lastVisit) <= CACHE_ONE_DAY){
			return true;
		}

		return false;
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
        $this->_permission['admin/index/index'] = 1;
        $this->_permission['admin/index/logout'] = 1;
        $this->_permission['admin/index/nopermission'] = 1;
        $this->_permission['admin/dashboard/welcome'] = 1;
        $this->_permission['admin/dashboard/aboutus'] = 1;

        $this->assign('permission',$this->_permission);
        
        if(!isset($this->_permission[$currentUri])){
            //file_put_contents("deb.txt",$currentUri,FILE_APPEND);
            if($this->input->is_ajax_request()){
                $this->responseJSON('没有足够的权限,请联系管理员');
            }else{
                redirect(admin_site_url('index/nopermission'));
            }
        }

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

