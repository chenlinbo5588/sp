<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 近期 货品表
 */
class Hp_Recent_Model extends MY_Model {
    
    public $_tableName = 'hp_recent';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
}