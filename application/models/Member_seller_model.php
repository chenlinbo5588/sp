<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 卖家信息表
 */
class Member_Seller_Model extends MY_Model {
    
    public $_tableName = 'member_seller';
    
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
}