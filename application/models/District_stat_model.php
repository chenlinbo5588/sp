<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 地区统计数据
 * 
 * 用户统计 某一个地区的 各类运动种类 的队伍数，比赛数量，裁判数量等
 * 
 */
class District_Stat_Model extends MY_Model {
    
    public $_tableName = 'district_stat';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
}