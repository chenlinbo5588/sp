<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class CmsArticleStatus
{
	//草稿
	public static $draft = 1;
	
	//待审核
	public static $unverify = 2;
	
	//已审核
	public static $verify = 3;
	
	//已发布
	public static $published = 4;
	
	//回收站
	public static $recylebin = 5;
	
	
	public static $statusName = array(
		1 => '草稿',
		2 => '待审核',
		3 => '已审核',
		4 => '已发布',
		5 => '回收站',
	);
}



class Cms_service extends Base_service {
	
	private $_cmsArticleModel = null;
	private $_cmsArticleClassModel = null;
	
	private $_parentList = array();
	
	
	
	//列表
	public static $classData = array();
	
	//原始树
	public static $classDataTree = array();
	
	//键树
	public static $classAssocDataTree = array();
	
	

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Cms_Article_Model', 'Cms_Article_Class_Model'));
		
		$this->_cmsArticleModel = self::$CI->Cms_Article_Model;
		$this->_cmsArticleClassModel = self::$CI->Cms_Article_Class_Model;
		
		
		
		self::$classData = $this->_cmsArticleClassModel->getList(array(),'id');
		self::$CI->phptree->resetData();
		
		
		if(self::$classData){
			
			self::$classDataTree = self::$CI->phptree->makeTree(self::$classData,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => true
			));
			
			$this->makeAssocData(self::$classDataTree,self::$classAssocDataTree);
		}
		
	}
	
	
	
	/**
	 * 转换成键值的数组
	 */
	public function makeAssocData($tempAllData,&$destData){
		foreach($tempAllData as $basicDataItem){
			$destData[$basicDataItem['name']] = array(
				'id' => $basicDataItem['id'],
				'name' => $basicDataItem['name'],
				'ac_sort' => $basicDataItem['ac_sort'],
				'pid' => $basicDataItem['pid'],
			);
			
			if($basicDataItem['children']){
				$destData[$basicDataItem['name']]['children'] = array();
				$this->makeAssocData($basicDataItem['children'],$destData[$basicDataItem['name']]['children']);
			}
		}
	}
	
	/**
	 * 返回键树
	 */
	public static function getAssocDataTree(){
		return self::$classAssocDataTree;
	}
	
	
	
	public function getArticleClassTreeHTML(){
		$list = $this->_cmsArticleClassModel->getList();
		
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
	
	public function getArticleClassTree(){
		$list = $this->_cmsArticleClassModel->getList();
		
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
	
	/**
	 * 删除 cms 数据
	 */
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
	
	
	
	/**
	 * 检查
	 */
	public function checkpid($pid, $extra = ''){
		
		
		//不能是自己，也不能是其下级分类
		$currentId = self::$CI->input->post('id');
		
		$deep = $this->getArticleClassDeepById($pid);
		
		if($deep >=3){
			self::$CI->form_validation->set_message('checkpid_callable','父级只能是一级分类或者二级分类');
			return false;
		}
		
		if($extra == 'add'){
			//如果是增加的不需要再网后面继续执行了
			return true;
		}
		
		
		$list = $this->_cmsArticleClassModel->getList(array(
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
				$list = $this->_cmsArticleClassModel->getList(array(
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
	 * 更改状态
	 */
	public function changeArticleStatus($pIds,$statusDest,$statusCurrent,$extraData = array()){
		return $this->_cmsArticleModel->updateByCondition(array_merge(array(
			'article_state' => $statusDest
		),$extraData),array(
			'where' => array(
				'article_state' => $statusCurrent
			),
			'where_in' => array(
				array('key' => 'id', 'value' => $pIds)
			)
		));
	}
	
	
	
	
	/**
	 * 审核
	 */
	public function articleVerify($param ,$when, $who){
		$updateData = array(
			'verify_reason' => $param['reason']
		);
		
		switch($param['op']){
			case '审核通过':
				$updateData['article_state'] = CmsArticleStatus::$verify;
				$updateData = array_merge($updateData,$who);
				$updateData['verify_time'] = $when;
				break;
			case '退回':
				$updateData['article_state'] = CmsArticleStatus::$draft;
				break;
			default:
				break;
		}
		
		return $this->_cmsArticleModel->updateByCondition($updateData,array(
			'where_in' => array(
				array('key' => 'id', 'value' => $param['id'])
			)
		));
	}
	
}
