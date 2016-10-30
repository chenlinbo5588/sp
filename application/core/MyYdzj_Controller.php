<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 登陆态
 */
class MyYdzj_Controller extends Ydzj_Controller {
	private $_pushObject;
	protected $_loginUID = 0;
	protected $_currentOid = 0;
	protected $_currentRoleId = 0;
	public $_newpm = 0;
	
	public $_permission = null;
	
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
		$this->_permission = config_item('permission');
		
		$this->_checkPermission();
	}
	
	
	
	/**
	 * 初始化用户相关参数
	 */
	protected function _initUserParam(){
		$param = $this->lab_service->getMemberOrginationList($this->_loginUID);
		if($param){
			$this->_profile['lab'] = $param;
			//获得用户当前机构的所用的实验室列表
			
			$this->_currentOid = $param['current']['oid'];
			//当前角色
			$this->_currentRoleId = $param['current']['role_id'];
				
			/*
			$org = $this->session->userdata('org');
			$setSession = array();
			
			if(empty($org)){
				$this->session->set_userdata('org',$param['current']);
			}else{
				$this->_currentOid = $org['oid'];
				$this->_currentRoleId = $org['role_id'];
			}
			*/
			
			$this->lab_service->setLabTablesByOrgination($this->_currentOid);
			$userJoinedLabs = $this->lab_service->getUserJoinedLabListByOrgination($this->_loginUID,$param['current']['oid']);
			$joinedLabsIds = array();
			$managerLabsIds = array();
			
			if($userJoinedLabs){
				foreach($userJoinedLabs as $lab){
					$joinedLabsIds[] = $lab['lab_id'];
					if($lab['is_manager'] == 'y'){
						$managerLabsIds[] = $lab['lab_id'];
					}
				}
			}
			
			$this->session->set_userdata(array('lab' => $param,'user_labs' => $joinedLabsIds,'manager_labs' => $managerLabsIds));
			
			$this->assign(
				array(
					'user_labs' => json_encode($joinedLabsIds),
					'manager_labs' => json_encode($managerLabsIds),
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
	
	
	private function _checkPermission(){
        //print_r($this->_adminProfile);
        $currentUri = $this->uri->uri_string();

        if(!$this->isOrginationFounder()){
	        $roleInfo = $this->Lab_Role_Model->getFirstByKey($this->_currentRoleId,'id');
	        $currentPer = $this->encrypt->decode($roleInfo['permission'],config_item('encryption_key'));
	
	        if(trim($currentPer)){
	            $this->_permission = array_merge($this->_permission,array_flip(explode('|',$currentPer)));
	        }
        }else{
        	
        	$fnList = $this->getCacheObject()->get('siteFn');
        	if(empty($fnList)){
        		$fnList = $this->Lab_Fn_Model->getList(array(
        			'where' => 'url'
        		));
        		
        		$this->getCacheObject()->save('siteFn',$fnList,CACHE_ONE_DAY);
        	}
        	
        	foreach($fnList as $fn){
    			$this->_permission[$fn['url']] = true;
    		}
        }

        $this->assign('permission',$this->_permission);
        
        if(!$this->isOrginationFounder() && !isset($this->_permission[$currentUri])){
            //file_put_contents("deb.txt",$currentUri,FILE_APPEND);
            if($this->input->is_ajax_request()){
                $this->responseJSON('没有足够的权限,请联系管理员');
            }else{
                js_redirect('my/nopermission');
            }
        }

    }
    
    
	
	/**
	 * 
	 */
	protected function _addBreadCrumbs(){
		
		
		
	}
	
	
	protected function isOrginationFounder(){
		return $this->_profile['basic']['uid'] == $this->_currentOid ? true : false;
	}
	
	public function addWhoHasOperated($action = 'add',$user = array()){
    	$rt = array();
    	
    	switch($action){
    		case 'add':
    			$rt = array('add_uid' => $this->_profile['basic']['uid'], 'creator' => $this->_profile['basic']['username'],'oid' => $this->_currentOid);
    			break;
    		case 'edit':
    			$rt = array('edit_uid' => $this->_profile['basic']['uid'], 'updator' => $this->_profile['basic']['username']);
    			break;
    		default;
    			break;
    	}
    	
    	return $rt;
    	
    }
	
    
}



