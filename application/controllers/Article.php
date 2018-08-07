<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	/**
	 * 新闻详情
	 */
	public function detail()
	{
		
		$this->load->library(array('Cms_service'));
		
		$articleId = $this->input->get_post('id');
		$cmsArticleInfo = $this->Cms_Article_Model->getFirstByKey($articleId);
		
		$this->seo($cmsArticleInfo['article_title']);
		
		$this->assign(array(
			'article' => $cmsArticleInfo
		));

		$this->display();
		
	}
	
	

}
