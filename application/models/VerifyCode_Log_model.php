<?php


class VerifyCode_Log_Model extends MY_Model {
    
    public $_tableName = 'verifycode_log';
    
    public function __construct(){
        parent::__construct();
    }
    
    protected function _metaData(){
    	static $_meta = array(
			'id','phone','ip','code','expire_time','send_normal','send_fail','gmt_create','gmt_modify'
		);
    	
    	return $_meta;
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