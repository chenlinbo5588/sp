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
	
	public function getArticleClassTreeHTML(){
		$list = $this->_navigationModel->getList();
		
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
	public function getAllChildClassByPid($id,$field = 'id',$maxDeep = 2){
		$list = $this->_articleClassModel->getList(array(
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
				$list = $this->_articleClassModel->getList(array(
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
	
	
	
	public function getArticleClassDeepById($id,$maxDeep = 5){
		$info = $this->_articleClassModel->getFirstByKey($id,'id');
		
		//默认一级
		$deep = 1;
		
		while($info['pid']){
			$deep++;
			$info = $this->_articleClassModel->getFirstByKey($info['pid'],'id');
			
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
		$list = $this->_articleClassModel->getList(array(
			'where' => array('ac_parent_id' => $id),
			'order' => 'ac_sort ASC'
		));
		
		return $this->toEasyUseArray($list,'ac_id');
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
	
	
	public function getCommandArticleList($currentId,$moreCondition = array('limit' => 8)){
		
		$articleClassIds = $this->getAllChildArticleClassByPid($currentId);
		$articleClassIds[] = $currentId;
		
		$condition = array(
			'where' => array(
				'article_show' => 1,
			),
			'where_in' => array(
				array('key' => 'ac_id' , 'value' => $articleClassIds )
			),
			'order' => 'article_id DESC',
			'limit' => 8
		);
		
		$condition = array_merge($condition,$moreCondition);
		
		return $this->_articleModel->getList($condition);
	}
}
