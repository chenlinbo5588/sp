<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 后台登陆账号
 */
 
class Adminuser_Model extends MY_Model {
    public $_tableName = 'adminuser';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    /**
     * 获得用户
     */
    public function getUserByAccount($account,$field = '*'){
        $sql = "SELECT {$field} FROM ".$this->_tableRealName ." WHERE account = ?  and status = '正常'"; 
        $query = $this->db->query($sql, array($account));
        $row = $query->result_array();
        return $row;
    }
    
    private function _paramProcess($user){
    	if(!$user['psw']){
            $user['psw'] = md5(config_item('encryption_key').config_item('default_password'));
        }else{
            $user['psw'] = md5(config_item('encryption_key').$user['psw']);
        }
        
        if(!in_array($user['is_manager'],array('y','n'))){
        	$user['is_manager'] = 'n';
        }
        
        return $user;
    }
    
    public function add($user){
        $user = $this->_paramProcess($user);
        
        return $this->_add($user);
    }
    
    /**
     * 
     */
    public function updateInfo($user){
    	
        $user = $this->_paramProcess($user);
        return $this->update($user,array('id' => $user['id']));
    }
    
    
    
    /**
     * really delete
     * @param type $user 
     */
    public function delete($user){
        $where = array(
            'id' => $user['id']
        );
        
        $this->db->delete($this->_tableName, $where);
        return $this->db->affected_rows();
    }
    
    public function fake_delete($user){
        
        $where = array(
            'id' => $user['id']
        );
        
        $data = array(
            'status' => '已删除'
        );
        
        return $this->update($data, $where);
    }
    
    /*
    public function update($user){
        $data = array(
            'name' => $user['name'],
            'updator' => $user['updator'],
        );
        
        if($user['is_manager'] == 'y'){
        	$data['is_manager'] = $user['is_manager'];
        }
        
        if(!empty($user['psw'])){
            $data['psw'] = md5(config_item('encryption_key').$user['psw']);
        }
        
        if(!empty($user['status']) && $user['status'] == '正常'){
        	$data['status'] = $user['status'];
        }
        
        $where = array(
            'id' => $user['id']
        );
        
        return $this->update($data, $where);
    }
    */
    
    public function updatePassword($user){
    	
    	$data['psw'] = md5(config_item('encryption_key').$user['psw']);
    	$where = array(
            'id' => $user['id']
        );
        return $this->update($data, $where);
    }
}