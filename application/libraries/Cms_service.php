<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Cms_service extends Base_service {
	
	private $_cmsArticleModel = null;
	private $_cmsArticleClassModel = null;
	
	private $_parentList = array();

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Cms_Article_Model', 'Cms_Article_Class_Model'));
		
		$this->_cmsArticleModel = self::$CI->Cms_Article_Model;
		$this->_cmsArticleClassModel = self::$CI->Cms_Article_Class_Model;
	}
	
	public function getArticleClassTreeHTML(){
		$list = $this->_cmsArticleClassModel->getList();
		
		if($list){
			return self::$CI->phptree->makeTreeForHtml($list,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => true
			));
		}else{
			return array();
		}
	}
	
	public function getArticleClassTree(){
		$list = $this->_cmsArticleClassModel->getList();
		
		if($list){
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
            'ac_id' => $id
        );
        
        $result = $this->_cmsArticleClassModel->getById($condition);
        if($result){
            $this->_parentList[] = $result;
            $this->getParentsById($result['pid']);
        }
        
        return $this->_parentList;
    }
    
	
	/**
	 * 获得所所有的子孙
	 */
	public function getAllChildArticleClassByPid($id,$field = 'id',$maxDeep = 3){
		$list = $this->_cmsArticleClassModel->getList(array(
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
				$list = $this->_cmsArticleClassModel->getList(array(
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
	public function getArticleClassDeepById($id,$maxDeep = 5){
		$info = $this->_cmsArticleClassModel->getFirstByKey($id,'id');
		
		//默认一级
		$deep = 1;
		
		while($info['pid']){
			$deep++;
			$info = $this->_cmsArticleClassModel->getFirstByKey($info['pid'],'id');
			
			//防止无限循环,最多5级
			if($deep >= $maxDeep){
				break;
			}
		}
		
		return $deep;
		
	}
	
	
	public function deleteArticleClass($delId){
		
		$list = $this->_cmsArticleClassModel->getList(array(
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
				
				$this->_cmsArticleClassModel->deleteByCondition(array(
					'where_in' => array(
						array('key' => 'id', 'value' => $ids)
					)
				));
				
				$list = $this->_cmsArticleClassModel->getList(array(
					'where_in' => array(
						array('key' => 'ac_parent_id', 'value' => $ids)
					)
				));
			}
		}
		
		$this->_cmsArticleClassModel->deleteByWhere(array('ac_id' => $delId));
		
	}
	
	public function getArticleClassByParentId($id = 0){
		$list = $this->_cmsArticleClassModel->getList(array(
			'where' => array('pid' => $id),
			'order' => 'ac_sort ASC'
		));
		
		return $this->toEasyUseArray($list,'id');
	}
}
