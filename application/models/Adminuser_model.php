<?php

/**
 * 后台登陆账号
 */
 
class Adminuser_Model extends MY_Model {
    public $_tableName = 'adminuser';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
    }
    
}