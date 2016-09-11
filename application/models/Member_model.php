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
        $sql = "SELECT {$field} FROM ".$this->_tableRealName ." WHERE uid = ?"; 
        $query = $this->db->query($sql, array($uid));
        $row = $query->result_array();
        
        if($row[0]){
            return $row[0];
        }else{
            return false;
        }
    }
    
    public function getUserListByIds($uids,$field = '*'){
    	if(empty($uids)){
    		return array();
    	}
    	
    	$this->db->select($field);
    	$this->db->where_in('uid' , $uids);
    	$query = $this->db->get($this->getTableRealName());
    	
    	$tempList = $query->result_array();
    	
    	$userList = array();
    	foreach($tempList as $user){
    		$userList[$user['uid']] = $user;
    	}
    	return $userList;
    }
    
    
    
}