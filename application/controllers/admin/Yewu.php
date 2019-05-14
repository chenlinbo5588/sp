<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Yewu extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Basic_data_service','User_service','Yewu_service'));
		
		$this->basic_data_service->setDataModule($this->_dataModule);
		
		$this->_moduleTitle = '业务';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'workCategory' => $this->basic_data_service->getTopChildList('工作类别'),
			'serviceArea' => $this->basic_data_service->getTopChildList('服务区域'),
			'basicData' => $this->basic_data_service->getBasicDataList(),
			'operation' => Operation::$typeName,
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
		$search['yewu_name'] = $this->input->get_post('yewu_name');
		$search['real_name'] = $this->input->get_post('real_name');
		$search['worker_name'] = $this->input->get_post('worker_name');
		$search['worker_mobile'] = $this->input->get_post('worker_mobile');
		$search['user_name'] = $this->input->get_post('user_name');
		$search['user_mobile'] = $this->input->get_post('user_mobile');
		$search['work_category'] = $this->input->get_post('work_category');
		$search['service_area'] = $this->input->get_post('service_area');
		$search['status'] = $this->input->get_post('status');
		
		
		if($search['yewu_name']){
			$condition['like']['yewu_name'] = $search['yewu_name'];
		}
		if($search['real_name']){
			$condition['like']['real_name'] = $search['real_name'];
		}
		if($search['worker_name']){
			$condition['like']['worker_name'] = $search['worker_name'];
		}
		if($search['worker_mobile']){
			$condition['like']['worker_mobile'] = $search['worker_mobile'];
		}
		if($search['user_name']){
			$condition['like']['user_name'] = $search['user_name'];
		}
		if($search['user_mobile']){
			$condition['like']['user_mobile'] = $search['user_mobile'];
		}
		if($search['work_category']){
			$condition['where']['work_category'] = $search['work_category'];
		}
		if($search['service_area']){
			$condition['where']['service_area'] = $search['service_area'];
		}
		if($search['status']){
			$condition['where']['status'] = $search['status'];
		}
		
		$list = $this->Yewu_Model->getList($condition);
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
		$id = $this->input->get_post('id');
		$info = $this->Yewu_Model->getFirstByKey($id);
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){		
/*				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}*/
				
				$updateData = array_merge($_POST,$this->addWhoHasOperated('edit'));
				$returnVal = $this->Yewu_Model->update($updateData,array('id' => $id));
				
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
				$newid =$this->Yewu_Model->_add($insertData);
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
		
		$workCategory = $this->basic_data_service->getTopChildList('工作类别');
		$serviceArea = $this->basic_data_service->getTopChildList('服务区域');
		$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		$this->form_validation->set_rules('name','姓名','required');
		$this->form_validation->set_rules('work_category','业务类型','required|in_list['.implode(',',array_keys($workCategory)).']',array(
				'in_list' => '业务类型'
		));
	}
	
	public function delete(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			$deleteRows = $this->Yewu_Model->updateByCondition(
					array(
						'status' => Operation::$revoke,
					),
					array(
						'where_in' => array(array('key' => 'id','value' => $ids)
					)
			));
			
			
			$this->jsonOutput('撤销成功',array('jsReload' => true));
		}else{
			$this->jsonOutput('撤销成功',$this->getFormHash());
			
		}
	}
	
	
	public function groupTransfer(){
		
		
	}
	
	
	
}
