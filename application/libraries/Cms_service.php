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
            'id' => $id
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
						array('key' => 'id', 'value' => $ids)
					)
				));
			}
		}
		
		$this->_cmsArticleClassModel->deleteByWhere(array('id' => $delId));
		
	}
	
	public function getArticleClassByParentId($id = 0){
		$list = $this->_cmsArticleClassModel->getList(array(
			'where' => array('pid' => $id),
			'order' => 'ac_sort ASC'
		));
		
		return $this->toEasyUseArray($list,'id');
	}
	
	
	
	public function getNextByArticle($article){
		$article = $this->_cmsArticleModel->getList(array(
			'where' => array('ac_id' => $article['ac_id'], 'id >' => $article['id'] , 'article_state' => 3),
			'order' => 'id ASC',
			'limit' => 1
		));
		
		if($article[0]){
			return $article[0];
		}else{
			return false;
		}
	}
	
	
	public function getPreByArticle($article){
		$article = $this->_cmsArticleModel->getList(array(
			'where' => array('ac_id' => $article['ac_id'], 'id <' => $article['id'] , 'article_state' => 3),
			'order' => 'id DESC',
			'limit' => 1
		));
		
		 //print_r($article);
		
		if($article[0]){
			return $article[0];
		}else{
			return false;
		}
	}
	
	
	/**
	 * 推荐列表
	 */
	public function getCommandArticleList($currentId,$moreCondition = array('limit' => 8)){
		
		$articleClassIds = $this->getAllChildArticleClassByPid($currentId);
		$articleClassIds[] = $currentId;
		
		$condition = array(
			'where' => array(
				'article_state' => 3,
			),
			'where_in' => array(
				array('key' => 'ac_id' , 'value' => $articleClassIds )
			),
			'order' => 'id DESC',
			'limit' => 8
		);
		
		$condition = array_merge($condition,$moreCondition);
		
		return $this->_cmsArticleModel->getList($condition);
	}
}
