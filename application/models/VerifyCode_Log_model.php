<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VerifyCode_Log_Model extends MY_Model {
    
    public $_tableName = 'verifycode_log';
    public static $_tableMeta = null;
    
    
    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    public function checkVerifyCode($phone,$code){
    	
    }
    
    public function getVerifyCodeByPhone($phone,$field = '*'){
        $sql = "SELECT {$field} FROM ".$this->_tableRealName ." WHERE phone = ?"; 
        $query = $this->db->query($sql, array($phone));
        $row = $query->result_array();
        
        if($row[0]){
            return $row[0];
        }else{
            return false;
        }
    }
    
}