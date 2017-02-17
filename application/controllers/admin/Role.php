<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Role extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model(array('Adminuser_Model','Fn_Model','Role_Model'));
	}
	
	
	
	private function _prepareRoleData(){
		
		$info = array(
			'name' => $this->input->post('name'),
			'permission' => $this->input->post('permission'),
			'status' => $this->input->post('status'),
		);
		
		
		
		if(is_array($this->input->post('permission'))){
			$info['permission'] = array_flip($this->input->post('permission'));
			
		}else{
			$info['permission'] = array();
		}
			
		
		return $info;
	}
	
	
	public function index(){
		
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
	
	
	
	public function add(){
		$feedback = '';
		
		$action = 'add';
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','权限组名称','required|is_unique['.$this->Role_Model->getTableRealName().'.name]');
			
			
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
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Role_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$action = 'edit';
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
		
		
		
		return $info;
	}
	
	
	
	public function edit(){
		
		$this->assign('action','edit');
		
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			$this->assign('ispost',true);
			$this->_getRoleRules();
			
			$this->form_validation->set_rules('name','权限组名称','required|is_unique_not_self['.$this->Role_Model->getTableRealName().".name.id.{$id}]");
			
			
			$info = $this->_prepareRoleData();
			$info['id'] = $id;
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				//unset($info['id']);
				
				$info['permission'] = $this->_getEncodePermision($this->input->post('permission'),$info['name']);
				
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
		
		$this->display('role/add');
	}
	
}
