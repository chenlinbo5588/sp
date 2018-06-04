<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 微信公众 关注者
 */
 
class Wx_Customer_Model extends MY_Model {
    public $_tableName = 'wx_customer';
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    public function unsubscribe($param){
    	$now = time();
        $data = array(
            'subscribe' => 0
        );
        
        $where = array(
            'openid' => $param['openid']
        );
        
        return $this->updateByWhere($data, $where);
    }
    
    /**
     *
     * @param type $openid
     * @param type $mobile
     * @param type $virtual_no 
     */
    public function bind($openid , $mobile,$virtual_no){
        $data = array(
            'mobile' => $mobile,
            'virtual_no' => empty($virtual_no) ? '' : $virtual_no
        );
        
        $where = array(
            'openid' => $openid
        );
        
        return $this->updateByWhere($data, $where);
        
    }
    
    
}