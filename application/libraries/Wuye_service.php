<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wuye_service extends Base_service {
	
	private $_basicDataModel = null;
	private $_workerModel = null;
	private $_workerImagesModel = null;
	

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Basic_Data_Model','Worker_Model','Worker_Images_Model'));
		$this->_basicDataModel = self::$CI->Basic_Data_Model;
		$this->_workerModel = self::$CI->Worker_Model;
		$this->_workerImagesModel = self::$CI->Worker_Images_Model;
		
	}
	
	
	
	public function getBasicDataTreeHTML(){
		$list = $this->_basicDataModel->getList();
		
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
	
	public function getBasicDataTree(){
		$list = $this->_basicDataModel->getList();
		
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
	
	
	public function getParentsById($id = 0,$field = '*'){

        $condition['select'] = $field;
        $condition['where'] = array(
            'id' => $id
        );
        
        $result = $this->_basicDataModel->getById($condition);
        if($result){
            $this->_parentList[] = $result;
            $this->getParentsById($result['pid']);
        }
        
        return $this->_parentList;
    }
    
	
	/**
	 * 获得所所有的子孙
	 */
	public function getAllChildBasicDataByPid($id,$field = 'id',$maxDeep = 3){
		$list = $this->_basicDataModel->getList(array(
			'select' => $field,
			'where' => array('pid' => $id)
		));
		
		$deep = 0;
		
		$allIds = array();
		
		while($list){
			$ids = array();
			foreach($list as $item){
				$ids[] = $item['id'];
				$allIds[] = $item['id'];
			}
			
			if($ids){
				$list = $this->_basicDataModel->getList(array(
					'select' => $field,
					'where_in' => array(
						array('key' => 'pid', 'value' => $ids)
					)
				));
			}else{
				$canQuit = true;
			}
			
			$deep++;
			
			if($deep >= $maxDeep){
				break;
			}
		}
		
		return $allIds;
	}
	
	
	/**
	 * 
	 */
	public function getBasciDataDeepById($id,$maxDeep = 5){
		$info = $this->_basicDataModel->getFirstByKey($id,'id');
		
		//默认一级
		$deep = 1;
		
		while($info['pid']){
			$deep++;
			$info = $this->_basicDataModel->getFirstByKey($info['pid'],'id');
			
			//防止无限循环,最多5级
			if($deep >= $maxDeep){
				break;
			}
		}
		
		return $deep;
		
	}
	
	
	public function deleteBasicData($delId){
		
		$list = $this->_basicDataModel->getList(array(
			'where' => array('pid' => $delId)
		));
		
		$hasData = true;
		
		while($list && $hasData){
			$ids = array();
			foreach($list as $item){
				$ids[] = $item['id'];
			}
			
			if(empty($ids)){
				$hasData = false;
			}else{
				
				$this->_basicDataModel->deleteByCondition(array(
					'where_in' => array(
						array('key' => 'id', 'value' => $ids)
					)
				));
				
				$list = $this->_basicDataModel->getList(array(
					'where_in' => array(
						array('key' => 'id', 'value' => $ids)
					)
				));
			}
		}
		
		$this->_basicDataModel->deleteByWhere(array('id' => $delId));
		
	}
	
	/**
	 * 
	 */
	public function getBasicDataByParentId($id = 0){
		return $this->_basicDataModel->getList(array(
			'where' => array('pid' => $id),
			'order' => 'displayorder ASC'
		),'id');
		
	}
	
	
	/**
	 * 检查数据合法性
	 */
	public function checkpid($pid, $extra = ''){
		
		//echo $extra;
		
		$deep = $this->getBasciDataDeepById($pid);
		
		if($deep >=3){
			self::$CI->form_validation->set_message('checkpid_callable','父级只能是一级分类或者二级分类');
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
	
	
	/**
	 * 获得分组
	 */
	public function getBasicDataByName($pName){
		
		$tempInfo = $this->_basicDataModel->getById(array(
			'select' => 'id',
			'where' => array('show_name' => $pName,'pid' => 0)
		));
		
		
		return $this->_basicDataModel->getList(array(
			'where' => array(
				'pid' => $tempInfo['id'],
				'enable' => 1
			),
			'order' => 'displayorder ASC'
		),'id');
	}
	
}
