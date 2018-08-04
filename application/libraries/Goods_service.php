<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GoodsVerift
{
	//未通过
	public static $draft = 1;
	
	//通过
	public static $unverify = 2;		
	public static $statusName = array(
		1 => '未审核',
		2 => '审核通过'
	);
}

class GoodsStatus
{
	//下架
	public static $draft = 1;
	
	//正常
	public static $unverify = 2;		
	public static $statusName = array(
		1 => '下架',
		2 => '正常'
	);
}
class GoodsReCommend
{
	//未推荐
	public static $draft = 1;
	
	//推荐
	public static $unverify = 2;		
	public static $statusName = array(
		1 => '未推荐',
		2 => '推荐'
	);
}


class Goods_service extends Base_service {
	
	private $_goodsModel = null;
	private $_goodsClassModel = null;
	private $_goodsClassTagModel = null;
	private $_goodsImagesModel = null;
	
	private $_brandModel = null;
	private $_parentList = array();

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Goods_Model', 'Goods_Class_Model','Goods_Class_Tag_Model','Brand_Model','Goods_Images_Model'));
		
		$this->_goodsModel = self::$CI->Goods_Model;
		$this->_goodsClassModel = self::$CI->Goods_Class_Model;
		$this->_goodsClassTagModel = self::$CI->Goods_Class_Tag_Model;
		$this->_brandModel = self::$CI->Brand_Model;
		$this->_goodsImagesModel = self::$CI->Goods_Images_Model;
	}
	
	
	
	public function getGoodsClassTreeHTML($condition = array()){
		$list = $this->_goodsClassModel->getList($condition);
		
		if($list){
			self::$CI->phptree->resetData();
			return self::$CI->phptree->makeTreeForHtml($list,array(
				'primary_key' => 'gc_id',
				'parent_key' => 'gc_parent_id',
				'expanded' => true
			));
		}else{
			return array();
		}
		
	}
	

	/**
	 * 更改状态
	 * @param array $pIds
	 */
	public function changeGoodsStatus($pIds,$pNewValue,$pOldValue,$pFieldName,$extraData = array()){
		return $this->_goodsModel->updateByCondition(array_merge(array(
			$pFieldName => $pNewValue
		),$extraData),array(
			'where' => array(
				$pFieldName => $pOldValue
			),
			'where_in' => array(
				array('key' => 'goods_id', 'value' => $pIds)
			)
		));
	}
	

	public function getGoodClassTree($condition = array()){
		$list = $this->_goodsClassModel->getList($condition);
		
		if($list){
			self::$CI->phptree->resetData();
			return self::$CI->phptree->makeTree($list,array(
				'primary_key' => 'gc_id',
				'parent_key' => 'gc_parent_id',
				'expanded' => true
			));
		}else{
			
			return array();
		}
	}
	
	public function tagAdd($param,$nameKey = 'name_cn'){
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
				$class_name_1	= trim($value[$nameKey]);
				$class_id		= $value['gc_id'];
				$type_id		= $value['type_id'];
				$class_id_2		= '';
				$class_id_3		= '';
				$class_name_2	= '';
				$class_name_3	= '';
				
				if(is_array($value['children']) && !empty($value['children'])){	//二级
					foreach ($value['children'] as $val){
						$class_id_2		= $val['gc_id'];
						$class_name_2	= trim($val[$nameKey]);
						$class_id		= $val['gc_id'];
						$type_id		= $val['type_id'];
						
						if(is_array($val['children']) && !empty($val['children'])){	//三级
							foreach ($val['children'] as $v){
								$class_id_3		= $v['gc_id'];
								$class_name_3	= trim($v[$nameKey]);
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
		
		return $this->_goodsClassTagModel->execSQL("insert into `".$this->_goodsClassTagModel->getTableRealName()."` (`gc_id_1`,`gc_id_2`,`gc_id_3`,`gc_tag_name`,`gc_tag_value`,`gc_id`,`type_id`) values ".$condition_str);
	
	}
	
	
	public function deleteGoodsClassTag($ids){
		return $this->_goodsClassTagModel->deleteByCondition(array(
			'where_in' => array(
				array('key' => 'gc_tag_id', 'value' => $ids)
			)
		));
		
	}
	
}
