<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_Log_Model extends MY_Model {
    
    public $_tableName = 'audit_log';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    

}