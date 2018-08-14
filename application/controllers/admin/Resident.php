<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Resident extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service'));
		
		$this->_moduleTitle = '小区';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
		);
		
		
		$this->form_validation->set_error_delimiters('<div>','</div>');
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
		$search['address'] = $this->input->get_post('address');
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		
		if($search['address']){
			$condition['like']['address'] = $search['address'];
		}
		
		
		$list = $this->Resident_Model->getList($condition);
		
		
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'currentPage' => $currentPage
		));
		
		$this->display();
		
	}
	
	
	/**
	 * 验证
	 */
	private function _getRules(){
		
		$this->form_validation->set_rules('address','小区地址','required|min_length[2]|max_length[200]');
		$this->form_validation->set_rules('yezhu_num','入驻业主数量','required|is_natural');
		$this->form_validation->set_rules('total_num','总套数','required|is_natural');
		
		$this->form_validation->set_rules('lng','经度','required|is_numeric');
		$this->form_validation->set_rules('lat','纬度','required|is_numeric');
	}
	
	
	
	/**
	 * 
	 */
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			/*
			$this->Resident_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'brand_id','value' => $ids)
				)
			));
			*/
			
			//@todo 
			$this->jsonOutput('删除失败，待开发完善',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	/**
	 * 
	 */
	private function _prepareData(){
		$displayorder = $this->input->post('displayorder');
		
		return array(
			'displayorder' => $displayorder ? $displayorder : 255
		);
	}
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		//print_r($_FILES);
		if($this->isPostRequest()){
			
			$this->_getNameRule();
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$newid = $this->Resident_Model->_add(array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add')));
				$error = $this->Resident_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('name').'名称已被占用');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			$this->display();
		}
		
	}
	
	
	/**
	 * 获取
	 */
	private function _getNameRule($id = 0){
		if($id){
			$this->form_validation->set_rules('name','小区名称','required|min_length[2]|max_length[200]|is_unique_not_self['.$this->Resident_Model->getTableRealName().".name.id.{$id}]");
		}else{
			$this->form_validation->set_rules('name','小区名称','required|min_length[2]|max_length[200]|is_unique['.$this->Resident_Model->getTableRealName().".name]");
		}
		
	}
	
	
	/**
	 * 编辑页面
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$info = $this->Resident_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getNameRule($id);
			$this->_getRules();
			for($i = 0; $i < 1; $i++){
				$info = array_merge($info,$_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$this->Resident_Model->update($info,array('id' => $id));
				$error = $this->Resident_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('name').'名称已存在');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
		}else{
			$this->assign('info',$info);
			$this->display($this->_className.'/add');
		}
		
	}
	
	
	
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		
		$this->form_validation->set_error_delimiters('','');
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[name,displayorder]');
			
			switch($fieldName){
				case 'name':
					$this->_getNameRule($id);
					break;
				case 'displayorder';
					$this->form_validation->set_rules('displayorder','排序',"required|is_natural|less_than[256]");
					break;
				default:
					break;
			}
			
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				
				if($this->Resident_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
	}
	
	
	/**
	 * 获得小区名称 自动完成
	 */
	public function getResidentName(){
		
		$searchKey = $this->input->get_post('term');
		
		$return = array();
		
		if($searchKey){
			$residentList = $this->Resident_Model->getList(array(
				'like' => array(
					'name' => $searchKey
				),
				'limit' => 50
			));
			
			foreach($residentList as $feetypeItem){
				$return[] = array(
					'id' =>$feetypeItem['id'],
					'label' => $feetypeItem['name'],	
				);
			}
		}
		
		//echo json_encode($return);
		$this->jsonOutput2('',$return,false);
		
	}
	
}
