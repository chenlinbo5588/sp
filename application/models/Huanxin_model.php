<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Huanxin_Model extends MY_Model {
    public $_tableName = 'huanxin';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
}