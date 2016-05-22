<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
		
		$this->load->library('Goods_service');
		
		$topClassId = 0;
		
		$topClassInfo = $this->Goods_Class_Model->getList(array(
			'where' => array(
				'gc_name' => '产品中心',
				'gc_parent_id' => 0
			)
		));
		
		
		$goodsClassIds = $this->goods_service->getAllChildGoodsClassByPid($topClassInfo[0]['gc_id']);
		$goodsClassIds[] = $topClassInfo[0]['gc_id'];
		
		//print_r($goodsClassIds);
		$goodsList = $this->Goods_Model->getList(array(
			/*'where' => array(
				'goods_commend' => 1,
				'goods_verify' => 1,
				'goods_state' => 1
			),*/
			'where_in' => array(
				array('key' => 'gc_id' , 'value' => $goodsClassIds )
			),
			'order' => 'goods_sort ASC',
			'limit' => 20
		));
		
		$this->assign('goodsList',$goodsList);
		$this->display();
	}
	

}
