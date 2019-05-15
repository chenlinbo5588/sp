<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shoes_Model extends MY_Model {
    
    public $_tableName = 'shoes';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
    }
    

}