<?php


class Team_Model extends MY_Model {
    
    public $_tableName = 'team';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
    }
    
    
    public function isTitleNotUsed($title,$param){
		if(!$this->getCount(
			array(
				'where' => array('title' => $title , 'd4' => $param)
			))
		){
			return true;
		}else{
			return false;
		}
		
	}
}