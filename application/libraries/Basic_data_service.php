<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basic_data_service extends Base_service {
	
	
	private $_basicDataModel = null;
	
	//列表
	public static $basicData = array();
	
	//原始树
	public static $basicDataTree = array();
	
	//键树
	public static $basicAssocDataTree = array();
	

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'Basic_Data_Model'
		));
		
		$this->_basicDataModel = self::$CI->Basic_Data_Model;
		
		self::$basicData = $this->_basicDataModel->getList($this->_getDefaultCondition(),'id');
		
		self::$CI->phptree->resetData();
		
		self::$basicDataTree = self::$CI->phptree->makeTree(self::$basicData,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => true
			));
			
			
		$this->makeAssocData(self::$basicDataTree,self::$basicAssocDataTree);
		
	}
	
	
	/**
	 * 转换成键值的数组
	 */
	public function makeAssocData($tempAllData,&$destData){
		foreach($tempAllData as $basicDataItem){
			$destData[$basicDataItem['show_name']] = array(
				'id' => $basicDataItem['id'],
				'show_name' => $basicDataItem['show_name'],
				'real_val' => $basicDataItem['real_val'],
				'displayorder' => $basicDataItem['displayorder'],
				'pid' => $basicDataItem['pid'],
				'selected' => false,
				'style' => ''
			);
			
			if($basicDataItem['children']){
				$destData[$basicDataItem['show_name']]['children'] = array();
				$this->makeAssocData($basicDataItem['children'],$destData[$basicDataItem['show_name']]['children']);
			}
		}
	}
	
	/**
	 * 返回键树
	 */
	public static function getAssocBasicDataTree(){
		return self::$basicAssocDataTree;
	}
	
	
	/**
	 * 获得顶级的子项
	 */
	public static function getTopChildList($pTopName){
		
		if(isset(self::$basicAssocDataTree[$pTopName])){
			return self::$basicAssocDataTree[$pTopName]['children'];
		}else{
			return array();
		}
	}
	
	/**
	 * 获得默认条件
	 */
	private function _getDefaultCondition(){
		return array(
			'select' => 'id,show_name,real_val,pid,displayorder,enable', 
			'where' => array('enable' => 1),
			'order' => 'displayorder ASC,id DESC'
		);
	}
	
	
	/**
	 * 
	 */
	public function getBasicDataTreeHTML($condition = array()){
		
		$condition = array_merge($this->_getDefaultCondition(),$condition);
		
		$list = $this->_basicDataModel->getList($condition);
		
		if($list){
			self::$CI->phptree->resetData();
			return self::$CI->phptree->makeTreeForHtml($list,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => true
			));
		}else{
			return array();
		}
	}
	
	
	/**
	 * 获得基础数据类别
	 */
	public function getBasicDataList($condition = array()){
		$condition = array_merge($this->_getDefaultCondition(),$condition);
		return $this->_basicDataModel->getList($condition,'id');
	}
	
	/**
	 * 获得树对象
	 */
	public function getBasicDataTree($condition = array()){
		$condition = array_merge($this->_getDefaultCondition(),$condition);
		
		$list = $this->_basicDataModel->getList($condition);
		if($list){
			self::$CI->phptree->resetData();
			return self::$CI->phptree->makeTree($list,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => true
			));
		}else{
			
			return array();
		}
	}
	
	
	
	/**
	 * 检查数据合法性
	 */
	public function checkpid($pid, $extra = ''){
		
		//echo $extra;
		
		$deep = $this->_basicDataModel->getDeepById($pid);
		
		//最大深度4
		if($deep >= 4){
			self::$CI->form_validation->set_message('checkpid_callable','父级只能是一级、二级、三级分类');
			return false;
		}
		
		if($extra == 'add'){
			//如果是增加的不需要再网后面继续执行了
			return true;
		}
		
		//不能是自己，也不能是其下级分类
		$currentId = self::$CI->input->post('id');
		
		$list = $this->_basicDataModel->getList(array(
			'where' => array('pid' => $currentId)
		));
		
		$subIds = array($currentId);
		$hasData = true;
		
		while($list && $hasData){
			
			$ids = array();
			foreach($list as $item){
				$subIds[] = $item['id'];
				$ids[] = $item['id'];
			}
			
			if($ids){
				$hasData = false;
			}else{
				$list = $this->_basicDataModel->getList(array(
					'where_in' => array(
						array('key' => 'pid', 'value' => $ids)
					)
				));
			}
		}
		
		//print_r($subIds);
		if(in_array($pid,$subIds)){
			self::$CI->form_validation->set_message('checkpid_callable','父级不能选择自己和自己的下级分类');
			return false;
		}else{
			
			return true;
		}
	}
	
	
	
	
	
}
