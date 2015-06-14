<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium_Service extends Base_Service {

    private $_stadiumModel;
    private $_stadiumMetaModel;
	
	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Stadium_Model');
		$this->CI->load->model('Stadium_Meta_Model');
        
        $this->_stadiumModel = $this->CI->Stadium_Model;
        $this->_stadiumMetaModel = $this->CI->Stadium_Meta_Model;
        
	}
    
    public function addOneStadium($param){
        
        if($param['has_coordinates'] != '1'){
            $param['has_coordinates'] = 0;
        }
        
        return $this->_stadiumModel->_add($param);
    }
    
    public function getAllMetaGroups(){
        
        $all = $this->_stadiumMetaModel->getList(array(
            'order' => 'group ASC ,displayorder DESC'
            
        ));
        
        $data = array();
        
        foreach($all as $key => $item){
            if(!isset($data[$item['group']])){
                $data[$item['group']] = array();
            }
            
            $data[$item['group']][] = $item;
        }
        
        return $data;
    }
	
}
