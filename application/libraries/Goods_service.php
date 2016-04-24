<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Goods_Service extends Base_Service {
	
	private $_goodsClassModel = null;
	private $_goodsClassTagModel = null;

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Goods_Class_Model','Goods_Class_Tag_Model'));
		$this->_goodsClassModel = self::$CI->Goods_Class_Model;
		$this->_goodsClassTagModel = self::$CI->Goods_Class_Tag_Model;
		
	}
	
	
	public function getGoodsClassTreeHTML(){
		$list = $this->_goodsClassModel->getList();
		
		if($list){
			return self::$CI->phptree->makeTreeForHtml($list,array(
				'primary_key' => 'gc_id',
				'parent_key' => 'gc_parent_id',
				'expanded' => true
			));
		}else{
			return array();
		}
		
	}
	
	
	public function getGoodsClassDeepById($id){
		$info = $this->_goodsClassModel->getFirstByKey($id,'gc_id');
		
		//默认一级
		$deep = 1;
		
		while($info['gc_parent_id']){
			$deep++;
			$info = $this->_goodsClassModel->getFirstByKey($info['gc_parent_id'],'gc_id');
			
			//防止无限循环
			if($deep >= 5){
				break;
			}
		}
		
		return $deep;
		
	}
	
	
	public function deleteGoodsClass($delId){
		
		$list = $this->Goods_Class_Model->getList(array(
			'where' => array('gc_parent_id' => $delId)
		));
		
		$hasData = true;
		
		while($list && $hasData){
			$ids = array();
			foreach($list as $item){
				$ids[] = $item['gc_id'];
			}
			
			if(empty($ids)){
				$hasData = false;
			}else{
				
				$this->Goods_Class_Model->deleteByCondition(array(
					'where_in' => array(
						array('key' => 'gc_id', 'value' => $ids)
					)
				));
				
				$list = $this->Goods_Class_Model->getList(array(
					'where_in' => array(
						array('key' => 'gc_parent_id', 'value' => $ids)
					)
				));
			}
		}
		
		$this->Goods_Class_Model->delete(array('gc_id' => $delId));
		
	}
	
	
	
	public function getGoodClassTree(){
		$list = $this->_goodsClassModel->getList();
		
		if($list){
			return self::$CI->phptree->makeTree($list,array(
				'primary_key' => 'gc_id',
				'parent_key' => 'gc_parent_id',
				'expanded' => true
			));
		}else{
			
			return array();
		}
	}
	
	public function tagAdd($param){
		$class_id_1		= '';
		$class_id_2		= '';
		$class_id_3		= '';
		$class_name_1	= '';
		$class_name_2	= '';
		$class_name_3	= '';
		$class_id		= '';
		$type_id		= '';
		$condition_str	= '';
		
		if(is_array($param) && !empty($param)){	//一级
			foreach ($param as $value){
				$class_id_1		= $value['gc_id'];
				$class_name_1	= trim($value['gc_name']);
				$class_id		= $value['gc_id'];
				$type_id		= $value['type_id'];
				$class_id_2		= '';
				$class_id_3		= '';
				$class_name_2	= '';
				$class_name_3	= '';
				
				if(is_array($value['children']) && !empty($value['children'])){	//二级
					foreach ($value['children'] as $val){
						$class_id_2		= $val['gc_id'];
						$class_name_2	= trim($val['gc_name']);
						$class_id		= $val['gc_id'];
						$type_id		= $val['type_id'];
						
						if(is_array($val['children']) && !empty($val['children'])){	//三级
							foreach ($val['children'] as $v){
								$class_id_3		= $v['gc_id'];
								$class_name_3	= trim($v['gc_name']);
								$class_id		= $v['gc_id'];
								$type_id		= $v['type_id'];
								
								//合并成sql语句
								$condition_str .= '("'.$class_id_1.'", "'.$class_id_2.'", "'.$class_id_3.'", "'.$class_name_1.'&nbsp;&gt;&nbsp;'.$class_name_2.'&nbsp;&gt;&nbsp;'.$class_name_3.'", "'.$class_name_1.','.$class_name_2.','.$class_name_3.'", "'.$class_id.'", "'.$type_id.'"),';
							}
						}else{
							//合并成sql语句
							$condition_str .= '("'.$class_id_1.'", "'.$class_id_2.'", "", "'.$class_name_1.'&nbsp;&gt;&nbsp;'.$class_name_2.'", "'.$class_name_1.','.$class_name_2.'", "'.$class_id.'", "'.$type_id.'"),';
						}
						
					}
				}else{
					//合并成sql语句
					$condition_str .= '("'.$class_id_1.'", "", "", "'.$class_name_1.'", "'.$class_name_1.'", "'.$class_id.'", "'.$type_id.'"),';
				}
				
			}
		}else{
			return false;
		}
		
		$condition_str = trim($condition_str,',');
		
		return $this->_goodsClassTagModel->execSQL("insert into `{$this->_goodsClassTagModel->_tableRealName}` (`gc_id_1`,`gc_id_2`,`gc_id_3`,`gc_tag_name`,`gc_tag_value`,`gc_id`,`type_id`) values ".$condition_str);
	
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
	
	
	
	public function deleteGoodsClassTag($ids){
		return $this->_goodsClassTagModel->deleteByCondition(array(
			'where_in' => array(
				array('key' => 'gc_tag_id', 'value' => $ids)
			)
		));
		
	}
}
