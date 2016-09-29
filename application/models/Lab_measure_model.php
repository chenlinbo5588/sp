<?php


class Measure_Model extends MY_Model {
    
    public $_tableName = 'measure';
    
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
}