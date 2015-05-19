<?php


class Member_Model extends MY_Model {
    
    public $_tableName = 'member';
    
    public function __construct(){
        parent::__construct();
        
        $this->_tableRealName = $this->getTableRealName();
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
    
    
    public function add($user){
        
        $now = time();
        
        if(!$user['psw']){
            $user['psw'] = md5(config_item('encryption_key').config_item('default_password'));
        }else{
            $user['psw'] = md5(config_item('encryption_key').$user['psw']);
        }
        
        $data = array(
            'id' => NULL,
            'name' => $user['name'],
            'account' => $user['account'],
            
            'email' => $user['email'],
            
            'psw' => $user['psw'],
            'sex' => $user['sex'],
            'mobile' => $user['mobile'],
            'tel' => $user['tel'],
            'creator' => $user['creator'],
            'updator' => $user['updator'],
            'gmt_create' => $now,
            'gmt_modify' => $now
        );
        
        
        $this->db->insert($this->_tableRealName, $data);
        return $this->db->insert_id();
    }
    
    /**
     * really delete
     * @param type $user 
     */
    public function delete($user){
        
    }
    
    public function fake_delete($user){
        
        $where = array(
            'id' => $user['id']
        );
        
        $data = array(
            'status' => '已删除'
        );
        
        $this->db->update($this->_tableRealName, $data, $where);
        return $this->db->affected_rows();
    }
    
    
    public function update($user){
        $data = array(
            'name' => $user['name'],
            'account' => $user['account'],
            'email' => $user['email'],
            'sex' => $user['sex'],
            'mobile' => $user['mobile'],
            'tel' => $user['tel'],
            'updator' => $user['updator'],
            'gmt_modify' => time()
        );
        
        if(!empty($user['psw'])){
            $data['psw'] = md5(config_item('encryption_key').$user['psw']);
        }
        
        $where = array(
            'id' => $user['id']
        );
        
        $this->db->update($this->_tableRealName, $data, $where);
        return $this->db->affected_rows();
    }
    
    
}