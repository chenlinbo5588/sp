<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Goods_Service extends Base_Service {
	
	private $_goodsClassModel = null;

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Goods_Class_Model');
		$this->_goodsClassModel = self::$CI->Goods_Class_Model;
		
	}
	
	
	public function getGoodsClassTreeHTML(){
		$list = $this->_goodsClassModel->getList();
		return self::$CI->phptree->makeTreeForHtml($list,array(
			'primary_key' => 'gc_id',
			'parent_key' => 'gc_parent_id',
			'expanded' => true
		));
	}
	
	
	public function getGoodsClassModel(){
		return $this->_goodsClassModel;
	}
	
}
