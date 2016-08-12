<?php


class Lab_Member_Model extends MY_Model {
    
    public $_tableName = 'lab_member';
    
    /**
	 * 用户所属lab 的缓存 1,3,4,5 这样子
	 */
	public $_cacheUserLabKey = "user_labs_{uid}";
	
	public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    
    /**
     * 单用户 单lab
     */
    public function singleAdd($user_id,$lab_id , $is_manager = 'n', $uid = 0 , $creator = ''){
    	$now = time();
    	
    	$data = array(
			'user_id' => $user_id,
			'lab_id' => $lab_id,
			'is_manager' => $is_manager,
			'uid' => $uid,
			'creator' => $creator,
			'updator' => $creator,
			'gmt_create' => $now,
			'gmt_modify' => $now
		);
		
    	$this->db->replace($this->_tableName, $data); 
    	return $this->db->affected_rows(); 
    }
    
    /**
     * 多用户 ，多Lab
     */
    public function addMultiUserLabs($userLabs){
    	$now = time();
    	$data = array();
    	$affect = 0;
    	foreach($userLabs as $user){
    		$ins = array(
    			'user_id' => $user['user_id'],
    			'lab_id' => $user['lab_id'],
    			'is_manager' => $user['is_manager'],
    			'uid' => $user['uid'],
    			'creator' => $user['creator'],
    			'updator' => $user['creator'],
    			'gmt_create' => $now,
    			'gmt_modify' => $now
    		);
    		
    		$this->db->replace($this->_tableName, $ins);
    		$affect += $this->db->affected_rows();
    		
    	}
    	return $affect;
    }
    
    /**
     * 单用户 多lab
     */
    public function addUserLabs($user_id,$labs , $is_manager = 'n', $uid = 0 , $creator = ''){
    	$now = time();
        $data = array();
        
        if(is_array($labs)){
        	foreach($labs as $lab){
        		$data[] = array(
        			'user_id' => $user_id,
        			'lab_id' => $lab,
        			'is_manager' => $is_manager,
        			'uid' => $uid,
        			'creator' => $creator,
        			'updator' => $creator,
        			'gmt_create' => $now,
        			'gmt_modify' => $now
        		);
        	}
        	
        	$this->db->insert_batch($this->_tableName, $data); 
        	return $this->db->affected_rows();
        	
        }else{
        	return $this->singleAdd($user_id,$labs , $is_manager, $uid , $creator);
        }
    }
    
    public function getLabUserList($lab_id , $is_manager = '' , $users = array()){
    	if(empty($lab_id)){
    		return array();
    	}
    	
    	if(!is_array($lab_id)){
    		$lab_id = (array)$lab_id;
    	}
    	
    	$condition = array(
    		/*
    		'where' => array(
    			'user_id !=' => LAB_FOUNDER_ID
    		),
    		*/
    		'where_in' => array(
    			array('key' => 'lab_id' , 'value' => $lab_id)
    		),
    		'order' => 'is_manager DESC , user_id ASC'
    	);
    	
    	
    	if($is_manager){
    		$condition['where'] = array(
    			'is_manager' => $is_manager
    		);
    	}
    	
    	if($users){
    		$condition['where_in'][] = array(
    			'key' => 'user_id', 'value' => $users
    		);
    	}
    	
    	return $this->getList($condition);
    }
    
    
    /**
     * 获得用户管理的lab
     */
    public function getUserLabs($user_id){
    	$d = $this->getList(array(
    		'where' => array(
    			'user_id' => $user_id
    		)
    	));
    	
    	if($d['data']){
    		return $d['data'];
    	}else{
    		return array();
    	}
    }
    
    
    public function deleteAllUserByLabs($lab_id){
    	if(!is_array($lab_id)){
    		$lab_id = (array)$lab_id;
    	}
    	
    	$this->db->where_in('lab_id', $lab_id);
    	$this->db->delete($this->_tableName);
    	
    	return $this->db->affected_rows();
    }
    
    /**
     * 删除用户 lab 归属
     */
    public function deleteByUserIdAndLabId($user_id ,$lab_id){
    	
    	if(!is_array($lab_id)){
    		$lab_id = (array)$lab_id;
    	}
    	
    	
    	$this->db->where('user_id', $user_id);
    	$this->db->where_in('lab_id', $lab_id);
    	$this->db->delete($this->_tableName);
    	
    	return $this->db->affected_rows();
    }
    
    public function deleteByUserId($user_id){
    	$this->db->delete($this->_tableName, array('user_id' => $user_id));
    	return $this->db->affected_rows();
    }
    
    public function deleteByLabId($lab_id){
    	$this->db->delete($this->_tableName, array('lab_id' => $lab_id));
    	return $this->db->affected_rows();
    }
    
    public function deleteUsersByLabId($lab_id , $users , $createFrom = array()){
    	
    	$this->db->where_in('user_id', $users);
    	
    	if($createFrom){
    		$this->db->where_in('uid', $createFrom);
    	}
    	$this->db->where_in('lab_id', $lab_id);
    	$this->db->delete($this->_tableName);
    	return $this->db->affected_rows();
    	
    }
    
}