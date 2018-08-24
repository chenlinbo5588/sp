<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Group extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Admin_service','Wuye_service'));
		
		$this->_moduleTitle = '用户组';
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
	 * 初始话页面
	 */
	private function _initPageData(){
		$residentList = $this->Resident_Model->getList();
		
		$this->assign('residentList',$residentList);
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
		
		
		$username = $this->input->post('username');
		if($username){
			$condition['like']['name'] = $username;
		}
		
		
		$list = $this->Group_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		
		$this->display();
	}
	
	
	/**
	 * 获得
	 */
	private function _getRules(){
		$this->form_validation->set_rules('enable','状态','required|in_list[0,1]');
		$this->form_validation->set_rules('group_data[]','可见数据','required');
	}
	
	
	/**
	 * 
	 */
	public function delete(){
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest() && !empty($id)){
			
			if(is_array($id)){
				$id = $id[0];
			}
			
			
			$returnVal = $this->Group_Model->deleteByCondition(array(
				'where' => array(
					'id' => $id,
					'user_cnt' => 0
				)
			));
			
			if($returnVal > 0){
				$this->jsonOutput('删除成功');
			}else{
				$this->jsonOutput('删除失败,只能删除成员数量为0的组');
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
			
			$returnVal = $this->admin_service->groupOnOff($ids,$op,$this->addWhoHasOperated('edit'));
			
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
	 * 添加组
	 */
	public function add(){
		$feedback = '';
		
		$info = array();
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','组名称','required|is_unique['.$this->Group_Model->getTableRealName().'.name]');
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败'.$this->form_validation->error_first_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$info = array_merge($_POST,$this->addWhoHasOperated('add'));
				
				
				if(($newid = $this->admin_service->saveGroup($info)) < 0){
					$error = $this->Group_Model->getError();
					$this->jsonOutput($error['message']);
					
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				
			}
		}else{
			
			$this->_initPageData();
			
			$info = array(
				'enable' => 1,
				'group_data' => array()
			);
			
			$this->assign(array(
				'info' => $info,
				'feedback' => $feedback
			));
			
			$this->display();
		}
		
	}
	
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$feedback = '';
		
		$id = $this->input->get_post('id');
		$info = $this->admin_service->getGroupInfo($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','组名称','required|is_unique_not_self['.$this->Group_Model->getTableRealName().'.name.id.'.$id.']');
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败'.$this->form_validation->error_first_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$info = array_merge($_POST,$this->addWhoHasOperated('edit'));
				
				if(($newid = $this->admin_service->saveGroup($info)) < 0){
					$error = $this->Group_Model->getError();
					$this->jsonOutput($error['message']);
					
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
		}else{
			
			$this->_initPageData();
			
			$this->assign(array(
				'info' => $info,
				'feedback' => $feedback
			));
			
			$this->display($this->_className.'/add');
		}
	}
	
}
