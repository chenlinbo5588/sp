<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_Class_Model extends MY_Model {
    
    public $_tableName = 'goods_class';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return array_keys(self::$_tableMeta);
    }
    
    
    public function getGoodsClassById($id){
    	return $this->getFirstByKey($id,'gc_id');
    }
    
    
    public function updateGoodsClassSeoById($goodClassId,$data){
    	return $this->update($data,array('gc_id' => $goodClassId));
    }

}