<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Cms_Article_Class extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Cms_service'));
		
		$this->_moduleTitle = 'CMS文章分类';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/category','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '新增'),
		);
	}
	
	
	public function category(){
		
		$id = $this->input->get_post('pid') ? $this->input->get_post('pid') : 0;
		
		$treelist = $this->cms_service->getArticleClassTreeHTML();
		$deep = 0;
		
		
		//print_r($list);
		$parentId = 0;
		foreach($treelist as $item){
			if($id == $item['id']){
				$deep = $item['level'];
				$parentId = $item['pid'];
			}
			
		}
		
		$list = $this->cms_service->getArticleClassByParentId($id);
		
		$this->assign(array(
			'list' => $list,
			'parentId' => $parentId,
			'deep' => $deep + 1,
			'id' => $id
		));
		
		$this->display();
	}
	
	
	private function _getRules($action = 'add'){
		
		$this->form_validation->set_rules('name','中文名称','required|min_length[1]|max_length[30]');
		$this->form_validation->set_rules('list_tplname','文章列表模版名称','required|min_length[1]|max_length[30]');
		$this->form_validation->set_rules('detail_tplname','文章详情页模版名称','required|min_length[1]|max_length[30]');
		
		$this->form_validation->set_rules('status','开启状态','required|in_list[0,1]');
		
		$pid = $this->input->post('pid');
		
		if($pid){
			$this->form_validation->set_rules('pid','父级分类', array(
					'in_db_list['.$this->Cms_Article_Class_Model->getTableRealName().".id]",
					array(
						'checkpid_callable['.$action.']',
						array(
							$this->cms_service,'checkpid'
						)
					)
				)
			);
		}
		
		$sort = $this->input->post('ac_sort');
		if($sort){
			$this->form_validation->set_rules('ac_sort','排序',"is_natural|less_than[256]");
		}
		
	}
	
	public function delete(){
		
		$delId = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($delId)){
			
			if(is_array($delId)){
				$delId = $delId[0];
			}
			
			$this->cms_service->deleteArticleClass($delId);
			$this->jsonOutput('删除成功',$this->getFormHash());
			
		}else{
			$this->jsonOutput('请求非法');
			
		}
	}
	
	
	/**
	 * 
	 */
	private function _prepareData($action = 'add'){
		$info = array(
			'pid' => $this->input->post('pid') ? $this->input->post('pid') : 0,
			'list_tplname' => $this->input->post('list_tplname') ? $this->input->post('list_tplname') : '',
			'detail_tplname' => $this->input->post('detail_tplname') ? $this->input->post('detail_tplname') : '',
			'ac_sort' => $this->input->post('ac_sort') ? $this->input->post('ac_sort') : 255,
		);
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		$info = array();
		
		$treelist = $this->cms_service->getArticleClassTreeHTML();
		$pid = $this->input->get_post('pid');
		if($pid){
			$info['pid'] = $pid;
		}
		
		
		if($this->isPostRequest()){
			$this->_getRules('add');
			
			for($i = 0; $i < 1; $i++){
				$info = array_merge($_POST,$this->_prepareData('add'));
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				
				
				$newid = $this->Cms_Article_Class_Model->_add($info);
				
				unset($info['id']);
				
				$error = $this->Cms_Article_Class_Model->getError();
				
				if(QUERY_OK != $error['code']){
					$feedback = getErrorTip('保存失败,'.$error['message']);
					break;
				}
				
				$feedback = getSuccessTip('保存成功,页面将自动刷新');
				$info = $this->Cms_Article_Class_Model->getFirstByKey($newid);
				
				$redirectUrl = admin_site_url($this->_className.'/edit?id='.$newid);
			}
		}else{
			$info['status'] = 1; 
			$info['list_tplname'] = 'article_list'; 
			$info['detail_tplname'] = 'article'; 
		}
		
		$this->assign(array(
			'list' => $treelist,
			'info' => $info,
			'feedback' => $feedback,
			'redirectUrl' => $redirectUrl
		));
		
		$this->display();
	}
	
	
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		$info = $this->Cms_Article_Class_Model->getFirstByKey($id);
		$treelist = $this->cms_service->getArticleClassTreeHTML();
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		
		if($this->isPostRequest()){
			
			$this->_getRules('edit');
			
			for($i = 0; $i < 1; $i++){
				$infoPost = array_merge($_POST,$this->_prepareData('edit'));
				
				$infoPost['id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					$info = $infoPost;
					break;
				}
				
				$info = array_merge($infoPost,$this->addWhoHasOperated('edit'));
				
				$this->Cms_Article_Class_Model->update($info,array('id' => $id));
				
				$error = $this->Cms_Article_Class_Model->getError();
				
				if(QUERY_OK != $error['code']){
					$feedback = getErrorTip('保存失败,'.$error['message']);
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback,
			'list' => $treelist
		));
		
		$this->display($this->_className.'/add');
	}
	
	
	
	/**
	 * 快速编辑
	 */
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
			$this->form_validation->set_rules('fieldname','字段','in_list[name,ac_sort,status]');
			
			switch($fieldName){
				case 'name':
					$this->form_validation->set_rules('name','名称','trim|required|min_length[1]|max_length[50]');
					break;
				case 'ac_sort';
					$this->form_validation->set_rules('ac_sort','排序',"is_natural|less_than[256]");
					break;
				case 'status':
					$this->form_validation->set_rules('status','开启状态','required|in_list[0,1]');
					break;
				default:
					break;
			}
		
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				
				if($this->Cms_Article_Class_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
		
		
	}
	
}