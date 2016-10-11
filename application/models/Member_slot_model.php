<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户货柜 表
 */
class Member_Slot_Model extends MY_Model {
    
    public $_tableName = 'member_slot';
    
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
}