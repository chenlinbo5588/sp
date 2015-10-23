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
    
    
    public function checkExists($account){
		if(!$this->getCount(
			array(
				'where' => array('mobile' => $account)
			))
		){
			return true;
		}else{
			return false;
		}
		
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
    
    
    
}