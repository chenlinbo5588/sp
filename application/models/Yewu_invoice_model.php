<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yewu_Invoice_Model extends MY_Model {
    
    public $_tableName = 'yewu_invoice';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
	
	
    
}