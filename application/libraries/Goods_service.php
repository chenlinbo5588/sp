<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Goods_Service extends Base_Service {
	
	private $_goodsClassModel = null;

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Goods_Class_Model');
		$this->_goodsClassModel = self::$CI->Goods_Class_Model;
		
	}
	
	
	public function getGoodsClassTreeHTML($condition = array()){
		$list = $this->_goodsClassModel->getList($condition);
		return self::$CI->phptree->makeTreeForHtml($list,array(
			'primary_key' => 'gc_id',
			'parent_key' => 'gc_parent_id',
			'expanded' => true
		));
	}
	
	
	public function getGoodsClassModel(){
		return $this->_goodsClassModel;
	}
	
	public function getGoodsClassByParentId($id = 0){
		$list = $this->_goodsClassModel->getList(array(
			'where' => array('gc_parent_id' => $id),
			'order' => 'gc_sort DESC'
		));
		
		return $this->toEasyUseArray($list,'gc_id');
	}
}
