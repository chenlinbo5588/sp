<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Admin_service');
		
		$this->_moduleTitle = '用户';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '新增'),
		);
		
		
	}
	
	
	/**
	 * 初始话
	 */
	private function _initPageData(){
		
		/*
		$roles = array();
		foreach($list['data'] as $user){
			$roles[] = $user['role_id'];
		}
		
		//print_r($roles);
		if($roles){
			$roleList = $this->Role_Model->getList(
				array(
					'where_in' => array(
						array('key' => 'id','value' => $roles)
					)
				)
			);
			
			$roleKeyList = array();
			foreach($roleList as $role){
				$roleKeyList[$role['id']] = $role['name'];
			}
			
			$this->assign('roleList',$roleKeyList);
			
		}
		*/
		
		
		
		
		$this->assign(array(
			'roleList' => $this->Role_Model->getList(array(
				'where' => array(
					'enable' => 1
				)
			)),
			
			'groupList' => $this->Group_Model->getList(array(
				'where' => array(
					'enable' => 1
				)
			))
		));
		
	}
	
	
	
	
	public function index(){
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		$condition = array(
			'order' => 'uid DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		$username = $this->input->post('username');
		if($username){
			$condition['like']['username'] = $username;
		}
		
		$email = $this->input->post('email');
		if($email){
			$condition['like']['email'] = $email;
		}
		
		$list = $this->Adminuser_Model->getList($condition);
		
		$roleIds = array();
		$groupIds = array();
		foreach($list['data'] as $userItem){
			$roleIds[] = $userItem['role_id'];
			$groupIds[] = $userItem['group_id'];
		}
		
		
		$roleIds = array_unique($roleIds);
		$groupIds = array_unique($groupIds);
		
		$roleList = array();
		$groupList = array();
		
		if($roleIds){
			$roleList = $this->Role_Model->getList(array(
				'select' => 'id,name',
				'where_in' => array(
					array('key' => 'id' , 'value' => $roleIds)
				)
			),'id');
		}
		
		if($groupIds){
			$groupList = $this->Group_Model->getList(array(
				'select' => 'id,name',
				'where_in' => array(
					array('key' => 'id' , 'value' => $groupIds)
				)
			),'id');
		}
		
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'currentPage' => $currentPage,
			'groupList' => $groupList,
			'roleList' => $roleList,
		));
		
		
		$this->display();
	}
	
	
	private function _getUserRules($action){
		$this->form_validation->set_rules('username','真实名称',"required|min_length[1]|max_length[30]");
		$this->form_validation->set_rules('enable','状态','required|in_list[0,1]');
		
		$this->form_validation->set_rules('role_id','所属角色','required|is_natural_no_zero');
		$this->form_validation->set_rules('group_id','用户组','required|is_natural_no_zero');
		
		
		if($action == 'add'){
			$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|valid_password');
			$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
		}else{
			if($this->input->post('admin_password')){
				$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|valid_password');
				$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
			}
		}
		
	}
	
	
	/**
	 * 删除
	 */
	public function delete(){
		$id = $this->input->get_post('uid');
		
		if($this->isPostRequest() && !empty($id)){
			
			if(is_array($id)){
				$id = $id[0];
			}
			
			
			$returnVal = $this->Adminuser_Model->deleteByCondition(array(
				'where' => array(
					'uid' => $id
				)
			));
			
			if($returnVal > 0){
				$this->jsonOutput('删除成功');
			}else{
				$this->jsonOutput('删除失败');
			}
			
		}else{
			$this->jsonOutput('请求非法');
		}
		
	}
	
	
	
	/**
	 * 开启关闭
	 */
	private function _onoff($op){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->admin_service->userOnOff($ids,$op,$this->addWhoHasOperated('edit'));
			
			if($returnVal > 0){
				$this->jsonOutput($op.'成功');
			}else{
				$this->jsonOutput($op.'失败');
			}
			
		}else{
			$this->jsonOutput('请求非法');
		}
	}
	
	
	/**
	 * 开启
	 */
	public function turnon(){
		$this->_onoff('开启');
		
	}
	
	
	/**
	 * 关闭
	 */
	public function turnoff(){
		$this->_onoff('关闭');
	}
	
	
	
	/**
	 * 
	 */
	private function _prepareData(){
		$info = array(
			'role_id' => $this->input->post('role_id') ? $this->input->post('role_id') : 0,
			'group_id' => $this->input->post('group_id') ? $this->input->post('group_id') : 0,
			'password' => $this->input->post('admin_password') ? $this->input->post('admin_password') : '',
		);
		
		return $info;
	}
	
	
	
	
	
	public function add(){
		$feedback = '';
		
		$info = array();
		
		$action = 'add';
		
		//
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('email','登陆名','required|valid_email|is_unique['.$this->Adminuser_Model->getTableRealName().'.email]');
			
			$this->_getUserRules('add');
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add'));
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败'.$this->form_validation->error_first_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$info['password'] = $this->_getEncodePassword($info['password'],$info['email']);
				
				if(($newid = $this->admin_service->saveUser($info)) < 0){
					$this->jsonOutput('数据库错误');
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			
			$info['enable'] = 1;
			
			$this->assign(array(
				'info' => $info,
			));
			
			$this->_initPageData();
			
			$this->display();
		}
		
		
		
	}
	
	
	/**
	 * 编辑用户
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('uid');
		
		$info = $this->Adminuser_Model->getFirstByKey($id,'uid');
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?uid='.$id,'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('email','登陆名','required|valid_email|is_unique_not_self['.$this->Adminuser_Model->getTableRealName().".email.uid.{$id}]");
			$this->_getUserRules('edit');
			
			for($i = 0; $i < 1; $i++){
				
				$oldInfo = $info;
				
				
				$info = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				$info['uid'] = $id;
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败'.$this->form_validation->error_first_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				//重新设置密码
				if(trim($info['password'])){
					$info['password'] = $this->_getEncodePassword($info['password'],$info['email']);
				}else{
					//不更新
					unset($info['password']);
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->admin_service->saveUser($info,$oldInfo) < 0){
					$error = $this->Adminuser_Model->getError();
					
					$this->jsonOutput($error['message']);
					
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
		}else{
			$this->assign(array(
				'info' => $info,
			));
			
			$this->_initPageData();
			
			$this->display($this->_className.'/add');
		}
		
		
	}
	
	
	
	private function _getEncodePassword($psw,$email){
		return $this->encrypt->encode(trim($psw),config_item('encryption_key').md5(trim($email)));
	}
	
	
	
}
