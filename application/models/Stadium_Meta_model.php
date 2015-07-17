<?php


class Stadium_Meta_Model extends MY_Model {
    
    public $_tableName = 'stadium_meta';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
    }
    
    public function getMetaDataByGroup($group = ''){
        
        return $this->getList(array(
            'where' => array(
                'group' => $group
            )
        ));
    }
    
}