<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pm_Message_Model extends MY_Model {
    
    public $_tableName = 'pm_message';
    
    
    private $_uid = 0;
    private $_tableId = 0;
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    /**
     * 
     */
    public function splite_by_uid($uid = 0){
    	
    	
    }
    
    
    public function getTableRealName(){
    	return $this->_tablePre.$this->_tableName; 
    }
    
    
    public function loadConfig(){
    	
   		//$constHash = new Flexihash();
		//$constHash->addTargets($targets);

		$whichTable = $constHash->lookup($music_id);

		return $whichTable;
    	
    	
    }
    
    
    
    /**
     * 
     */
    public function add($data, $uid = 0){
    	
    	return $this->_add($data);
    	
    }

}