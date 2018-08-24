<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Role extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Admin_service');
		
		$this->_moduleTitle = '角色';
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
	
	
	
	private function _prepareData(){
		
		$info = array();
		
		if(is_array($this->input->post('permission'))){
			$info['permission'] = array_flip($this->input->post('permission'));
			
		}else{
			$info['permission'] = array();
		}
		
		$expire = $this->input->post('expire');
		
		if(empty($expire)){
			$info['expire'] = 0;
		}else{
			$info['expire'] = strtotime($info['expire']);
		}
		
		
		return $info;
	}
	
	
	public function index(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$keywords = $this->input->get_post('keywords');
		$condition = array(
			'select' => 'id,name,enable,user_cnt,add_uid,edit_uid,add_username,edit_username,gmt_create,gmt_modify',
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
			
		);
		
		if($keywords){
			$condition['like']['name'] = $keywords;
		}
		

		$switchType = $this->input->get_post('submit_type');
		
		for($i = 0; $i < 1; $i++){
			
			if(!in_array($switchType,array('开启','关闭'))){
				break;
			}
			
			$ids = $this->input->get_post('del_id');
			
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

		
		$list = $this->Role_Model->getList($condition);
		
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'currentPage' => $currentPage,
			'editUrl' => admin_site_url('role/edit'),
		));
		
		$this->display();
	}
	
	
	/**
	 * 验证规则
	 */
	private function _getRules(){
		
		$this->form_validation->set_rules('enable','权限组状态','required|in_list[0,1]');
		$this->form_validation->set_rules('permission[]','权限','required');
		
	}
	
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','角色名称','required|is_unique['.$this->Role_Model->getTableRealName().'.name]');
			
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add'));
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败'.$this->form_validation->error_first_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$info['permission'] = $this->_getEncodePermision($this->input->post('permission'),$this->input->post('name'));
				
				if(($newid = $this->Role_Model->_add($info)) < 0){
					$error = $this->Role_Model->getError();
					$this->jsonOutput($error['message']);
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			
			$list = $this->Fn_Model->getList(array(
				'order' => 'displayorder ASC, id ASC'
			));
			
			$fnTree = $this->phptree->makeTree($list,array(
				'primary_key' => 'id',
				'parent_key' => 'parent_id',
				'expanded' => true
			));
			
			$this->assign(array(
				'fnTree' => $fnTree,
				'info' => array('enable' => 1)
			));
			
			$this->display();
			
		}
		
	}
	
	
	private function _getEncodePassword($psw,$email){
		return $this->encrypt->encode(trim($psw),config_item('encryption_key').md5(trim($email)));
	}
	
	
	private function _getEncodePermision($permisionArray , $name){
		
		$limit_str = '';
		
		if (is_array($permisionArray)){
			$limit_str = implode('|',$permisionArray);
		}
		
		return $this->encrypt->encode(strtolower($limit_str),config_item('encryption_key').md5($name));
	}
	
	
	private function _refreshRoleInfo($id ){
		
		
		$info = $this->Role_Model->getFirstByKey($id,'id');
		$info['permission'] = $this->encrypt->decode($info['permission'],config_item('encryption_key').md5($info['name']));
		$info['permission'] = explode('|',$info['permission']);
		$info['permission'] = array_flip($info['permission']);
		
		
		return $info;
	}
	
	
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		
		$id = $this->input->get_post('id');
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id,'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			$this->form_validation->set_rules('name','角色名称','required|is_unique_not_self['.$this->Role_Model->getTableRealName().".name.id.{$id}]");
			
			$info = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败',array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$info['permission'] = $this->_getEncodePermision($this->input->post('permission'),$info['name']);
				
				if($this->Role_Model->update($info, array('id' => $id)) < 0){
					$error = $this->Role_Model->getError();
					$this->jsonOutput($error['message']);
					
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
		}else{
			
			$info = $this->admin_service->getRoleInfo($id);
			
			$list = $this->Fn_Model->getList(array(
				'order' => 'displayorder ASC, id ASC'
			));
			
			$fnTree = $this->phptree->makeTree($list,array(
				'primary_key' => 'id',
				'parent_key' => 'parent_id',
				'expanded' => true
			));
			
			$this->assign('info',$info);
			
			
			$this->assign('fnTree',$fnTree);
			
			$this->display('role/add');
		}
		
		
		
	}
	
	
	
	
	/**
	 * 删除
	 */
	public function delete(){
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest() && !empty($id)){
			
			if(is_array($id)){
				$id = $id[0];
			}
			
			$returnVal = $this->Role_Model->deleteByCondition(array(
				'where' => array(
					'id' => $id,
					'user_cnt' => 0
				)
			));
			
			if($returnVal > 0){
				$this->jsonOutput('删除成功');
			}else{
				$this->jsonOutput('删除失败,只能删除成员数量为0的角色');
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
			
			$returnVal = $this->admin_service->roleOnOff($ids,$op,$this->addWhoHasOperated('edit'));
			
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
		$this->_onoff('禁用');
	}
	
}
