<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Lab 服务
 */
class Lab_service extends Base_service {
	
	private $_labModel = null;
	private $_labMemberModel = null;
	private $_labCacheModel = null;
	
	private $_cacheXMLUserKey = "labxml_{uid}";
	private $_cacheXMLGroupKey = "labxml";

	public function __construct(){
		parent::__construct();
		self::$CI->load->model(array('Lab_Model','Lab_Member_Model','Lab_Cache_Model'));
		
		$this->_labModel = self::$CI->Lab_Model;
		$this->_labMemberModel = self::$CI->Lab_Member_Model;
		$this->_labCacheModel = self::$CI->Lab_Cache_Model;
		
		
	}
	
	
	/**
	 * 获得用户当前 所在的 实验室列表
	 */
	public function getUserLabsByUID($uid){
		return $this->_labMemberModel->getList(array(
    		'select' => 'lab_id,is_manager',
			'where' => array(
				'user_id' => $uid
			)
    	));
		
	}
	
	
	
	/**
	 * 添加实验室
	 */
	public function addLab($param,$userInfo){
		
		$param['displayorder'] = empty($param['displayorder']) ? 255 : intval($param['displayorder']);
		$param['creator'] = $userInfo['name'];
		
		$labId = $this->_labModel->_add($param);
		
		$labMember = array(
			'user_id' => $userInfo['id'],
			'lab_id' => $labId,
			'is_manager' => 'y',
			'uid' => $userInfo['id'],
			'creator' => $param['creator'],
			'updator' => $param['creator'],
		);
		
		$labMember = array_merge($labMember,$this->addWhoHasOperated($userInfo));
		
		
		$this->_labMemberModel->_add($labMember,true);
		
		return	$labId;
	}
	
	
	/**
	 * 缓存过期
	 */
	public function makeLabXMLExpire(){
    	$this->_labCacheModel->update(array('expire' => -1),array('key_group' => $this->getXMLGroupKey()));
    }
	
	
	/**
	 * 获得用户 lab 管理的lab  以及用户所在lab 的用户列表
	 */
	public function getUserLabsAssoc($uid){
		$userLabs = $this->getUserLabsByUID($uid);
		
		//用户所在的 lab 列表
		$labIds = array('0' => 0);
		
		//用户有管理权限的 lab 列表
    	$userManagerLabs = array();
		//
    	$userIds = array(0);
    	
		foreach($userLabs as $lab){
    		$labIds[$lab['lab_id']] = $lab['lab_id'];
    		
    		if(strtolower($lab['is_manager']) == 'y'){
    			$userManagerLabs[] = $lab['lab_id'];
    		}
    	}
    	
    	
    	
    	/**
         * 取出 lab 列表中 所有的用户 id
         */
        if(LAB_FOUNDER_ID != $uid){
        	$users = $this->_labMemberModel->getList(array(
	        	'select' => 'user_id',
	        	'where_in' => array(
	        		array('key' => 'lab_id', 'value' => array_keys($labIds))
	        	)
	        ));
	        
	        foreach($users as $u){
	        	$userIds[] = $u['user_id'];
	        }
        }
        
    	$userIds = array_unique($userIds);
    	
    	return  array(
    		'user_labs' => $labIds,	//用户所在的实验室
    		'user_manager_labs' => $userManagerLabs,//用户管理的实验室
    		'user_ids' => $userIds,   //lab 列表中 所有的用户
    	);
    	
	}
	
	/**
	 * 删除用户的实验室
	 */
	public function deleteUserLab($userId,$lab_id,$operator = array()){
		$this->_labModel->clearMenuTree();
		
		//获得子孙实验室
		$subIds = $this->_labModel->getListByTree($lab_id);
		
		$ids = array();
		$ids[] = $lab_id;
		if($subIds){
			foreach($subIds as $item){
				$ids[] = $item['id'];
			}
		}
		
		$ids = array_unique($ids);
		
		/**
		 * 查询是否是 这个实验室的管理管理员, 如果不是这个实验室的管理员
		 * 不能进行删除
		 */
		if(LAB_FOUNDER_ID != $userId){
			if(!$this->getLabManager($userId,$lab_id)){
				return $this->formatArrayReturn('failed','对不起，您不是这个实验室的管理员，无权删除');
    		}
		}
		
		//原来不删除,现在直接删除
		$this->_labModel->updateByCondition(
			array('status' => '已删除','updator' => $operator['name']),
			array(
				'where_in' => array(
					array('key' => 'id' , 'value' => $ids)
			)
		));
		
		if($ids){
			//
			$this->_labMemberModel->deleteAllUserByLabs($ids);
			
			/**
			 * @todo 优化，后期可以考虑后台定时清理
			 */
			 
			/*
			// 性能考虑 不自动清理了，
			$this->load->model('Goods_Model');
			$this->Goods_Model->deleteByLabIds($ids);
			$this->_labModel->deleteByCondition(array(
	    		'where_in' => array(
	    			array('key' => 'id' , 'value' => $ids)
	    		)
	    	
	    	));
	    	*/
		}
		
		return $this->formatArrayReturn('success');
		
	}
	
	
	
	/**
	 * 获得用户lab XML
	 */
	public function getUserLabXML($uid = 0){
		
		$str = array();
		
		$str[] = '<?xml version="1.0" ?><tree id="0">';
   		$list = array();
   		
   		$condition = array(
   			'where' => array(
   					'status' => '正常'
   			),
   			'order' => 'pid ASC , displayorder DESC'
   		);
   		
   		if($uid && $uid != LAB_FOUNDER_ID){
   			$labs = self::$CI->session->userdata('user_labs');
   			
   			if($labs){
   				$condition['where_in'] = array(
	   				array('key' => 'id', 'value' => $labs)
	   			);
   			}
   		}
   		
   		$list = $this->_labModel->getList($condition);
		$tree = array();
		
		
		//获得 祖先，才能将树构建起来 重要
		foreach($list as $node){
			$tree = $this->_labModel->getParents($node['id']);
		}
		
		$tree = self::$CI->phptree->makeTree($tree,array(
			'primary_key' => 'id',
			'parent_key' => 'pid',
			'expanded' => true
		));
		
   		$str[] = $this->_labModel->toXML($tree);
   		$str[] = '</tree>';
   		
   		return implode('',$str);
	}
	
	/**
	 * 缓存用户 Lab XML
	 */
	public function cacheUserLabXML($uid,$xml){
		return $this->_labCacheModel->_add($data = array(
            'key_id' => $this->getCacheXMLUserKey($uid),
            'key_group' => $this->getXMLGroupKey(),
			'content' => $xml,
        ),true);
	}
	
	
	public function getXMLGroupKey(){
    	return $this->_cacheXMLGroupKey;
    }
    
    /**
     * 用户 Lab XML Key
     */
    public function getCacheXMLUserKey($user_id){
    	return str_replace('{uid}',$user_id,$this->_cacheXMLUserKey);
    }
    
    
    /**
     * 获得用户 是管理员的实验室
     */
    public function getLabManager($user_id,$lab_id){
    	if(!is_array($lab_id)){
    		$lab_id = (array)$lab_id;
    	}
    	
    	return $this->_labMemberModel->getList(array(
    		'where' => array(
    			'is_manager' => 'y',
    			'user_id' => $user_id
    		),
    		'where_in' => array(
    			array('key' => 'lab_id' , 'value' => $lab_id)
    		)
    	));
    }
    
    /**
     * 获得 某一个 lab 的成员列表
     * 
     */
     public function getLabMemberList($lab_id){
		
		$userIds = array();
		$users = array();
		
		$userList = $this->_labeMemberModel->getLabUserList($lab_id);
		
		if($userList){
			foreach($userList as $user){
				$userIds[$user['user_id']] = $user;
			}
		}
		
		if($userIds){
			$users = self::$adminUserModel->getList(array(
				'where_in' => array(
					array(
						'key' => 'id','value' => array_keys($userIds)
					)
				),
				'order' => 'id ASC '
			));
			foreach($users as $uk => $user){
				$userIds[$user['id']]['name'] = $user['name'];
			}
		}
		
		return $userIds;
	}
	
	/**
	 * 
	 */
	public function getUserLabList($user_id){
    	return $this->_labMemberModel->getList(array(
    		'where' => array(
    			'user_id' => $user_id
    		)
    	));
    }
}
