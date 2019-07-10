<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->_className = strtolower(get_class());
		
	}
	
	/**
	 * 新闻详情
	 */
	public function detail()
	{
		
		$this->load->library(array('Cms_service'));
		
		$articleId = $this->input->get_post('id');
		$cmsArticleInfo = $this->Cms_Article_Model->getFirstByKey($articleId);
		
		
		$key = 'Article'.$articleId.'Clicked';
		
		$isClicked = $this->session->userdata('Article'.$articleId.'Clicked');
		
		if(!$isClicked){
			$this->Cms_Article_Model->updateByWhere(array(
				'article_click' =>  $cmsArticleInfo['article_click'] + 1,
			),array(
				'id' => $cmsArticleInfo['id']
			));
			
			$this->session->set_userdata('Article'.$articleId.'Clicked',true);
		}
		
		$this->seo($cmsArticleInfo['article_title']);
		
		$this->output->set_cache_header($cmsArticleInfo['gmt_modify'],3600);
		
		$this->assign(array(
			'article' => $cmsArticleInfo
		));
		
		if($cmsArticleInfo['ac_id'] == 24){
			$this->display($this->_className.'/engineering_case');
		}else{
			$this->display();
		}
		
	}
	
	

}
