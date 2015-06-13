<?php


class VerifyCode_Log_Model extends MY_Model {
    
    public $_tableName = 'verifycode_log';
    public static $_tableMeta = null;
    
    
    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
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
    
    
    public function sendNormalAddup($id){
    	$this->db->set('send_normal','send_normal + 1',false);
    	$this->db->where('id',$id);
    	$this->db->update($this->_tableRealName);
    	
    	return $this->db->affected_rows();
    }
    
    public function sendFailedAddup($id){
    	$this->db->set('send_fail','send_fail + 1',false);
    	$this->db->where('id',$id);
    	$this->db->update($this->_tableRealName);
    	
    	return $this->db->affected_rows();
    }
    
}