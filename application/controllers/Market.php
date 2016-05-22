<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Market extends Ydzj_Controller {
	
	private $sideNavs = null;
	
	public function __construct(){
		parent::__construct();
		
		$topName = '营销招商';
		
		$this->assign('pgClass',strtolower(get_class()).'Pg');
		
		
		$this->assign('sideTitle',$topName);
		
		$this->load->model('Article_Model');
		
		$this->sideNavs = array(
			'经销商网络' => site_url('market/agency'),
			'合作加盟' => site_url('market/cooperation'),
		
		);
		
		$this->assign('sideNavs',$this->sideNavs);
		
		$this->_navigation = array(
			'首页' => site_url('/'),
			$topName => site_url('market')
		);
	}
	
	private function _getArticleContent($key){
		
		$this->_navigation[$key] = $this->sideNavs[$key];
		
		$article = $this->Article_Model->getFirstByKey($key,'article_title');
		$this->assign('article',$article);
		
		$this->assign('breadcrumb',$this->breadcrumb());
		
	}
	
	
	public function agency()
	{
		$key = '经销商网络';
		$this->_getArticleContent($key);
		
		$this->display('common/art');
	}
	
	
	public function cooperation()
	{
		$key = '合作加盟';
		$this->_getArticleContent($key);
		$this->display('common/art');
	}
	
}
