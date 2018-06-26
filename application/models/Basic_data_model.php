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
	 */
	public function deleteById($delId){
		
		$list = $this->getList(array(
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
				
				$this->deleteByCondition(array(
					'where_in' => array(
						array('key' => 'id', 'value' => $ids)
					)
				));
				
				$list = $this->getList(array(
					'where_in' => array(
						array('key' => 'id', 'value' => $ids)
					)
				));
			}
		}
		
		return $this->deleteByWhere(array('id' => $delId));
		
	}
	
	/**
	 * 根据父级ID 获取全部
	 */
	public function getByParentId($id = 0){
		return $this->getList(array(
			'where' => array('pid' => $id),
			'order' => 'displayorder ASC'
		),'id');
		
	}
    
    
}