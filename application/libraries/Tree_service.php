<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * 
 */
class Tree_service extends Base_service {
	
	private $_targetModel;
	private $_phpTree;
	
	
	private $_nameKey = 'name';
	private $_primaryKey = 'id';
	private $_parentKey = 'pid';
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library('PHPTree');
		$this->_phpTree = self::$CI->phptree;
	}
	
	
	public function saveTree($tree){
        $this->_fullTree = $tree;
    }
    
    public function clearMenuTree(){
        $this->_userTree = array();
    }
    
    public function setTargetModel($model,$primaryKey,$parentKey,$nameKey){
    	$this->_targetModel = $model;
    	
    	$this->_primaryKey = $primaryKey;
    	$this->_parentKey = $parentKey;
    	$this->_nameKey = $nameKey;
    }
    
    /**
     * 转成 tree
     */
    public function toTree($condition = array()){
    	$list = $this->_targetModel->getList($condition);
    	if($list){
    		return $this->_phpTree->makeTree($list,array(
				'primary_key' => $this->_primaryKey,
				'parent_key' => $this->_parentKey,
				'expanded' => true
			));
    	}else{
    		
    		return array();
    	}
    	
    }
    
    
    /**
     * 获得祖先列表
     * @param $selfid 
     */
    public function getParents($selfid = 0,$field = '*',$moreCondition = array()){
        
        $condition['select'] = $field;
        $condition['where'] = array(
            $this->_primaryKey => $selfid
        );
        
        $condition = array_merge($condition,$moreCondition);
        
        $result = $this->_targetModel->getById($condition);
        if($result){
            $this->_parentList[$result[$this->_primaryKey]] = $result;
            $this->getParents($result[$this->_parentKey],$field,$moreCondition);
        }
        
        return $this->_parentList;
    }
    
    
    /**
     * 转换格式
     */
    public function getTreeFormat($list,$format = 'xml'){
		if($format == 'xml'){
			return $this->toXML($this->_phpTree->makeTree($list,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => true
			)));
		}else{
			return $this->toHTML($this->_phpTree->makeTreeForHtml($list,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => true
			)));
		}
    }
    
    /**
     * 
     */
    public function toHTML($tree){
    	$xml = '';
		foreach($tree as $t)
		{
		   if(empty($t['children']))
		   {
		   		$xml .= '<li id="'.$t[$this->_primaryKey].'" open="0">'.$t[$this->_nameKey].'</li>';
		   }
		   else
		   {
			    $xml .= '<ul id="'.$t[$this->_primaryKey].'" open="0"><span>'.$t[$this->_nameKey].'</span>';
			    $xml .= $this->toXML($t['children']);
			    $xml = $xml."</ul>";
		   }
		}
		
		return $xml;
    	
    }
    
    
    /**
     * 返回XML
     */
    public function toXML($tree)
	{
		$xml = '';
		foreach($tree as $t)
		{
		   if(empty($t['children']))
		   {
		   		$xml .= '<item text="'.$t[$this->_nameKey].'" id="'.$t[$this->_primaryKey].'" open="0"></item>';
		   }
		   else
		   {
			    $xml .= '<item text="'.$t[$this->_nameKey].'" id="'.$t[$this->_primaryKey].'" open="0">';
			    $xml .= $this->toXML($t['children']);
			    $xml = $xml."</item>";
		   }
		}
		
		return $xml;
	}
    
  
    
    /**
     * 递归获取
     */
    public function getListByTree($parentId = 0,$selfid = 0,$moreCondition = array(), $separate = '----',$level = 0) {
        
        $parentId = $parentId < 0 ? 0 : intval($parentId);
        //$selfId   = $selfId < 0 ? 0 : intval($selfId);
        $condition = array(
          'where' => array(
              $this->_parentKey => $parentId,
          )
        );
        
        $condition = array_merge($condition,$moreCondition);
        
        if(is_array($selfid)){
            $condition['where_not_in'] = array(
                array('key' => $this->_primaryKey ,'value' => $selfid)
            );
            
        }else{
            $condition['where'][$this->_primaryKey. ' !='] =  $selfid;
        }
        
        $childrenList = $this->_targetModel->getList($condition);
        if(is_array($childrenList)){
            foreach ($childrenList as $item){
                $item['sep'] = str_repeat($separate,$level);
                $item['level'] = $level;
                $this->_userTree[$item[$this->_primaryKey]] = $item;
                $this->getListByTree($item[$this->_primaryKey],$selfid, $moreCondition, $separate,$level + 1);
            }
        }
		
		return $this->_userTree;
	}
}
