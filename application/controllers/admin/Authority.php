<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Authority extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model(array('Adminuser_Model','Fn_Model','Role_Model','Website_Model'));
	}
	
	public function user(){
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
		
		if($this->_adminProfile['basic']['uid'] != WEBSITE_FOUNDER){
			$condition['where']['uid !='] = WEBSITE_FOUNDER;
		}
		
		
		
		
		//update status
		if($this->isPostRequest()){
			$switchType = $this->input->post('submit_type');
			for($i = 0; $i < 1; $i++){
				
				if(!in_array($switchType,array('开启','关闭'))){
					break;
				}
				
				
				$ids = $this->input->post('del_id');
				
				
				if(empty($ids)){
					break;
				}
				
				$updateData = array();
				
				foreach($ids as $id){
					
					$updateData[] = array(
						'uid' => $id,
						'status' => $switchType
					);
				}
				
				$this->Adminuser_Model->batchUpdate($updateData,'uid');
				
			}
			
		}
		
		
		$list = $this->Adminuser_Model->getList($condition);
		
		
		$roles = array();
		foreach($list['data'] as $user){
			$roles[] = $user['group_id'];
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
			
			//print_r($roleKeyList);
		}
		
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		
		$this->display();
	}
	
	
	private function _getUserRules($action){
		$this->form_validation->set_rules('username','真实名称',"required|min_length[1]|max_length[30]");
		$this->form_validation->set_rules('status','权限组状态','required|in_list[开启,关闭]');
		$this->form_validation->set_rules('group_id','权限组','required|is_natural');
		
		
		if($action == 'add'){
			$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|alpha_dash');
			$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
		}else{
			if($this->input->post('admin_password')){
				$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|alpha_dash');
				$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
			}
		}
		
	}
	
	
	private function _getWebSiteList(){
		$list = $this->Website_Model->getList();
		
		$this->assign('websiteList',$list);
		return $list;
		
	}
	
	
	private function _prepareUserData(){
		$info = array(
			'email' => $this->input->post('email'),
			'username' => $this->input->post('username'),
			'group_id' => $this->input->post('group_id') ? $this->input->post('group_id') : 0,
			'password' => $this->input->post('admin_password') ? $this->input->post('admin_password') : '',
			'status' => $this->input->post('status'),
			'site_ids' => $this->input->post('site_ids'),
		);
		
		if(empty($info['site_ids'])){
			$info['site_ids'] = array();
		}
		
		
		return $info;
	}
	
	private function _prepareRoleData(){
		
		$info = array(
			'name' => $this->input->post('name'),
			'permission' => $this->input->post('permission'),
			'status' => $this->input->post('status'),
			'site_ids' => $this->input->post('site_ids'),
		);
		
		if(empty($info['site_ids'])){
			$info['site_ids'] = array();
		}
		
		
		if(is_array($this->input->post('permission'))){
			$info['permission'] = array_flip($this->input->post('permission'));
			
		}else{
			$info['permission'] = array();
		}
			
		
		return $info;
	}
	
	
	
	
	
	
	
	public function user_add(){
		$feedback = '';
		
		$action = 'add';
		
		$roleList = $this->Role_Model->getList();
		
		$this->_getWebSiteList();
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('email','登陆名',"required|valid_email|is_unique[{$this->Adminuser_Model->_tableRealName}.email]");
			$this->_getUserRules('add');
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareUserData();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info['password'] = $this->_getEncodePassword($info['admin_password'],$info['email']);
				
				//更新前，扁平化
				$info['site_ids'] = implode(',',$info['site_ids']);
				
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Adminuser_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$action = 'edit';
				$info = $this->Adminuser_Model->getFirstByKey($newid,'uid');
				$info['site_ids'] = explode(',',$info['site_ids']);
				
			}
		}else{
			
			
			$info['site_ids'] = array();
		}
		
		
		$this->assign('action',$action);
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('roleList',$roleList);
		$this->display();
	}
	
	
	public function user_edit(){
		
		$this->assign('action','edit');
		
		$feedback = '';
		$id = $this->input->get_post('uid');
		$roleList = $this->Role_Model->getList();
		$this->_getWebSiteList();
		
		$info = $this->Adminuser_Model->getFirstByKey($id,'uid');
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('email','登陆名',"required|valid_email|is_unique_not_self[{$this->Adminuser_Model->_tableRealName}.email.uid.{$id}]");
			$this->_getUserRules('edit');
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareUserData();
				$info['uid'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				
				//重新设置密码
				if(trim($info['password'])){
					$info['password'] = $this->_getEncodePassword($info['password'],$info['email']);
				}else{
					//不更新
					unset($info['password']);
				}
				
				//更新前，扁平化
				$info['site_ids'] = implode(',',$info['site_ids']);
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Adminuser_Model->update($info,array('uid' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				
				$info = $this->Adminuser_Model->getFirstByKey($id,'uid');
				$info['site_ids'] = explode(',',$info['site_ids']);
			}
		}
		
		if(!is_array($info['site_ids'])){
			$info['site_ids'] = explode(',',$info['site_ids']);
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('roleList',$roleList);
		$this->display('authority/user_add');
		
	}
	
	
	
	public function role(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
			
		);
		
		
		$keywords = $this->input->post('keywords');
		if($keywords){
			$condition['like']['name'] = $keywords;
		}
		
		
		//update status
		if($this->isPostRequest()){
			$switchType = $this->input->post('submit_type');
			for($i = 0; $i < 1; $i++){
				
				if(!in_array($switchType,array('开启','关闭'))){
					break;
				}
				
				$ids = $this->input->post('del_id');
				
				if(empty($ids)){
					break;
				}
				
				$updateData = array();
				
				foreach($ids as $id){
					
					$updateData[] = array(
						'id' => $id,
						'status' => $switchType
					);
				}
				
				$this->Role_Model->batchUpdate($updateData,'id');
				
			}
			
		}
		
		
		$list = $this->Role_Model->getList($condition);
		//print_r($list);
		//print_r($_POST);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		$this->display();
	}
	
	
	private function _getRoleRules(){
		
		$this->form_validation->set_rules('status','权限组状态','required|in_list[开启,关闭]');
		$this->form_validation->set_rules('permission[]','权限','required');
		
	}
	
	
	
	public function role_add(){
		$feedback = '';
		
		$action = 'add';
		$this->_getWebSiteList();
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','权限组名称',"required|is_unique[{$this->Role_Model->_tableRealName}.name]");
			
			
			$this->_getRoleRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareRoleData();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					
					if(is_array($info['permission'])){
						$info['permission'] = array_flip($info['permission']);
					}
					
					break;
				}
				
				$info['permission'] = $this->_getEncodePermision($this->input->post('permission'),$this->input->post('name'));
				//更新前，扁平化
				$info['site_ids'] = implode(',',$info['site_ids']);
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Role_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$action = 'edit';
				$feedback = getSuccessTip('保存成功');
				$info = $this->_refreshRoleInfo($newid);
				
				
			}
		}else{
			$info['site_ids'] = array();
		}
		
		
		$list = $this->Fn_Model->getList();
		$fnTree = $this->phptree->makeTree($list,array(
			'primary_key' => 'id',
			'parent_key' => 'parent_id',
			'expanded' => true
		));
		
		//print_r($fnTree);
		$this->assign('action',$action);
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('fnTree',$fnTree);
		
		$this->display();
	}
	
	
	private function _getEncodePassword($psw,$email){
		return $this->encrypt->encode(trim($psw),config_item('encryption_key').md5(trim($email)));
	}
	
	private function _getEncodePermision($permisionArray , $name){
		
		$limit_str = '';
		
		if (is_array($permisionArray)){
			$limit_str = implode('|',$permisionArray);
		}
		
		return $this->encrypt->encode($limit_str,config_item('encryption_key').md5($name));
	}
	
	
	private function _refreshRoleInfo($id ){
		
		
		$info = $this->Role_Model->getFirstByKey($id,'id');
		$info['permission'] = $this->encrypt->decode($info['permission'],config_item('encryption_key').md5($info['name']));
		$info['permission'] = explode('|',$info['permission']);
		$info['permission'] = array_flip($info['permission']);
		
		
		$info['site_ids'] = explode(',',$info['site_ids']);
		
		return $info;
	}
	
	
	
	public function role_edit(){
		
		$this->assign('action','edit');
		
		$id = $this->input->get_post('id');
		$this->_getWebSiteList();
		
		if($this->isPostRequest()){
			$this->assign('ispost',true);
			$this->_getRoleRules();
			
			$this->form_validation->set_rules('name','权限组名称',"required|is_unique_not_self[{$this->Role_Model->_tableRealName}.name.id.{$id}]");
			
			
			$info = $this->_prepareRoleData();
			$info['id'] = $id;
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				//unset($info['id']);
				
				$info['permission'] = $this->_getEncodePermision($this->input->post('permission'),$info['name']);
				//更新前，扁平化
				$info['site_ids'] = implode(',',$info['site_ids']);
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Role_Model->update($info, array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				
				$info = $this->_refreshRoleInfo($id);
			}
		}else{
			
			$info = $this->_refreshRoleInfo($id);
		}
		
		
		$list = $this->Fn_Model->getList();
		$fnTree = $this->phptree->makeTree($list,array(
			'primary_key' => 'id',
			'parent_key' => 'parent_id',
			'expanded' => true
		));
		
		//print_r($fnTree);
		//print_r($info['permission']);
		
		$this->assign('info',$info);
		
		$this->assign('feedback',$feedback);
		$this->assign('fnTree',$fnTree);
		
		$this->display('authority/role_add');
	}
	
}
