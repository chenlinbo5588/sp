<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends Ydzj_Controller {
	
	private $sideNavs = null;
	
	
	public function __construct(){
		parent::__construct();
		//print_r($this->uri);
		
		
		
	}
	
	private function _getArticleContent($key){
		$article = $this->Article_Model->getFirstByKey($key,'article_id');
		
		$this->_navigation[$key] = $this->sideNavs[$article['article_title']];
		$this->assign('article',$article);
		$this->assign('breadcrumb',$this->breadcrumb());
		
	}
	
	
	
	
	
	
	public function introduce()
	{
		$key = '企业简介';
		$this->_getArticleContent($key);
		$this->seo($key);
		
		$this->display('common/art');
	}
	
	
	public function philosophy()
	{
		$key = '公司理念';
		$this->_getArticleContent($key);
		$this->seo($key);
		
		$this->display('common/art');
	}
	
	public function presence()
	{
		$key = '企业风采';
		$this->_getArticleContent($key);
		$this->seo($key);
		
		$this->display('common/art');
	}
}
