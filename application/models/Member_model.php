<?php


class Member_Model extends MY_Model {
    
    public $_tableName = 'member';
    
    public function __construct(){
        parent::__construct();
        
    }
    
    protected function _metaData(){
    	static $_meta = array(
			'uid','emai','username','password','status','mobile','sex',
			'nickname','emailstatus','avatarstatus','videophotostatus',
			'groupid','groupexpiry','extgroupids','regdate','credits','notifysound',
			'timeoffset','newpm','newprompt','onlyacceptfriendpm','conisbind',
			'district_id','freeze','gmt_create','gmt_modify'
		);
    	
    	return $_meta;
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
    
    public function checkUserNameNoExist($username){
    	$count = $this->getCount(array(
    		'where' => array(
    			'username' => $username
    		)
    	));
    	
    	if($count > 0){
    		return false;
    	}else{
    		return true;
    	}
    }
}