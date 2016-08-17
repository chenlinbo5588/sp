<?php


class Lab_Model extends MY_Model {
    
    public $_tableName = 'lab';
    public $_menuTree = array() ;
    public $_fullTree = array();
    public $_parentList = array();
    
    
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    public function saveTree($tree){
        $this->_fullTree = $tree;
    }
    
    public function clearMenuTree(){
        $this->_menuTree = array();
    }
    
    
    /**
     * 获得祖先列表
     * @param $selfid 
     */
    public function getParents($selfid = 0,$field = '*'){
        
        $condition['select'] = $field;
        $condition['where'] = array(
            'status' => '正常',
            'id' => $selfid
        );
        
        $result = $this->getById($condition);
        if($result){
            $this->_parentList[$result['id']] = $result;
            $this->getParents($result['pid'],$field);
        }
        
        return $this->_parentList;
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
		   		$xml .= '<item text="'.$t['address'].'" id="'.$t['id'].'" open="0"></item>';
		   }
		   else
		   {
			    $xml .= '<item text="'.$t['address'].'" id="'.$t['id'].'" open="0">';
			    $xml .= $this->toXML($t['children']);
			    $xml = $xml."</item>";
		   }
		}
		
		return $xml;
	}
    
    
    public function getListByTree($parentId = 0,$selfid = 0,$separate = '----',$level = 0) {
        
        $parentId = $parentId < 0 ? 0 : intval($parentId);
        //$selfId   = $selfId < 0 ? 0 : intval($selfId);
        $condition = array(
          'where' => array(
              'status' => '正常',
              'pid' => $parentId,
          ),
          'order' => 'pid ASC, displayorder DESC'
        );
        
        if(is_array($selfid)){
            $condition['where_not_in'] = array(
                array('key' => 'id' ,'value' => $selfid)
                );
        }else{
            $condition['where']['id !='] =  $selfid;
        }
        
        $childrenList = $this->getList($condition);
        if(is_array($childrenList)){
            foreach ($childrenList as $item){
                
                $sepA = array();
                
                //$repeatChar = str_repeat("&nbsp;", strlen($separate));
                
                for($i = 0; $i < $level; $i++){
                    $sepA[] = $separate;
                }
     
                $item['sep'] = implode('',$sepA);
               
                //$item['sep'] = str_replace($repeatChar,$separate,$item['sep'],$level );
                
                $item['level'] = $level;
                $this->_menuTree[$item['id']] = $item;
                $this->getListByTree($item['id'],$selfid, $separate,$level + 1);
            }
        }
		
		return $this->_menuTree;
	}
    
}