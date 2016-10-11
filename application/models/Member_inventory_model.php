<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户库存 
 */
class Memebr_Inventory_Model extends MY_Model {
    
    public $_tableName = 'member_inventory';
    
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
}