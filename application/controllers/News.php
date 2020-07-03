<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Ydzj_Controller {
	
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
		$this->seoTitle('新闻中心');
		
		$search = $this->input->get_post('search');
		
		
		
		if($search!=""){
			$condition = array(
				'where' => array(
					'article_state'=>CmsArticleStatus::$published,
					//新闻分类
					'ac_id' =>14,
					
				),
				'order' => 'id DESC',
				'like'  => array(
					'article_title'=>$search
				)
			);
			$this->assign('search',$search);
			
			$info = $this->Cms_Article_Model->getList($condition);
			if(!$info){
				$this->assign('newCount','0');
				$condition = array(
					'order' => 'id DESC',
					'where' => array(
						'article_state'=>CmsArticleStatus::$published,
						'ac_id' =>14,
					)
				);
				$info = $this->Cms_Article_Model->getList($condition);
			
			}else{
				$this->assign('newCount',count($info,2));
			}
			
			
		}else{
			$condition = array(
				'order' => 'id DESC',
				'where' => array(
					'article_state'=>CmsArticleStatus::$published,
				)
			);
			$info = $this->Cms_Article_Model->getList($condition);
		}
		//新闻少于4条的时候页面下方查看更多不显示
		if(count($info,2)<3){
			$this->assign('isNotDisplay',"false");
		}
		for($i=0;$i<count($info,2);$i++){
			$info[$i]['time'] =date("Y-m-d H:i:s",$info[$i]['gmt_create']);
			if($i >3){
				$info[$i]['isNotDisplay'] = "true";
			}
		}
		$this->assign('info',$info);
		
		
		$this->display();
	}
	
	


	public function project(){
		$this->assign('url',$_SERVER['PHP_SELF']);
		$this->seoTitle('工程案例');
		$this->display();
	}
	
	public function detail(){
		$this->assign('url',$_SERVER['PHP_SELF']);
		
		$id = $this->input->get_post('id');
		
		
		
		$info = $this->Cms_Article_Model->getById(array(
			'where' => array(
				'id' => $id,
				'article_state'=>CmsArticleStatus::$published,
			)
		));
		if(!$info){
				$this->assign('isNotFind',"true");
		}else{
			$this->assign('isNotFind',"false");
			$this->assign('info',$info);
			$time = date("Y-m-d H:i:s",$info['gmt_create']);
			$this->assign('time',$time);
			
			
			$condition = array(
				'order' => 'id DESC',
				'limit' => 10,
				'where' => array(
					'article_state'=>CmsArticleStatus::$published,
				)
			);
			$news = $this->Cms_Article_Model->getList($condition);
			
			
			if($news){
				$this->assign('news',$news);
			}
		}

		$this->display();
	}
	
	
	
	
}
