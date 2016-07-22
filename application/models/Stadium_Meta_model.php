<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium_Meta_Model extends MY_Model {
    
    public $_tableName = 'stadium_meta';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    public function getMetaDataByGroup($group = ''){
        
        return $this->getList(array(
            'where' => array(
                'gname' => $group
            )
        ));
    }
    
}