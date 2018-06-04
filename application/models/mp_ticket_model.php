<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 微信公众账号
 */
class Mp_Ticket_Model extends MY_Model {
    public $_tableName = 'mp_ticket';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
}