<?php


class Sports_Category_Model extends MY_Model {
    
    public $_tableName = 'sports_category';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
    }
    
    
    public function avaiableCategory($category){
    	
    	$info = $this->getById(array(
    		'where' => array(
    			'id' => $category
    		)
    	));
    	
    	if($info){
    		return true;
    	}else{
    		
    		return false;
    	}
    }
    
}