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
		$this->_checkPermission();
		$this->_initUserLabsRelate();
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
	
	
	private function _friendSiteWelcome(){
		
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
        $this->_permission['admin/index/profile'] = 1;
        $this->_permission['admin/index/index'] = 1;
        $this->_permission['admin/index/logout'] = 1;
        $this->_permission['admin/index/nopermission'] = 1;
        $this->_permission['admin/dashboard/welcome'] = 1;
        $this->_permission['admin/dashboard/aboutus'] = 1;

		//print_r($this->_permission);
		
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
		
		switch($action){
			case 'add':
				$ar = array(
					"creator" => $this->_adminProfile['basic']['name']
				);
				break;
			case 'edit':
				$ar = array(
					"updator" => $this->_adminProfile['basic']['name']
				);
				break;
			default:
				break;
		}
		
		return $ar;
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj_admin';
	}
	
    /**
     *  检查是否是 系统管理员
     */
    protected function _checkIsSystemManager(){
    	
    	if($this->_loginUID == LAB_FOUNDER_ID){
    		return true;
    	}
    	
    	if($this->_adminProfile['basic']['is_manager'] != 'y'){
			return false;
		}else{
			return true;
		}
    }
}

