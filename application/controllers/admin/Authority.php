<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Authority extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model(array('Adminuser_Model','Fn_Model','Role_Model'));
	}
	
	public function user(){
		
		$currentPage = $this->input->get('page') ? $this->input->get('page') : 1;
		$condition = array(
			'order' => 'uid DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$list = $this->Adminuser_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		
		$this->display();
	}
	
	
	public function user_add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('admin_email','登陆名',array(
						'required',
						'valid_email',
						array(
							'email_callable[email]',
							array(
								$this->Adminuser_Model,'checkExists'
							)
						)
					),
					array(
						'email_callable' => '%s已经被占用'
					)
				);
				
				
			$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|alpha_dash');
			$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
			$this->form_validation->set_rules('group_id','权限组','required');
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
			}
		}
		
		
		$this->display();
	}
	
	
	public function user_edit(){
		
	}
	
	
	
	public function role(){
		
		$currentPage = $this->input->get('page') ? $this->input->get('page') : 1;
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
		
		
		$list = $this->Role_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		$this->display();
	}
	
	
	private function _getRoleRules($action = 'checkExists'){
		
		
		$this->form_validation->set_rules('gname','权限组名称',array(
					'required',
					array(
						'name_callable[name]',
						array(
							$this->Role_Model,$action
						)
					)
				),
				array(
					'name_callable' => '%s已经被占用'
				)
			);
			
		$this->form_validation->set_rules('status','权限组状态','required|in_list[开启,关闭]');
		$this->form_validation->set_rules('permission[]','权限','required');
		
		
		$data['name'] = $this->input->post('gname');
		$data['status'] = $this->input->post('status');
		
		$limit_str = '';
		
		if (is_array($_POST['permission'])){
			$limit_str = implode('|',$this->input->post('permission'));
		}
		
		$data['permission'] = $this->encrypt->encode($limit_str,config_item('encryption_key').md5($this->input->post('gname')));
		
		return $data;
	}
	
	
	
	public function role_add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			
			$data = $this->_getRoleRules('checkExists');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->Role_Model->_add($data) < 0){
					$feedback = getErrorTip('建立失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		
		$list = $this->Fn_Model->getList();
		$fnTree = $this->phptree->makeTree($list,array(
			'primary_key' => 'id',
			'parent_key' => 'parent_id',
			'expanded' => true
		));
		
		//print_r($fnTree);
		$this->assign('feedback',$feedback);
		$this->assign('fnTree',$fnTree);
		
		$this->display();
	}
	
	
	public function role_edit(){
		
		$id = $this->input->get_post('id');
		
		
		if($this->isPostRequest()){
			
			$data = $this->_getRoleRules('checkExistsExcludeSelf');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->Role_Model->_add($data) < 0){
					$feedback = getErrorTip('建立失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}else{
			$info = $this->Role_Model->getFirstByKey($id,'id');
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
		
	}
	
}
