<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Lab_role extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
	}
	
	
	/**
	 * 
	 */
	private function _prepareRoleData(){
		$info = array(
			'name' => trim($this->input->post('name')),
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
			'where' => array(
				'oid' => $this->_currentOid
			),
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
				
				if(!in_array($switchType,array('1','-1'))){
					break;
				}
				
				$ids = $this->input->post('id');
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
				
				$this->Lab_Role_Model->batchUpdate($updateData,'id');
			}
			
		}
		
		
		$list = $this->Lab_Role_Model->getList($condition);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		
		$this->display();
	}
	
	
	private function _getRoleRules(){
		
		$this->form_validation->set_rules('status','权限组状态','required|in_list[1,-1]');
		$this->form_validation->set_rules('permission[]','权限','required');
		
	}
	
	
	/**
	 * 检查角色名称
	 */
	public function checkRoleName($rolename,$id = 0){
		$info = $this->Lab_Role_Model->getById(array(
			'where' => array(
				'name' => $rolename,
				'oid' => $this->_currentOid
			)
		));
		
		if($info){
			if($id && $info['id'] == $id){
				//如果是自己就通过
				return true;
			}
			
			$this->form_validation->set_message('checkRoleName', "名称{$rolename}已经存在");
			return false;
		}else{
			return true;
		}
	}
	
	
	
	public function add(){
		$feedback = '';
		if($this->isPostRequest()){
			$this->form_validation->set_rules('name','角色名称','required|callback_checkRoleName');
			$this->_getRoleRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareRoleData();
				
				if(!$this->form_validation->run()){
					///$feedback = $this->form_validation->error_string();
					if(is_array($info['permission'])){
						$info['permission'] = array_flip($info['permission']);
					}
					
					break;
				}
				
				$info['permission'] = $this->_getEncodePermision($this->input->post('permission'),$this->input->post('name'));
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Lab_Role_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->_refreshRoleInfo($newid);
			}
		}
		
		
		
		
		/*
		$list = $this->Lab_Fn_Model->getList();
		$fnTree = $this->phptree->makeTree($list,array(
			'primary_key' => 'id',
			'parent_key' => 'parent_id',
			'expanded' => true
		));
		*/
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('fnTree',$this->_getFnTree());
		
		$this->display();
	}
	
	private function _getFnTree(){
		$this->load->library('Tree_service');
		$this->tree_service->setTargetModel($this->Lab_Fn_Model,'id','parent_id','name');
		return $this->tree_service->toTree();
		
	}
	
	
	private function _getEncodePermision($permisionArray){
		$limit_str = '';
		if (is_array($permisionArray)){
			$limit_str = implode('|',$permisionArray);
		}
		
		return $this->encrypt->encode($limit_str,config_item('encryption_key'));
	}
	
	
	private function _refreshRoleInfo($id ){
		
		$info = $this->Lab_Role_Model->getFirstByKey($id,'id');
		$info['permission'] = $this->encrypt->decode($info['permission'],config_item('encryption_key'));
		$info['permission'] = explode('|',$info['permission']);
		$info['permission'] = array_flip($info['permission']);
		
		return $info;
	}
	
	
	
	public function edit(){
		
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			$this->_getRoleRules();
			
			$this->form_validation->set_rules('name','权限组名称','required|is_unique_not_self['.$this->Lab_Role_Model->getTableRealName().".name.id.{$id}]");
			
			$info = $this->_prepareRoleData();
			$info['id'] = $id;
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					//$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info['permission'] = $this->_getEncodePermision($this->input->post('permission'));
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Lab_Role_Model->update($info, array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->_refreshRoleInfo($id);
			}
		}else{
			$info = $this->_refreshRoleInfo($id);
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('fnTree',$this->_getFnTree());
		
		$this->display('lab_role/add');
	}
	
}
