<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Company extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Basic_data_service','User_service'));
		
		$this->basic_data_service->setDataModule($this->_dataModule);
		
		$this->_moduleTitle = '公司';
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
		
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		$list = $this->Company_Model->getList($condition);
		$this->assign(array(
			'basicData' => $this->basic_data_service->getBasicData(),
			'list' => $list,
			'page' => $list['pager'],
			'currentPage' => $currentPage
			
		));
		
		$this->display();
		
	}
	public function edit(){
		$id = $this->input->get_post('id');
		$info = $this->Company_Model->getFirstByKey($id);
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){		
/*				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}*/
				
				$updateData = array_merge($_POST,$this->addWhoHasOperated('edit'));
				$returnVal = $this->Company_Model->update($updateData,array('id' => $id));
				
				if($returnVal < 0){
					$this->jsonOutput('保存失败',$this->getFormHash());
				}
				else{
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{
		
			$this->assign('info',$info);
			$this->assign('edit','edit');
			$this->display($this->_className.'/add');
			
		}
	}
	
	
	public function add(){

		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){

				$insertData = array_merge($_POST,$this->addWhoHasOperated('add'));
				$newid =$this->Company_Model->_add($insertData);
				if($newid){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
				
			}
		}else{
			
			$this->display();
		}
	}
	
	public function getEditRules(){
		$this->form_validation->set_rules('staff_name','服务人员姓名','required');
		$this->form_validation->set_rules('staff_sex','性别','required');
		$this->form_validation->set_rules('staff_mobile','手机号码','required|valid_mobile');
	}
	
	public function detail(){
		$id = $this->input->get_post('id');
		$info = $this->Company_Model->getFirstByKey($id);
		$compangMemberList = $this->Company_Member_Model->getList(array('where' => array('company_id' => $id)));

		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '详情');
		$this->assign('info',$info);
		$this->assign('compangMemberList',$compangMemberList);
		$this->display();
			
	}
	public function delete(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			
			$deleteRows = $this->Company_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			$this->Company_Member_Model->deleteByCondition(array(
				'whrer_in' => array(
					array('key' => 'company_id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',array('jsReload' => true));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	/**
	 * @param 自动完成
	 * 获得房屋地址
	 */
	public function getCompany(){
		
		$searchKey = $this->input->get_post('term');
		$companyId = $this->input->get_post('id');
		
		$return = array();
		
		if($searchKey){
			
			$condition = array(
				'like_after' => array(
					'name' => $searchKey
				),
				'limit' => 20
			);
			
			if($companyId){
				$condition['where']['id'] = intval($companyId);
			}
			
			$companyList = $this->Company_Model->getList($condition);

			foreach($companyList as $companyItem ){
				$return[] = array(
					'id' => $companyItem['id'],
					'label' => $companyItem['name'],
					'value' => $companyItem['name'],
				);
			}

		}
		
		$this->jsonOutput2('',$return,false);
		
	}
	
	
	
}
