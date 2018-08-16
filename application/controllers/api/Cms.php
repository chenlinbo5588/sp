<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
    	
    	$this->load->library('Cms_service');
    	
	}
	
	/**
	 * 获得首页信息
	 */
	public function getHomeInformation(){
		$artileClassAssoc = $this->cms_service->getAssocDataTree();
		
		$notify = $this->Cms_Article_Model->getList(array(
			'select' => 'id,image_url,article_title,publish_time,jump_url',
			'where' => array(
				'ac_id' => $artileClassAssoc['通知讯息']['id'],
				'article_state' => CmsArticleStatus::$published
			),
			'order' => 'publish_time DESC',
			'limit' => 5
		));
		
		$notify = $this->editInformation($notify);
		
		$news = $this->Cms_Article_Model->getList(array(
			'select' => 'id,image_url,article_title,publish_time,jump_url',
			'where' => array(
				'ac_id' => $artileClassAssoc['新闻资讯']['id'],
				'article_state' => CmsArticleStatus::$published
			),
			'order' => 'publish_time DESC',
			'limit' => 5
		));
		
		$news = $this->editInformation($news);
		
		$services = $this->Cms_Article_Model->getList(array(
			'select' => 'id,image_url,article_title,publish_time,jump_url',
			'where' => array(
				'ac_id' => $artileClassAssoc['生活服务']['id'],
				'article_state' => CmsArticleStatus::$published
			),
			'order' => 'publish_time DESC',
			'limit' => 5
		));
		
		$services = $this->editInformation($services);
		
		$artileList = array(
			'announce' => $notify,'catagroy'=> array(
				array('title' => '新闻资讯'),
				array('title' => '生活服务'),
			),
			'artileList' => array(
				'新闻资讯' => $news,
				'生活服务' => $services,
			)			
		);
		$this->jsonOutput2(RESP_SUCCESS,$artileList);
	}
	
	/**
	 * 处理数据
	 */
	private function editInformation( $information= array()){
		if($information)
		{
			foreach($information as $key => $item){
				if($information[$key]['image_url']){
					$information[$key]['image_url'] = resource_url($item['image_url']);
				}
				else{
					$information[$key]['image_url'] = '';
				}
				$information[$key]['publish_time'] = date('Y-m-d H:i',$item['publish_time']);
				$information[$key]['jump_url'] = site_url('article/detail?id='.$item['id']);   

				
			}
		}
		return $information;
	}
	
	
}
