<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bdc_Model extends MY_Model {
    
    public $_tableName = 'bdc';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    /**
     * 获得某个办事机构的当天的业务笔数
     */
    public function getBussCount($dept_id,$date){
    	return $this->getCount(array(
			'where' => array(
				'dept_id' => $dept_id,
				'date_key' => $date
			)
		));
    }

}