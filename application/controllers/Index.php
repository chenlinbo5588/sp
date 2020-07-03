<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Cms_service'));
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
	
		
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->seoTitle('首页');
		$condition = array(
				'order' => 'id DESC',
				'limit' => 4,
				'where' => array(
					'article_state'=>CmsArticleStatus::$published,
				)
			);
		$info = $this->Cms_Article_Model->getList($condition);
		
		for($i=0;$i<count($info,2);$i++){
			$info[$i]['time'] =date("Y-m-d H:i",$info[$i]['gmt_create']);
		}
		$this->assign('info',$info);
		
		
		
		$this->display();
		
		
		
		
	}
	
	public function gsjstk()
	
	{
		
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->display();
	}
	

}
