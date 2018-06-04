<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_Model extends MY_Model {
    
    public $_tableName = 'member';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    public function getUserByUid($uid,$field = '*'){
        $sql = "SELECT {$field} FROM ".$this->getTableRealName() ." WHERE uid = ?"; 
        $query = $this->db->query($sql, array($uid));
        $row = $query->result_array();
        
        if($row[0]){
            return $row[0];
        }else{
            return false;
        }
    }
    
    
    
}