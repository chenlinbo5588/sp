<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seo_service extends Base_service {
	
	private $_seoModel = null;
	private $_seoTypeList = null;

	public function __construct(){
		parent::__construct();
		
		$this->_seoTypeList = array(
			'index',
			'group','group_content',
			'brand','brand_list',
			'coupon_content',
			'credits','credits_content',
			'article','article_content',
			'shop',
			'product'
		);
		
		self::$CI->load->model('Seo_Model');
		
		$this->_seoModel = self::$CI->Seo_Model;
	}
	
	
	public function getCurrentSeoSetting(){
		$list = $this->_seoModel->getList();
		
		return $this->toEasyUseArray($list, 'type');
	}
	
	
	public function updateSeo($data){
		
		$updateDt = array();
		foreach($data as $key => $value){
			if(!in_array($key,$this->_seoTypeList)){
				continue;
			}
			
			$updateDt[] = array(
				'type' => $key,
				'title' => $value['title'],
				'keywords' => $value['keywords'],
				'description' => $value['description'],
				'gmt_modify' => self::$CI->input->server('REQUEST_TIME')
			);
		}
		
		
		if($updateDt){
			return $this->_seoModel->batchUpdate($updateDt, 'type');
		}else{
			return 0;
		}
		
	}
	
}
