<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Navigation extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Navigation_service'));
	}
	
	public function index(){
		
		$treelist = $this->navigation_service->toEasyUseArray($this->navigation_service->getArticleClassTreeHTML(),'ac_id');
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array(),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$search_title = $this->input->get_post('search_title');
		$classId = $this->input->get_post('id') ? $this->input->get_post('id') : 0;
		
		
		if($search_title){
			$condition['like']['name_cn'] = $search_title;
		}
		
		if($classId){
			$classIdList = $this->navigation_service->getAllChildClassByPid($classId);
			$classIdList[] = $classId;
			$condition['where_in'][] = array('key' => 'id', 'value' => $classIdList);
		}
		
		
		//print_r($condition);
		$list = $this->Navigation_Model->getList($condition);
		
		$this->assign('classList',$treelist);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
	
	
	
	private function _getRules(){
		$this->form_validation->set_rules('article_title','文章标题','required|max_length[80]');
		$this->form_validation->set_rules('article_content','文章内容','required');
		$this->form_validation->set_rules('ac_id','文章分类',"required|in_db_list[{$this->Article_Class_Model->_tableRealName}.ac_id]");
		
		if($this->input->post('article_url')){
			
			$this->form_validation->set_rules('article_url','链接','required|valid_url');
		}
		
		
		$this->form_validation->set_rules('article_show','是否显示','required|in_list[0,1]');
		
		
		if($this->input->post('article_sort')){
			$this->form_validation->set_rules('article_sort','排序',"is_natural|less_than[256]");
		}
		
		if($this->input->post('article_pic')){
			$this->form_validation->set_rules('article_pic','文章封面',"required|valid_url");
		}
		
		if($this->input->post('article_author')){
			$this->form_validation->set_rules('article_author','文章作者',"required|max_length[30]");
		}
		
		if($this->input->post('article_digest')){
			$this->form_validation->set_rules('article_digest','文章摘要',"required|max_length[200]");
		}
		
	}
	
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Article_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'article_id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	private function _prepareArticleData(){
		
		
		$info = array(
			'article_title' => $this->input->post('article_title'),
			'article_content' => $this->input->post('article_content'),
			'ac_id' => $this->input->post('ac_id') ? $this->input->post('ac_id') : 0,
			'article_url' => $this->input->post('article_url') ? $this->input->post('article_url') : '',
			'article_pic' => $this->input->post('article_pic') ? $this->input->post('article_pic') : '',
			'article_pic_id' => $this->input->post('article_pic_id') ? $this->input->post('article_pic_id') : 0,
			'article_show' => $this->input->post('article_show'),
			'article_sort' => $this->input->post('article_sort') ? $this->input->post('article_sort') : 255,
			
		);
		
		if(trim($this->input->post('article_author'))){
			$info['article_author'] = $this->_adminProfile['basic']['username'];
		}else{
			$info['article_author'] = $this->input->post('article_author');
		}
		
		if(trim($this->input->post('article_digest'))){
			$info['article_digest'] = cutText(trim(html_entity_decode(strip_tags($this->input->post('article_content')))),120);
		}else{
			$info['article_digest'] = cutText(trim(html_entity_decode(strip_tags($info['article_content']))),120);
		}
		
		$info['article_time'] = $this->input->server('REQUEST_TIME');
		$info['uid'] = $this->_adminProfile['basic']['uid'];
		
		return $info;
	}
	
	
	
	
	public function add(){
		$feedback = '';
		
		$treelist = $this->navigation_service->getArticleClassTreeHTML();
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareArticleData();
				$fileList = $this->_getFileList();
				
				$this->assign('fileList',$fileList);
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if(($newid = $this->Article_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				if($fileList){
					$insData = array();
					
					$whoadd = $this->addWhoHasOperated('add');
					
					foreach($fileList as $fileInfo){
						$temp = array(
							'article_id' => $newid,
							'orig_name' => $fileInfo['orig_name'],
							'file_url' => $fileInfo['file_url'],
							'file_size' => $fileInfo['file_size'],
							'file_ext' => $fileInfo['file_ext'],
							'is_image' => $fileInfo['is_image'],
							'image_type' => $fileInfo['image_type'],
							'file_id' => $fileInfo['id']
						);
						
						$insData[] = array_merge($temp,$whoadd);
					}
					
					$this->Article_File_Model->batchInsert($insData);
				}
				
				$feedback = getSuccessTip('保存成功');
				
				$info = $this->Article_Model->getFirstByKey($newid,'article_id');
			}
		}else{
			$info['uid'] = $this->_adminProfile['basic']['uid'];
			$info['article_show'] = 1;
			$info['article_author'] = $this->_adminProfile['basic']['username'];
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('articleClassList',$treelist);
		$this->display();
	}
	
	
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('article_id');
		
		$treelist = $this->navigation_service->getArticleClassTreeHTML();
		
		$info = $this->Article_Model->getFirstByKey($id,'article_id');
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareArticleData();
				$fileList = $this->_getFileList();
				
				$info['article_id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->Article_Model->update($info,array('article_id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}else{
			
			$currentFiles = $this->Article_File_Model->getList(array(
				'select' => 'file_id,orig_name',
				'where' => array('article_id' => $id)
			));
			
			
			foreach($currentFiles as $item){
				$fileList[] = array(
					'id' => $item['file_id'],
					'orig_name' => $item['orig_name']
				);
			}
		}
		$this->assign('fileList',$fileList);
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('articleClassList',$treelist);
		$this->display('article/add');
	}
	
}