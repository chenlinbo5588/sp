<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Cms_Article extends Ydzj_Admin_Controller {
	
	private $_imageConfig = array();
	private $_articleState;
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Cms_service','Attachment_service'));
		
		$this->assign('imageConfig',config_item('weixin_img_size'));
		
		
		$this->_moduleTitle = 'CMS文章';
		$this->_className = strtolower(get_class());
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '新增'),
			array('url' => $this->_className.'/draft','title' => '草稿'),
			array('url' => $this->_className.'/unverify','title' => '待审核'),
			array('url' => $this->_className.'/verify','title' => '已审核'),
			array('url' => $this->_className.'/published','title' => '已发布'),
			array('url' => $this->_className.'/recylebin','title' => '回收站'),
		);
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'article_state' => CmsArticleStatus::$statusName
		));
	}
	
	
	
	/**
	 * 查询条件
	 */
	public function _searchCondition($moreSearchVal = array()){
		
		$treelist = $this->cms_service->toEasyUseArray($this->cms_service->getArticleClassTreeHTML(),'id');
		
		
		$search['currentPage'] = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		$search['article_title'] = $this->input->get_post('article_title');
		$search['article_state'] = $this->input->get_post('article_state') ? $this->input->get_post('article_state') : '';
		$search['articleClassId'] = $this->input->get_post('id') ? $this->input->get_post('id') : 0;

		if(empty($search['article_state'])){
			unset($search['article_state']);
		}
		
		
		$search = array_merge($search,$moreSearchVal);
		
	
		$condition = array(
			'where' => array_merge(array(),$moreSearchVal),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => 8,
				'current_page' => $search['currentPage'],
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		
		if($search['article_title']){
			$condition['like']['article_title'] = $search['article_title'];
		}
		
		if($search['article_state']){
			$condition['where']['article_state'] = $search['article_state'];
		}
		
		if($search['articleClassId']){
			$articleClassIdList = $this->cms_service->getAllChildArticleClassByPid($search['articleClassId']);
			$articleClassIdList[] = $search['articleClassId'];
			$condition['where_in'][] = array('key' => 'ac_id', 'value' => $articleClassIdList);
		}
		
		
		$list = $this->Cms_Article_Model->getList($condition);
		
		
		$this->assign(array(
			'articleClassList' => $treelist,
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $search['currentPage']
		));
		
	}
	
	
	
	public function index(){
		
		$this->_searchCondition(array(
			'article_state != ' => CmsArticleStatus::$recylebin
		));
		$this->display();
	}
	
	
	
	/*
	 * 草稿 列表
	 */
	public function draft(){
		
		$this->_searchCondition(array(
			'article_state' => CmsArticleStatus::$draft
		));
		
		$this->display($this->_className.'/index');
		
	}
	
	
	
	/*
	 * 未审核 列表
	 */
	public function unverify(){
		
		$this->_searchCondition(array(
			'article_state' => CmsArticleStatus::$unverify
		));
		
		$this->display($this->_className.'/index');
		
	}
	
	
	/**
	 * 已审核列表
	 */
	public function verify(){
		
		$this->_searchCondition(array(
			'article_state' => CmsArticleStatus::$verify
		));
		
		
		$this->display($this->_className.'/index');
	}
	
	
	/**
	 * 已发布
	 */
	public function published(){
		
		$this->_searchCondition(array(
			'article_state' => CmsArticleStatus::$published
		));
		$this->display($this->_className.'/index');
	
		
	}
	
	
	/**
	 * 回收站
	 */
	public function recylebin(){
		
		$this->_searchCondition(array(
			'article_state' => CmsArticleStatus::$recylebin
		));
		
		$this->display($this->_className.'/index');
		
	}
	
	
	
	/**
	 * 提交审核
	 */
	public function handle_verify(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->cms_service->changeArticleStatus($ids,CmsArticleStatus::$unverify,CmsArticleStatus::$draft);
			
			if($returnVal >= 0){
				$this->jsonOutput('提交审核成功',$this->getFormHash());
			}else{
				$this->jsonOutput('提交审核失败',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	
	
	/**
	 * 单个审核
	 */
	public function single_verify(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$treelist = $this->cms_service->getArticleClassTreeHTML();
		
		$info = $this->Cms_Article_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/single_verify?id='.$id, 'title' => '审核');
		
		
		if($this->isPostRequest()){
			
			$this->_getVerifyRules();
			
			$inPost = true;
			
			for($i = 0; $i < 1; $i++){
				//$fileList = $this->_getFileList();
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$op = $this->input->get_post('op');
				
				$returnVal = $this->cms_service->articleVerify(array(
					'op' => $op,
					'id' => array($id),
					'reason' => $this->input->post('reason')
				),$this->_reqtime, $this->addWhoHasOperated('verify'));
				
				
				if($returnVal < 0){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
			}
			
		}else{
			
			$this->assign(array(
				'info' => $info,
				'feedback' => $feedback,
				'articleClassList' => $treelist
			));
			
			$this->display();
		}
		
	}
	
	
	private function _getVerifyRules(){
		
		$this->form_validation->set_rules('id','记录ID必填','required');
		$this->form_validation->set_rules('reason','备注','required|min_length[2]|max_length[100]');
	}
	
	/**
	 * 批量审核
	 */
	public function batch_verify(){
		if($this->isPostRequest()){
			
			$this->_getVerifyRules();
			
			for($i = 0; $i < 1 ; $i++){
			
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$id = $this->input->post('id');
				$idAr = explode(',',$id);
				$op = $this->input->get_post('op');
				
				$returnVal = $this->cms_service->articleVerify(array(
					'op' => $op,
					'id' => $idAr,
					'reason' => $this->input->post('reason')
				),$this->_reqtime, $this->addWhoHasOperated('verify'));
				
				
				if($returnVal < 0){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
			}
			
		}else{
			
			$this->assign('id',implode(',',$this->input->get_post('id')));
			
			$this->display('staff/batch_verify');
		}
		
	}
	
	
	
	/**
	 * 批量发布
	 */
	public function batch_published(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->cms_service->changeArticleStatus($ids,CmsArticleStatus::$published,CmsArticleStatus::$verify,array_merge(array(
				'publish_time' => $this->_reqtime,
			),$this->addWhoHasOperated('publish')));
			
			
			
			if($returnVal > 0 ){
				$this->jsonOutput('发布成功',$this->getFormHash());
			}else{
				$this->jsonOutput('发布失败,没有记录被发布',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	
	
	
	
	/**
	 * 获得跳转链接
	 */
	public function getNavUrl(){
		$id = $this->input->get_post('id');
		$info = $this->Cms_Article_Model->getById(array(
			'where' => array(
				'id' => intval($id)
			
			)
		));
		
		if(empty($info)){
			$this->jsonOutput('',array(
				'name' => '',
				'url' => '',
			));
		}else{
			$this->jsonOutput('',array(
				'name' => $info['article_title'],
				'url' => base_url('cms/detail/'.$info['id'].'.html'),
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
		//$this->form_validation->set_rules('keyword','文章关键字',"required|max_length[100]");
		
		if($this->input->post('author')){
			$this->form_validation->set_rules('author','文章作者',"required|max_length[30]");
		}
		
		if($this->input->post('article_tag')){
			$this->form_validation->set_rules('article_tag','文章标签',"required|max_length[150]");
		}
		
		//$this->form_validation->set_rules('commend_flag','推荐标志',"required|in_list[0,1]");
		//$this->form_validation->set_rules('comment_flag','是否允许评论',"required|in_list[0,1]");
		
		//已发布
		if($this->input->post('article_state') == 3){
			//$this->form_validation->set_rules('verify_reason','审核意见',"required|min_length[2]|max_length[150]");
		}
		
		/*
		if($this->input->post('digest')){
			$this->form_validation->set_rules('digest','文章摘要',"required|max_length[80]");
		}
		*/
		
		
	}
	
	
	public function delete(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Cms_Article_Model->updateByCondition(array('article_state' => CmsArticleStatus::$recylebin),array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			/*
			$this->Cms_Article_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			*/
			
			$this->jsonOutput('删除成功');
		}else{
			$this->jsonOutput('请求非法');
			
		}
	}
	
	
	private function _prepareData(){
		
		$info = array(
			'article_origin' => $this->input->post('article_origin') ? $this->input->post('article_origin') : '',
			'origin_address' => $this->input->post('origin_address') ? $this->input->post('origin_address') : '',
			'jump_url' => $this->input->post('jump_url') ? $this->input->post('jump_url') : '',
			'article_sort' => $this->input->post('article_sort') ? $this->input->post('article_sort') : 255,
			'image_url' => $this->input->post('image_url') ? $this->input->post('image_url') : '',
			//'keyword' => $this->input->post('keyword') ? $this->input->post('keyword') : '',
			'article_tag' => $this->input->post('article_tag') ? $this->input->post('article_tag') : '',
			'article_state' => $this->input->post('article_state'),
			//'commend_flag' => $this->input->post('commend_flag'),
			//'comment_flag' => $this->input->post('comment_flag'),
			'verify_reason' => $this->input->post('verify_reason') ? $this->input->post('verify_reason') : '',
		);
		
		if(trim($this->input->post('author'))){
			$info['author'] = $this->_adminProfile['basic']['username'];
		}else{
			$info['author'] = $this->input->post('author');
		}
		
		/*
		if(trim($this->input->post('digest'))){
			$info['digest'] = cutText(trim(html_entity_decode(strip_tags($this->input->post('content')))),80);
		}else{
			$info['digest'] = cutText(trim(html_entity_decode(strip_tags($info['content']))),80);
		}
		*/
		
		$info['image_url'] = str_replace(base_url(),'',$info['image_url']);
		
		//已发布
		if($info['article_state'] == 3){
			$info['publish_time'] = $this->input->server('REQUEST_TIME');
			$info['verify_time'] = $info['publish_time'];
			
			$info['verify_username'] = $this->_adminProfile['basic']['username'];
			$info['publish_username'] = $info['verify_username'];
			
			$info['verify_uid'] = $this->_adminProfile['basic']['uid'];
		}
		
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		$treelist = $this->cms_service->getArticleClassTreeHTML();
		
		if($this->isPostRequest()){
			$this->_getRules();
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData());
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				if($info['image_url']){
					$avatarImg = getImgPathArray($info['image_url'],array('b','m','s'),'image_url');
					$info = array_merge($info,$avatarImg);
				}
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				$newid = $this->Cms_Article_Model->_add($info);
				$error = $this->Cms_Article_Class_Model->getError();
				
				if(QUERY_OK != $error['code']){
					$feedback = getErrorTip('保存失败,'.$error['message']);
					break;
				}
				$info = $this->Cms_Article_Model->getFirstByKey($newid);
				
				$feedback = getSuccessTip('保存成功,页面即将刷新');
				$redirectUrl = admin_site_url($this->_className.'/index');
			}
		}else{
			//$info['commend_flag'] = 0;
			//$info['comment_flag'] = 0;
			$info['author'] = $this->_adminProfile['basic']['username'];
		}
		
		
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback,
			'redirectUrl' => $redirectUrl,
			'articleClassList' => $treelist
		));
		
		
		$this->display();
	}

	
	public function edit(){
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$treelist = $this->cms_service->getArticleClassTreeHTML();
		
		$info = $this->Cms_Article_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			$this->form_validation->set_rules('article_click','点击数', 'required|is_natural');
			$this->form_validation->set_rules('comment_count','评论数', 'required|is_natural');
			$this->form_validation->set_rules('share_count','分享数', 'required|is_natural');
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData());
				$info['id'] = $id;
				
				$info['article_click'] = $this->input->post('article_click');
				$info['comment_count'] = $this->input->post('comment_count');
				$info['share_count'] = $this->input->post('share_count');
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				if($info['image_url']){
					$avatarImg = getImgPathArray($info['image_url'],array('b','m','s'),'image_url');
					$info = array_merge($info,$avatarImg);
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				$this->Cms_Article_Model->update($info,array('id' => $id));
				
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
			'articleClassList' => $treelist
		));
		
		$this->display($this->_className.'/add');
	}
	
	
	/**
	 * 删除图片
	 */
	public function delimg(){
		
		$imgUrl = $this->input->get_post('imgUrl');
		$id = $this->input->get_post('id');
		
		if($id){
			//如果在编辑页面
			$rowsDelete = $this->Cms_Article_Model->updateByCondition(array(
				'image_url' => ''
			),array(
				'where' => array(
					'id' => $id,
				)
			));
		}	
		
		
		if($rowsDelete){
			$fileList = getImgPathArray($imgUrl,array('b','m','s'));
			$this->attachment_service->deleteByFileUrl($fileList);
		}
		
		
		
		$this->jsonOutput('成功',$this->getFormHash());
	}
	
}
