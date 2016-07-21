<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 运动项目 服务
 */
class Sports_service extends Base_service {

    private $_sportsCategoryModel;
    private $_sportsMetaModel;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Sports_Category_Model');
		self::$CI->load->model('Sports_Meta_Model');
        
        $this->_sportsCategoryModel = self::$CI->Sports_Category_Model;
        $this->_sportsMetaModel = self::$CI->Sports_Meta_Model;
        
	}
	
	/**
	 * 
	 */
	public function getSportsCategory($condition = array())
	{
		$condition['where']['status'] = 1;
		return $this->_sportsCategoryModel->getList($condition);
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
	
	
	public function getGroupByCategory($categoryName){
		self::$dbInstance->distinct(true);
		
		$dataList = $this->_sportsMetaModel->getList(array(
			'select' => 'gname',
			'where' => array('category_name' => $categoryName,'status' => 1)
		));
		
		return $dataList ;
	}
	
	
}
