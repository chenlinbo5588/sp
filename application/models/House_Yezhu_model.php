<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class House_Yezhu_Model extends MY_Model {
    
    public $_tableName = 'house_yezhu';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta(); 
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
}