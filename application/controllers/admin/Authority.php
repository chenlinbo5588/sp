<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Authority extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model(array('Adminuser_Model','Fn_Model','Role_Model'));
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
	
	
	private function _getUserRules(){
		
		$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|alpha_dash');
		$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
		
		
	}
	
	
	
	public function user_add(){
		$feedback = '';
		
		$roleList = $this->Role_Model->getList();
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('email','登陆名',"required|valid_email|is_unique[{$this->Adminuser_Model->_tableRealName}.email]");
			$this->form_validation->set_rules('username','真实名称',"required|is_unique[{$this->Adminuser_Model->_tableRealName}.username]");
			$this->_getUserRules();
			$this->form_validation->set_rules('group_id','权限组','required|is_natural');
			
			for($i = 0; $i < 1; $i++){
				
				$info = array(
					'email' => $this->input->post('email'),
					'username' => $this->input->post('username'),
					'group_id' => $this->input->post('group_id'),
					'password' => $this->input->post('admin_rpassword')
				);
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if(($newid = $this->Adminuser_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Adminuser_Model->getFirstByKey($newid,'uid');
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('roleList',$roleList);
		$this->display();
	}
	
	
	public function user_edit(){
		
		$feedback = '';
		$id = $this->input->get_post('uid');
		$roleList = $this->Role_Model->getList();
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('email','登陆名',"required|valid_email|is_unique_not_self[{$this->Adminuser_Model->_tableRealName}.email.uid.{$id}]");
			$this->form_validation->set_rules('username','真实名称',"required|is_unique_not_self[{$this->Adminuser_Model->_tableRealName}.username.uid.{$id}]");
			
			if($this->input->post('admin_password')){
				$this->_getUserRules();
			}
			
			$this->form_validation->set_rules('group_id','权限组','required|is_natural');
			
			
			for($i = 0; $i < 1; $i++){
				
				$info = array(
					'uid' => $id,
					'email' => $this->input->post('email'),
					'username' => $this->input->post('username'),
					'group_id' => $this->input->post('group_id')
				);
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->input->post('admin_password')){
					$info['password'] = $this->_getEncodePassword($this->input->post('admin_password'),$info['email']);
				}
				
				
				if($this->Adminuser_Model->update($info,array('uid' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}else{
			$info = $this->Adminuser_Model->getFirstByKey($id,'uid');
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
		
		if($this->isPostRequest()){
			
			
			$this->form_validation->set_rules('name','权限组名称',"required|is_unique[{$this->Role_Model->_tableRealName}.name]");
			
			
			$this->_getRoleRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info['name'] = $this->input->post('name');
				$info['status'] = $this->input->post('status');
				$info['permission'] = $this->input->post('permission');
					
					
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					
					if(is_array($info['permission'])){
						$info['permission'] = array_flip($info['permission']);
					}
					
					break;
				}
				
				$info['permission'] = $this->_getEncodePermision($this->input->post('permission'),$this->input->post('name'));
				
				if(($newid = $this->Role_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->_refreshRoleInfo($newid);
			}
		}
		
		
		$list = $this->Fn_Model->getList();
		$fnTree = $this->phptree->makeTree($list,array(
			'primary_key' => 'id',
			'parent_key' => 'parent_id',
			'expanded' => true
		));
		
		//print_r($fnTree);
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('fnTree',$fnTree);
		
		$this->display();
	}
	
	
	private function _getEncodePassword($psw,$email){
		return $this->encrypt->encode($psw,config_item('encryption_key').md5($email));
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
		
		return $info;
	}
	
	
	
	public function role_edit(){
		
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			$this->assign('ispost',true);
			$this->_getRoleRules();
			
			$this->form_validation->set_rules('name','权限组名称',"required|is_unique_not_self[{$this->Role_Model->_tableRealName}.name.id.{$id}]");
			
			$info['id'] = $id;
			$info['name'] = $this->input->post('name');
			$info['status'] = $this->input->post('status');
			if(is_array($this->input->post('permission'))){
				$info['permission'] = array_flip($this->input->post('permission'));
				
			}else{
				$info['permission'] = array();
			}
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				//unset($info['id']);
				
				$info['permission'] = $this->_getEncodePermision($this->input->post('permission'),$info['name']);
				
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
