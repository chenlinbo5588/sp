<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
    	$cnt = $this->getCount(array(
    		'where' => array(
    			'id' => $category
    		)
    	));
    	
    	if($cnt > 0){
    		return true;
    	}else{
    		
    		return false;
    	}
    }
    
}