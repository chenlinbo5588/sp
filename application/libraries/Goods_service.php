<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Goods_service extends Base_service {
	
	private $_goodsModel = null;
	private $_goodsCategoryModel = null;
	
	private $_brandModel = null;
	private $_parentList = array();

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Goods_Model', 'Goods_Category_Model'));
		
		$this->_goodsModel = self::$CI->Goods_Model;
		$this->_goodsCategoryModel = self::$CI->Goods_Category_Model;
	}
	
	public function getParentsById($id = 0,$field = '*'){

        $condition['select'] = $field;
        $condition['where'] = array(
            'id' => $id
        );
        
        $result = $this->_goodsCategoryModel->getById($condition);
        if($result){
            $this->_parentList[] = $result;
            $this->getParentsById($result['pid']);
        }
        
        return $this->_parentList;
    }
	
	
	public function getGoodsCategoryTreeHTML(){
		$list = $this->_goodsCategoryModel->getList();
		
		if($list){
			return self::$CI->phptree->makeTreeForHtml($list,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => true
			));
		}else{
			return array();
		}
		
	}
	
	
	/**
	 * 获得所所有的子孙
	 */
	public function getAllChildGoodsCategoryByPid($id,$field = 'id',$maxDeep = 5){
		
		$list = $this->_goodsCategoryModel->getList(array(
			'select' => $field,
			'where' => array('pid' => $id)
		));
		
		$deep = 0;
		
		$allIds = array();
		
		while($list){
			$ids = array();
			foreach($list as $item){
				$ids[] = $item['id'];
				$allIds[] = $item['id'];
			}
			
			if($ids){
				$list = $this->_goodsCategoryModel->getList(array(
					'select' => $field,
					'where_in' => array(
						array('key' => 'pid', 'value' => $ids)
					)
				));
			}else{
				$canQuit = true;
			}
			
			$deep++;
			
			if($deep >= $maxDeep){
				break;
			}
		}
		
		return $allIds;
	}
	
	
	
	public function getGoodsClassDeepById($id,$maxDeep = 5){
		$info = $this->_goodsClassModel->getFirstByKey($id,'id');
		
		//默认一级
		$deep = 1;
		
		while($info['pid']){
			$deep++;
			$info = $this->_goodsClassModel->getFirstByKey($info['pid'],'id');
			
			//防止无限循环,最多5级
			if($deep >= $maxDeep){
				break;
			}
		}
		
		return $deep;
		
	}
	
	
	public function deleteGoodsCategory($delId){
		
		$list = $this->_goodsCategoryModel->getList(array(
			'where' => array('pid' => $delId)
		));
		
		$hasData = true;
		
		while($list && $hasData){
			$ids = array();
			foreach($list as $item){
				$ids[] = $item['id'];
			}
			
			if(empty($ids)){
				$hasData = false;
			}else{
				
				$this->_goodsCategoryModel->deleteByCondition(array(
					'where_in' => array(
						array('key' => 'id', 'value' => $ids)
					)
				));
				
				$list = $this->_goodsCategoryModel->getList(array(
					'where_in' => array(
						array('key' => 'pid', 'value' => $ids)
					)
				));
			}
		}
		
		$this->_goodsCategoryModel->deleteByWhere(array('id' => $delId));
		
	}
	
	
	
	public function getGoodCategoryTree(){
		$list = $this->_goodsCategoryModel->getList();
		
		if($list){
			return self::$CI->phptree->makeTree($list,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => true
			));
		}else{
			
			return array();
		}
	}
	
	
	
	public function getGoodsCategoryByParentId($id = 0){
		$list = $this->_goodsCategoryModel->getList(array(
			'where' => array('pid' => $id),
			'order' => 'id ASC'
		));
		
		return $this->toEasyUseArray($list,'id');
	}
	
	
	
	public function goodsCategoryXML(){
    	
    	$str = array();
    	
    	$str[] = '<?xml version="1.0" ?><tree id="0">';
   		$str[] = '<item text="实验室药品仪器分类" id="root" open="1">';
   		$categoryList = $this->_goodsCategoryModel->getList(array(
   			'where' => array(
   				'status' => '正常'
   			),
    		'order' => 'pid ASC , id ASC'
    	));
    	
    	
    	$tree = self::$CI->phptree->makeTree($categoryList,array(
			'primary_key' => 'id',
			'parent_key' => 'pid',
			'expanded' => true
		));
		
    	
    	if($tree){
    		$str[] = $this->_goodsCategoryModel->toXML($tree);
    	}
    	
   		$str[] = '</item></tree>';
   		
   		return implode('',$str);
    }
    
    
	
}
