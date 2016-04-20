<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_Model extends MY_Model {
    
    public $_tableName = 'role';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
    }
    
}