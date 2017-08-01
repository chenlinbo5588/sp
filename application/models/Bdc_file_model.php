<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bdc_File_Model extends MY_Model {
    
    public $_tableName = 'bdc_file';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
}