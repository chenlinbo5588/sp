<?php


class Member_Model extends MY_Model {
    
    public $_tableName = 'member';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
    }
    
    
    public function getUserByEmail($email,$field = '*'){
        $sql = "SELECT {$field} FROM ".$this->_tableRealName ." WHERE email = ?"; 
        $query = $this->db->query($sql, array($email));
        $row = $query->result_array();
        
        if($row[0]){
            return $row[0];
        }else{
            return false;
        }
    }
    
    
    /**
     * 获得用户
     */
    public function getUserByUserName($username,$field = '*'){
        $sql = "SELECT {$field} FROM ".$this->_tableRealName ." WHERE username = ?"; 
        $query = $this->db->query($sql, array($username));
        $row = $query->result_array();
        
        if($row[0]){
            return $row[0];
        }else{
            return false;
        }
    }
    
    
}