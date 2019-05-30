<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Mould extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Cms_service','Attachment_service'));
		
		$this->assign('imageConfig',config_item('weixin_img_size'));
		
		$this->_moduleTitle = '模板管理';
		$this->_className = strtolower(get_class());

		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
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
		$search['style'] = $this->input->get_post('style');
		
		if($search['type']){
			$condition['like']['type'] = $search['type'];
		}
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		
		$list = $this->Template_Model->getList($condition);
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $currentPage
			
		));
		
		$this->display();
		
	}
	public function edit(){
		$id = $this->input->get_post('id');
		$info = $this->Template_Model->getFirstByKey($id);
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_setRule();		
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$updateData = array_merge($_POST,$this->addWhoHasOperated('edit'));
				$updateData['gmt_edit'] = time();
				$resuit = $this->Template_Model->update($updateData,array('id' => $id));
				
				
				if($resuit){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{
			$this->assign('info',$info);
			$this->display($this->_className.'/add');
			
		}
	}
	
	
	public function add(){

		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_setRule();		
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$insertData = array_merge($_POST,$this->addWhoHasOperated('add'));
				$newid =$this->Template_Model->_add($insertData);
				
				if($newid){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{
			$this->display();
		}
	}
	
	private function _setRule(){
		$this->form_validation->set_rules('name','模板名称','required');
		$this->form_validation->set_rules('type','模板类型','required');
		$this->form_validation->set_rules('content','模板内容','required');
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
	

}
