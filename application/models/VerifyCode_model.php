<?php


class VerifyCode_Model extends MY_Model {
    
    public $_tableName = 'verifycode';
    
    public function __construct(){
        parent::__construct();
    }
    
    private function _metaData(){
    	static $_meta = array(
			'phone','ip','code','expire_time','gmt_create','gmt_modify'
		);
    	
    	return $_meta;
    }
    
    /**
     * 获得用户
     */
    public function getVerifyCodeByPhone($phone,$field = '*'){
        $sql = "SELECT {$field} FROM ".$this->_tableRealName ." WHERE username = ?"; 
        $query = $this->db->query($sql, array($phone));
        $row = $query->result_array();
        
        if($row[0]){
            return $row[0];
        }else{
            return false;
        }
    }
    
    
    
    public function add($param){
        $now = time();
        $fields = $this->_metaData();
        
        $data = array();
        
        foreach($param as $key => $value){
        	if(in_array($key,$fields)){
        		$data[$key] = $value;
        	}
        }
        
        $data['gmt_create'] = $data['gmt_modify'] = time();
        $this->db->insert($this->_tableRealName, $data);
        
        return $this->db->insert_id();
    }
    
    
}