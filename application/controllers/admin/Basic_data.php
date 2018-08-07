<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Basic_Data extends Ydzj_Admin_Controller {
	
	private $_moduleTitle = '';
	private $_className = '';
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Basic_data_service'));
		$this->_moduleTitle = '基础数据';
		
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/category','title' => '树状方式'),
			array('url' => $this->_className.'/index','title' => '列表方式'),
			array('url' => $this->_className.'/add','title' => '新增'),
		);
		//print_r($this->_subNavs);
	}
	
	
	/**
	 * 
	 */
	private function _getValidTree(){
		
		return $this->basic_data_service->getBasicDataTreeHTML(array(
			'where' => array(
				'status' => 1
			)
		));
		
	}
	
	
	/**
	 * 树形显示
	 */
	public function category(){
		
		$id = $this->input->get_post('pid') ? $this->input->get_post('pid') : 0;
		
		$treelist = $this->_getValidTree();
		
		
		$deep = 0;
		
		
		//print_r($list);
		$parentId = 0;
		foreach($treelist as $item){
			if($id == $item['id']){
				$deep = $item['level'];
				$parentId = $item['pid'];
			}
		}
		
		
		$list = $this->Basic_Data_Model->getList(array(
			'where' => array('pid' => $id,'status' => 1),
			'order' => 'displayorder ASC'
		),'id');
		
		
		$this->assign(array(
			'list' => $list,
			'parentId' => $parentId,
			'deep' => $deep + 1,
			'id' => $id
		));
		
		$this->display();
	}
	
	
	
	/**
	 * 表格形式
	 */
	public function index(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array('status' => 1),
			'order' => 'displayorder ASC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		$name = $this->input->get_post('search_name');
		if($name){
			$condition['like']['show_name'] = $name;
		}
		
		$list = $this->Basic_Data_Model->getList($condition);
		
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
		
	}
	
	/**
	 * 校验规则
	 */
	private function _getRules($action){
		$this->form_validation->set_rules('show_name','显示名称','trim|required|min_length[1]|max_length[50]');
		
		$this->form_validation->set_rules('pid','父级分类', array(
				'in_db_list['.$this->Basic_Data_Model->getTableRealName().".id]",
				array(
					'checkpid_callable['.$action.']',
					array(
						$this->basic_data_service,'checkpid'
					)
				)
			)
		);
		
		$this->form_validation->set_rules('enable','开启状态','required|in_list[0,1]');
				
		if($this->input->post('displayorder')){
			$this->form_validation->set_rules('displayorder','排序',"is_natural|less_than[256]");
		}
		
	}
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Basic_Data_Model->deleteById($ids[0]);
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	private function _prepareData(){
		$info = array(
			'show_name' => $this->input->post('show_name'),
			'pid' => $this->input->post('pid') ? $this->input->post('pid') : 0,
			'displayorder' => $this->input->post('displayorder') ? $this->input->post('displayorder') : 255,
			'enable' => $this->input->post('enable') ? $this->input->post('enable') : 0,
		);
		
		return $info;
	}
	
	
	/**
	 * 添加基础数据
	 */
	public function add(){
		$feedback = '';
		$info = array();
		
		$treelist = $this->_getValidTree();
		
		
		$pid = $this->input->get_post('pid');
		if($pid){
			$info['pid'] = $pid;
		}
		
		if($this->isPostRequest()){
			$this->_getRules('add');
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareData('add');
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				
				if(($newid = $this->Basic_Data_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$treelist = $this->_getValidTree();
				
				$feedback = getSuccessTip('保存成功,页面将再3秒内自动刷新');
				$info = $this->Basic_Data_Model->getFirstByKey($newid);
				
				$this->assign('redirectUrl',admin_site_url($this->_className.'/edit?id='.$info['id']));
			}
		}else{
			$info['enable'] = 1; 
		}
		
		$this->assign(array(
			'list' => $treelist,
			'info' => $info,
			'feedback' => $feedback,
			'action' => 'add'
		));
		
		$this->display();
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
			$this->form_validation->set_rules('fieldname','字段','in_list[show_name,displayorder,enable]');
			
			switch($fieldName){
				case 'show_name':
					$this->form_validation->set_rules('show_name','显示名称','trim|required|min_length[1]|max_length[50]');
					break;
				case 'displayorder';
					$this->form_validation->set_rules('displayorder','排序',"is_natural|less_than[256]");
					break;
				case 'enable':
					$this->form_validation->set_rules('enable','开启状态','required|in_list[0,1]');
					break;
				default:
					break;
			}
		
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				
				if($this->Basic_Data_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			
			$this->jsonOutput($message);
			
		}
		
		
	}
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		$treelist = $this->_getValidTree();
		
		$info = $this->Basic_Data_Model->getFirstByKey($id);
		
		if($this->isPostRequest()){
			
			$this->_getRules('edit');
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData();
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				if($this->Basic_Data_Model->update($info,array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$treelist = $this->_getValidTree();
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('list',$treelist);
		$this->display($this->_className.'/add');
	}
}
