<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Block_Model extends MY_Model {
    
    public $_tableName = 'block';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    public function getBlockInfoById($blockId){
    	$blockInfo = $this->Block_Model->getById(array(
        	'where' => array(
        		'block_id' => $blockId,
        		'is_show' => 1
        	)
        ));
        
        
        return $blockInfo;
    	
    	
    }
}