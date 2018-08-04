<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basic_Data_Model extends MY_Model {
    
    public $_tableName = 'basic_data';
    private $_parentList = array();
    
    
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    
	/**
	 * 获得 父级根据ID
	 */
	public function getParentsById($id = 0,$field = '*'){

        $condition['select'] = $field;
        $condition['where'] = array(
            'id' => $id
        );
        
        $result = $this->getById($condition);
        if($result){
            $this->_parentList[] = $result;
            $this->getParentsById($result['pid']);
        }
        
        return $this->_parentList;
    }
    
    
    
    /**
	 * 获得所所有的子孙
	 */
	public function getAllChildByPid($id,$field = 'id',$maxDeep = 4){
		$list = $this->getList(array(
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
				$list = $this->getList(array(
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
	
	
	/**
	 * 
	 */
	public function getDeepById($id,$maxDeep = 5){
		$info = $this->getFirstByKey($id,'id');
		
		//默认一级
		$deep = 1;
		
		while($info['pid']){
			$deep++;
			$info = $this->getFirstByKey($info['pid'],'id');
			
			//防止无限循环,最多5级
			if($deep >= $maxDeep){
				break;
			}
		}
		
		return $deep;
		
	}
	
	
	/**
	 * 删除基础数据,根据ID
	 * 
	 * @param int $delId
	 * @param bool $realDelete
	 * 
	 * return bool
	 */
	public function deleteById($delId,$realDelete = false){
		
		if($realDelete){
			$list = $this->getList(array(
				'where' => array('pid' => $delId)
			));
			
			$this->deleteByWhere(array('id' => $delId));
			
		}else{
			
			$this->updateByWhere(array('status' => 0),array('id' => $delId));
			
			//再去找子项目
			$list = $this->getList(array(
				'where' => array('pid' => $delId,'status' => 1)
			));
		}
		
		foreach($list as $subItem){
			$this->deleteById($subItem['id'],$realDelete);
		}
		
	}
	
}