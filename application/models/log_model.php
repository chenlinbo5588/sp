<?php


class Log_Model extends MY_Model {
    
    public $_tableName = 'log';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function add($param , $post_data = ''){
        $data = array(
            'id' => NULL,
            'url' => $param['REQUEST_URI'],
            'method' => $param['REQUEST_METHOD'],
            'occur_time' => date("Y-m-d H:i:s",$param['REQUEST_TIME']),
            'post_data' => $post_data,
            'gmt_create' => $param['REQUEST_TIME'],
            'gmt_modify' => $param['REQUEST_TIME']
        );
        
        $this->db->insert($this->_tableName, $data);
        return $this->db->insert_id();
    }
    
}