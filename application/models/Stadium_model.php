<?php


class Stadium_Model extends MY_Model {
    
    public $_tableName = 'stadium';
    
    public function __construct(){
        parent::__construct();
    }
    
    protected function _metaData(){
    	static $_meta = array(
			'stadium_id','title','address','contact','mobile','district_id','longitude','latitude','has_coordinates',
			'stadium_type','ground_type','charge_type','open_type','owner','score','can_booking',
			'gmt_create','gmt_modify'
		);
    	
    	return $_meta;
    }
    
}