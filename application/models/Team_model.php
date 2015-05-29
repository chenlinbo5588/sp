<?php


class Team_Model extends MY_Model {
    
    public $_tableName = 'team';
    
    public function __construct(){
        parent::__construct();
        
    }
    
    
    public function add($param){
        
        $now = time();
        $data = array(
            'id' => NULL,
            'creator' => $param['creator'],
            'updator' => $param['creator'],
            'gmt_create' => $now,
            'gmt_modify' => $now
        );
        
        
        $this->db->insert($this->_tableRealName, $data);
        return $this->db->insert_id();
    }
    
}