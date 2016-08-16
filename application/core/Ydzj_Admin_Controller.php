<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 运动之家 管理控制器
 */
class Ydzj_Admin_Controller extends Ydzj_Controller {
	
	protected $_loginUID = 0;
	
	
	public function __construct(){
		parent::__construct();
		
		
		$this->form_validation->set_error_delimiters('<label class="error">','</label>');
		$this->_initAdminLogin();
		
		
		$this->_initUserLabsRelate();
		
		//@todo 打开检查权限
		//$this->_checkPermission();
	}
	
	
	private function _friendSiteWelcome(){
		$this->load->helper('cookie');
		
		static $_friendWebsite = array(
	    	//欢迎进入浙江省农产品加工技术研究重点实验室药品仪器管理中心！
			'zjufs.zju.edu.cn' => 1,
	    	//欢迎进入浙江大学生工食品学院实验室药品仪器管理中心！
	    	'www.caefs.zju.edu.cn' => 2
	    );
		
		$showWelcome = $this->input->cookie('wel');
		if(!$showWelcome){
			if($_SERVER['HTTP_REFERER']){
				$urlParam = parse_url($_SERVER['HTTP_REFERER']);
				if($this->_friendWebsite[$urlParam['host']]){
					set_cookie('wel','no,'.$this->_friendWebsite[$urlParam['host']],86400 * 365);
				}
			}
		}
		
	}
	
	
	
	private function _initUserLabsRelate(){
		
		$this->load->library('Lab_service');
		$ar = $this->lab_service->getUserLabsAssoc($this->_adminProfile['basic']['id']);
    	
    	$this->session->set_userdata($ar);
	}
	
	protected function _initLibrary(){
		parent::_initLibrary();
		
		$this->load->model('Role_Model');
	}
	
	private function _initAdminLogin(){
		//print_r($this->session->all_userdata());
		$adminLastVisit = $this->session->userdata($this->_adminLastVisitKey);
		
		if(empty($adminLastVisit)){
			$this->session->set_userdata(array($this->_adminLastVisitKey => $this->_reqtime));
		}
		
		$this->_adminProfile = $this->session->userdata($this->_adminProfileKey);
		if(empty($this->_adminProfile)){
			$this->_adminProfile = array();
		}
		
		if(!$this->isAdminLogin($adminLastVisit)){
			if($this->input->is_ajax_request()){
				$this->responseJSON('您尚未登陆',array('url' => site_url('member/admin_login')));
			}else{
				$this->session->unset_userdata(array($this->_adminLastVisitKey,$this->_adminProfileKey));
				redirect(site_url('member/admin_login'));
			}
		}else{
			$this->_loginUID = $this->_adminProfile['basic']['id'];
			$this->assign($this->_adminProfileKey,$this->_adminProfile);
		}
		
		//如果没有被刷新，则刷新
		if($adminLastVisit){
			$this->session->set_userdata(array($this->_adminLastVisitKey => $this->_reqtime));
		}
		
	}
	
	
	public function isAdminLogin($lastVisitTime){
		
		/*
		echo $this->session->userdata('admin_lastvisit');
		echo '<br>';
		echo $this->_reqtime;
		print_r($this->_adminProfile);
		echo ($this->_reqtime - $this->session->userdata('admin_lastvisit'));
		*/
		
		if($this->_adminProfile && ($this->_reqtime - $this->session->userdata($this->_adminLastVisitKey)) <= 86400){
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
			"creator" => $this->_adminProfile['basic']['name']
		);
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj_admin';
	}
	
	
	protected function _checkIsFounder(){
    	if(1 != $this->_userProfile['id']){
			redirect(base_url("lab_goods"),'javascript:top');
		}
    }
    
    /**
     * 检查是否 有管辖实验室 即为某一个或者某几个实验室的管理员
     */
    protected function _checkIsLabManager()
    {
    	
    	if($this->_userProfile['id'] == LAB_FOUNDER_ID){
    		return true;
    	}
    	
    	$managerLabs = $this->session->userdata('user_manager_labs');
    	
    	//print_r($managerLabs);
    	if(empty($managerLabs)){
    		return false;
    	}
    	
    	return true;
    	
    }
    
    
    /**
     *  检查是否是 系统管理员
     */
    protected function _checkIsSystemManager(){
    	
    	if($this->_userProfile['id'] == LAB_FOUNDER_ID){
    		return true;
    	}
    	
    	if($this->_userProfile['is_manager'] != 'y'){
			return false;
		}else{
			return true;
		}
    }
    
    protected function show_access_deny(){
    	$this->display('access_deny','common');
    }
	
}

