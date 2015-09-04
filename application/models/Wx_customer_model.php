<?php

/**
 * 微信公众 关注者
 */
 
class Wx_Customer_Model extends MY_Model {
    public $_tableName = 'wx_customer';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
    }
    
}