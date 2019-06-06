<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Wx_Tdkc_Controller {
	
	public function __construct(){
		parent::__construct();
    	
    	$this->load->library('Cms_service');
    	
	}
	
	/**
	 * 获得首页信息
	 */
	public function getHomeInformation(){
		$artileClassAssoc = $this->cms_service->getAssocDataTree();
		$notifyArticleClassInfo = $this->Cms_Article_Class_Model->getList(array(
			'where' => array(
				'name' => '通知公告'
			)
		));
		if($notifyArticleClassInfo[0]['status'] ==1){
			$notify = $this->Cms_Article_Model->getList(array(
				'select' => 'id,image_url,article_title,publish_time,jump_url',
				'where' => array(
					'ac_id' => $artileClassAssoc['通知公告']['id'],
					'article_state' => CmsArticleStatus::$published,
				),
				'order' => 'publish_time DESC',
				'limit' => 5
			));	
		}
		$notify = $this->editInformation($notify);
		$newsArticleClassInfo = $this->Cms_Article_Class_Model->getList(array(
			'where' => array(
				'name' => '新闻资讯'
			)
		));
		if($newsArticleClassInfo[0]['status'] == 1){
			$news = $this->Cms_Article_Model->getList(array(
				'select' => 'id,image_url,article_title,publish_time,jump_url',
				'where' => array(
					'ac_id' => $artileClassAssoc['新闻资讯']['id'],
					'article_state' => CmsArticleStatus::$published
				),
				'order' => 'publish_time DESC',
				'limit' => 5
			));
		}
		$news = $this->editInformation($news);
		$servicesArticleClassInfo = $this->Cms_Article_Class_Model->getList(array(
			'where' => array(
				'name' => '生活服务'
			)
		));
		if($servicesArticleClassInfo[0]['status'] == 1){
		$services = $this->Cms_Article_Model->getList(array(
			'select' => 'id,image_url,article_title,publish_time,jump_url',
			'where' => array(
				'ac_id' => $artileClassAssoc['生活服务']['id'],
				'article_state' => CmsArticleStatus::$published
			),
			'order' => 'publish_time DESC',
			'limit' => 5
		));
		}		
		$services = $this->editInformation($services);
		
		$artileList = array(
			'announce' => $notify,'catagroy'=> array(
				//array('title' => '新闻资讯'),
				array('title' => '生活服务'),
			),
			'artileList' => array(
				//'新闻资讯' => $news,
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
				$information[$key]['publish_time'] = date('Y-m-d',$item['publish_time']);
				$information[$key]['jump_url'] = site_url('article/detail?id='.$item['id']);   

				
			}
		}
		return $information;
	}
	
	public function getCertificate(){
		$address = array('资质证书' => array(
			'url' =>array(
				'https://www.cxmap.net/static/img/zzry/yingyezhizhao.png', 
			    'https://www.cxmap.net/static/img/zzry/jigoudengji.png', 
			    'https://www.cxmap.net/static/img/zzry/cehuizizhi.png', 
			    'https://www.cxmap.net/static/img/zzry/jigouzhuce.png', 
			    'https://www.cxmap.net/static/img/zzry/linyezizhizhengshu.png', 
			    'https://www.cxmap.net/static/img/zzry/budongchandiaochadengjidaili.png' 
			) 
		),'荣誉证书' => array(
			'url' => array(
				'https://www.cxmap.net/static/img/zzry/hedaozhilijinjiang.png', 
			    'https://www.cxmap.net/static/img/zzry/tianyuwantongjiang.png', 
			    'https://www.cxmap.net/static/img/zzry/zuijuhuoliqiyezhengshu.png', 
			) 
		));
		$this->jsonOutput2(RESP_SUCCESS,array('imageurlList' => $address));
	}
	
	public function getIndexInformation(){
		$address = array('首页公告' => array(
			'notice' => '慈溪市土地勘测规划设计院有限公司是慈溪市国土资源局所属的原有企业（慈溪市土地勘测规划设计院）改制后的股份制民营企业。.....'
		),'首页照片' => array(
			'url' => array(
				'www.cxmap.net/static/img/xcxposter/haibao.png'
			)
		));
		$this->jsonOutput2(RESP_SUCCESS,array('infromationList' => $address));
	}

	
}
