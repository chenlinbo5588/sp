<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model(array('Adminuser_Model','Fn_Model','Role_Model'));
		
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
			$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|valid_password');
			$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
		}else{
			if($this->input->post('admin_password')){
				$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|valid_password');
				$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
			}
		}
		
	}
	
	
	
	
	
	private function _prepareUserData(){
		$info = array(
			'email' => $this->input->post('email'),
			'username' => $this->input->post('username'),
			'group_id' => $this->input->post('group_id') ? $this->input->post('group_id') : 0,
			'password' => $this->input->post('admin_password') ? $this->input->post('admin_password') : '',
			'status' => $this->input->post('status'),
		);
		
		
		
		return $info;
	}
	
	
	
	
	
	public function add(){
		$feedback = '';
		
		$action = 'add';
		
		$roleList = $this->Role_Model->getList();
		
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('email','登陆名','required|valid_email|is_unique['.$this->Adminuser_Model->getTableRealName().'.email]');
			$this->_getUserRules('add');
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareUserData();
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_first_html());
					break;
				}
				
				$info['password'] = $this->_getEncodePassword($info['password'],$info['email']);
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Adminuser_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$action = 'edit';
				$info = $this->Adminuser_Model->getFirstByKey($newid,'uid');
				
			}
		}
		
		
		$this->assign('action',$action);
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('roleList',$roleList);
		$this->display();
	}
	
	
	public function edit(){
		
		$this->assign('action','edit');
		
		$feedback = '';
		$id = $this->input->get_post('uid');
		$roleList = $this->Role_Model->getList();
		
		$info = $this->Adminuser_Model->getFirstByKey($id,'uid');
		
		
		$this->_subNavs['subNavs']['编辑管理员'] = 'user/edit?uid='.$id;
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('email','登陆名','required|valid_email|is_unique_not_self['.$this->Adminuser_Model->getTableRealName().".email.uid.{$id}]");
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
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Adminuser_Model->update($info,array('uid' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Adminuser_Model->getFirstByKey($id,'uid');
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('roleList',$roleList);
		$this->display('user/add');
		
	}
	
	
	
	private function _getEncodePassword($psw,$email){
		return $this->encrypt->encode(trim($psw),config_item('encryption_key').md5(trim($email)));
	}
	
	
	
}
