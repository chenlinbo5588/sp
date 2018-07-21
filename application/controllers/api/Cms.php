<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
    	
    	$this->load->library('Cms_service');
    	
	}
	
	
	/**
	 * 获得最新公告
	 */
	public function getTopNotify(){
		
		$artileClassAssoc = $this->cms_service->getAssocDataTree();
		$notify = $this->Cms_Article_Model->getList(array(
			'select' => 'id,image_url,article_title',
			'where' => array(
				'ac_id' => $artileClassAssoc['通知公告']['id'],
				'article_state' => CmsArticleStatus::$published
			),
			'order' => 'publish_time DESC',
			'limit' => 1
		));
		
		if($notify[0]){
			if($notify[0]['image_url']){
				$notify[0]['image_url'] = resource_url($notify[0]['image_url']);
			}
			
			$this->jsonOutput2(RESP_SUCCESS,$notify[0]);
		}else{
			$this->jsonOutput2(RESP_SUCCESS);
		}
		
	}
	
	
}
