<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Navigation_service extends Base_service {
	
	private $_navigationModel = null;
	
	private $_parentList = array();

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Navigation_Model'));
		
		$this->_navigationModel = self::$CI->Navigation_Model;
	}
	
	public function getClassTreeHTML(){
		$list = $this->_navigationModel->getList(array(
			'order' => 'pid ASC , displayorder ASC'
		));
		
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
	
	public function getClassTree(){
		$list = $this->_navigationModel->getList(array(
			'order' => 'pid ASC , displayorder ASC'
		));
		
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
	
	
	public function getInfoByUrl($url){
		return $this->_navigationModel->getFirstByKey($url,'url_cn');
	}
	
	public function getInfoByName($name){
		return $this->_navigationModel->getFirstByKey($name,'name_cn');
	}
	
	
	public function getParentsById($id = 0,$field = '*'){

        $condition['select'] = $field;
        $condition['where'] = array(
            'ac_id' => $id
        );
        
        $result = $this->_navigationModel->getById($condition);
        if($result){
            $this->_parentList[] = $result;
            $this->getParentsById($result['ac_parent_id']);
        }
        
        return $this->_parentList;
    }
    
	
	/**
	 * 获得所所有的子孙
	 */
	public function getAllChildClassByPid($id,$field = 'id',$maxDeep = 2){
		$list = $this->_navigationModel->getList(array(
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
				$list = $this->_navigationModel->getList(array(
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
	
	
	
	public function getClassDeepById($id,$maxDeep = 5){
		$info = $this->_navigationModel->getFirstByKey($id,'id');
		
		//默认一级
		$deep = 1;
		
		while($info['pid']){
			$deep++;
			$info = $this->_navigationModel->getFirstByKey($info['pid'],'id');
			
			//防止无限循环,最多5级
			if($deep >= $maxDeep){
				break;
			}
		}
		
		return $deep;
		
	}
	
	
	public function deleteClass($delId){
		
		$list = $this->_navigationModel->getList(array(
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
				
				$this->_navigationModel->deleteByCondition(array(
					'where_in' => array(
						array('key' => 'id', 'value' => $ids)
					)
				));
				
				$list = $this->_navigationModel->getList(array(
					'where_in' => array(
						array('key' => 'pid', 'value' => $ids)
					)
				));
			}
		}
		
		$this->_navigationModel->deleteByWhere(array('id' => $delId));
		
	}
	
	
	
	
	
	public function getClassByParentId($id = 0){
		$list = $this->_navigationModel->getList(array(
			'where' => array('pid' => $id),
			'order' => 'displayorder ASC'
		));
		
		return $this->toEasyUseArray($list,'id');
	}
}
