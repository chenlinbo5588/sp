<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class TdkcUser extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Basic_data_service','User_service'));
		
		$this->basic_data_service->setDataModule($this->_dataModule);
		
		$this->_moduleTitle = '用户';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'userType' => UserType::$typeName,
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
		);
		
	}
	
	/**
	 * 
	 */
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
		$search['name'] = $this->input->get_post('name');
		$search['mobile'] = $this->input->get_post('mobile');
		$search['user_type'] = $this->input->get_post('user_type');
		
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		if($search['mobile']){
			$condition['like']['mobile'] = $search['mobile'];
		}
		if($search['user_type']){
			$condition['where']['user_type'] = $search['user_type'];
		}
		
		$list = $this->User_Model->getList($condition);
		$this->assign(array(
			'basicData' => $this->basic_data_service->getBasicData(),
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $currentPage
			
		));
		
		$this->display();
		
	}
	public function edit(){
		$companyList = $this->Company_Model->getList(array('select' => 'id,name'),'name');
		$id = $this->input->get_post('id');
		$info = $this->User_Model->getFirstByKey($id);
		$userExtend = $this->User_Extend_Model->getList(array('where' => array('user_id' => $id)));
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			
			
			for($i = 0; $i < 1; $i++){
				$this->_setRule();		
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$updateData = array_merge($_POST,$this->addWhoHasOperated('edit'));
				if($updateData['company_name']){
					$updateData['company_id'] = $companyList[$updateData['company_name']]['id'];
				}
				if($updateData['group_name']){
					//$updateData['group_id'] = $companyList[$updateData['group_name']]['id'];
				}
				$returnVal = $this->User_Model->update($updateData,array('id' => $id));
				
				if($returnVal < 0){
					$this->jsonOutput('保存失败',$this->getFormHash());
				}
				else{
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{
		
			$this->assign('info',$info);
			$this->assign('userExtend',$userExtend);
			$this->display($this->_className.'/add');
			
		}
	}
	
	
	public function set_worker(){
		$ids = $this->input->post('id');
		//@TOOD
		//待加入微信通知
		if($this->isPostRequest() && !empty($ids)){
			for($i = 0; $i < 1 ; $i++){	
				if(!is_array($ids)){
					$ids = (array)$ids;
				}
				$userList = $this->User_Model->getList(array('where_in'=>array(array('key' => 'id', 'value' => $ids))));
				$who = $this->addWhoHasOperated('edit');
				
				foreach($userList as $key => $item){
					$password = $this->encrypt->encode(trim('123456'),config_item('encryption_key').md5(trim($item['mobile'])));
					$returnstatusVal = $this->User_Model->updateByCondition(
						array(
							'user_type' => 2,
							'password' => $password,
							'edit_uid' => $who['edit_uid'],
	    					'edit_username' => $who['edit_username'],
						),
						array('where_in' => array(array('key' => 'id', 'value' => $ids)))
					);
				}
				
				
				if($returnstatusVal < 1){
					$this->jsonOutput('工作人员设置失败失败,没有已派单记录');
					break;
				}
				
				$this->jsonOutput('工作人员设置成功',array('jsReload' => true));
			}
			
		}else{
			$this->display();
		}	
	}
	
	public function add(){
		$companyList = $this->Company_Model->getList(array('select' => 'id,name'),'name');
		//$groupList = $this->Worker_Group_Model->getList(array('select' => 'id,group_name'),'group_name');
		if($this->isPostRequest()){
			$this->_setRule();
			for($i = 0; $i < 1; $i++){

				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_html(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				$insertData = array_merge($_POST,$this->addWhoHasOperated('add'));
				if($insertData['company_name']){
					$insertData['company_id'] = $companyList[$insertData['company_name']]['id'];
				}
				if($insertData['group_name']){
					//$insertData['group_id'] = $companyList[$insertData['group_name']]['id'];
				}
				$newid =$this->User_Model->_add($insertData);
				if($newid){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
				
			}
		}else{
			
			$this->display();
		}
	}
	
	private function _setRule(){
		//缺少公司用户组的验证

		$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		$this->form_validation->set_rules('name','姓名','required');
		$this->form_validation->set_rules('user_type','用户类型','required');
		$this->form_validation->set_rules('company_name','公司名称','required');
	}
	
	public function delete(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$deleteRows = $this->User_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',array('jsReload' => true));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	public function inline_edit(){
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');

			$message = '修改失败';
			
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				
				if($this->User_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			
			$this->jsonOutput($message);
			
		}
		
		
	}
}
