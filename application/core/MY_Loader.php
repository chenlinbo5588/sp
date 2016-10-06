<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader {
	
	public function __construct(){
		parent::__construct();
	}
	
    public function entity($tableName){
        include(EntityPATH.$tableName.'.php');
        
        return $entity;
    }
    
    
    public function get_config($configFileName){
    	include(APPPATH.'config/'.$configFileName.'.php');
    	return $config;
    }
 
    
    
}
