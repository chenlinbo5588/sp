<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_Class_Model extends MY_Model {
    
    public $_tableName = 'goods_class';
    private $_parentList = array();
    
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    public function getGoodsClassById($id){
    	return $this->getFirstByKey($id,'gc_id');
    }
    
    
    public function updateGoodsClassSeoById($goodClassId,$data){
    	return $this->update($data,array('gc_id' => $goodClassId));
    }
    
    
    /**
     * 
     */
    public function getParentsById($id = 0,$field = '*'){

        $condition['select'] = $field;
        $condition['where'] = array(
            'gc_id' => $id
        );
        
        $result = $this->getById($condition);
        if($result){
            $this->_parentList[] = $result;
            $this->getParentsById($result['gc_parent_id']);
        }
        
        return $this->_parentList;
    }
    
	
    
    /**
	 * 获得所所有的子孙
	 */
	public function getAllChildGoodsClassByPid($id,$field = 'gc_id',$maxDeep = 3){
		
		$list = $this->getList(array(
			'select' => $field,
			'where' => array('gc_parent_id' => $id)
		));
		
		$deep = 0;
		
		$allIds = array();
		
		while($list){
			$ids = array();
			foreach($list as $item){
				$ids[] = $item['gc_id'];
				$allIds[] = $item['gc_id'];
			}
			
			if($ids){
				$list = $this->getList(array(
					'select' => $field,
					'where_in' => array(
						array('key' => 'gc_parent_id', 'value' => $ids)
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
	
	
	/**
	 * 获得深度
	 */
	public function getGoodsClassDeepById($id,$maxDeep = 5){
		$info = $this->getFirstByKey($id,'gc_id');
		
		//默认一级
		$deep = 1;
		
		while($info['gc_parent_id']){
			$deep++;
			$info = $this->getFirstByKey($info['gc_parent_id'],'gc_id');
			
			//防止无限循环,最多5级
			if($deep >= $maxDeep){
				break;
			}
		}
		
		return $deep;
		
	}
	
	
	/**
	 * 删除分类
	 */
	public function deleteGoodsClass($delId,$realDelete = false){
		
		if($realDelete){
			$list = $this->getList(array(
				'where' => array('gc_parent_id' => $delId)
			));
			
			$this->deleteByWhere(array('gc_id' => $delId));
			
		}else{
			
			$this->updateByWhere(array('status' => 0),array('gc_id' => $delId));
			
			//再去找子项目
			$list = $this->getList(array(
				'where' => array('gc_parent_id' => $delId,'status' => 1)
			));
		}
		
		foreach($list as $subItem){
			$this->deleteGoodsClass($subItem['gc_id'],$realDelete);
		}
		
	}

}