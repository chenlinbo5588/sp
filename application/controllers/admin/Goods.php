<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
		$this->load->library(array('Goods_Service'));
		
	}
	
	
	public function category(){
		
		$id = $this->input->get_post('gc_parent_id') ? $this->input->get_post('gc_parent_id') : 0;
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		$deep = 0;
		
		//print_r($list);
		$parentId = 0;
		foreach($treelist as $item){
			if($id == $item['gc_id']){
				$deep = $item['level'];
				$parentId = $item['gc_parent_id'];
			}
		}
		
		$list = $this->goods_service->getGoodsClassByParentId($id);
		$this->assign('list',$list);
		$this->assign('parentId',$parentId);
		$this->assign('deep',$deep + 1);
		$this->assign('id',$id);
		
		$this->display();
	}
	
	public function category_add(){
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		
		
		
		$this->assign('list',$treelist);
		$this->display();
	}
	
	
	public function category_edit(){
		
		
		$this->display('goods/category_add');
		
	}
	
}
