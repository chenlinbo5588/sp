<?php


class Open_Ticket_Model extends MY_Model {
    
    public $_tableName = 'open_ticket';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function add($param){
        $now = time();
        $data = array(
            'id' => NULL,
            'access_token' => $param['access_token'],
            'gmt_create' => empty($param['gmt_create']) ? $now : $param['gmt_create'],
            'gmt_modify' => empty($param['gmt_create']) ? $now : $param['gmt_create'],
        );
        
        if($param['expire_in']){
            $data['expire_in'] = $param['expire_in'];
        }
        
        $this->db->insert($this->_tableName, $data);
        return $this->db->insert_id();
    }
    
}