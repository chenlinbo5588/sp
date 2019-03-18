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
		$info = $this->User_Model->getFirstByKey($id);
		$userExtend = $this->User_Extend_Model->getList(array('where' => array('user_id' => $id)));
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->getEditRules();
			
			for($i = 0; $i < 1; $i++){
								
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$returnVal = $this->Staff_Booking_Model->update($info,array('id' => $id));
				
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
	
	public function getEditRules(){
		$this->form_validation->set_rules('staff_name','服务人员姓名','required');
		$this->form_validation->set_rules('staff_sex','性别','required');
		$this->form_validation->set_rules('staff_mobile','手机号码','required|valid_mobile');
	}
	
}
