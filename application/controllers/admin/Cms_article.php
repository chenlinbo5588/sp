<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_Article extends Ydzj_Admin_Controller {
	
	private $_imageConfig = array();
	private $_articleState;
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('cms_service','Attachment_service'));
		
		$this->assign('imageConfig',config_item('weixin_img_size'));
		
		$this->_articleState = array(
			'1' => '草稿',
			'2' => '待审核',
			'3' => '已发布',
			'4' => '回收站'
		);
		
		$this->assign('article_state',$this->_articleState);
		
	}
	
	public function index(){
		
		
		$treelist = $this->cms_service->toEasyUseArray($this->cms_service->getArticleClassTreeHTML(),'id');
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array(),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => 8,
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$article_title = $this->input->get_post('article_title');
		$article_state = $this->input->get_post('article_state');
		$articleClassId = $this->input->get_post('id') ? $this->input->get_post('id') : 0;
		
		if($article_title){
			$condition['like']['article_title'] = $article_title;
		}
		
		if($article_state){
			$condition['where']['article_state'] = $article_state;
		}
		
		if($articleClassId){
			$articleClassIdList = $this->cms_service->getAllChildArticleClassByPid($articleClassId);
			$articleClassIdList[] = $articleClassId;
			$condition['where_in'][] = array('key' => 'ac_id', 'value' => $articleClassIdList);
		}
		
		//print_r($condition);
		$list = $this->Cms_Article_Model->getList($condition);
		
		$this->assign('articleClassList',$treelist);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
	
	
	public function getNavUrl(){
		$id = $this->input->get_post('id');
		$info = $this->Cms_Article_Model->getById(array(
			'where' => array(
				'id' => intval($id)
			
			)
		));
		
		if(empty($info)){
			$this->jsonOutput('',array(
				'name_cn' => '',
				'name_en' => '',
				'url_cn' => '',
				'url_en' => '',
			));
		}else{
			$this->jsonOutput('',array(
				'name_cn' => $info['article_title'],
				'name_en' => $info['article_title'],
				'url_cn' => base_url('cms/detail/'.$info['id'].'.html'),
				'url_en' => base_url('cms/detail/'.$info['id'].'.html'),
			));
		}
	}
	
	
	/**
	 * 
	 */
	private function _getRules($action = 'add',$info = array()){
		$this->form_validation->set_rules('article_title','文章标题','required|max_length[80]');
		$this->form_validation->set_rules('content','文章内容','required');
		$this->form_validation->set_rules('ac_id','文章分类','required|in_db_list['.$this->Cms_Article_Class_Model->getTableRealName().'.id]');
		
		if($this->input->post('article_origin')){
			$this->form_validation->set_rules('article_origin','文章来源','required|max_length[30]');
		}
		
		if($this->input->post('origin_address')){
			$this->form_validation->set_rules('origin_address','文章来源链接','required|valid_url');
		}
		
		if($this->input->post('jump_url')){
			$this->form_validation->set_rules('jump_url','链接','required|valid_url');
		}
		
		if($this->input->post('article_sort')){
			$this->form_validation->set_rules('article_sort','排序',"is_natural|less_than[256]");
		}
		
		//$this->form_validation->set_rules('image_url','文章封面',"required|valid_url");
		$this->form_validation->set_rules('keyword','文章关键字',"required|max_length[100]");
		
		if($this->input->post('author')){
			$this->form_validation->set_rules('author','文章作者',"required|max_length[30]");
		}
		
		if($this->input->post('article_tag')){
			$this->form_validation->set_rules('article_tag','文章标签',"required|max_length[150]");
		}
		
		$this->form_validation->set_rules('commend_flag','文章标签',"required|in_list[0,1]");
		$this->form_validation->set_rules('comment_flag','是否允许评论',"required|in_list[0,1]");
		
		//已发布
		if($this->input->post('article_state') == 3){
			//$this->form_validation->set_rules('verify_reason','审核意见',"required|min_length[2]|max_length[150]");
		}
		
		if($this->input->post('digest')){
			$this->form_validation->set_rules('digest','文章摘要',"required|max_length[80]");
		}
		
		
		
	}
	
	
	public function delete(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			/*
			$this->Cms_Article_Model->updateByCondition(array('article_state' => 4),array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			*
			*/
			$this->Cms_Article_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功');
		}else{
			$this->jsonOutput('请求非法');
			
		}
	}
	
	
	private function _prepareArticleData(){
		
		$info = array(
			'article_title' => $this->input->post('article_title'),
			'ac_id' => $this->input->post('ac_id') ? $this->input->post('ac_id') : 0,
			'article_origin' => $this->input->post('article_origin') ? $this->input->post('article_origin') : '',
			'origin_address' => $this->input->post('origin_address') ? $this->input->post('origin_address') : '',
			'jump_url' => $this->input->post('jump_url') ? $this->input->post('jump_url') : '',
			'article_sort' => $this->input->post('article_sort') ? $this->input->post('article_sort') : 255,
			'image_url' => $this->input->post('image_url') ? $this->input->post('image_url') : '',
			'image_aid' => $this->input->post('image_aid') ? $this->input->post('image_aid') : 0,
			'content' => $this->input->post('content'),
			'keyword' => $this->input->post('keyword') ? $this->input->post('keyword') : '',
			'article_tag' => $this->input->post('article_tag') ? $this->input->post('article_tag') : '',
			'article_state' => $this->input->post('article_state'),
			'commend_flag' => $this->input->post('commend_flag'),
			'comment_flag' => $this->input->post('comment_flag'),
			'verify_reason' => $this->input->post('verify_reason') ? $this->input->post('verify_reason') : '',
		);
		
		if(trim($this->input->post('author'))){
			$info['author'] = $this->_adminProfile['basic']['username'];
		}else{
			$info['author'] = $this->input->post('author');
		}
		
		if(trim($this->input->post('digest'))){
			$info['digest'] = cutText(trim(html_entity_decode(strip_tags($this->input->post('content')))),80);
		}else{
			$info['digest'] = cutText(trim(html_entity_decode(strip_tags($info['content']))),80);
		}
		
		$info['image_url'] = str_replace(base_url(),'',$info['image_url']);
		
		//已发布
		if($info['article_state'] == 3){
			$info['publish_time'] = $this->input->server('REQUEST_TIME');
			$info['verify_time'] = $info['publish_time'];
			
			$info['verify_username'] = $this->_adminProfile['basic']['username'];
			$info['publish_username'] = $info['verify_username'];
			
			$info['verify_uid'] = $this->_adminProfile['basic']['uid'];
		}
		//print_r($info);
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		$treelist = $this->cms_service->getArticleClassTreeHTML();
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareArticleData();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Cms_Article_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				
				$info = $this->Cms_Article_Model->getFirstByKey($newid);
			}
		}else{
			$info['commend_flag'] = 0;
			$info['comment_flag'] = 0;
			$info['author'] = $this->_adminProfile['basic']['username'];
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('articleClassList',$treelist);
		$this->display();
	}
	
	
	public function edit(){
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$treelist = $this->cms_service->getArticleClassTreeHTML();
		
		$info = $this->Cms_Article_Model->getFirstByKey($id);
		
		if($this->isPostRequest()){
			$this->_getRules();
			$this->form_validation->set_rules('article_click','点击数', 'required|is_natural');
			$this->form_validation->set_rules('comment_count','评论数', 'required|is_natural');
			$this->form_validation->set_rules('share_count','分享数', 'required|is_natural');
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareArticleData();
				
				$info['id'] = $id;
				$info['article_click'] = $this->input->post('article_click');
				$info['comment_count'] = $this->input->post('comment_count');
				$info['share_count'] = $this->input->post('share_count');
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Cms_Article_Model->update($info,array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
			
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('articleClassList',$treelist);
		$this->display('cms_article/add');
	}
	
	
}
