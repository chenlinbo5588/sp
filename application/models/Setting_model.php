<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_Model extends MY_Model {
    
    public $_tableName = 'setting';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
}