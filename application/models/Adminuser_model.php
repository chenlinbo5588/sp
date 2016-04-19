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
    	return array_keys(self::$_tableMeta);
    }
    
    
    public function checkExists($email){
		if(!$this->getCount(
			array(
				'where' => array('email' => $email)
			))
		){
			return true;
		}else{
			return false;
		}
		
	}
    
    
}