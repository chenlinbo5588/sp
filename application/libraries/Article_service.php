<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Article_service extends Base_service {
	
	private $_articleModel = null;
	private $_articleClassModel = null;
	private $_articleFileModel = null;
	
	private $_parentList = array();

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Article_Model', 'Article_Class_Model','Article_File_Model'));
		
		$this->_articleModel = self::$CI->Article_Model;
		$this->_articleClassModel = self::$CI->Article_Class_Model;
		$this->_articleFileModel = self::$CI->Article_File_Model;
	}
	
	public function getArticleClassTreeHTML(){
		$list = $this->_articleClassModel->getList();
		
		if($list){
			self::$CI->phptree->resetData();
			return self::$CI->phptree->makeTreeForHtml($list,array(
				'primary_key' => 'ac_id',
				'parent_key' => 'ac_parent_id',
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
        
        $result = $this->_articleClassModel->getById($condition);
        if($result){
            $this->_parentList[] = $result;
            $this->getParentsById($result['ac_parent_id']);
        }
        
        return $this->_parentList;
    }
    
	
	/**
	 * 获得所所有的子孙
	 */
	public function getAllChildArticleClassByPid($id,$field = 'ac_id',$maxDeep = 3){
		$list = $this->_articleClassModel->getList(array(
			'select' => $field,
			'where' => array('ac_parent_id' => $id)
		));
		
		$deep = 0;
		
		$allIds = array();
		
		while($list){
			$ids = array();
			foreach($list as $item){
				$ids[] = $item['ac_id'];
				$allIds[] = $item['ac_id'];
			}
			
			if($ids){
				$list = $this->_articleClassModel->getList(array(
					'select' => $field,
					'where_in' => array(
						array('key' => 'ac_parent_id', 'value' => $ids)
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
	
	
	
	public function getArticleClassDeepById($id,$maxDeep = 5){
		$info = $this->_articleClassModel->getFirstByKey($id,'ac_id');
		
		//默认一级
		$deep = 1;
		
		while($info['ac_parent_id']){
			$deep++;
			$info = $this->_articleClassModel->getFirstByKey($info['ac_parent_id'],'ac_id');
			
			//防止无限循环,最多5级
			if($deep >= $maxDeep){
				break;
			}
		}
		
		return $deep;
		
	}
	
	
	public function deleteArticleClass($delId){
		
		$list = $this->_articleClassModel->getList(array(
			'where' => array('ac_parent_id' => $delId)
		));
		
		$hasData = true;
		
		while($list && $hasData){
			$ids = array();
			foreach($list as $item){
				$ids[] = $item['ac_id'];
			}
			
			if(empty($ids)){
				$hasData = false;
			}else{
				
				$this->_articleClassModel->deleteByCondition(array(
					'where_in' => array(
						array('key' => 'ac_id', 'value' => $ids)
					)
				));
				
				$list = $this->_articleClassModel->getList(array(
					'where_in' => array(
						array('key' => 'ac_parent_id', 'value' => $ids)
					)
				));
			}
		}
		
		$this->_articleClassModel->deleteByWhere(array('ac_id' => $delId));
		
	}
	
	
	
	public function getArticleClassTree(){
		$list = $this->_articleClassModel->getList();
		
		if($list){
			self::$CI->phptree->resetData();
			return self::$CI->phptree->makeTree($list,array(
				'primary_key' => 'ac_id',
				'parent_key' => 'ac_parent_id',
				'expanded' => true
			));
		}else{
			
			return array();
		}
	}
	
	public function getArticleClassByParentId($id = 0){
		return $this->_articleClassModel->getList(array(
			'where' => array('ac_parent_id' => $id),
			'order' => 'ac_sort ASC'
		),'ac_id');
		
	}
	
	
	public function getNextByArticle($article){
		$article = $this->_articleModel->getList(array(
			'where' => array('ac_id' => $article['ac_id'], 'article_id >' => $article['article_id'] , 'article_show' => 1),
			'order' => 'article_id ASC',
			'limit' => 1
		));
		
		if($article[0]){
			return $article[0];
		}else{
			return false;
		}
	}
	
	
	public function getPreByArticle($article){
		$article = $this->_articleModel->getList(array(
			'where' => array('ac_id' => $article['ac_id'], 'article_id <' => $article['article_id'] , 'article_show' => 1),
			'order' => 'article_id DESC',
			'limit' => 1
		));
		
		 //print_r($article);
		
		if($article[0]){
			return $article[0];
		}else{
			return false;
		}
	}
}
