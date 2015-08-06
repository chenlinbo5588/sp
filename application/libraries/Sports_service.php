<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 运动项目 服务
 */
class Sports_Service extends Base_Service {

    private $_sportsCategoryModel;
    private $_sportsMetaModel;
	
	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Sports_Category_Model');
		$this->CI->load->model('Sports_Meta_Model');
        
        $this->_sportsCategoryModel = $this->CI->Sports_Category_Model;
        $this->_sportsMetaModel = $this->CI->Sports_Meta_Model;
        
	}
	
	/**
	 * 
	 */
	public function getMetaByCategoryAndGroup($categoryName , $groupname){
		$dataList = $this->_sportsMetaModel->getList(array(
			'select' => 'id,category_name,name',
			'where' => array('category_name' => $categoryName , 'gname' => $groupname)
		));
		
		return $dataList ;
	}
	
	
}
