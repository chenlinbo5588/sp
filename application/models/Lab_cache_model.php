<?php


class Lab_Cache_Model extends MY_Model {
    
    public $_tableName = 'lab_cache';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    public function addByKey($key , $content = '' , $group = '', $expire = 0){
    	if(empty($key)){
    		return false;
    	}
    	
        $data = array(
            'key_id' => $key,
            'key_group' => $group,
			'content' => $content,
            'expire' => $expire,
        );
        
        return $this->_add($data,true);
    }
    
    public function updateByGroupKey($val, $data){
    	return $this->update($data,array('key_group' => $val));
    }
}