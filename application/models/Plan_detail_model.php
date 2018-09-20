<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan_Detail_Model extends MY_Model {
    
    public $_tableName = 'plan_detail';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        $this->setTableId(date('Y',time()));
        self::$_tableMeta = $this->getTableMeta(); 
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
}