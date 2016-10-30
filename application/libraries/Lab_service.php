<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Lab 服务
 */
class Lab_service extends Base_service {
	
	private $_orginationModel = null;
	private $_labModel = null;
	private $_labGcateModel = null;
	private $_labMemberModel = null;
	private $_labCacheModel = null;
	private $_labRoleModel = null;
	private $_labMeasureModel = null;
	private $_labGoodsModel = null;
	
	//组织架构hash, 使得不同的企业能够分离散存储,降低并发读写冲突
	private $_orginationHashObject = null;
	
	//
	private $_labHashObject = null;
	
	private $_hashLookup = false;

	public function __construct(){
		parent::__construct();
		self::$CI->load->model(array(
			'Orgination_Model','Lab_Model','Lab_Gcate_Model','Lab_Member_Model',
			'Lab_Cache_Model','Lab_Role_Model','Lab_Fn_Model','Lab_Measure_Model',
			'Lab_Goods_Model'
		));
		
		$this->_orginationModel = self::$CI->Orgination_Model;
		$this->_labModel = self::$CI->Lab_Model;
		$this->_labGcateModel = self::$CI->Lab_Gcate_Model;
		$this->_labMemberModel = self::$CI->Lab_Member_Model;
		$this->_labCacheModel = self::$CI->Lab_Cache_Model;
		$this->_labRoleModel = self::$CI->Lab_Role_Model;
		$this->_labMeasureModel = self::$CI->Lab_Measure_Model;
		$this->_labGoodsModel = self::$CI->Lab_Goods_Model;
	}
	
	
	/**
	 * 获得 lab hash object ,lab , lab_member ,lab_cache , lab_goods, lab_gcate ,labe_measure 统一水平分布
	 */
	public function getLabHashObj(){
		if(!$this->_labHashObject){
			$this->_labHashObject = new Flexihash();
			$this->_labHashObject->addTargets(self::$CI->load->get_config('split_lab'));
		}
		return $this->_labHashObject;
	}
	
	/**
	 * 获得 组织架构 hash object 
	 */
	public function getOrginationHashObject(){
		if(!$this->_orginationHashObject){
			$this->_orginationHashObject = new Flexihash();
			$this->_orginationHashObject->addTargets(self::$CI->load->get_config('split_orgination'));
		}
		return $this->_orginationHashObject;
	}
	
	/**
	 * 机构根据用户id 分表
	 */
	public function setOrginationTableByUid($uid){
		$tableId = $this->getOrginationHashObject()->lookup($uid);
		$this->_orginationModel->setTableId($tableId);
	}
	
	
	/**
	 * 根据 oid 分表
	 */
	public function setLabTablesByOrgination($oid){
		
		if(!$this->_hashLookup){
			$tableId = $this->getLabHashObj()->lookup($oid);
		
			$this->_labModel->setTableId($tableId);
			$this->_labGcateModel->setTableId($tableId);
			$this->_labMemberModel->setTableId($tableId);
			$this->_labCacheModel->setTableId($tableId);
			$this->_labRoleModel->setTableId($tableId);
			$this->_labMeasureModel->setTableId($tableId);
			$this->_labGoodsModel->setTableId($tableId);
			
			$this->_hashLookup = true;
		}
	}
	
	
	
	
	/**
	 * 新注册用户默认就是个组织架构
	 */
	public function addOrgination($name,$uid,$oid,$role_id = 0, $default = 0,$replace = false){
		
		$this->setOrginationTableByUid($uid);
		
		return $this->_orginationModel->_add(array(
			'uid' => $uid,
			'oid' => $oid,
			'name' => $name,
			'is_default' => $default,
			'role_id' => $role_id,
		),$replace);
	}
	
	public function updateOrgination($data,$uid,$oid){
		$this->setOrginationTableByUid($uid);
		return $this->_orginationModel->update($data,array('uid' => $uid,'oid' => $oid));
	}
	
	
	/**
	 * 根据用户获得 用户加入到机构列表
	 */
	public function getOrginationByCondition($condition , $uid){
		$this->setOrginationTableByUid($uid);
		return $this->_orginationModel->getList($condition);
	}
	
	
	/**
	 * 获得用户的机构列表
	 */
	public function getMemberOrginationList($uid){
		
		$userOrginationList = $this->getOrginationByCondition(array(
			'where' => array(
				'uid' => $uid
			)
		),$uid);
		
		if($userOrginationList){
			$keysList = array();
			$defaultItem = array();
			
			foreach($userOrginationList as $item){
				$keysList[$item['oid']] = $item;
				if($item['is_default'] == 1){
					$defaultItem = $item;
				}
			}
			
			return array(
				'list' => $keysList,
				'current' => $defaultItem,
			);
			
			//$this->session->set_userdata(array('orgination' => $keysList,'current_oid' => $defaultItem['oid']));
			//$this->assign('currentOrgination',$defaultItem);
		}else{
			
			return array();
		}
	}
	
	
	/**
	 * 初始话新的成员的 关联记录
	 * 
	 * 新用户需要初始化4条相关记录
	 */
	public function initNewMemberLab($memberInfo){
		
		$this->addOrgination($memberInfo['username'].'的实验中心',$memberInfo['uid'],$memberInfo['uid'],0,1,true);
		$this->setLabTablesByOrgination($memberInfo['uid']);
		
		
		$who = $this->addWhoHasOperated('add',$memberInfo);
		$lab = array(
			'name' => $memberInfo['username'].'实验室中心',
			'address' => '',
			'oid' => $memberInfo['uid']
		);
		
		
		$labId = $this->_labModel->_add(array_merge($lab,$who));
		
		$labMember = array(
			'uid' => $memberInfo['uid'],
			'oid' => $memberInfo['uid'],
			'lab_id' => $labId,
			'is_manager' => 'y'
		);
		
		$this->_labMemberModel->_add(array_merge($labMember,$who));
		$this->_labCacheModel->_add(array(
			'uid' => 0,
			'oid' => $memberInfo['uid'],
			'group_name' => 'treexml'
		));
	}
	
	
	
	//---------------------------------以下 Lab 相关  ---------------------------------
	
	/**
	 * 添加实验室, 根据用户id  分表存储 lab 
	 * 
	 * 然后再安装 返回的 lab_id 存储 lab member
	 */
	public function addLab($param,$userInfo,$oid){
		$param['displayorder'] = empty($param['displayorder']) ? 255 : intval($param['displayorder']);
		$param['oid'] = $oid;
		
		$who = $this->addWhoHasOperated('add',$userInfo);
		$labId = $this->_labModel->_add(array_merge($param,$who));
		
		$labMember = array(
			'uid' => $userInfo['uid'],
			'oid' => $oid,
			'lab_id' => $labId,
		);
		
		$this->_labMemberModel->_add(array_merge($labMember,$who),true);
		
		
		
		return	$labId;
	}
	
	
    /**
     * 使得缓存过期
     */
    public function expireCacheByCondition($data,$condition){
    	return $this->_labCacheModel->updateByCondition($data,$condition);
    }
	
	
	/**
	 * 删除用户的实验室
	 */
	public function deleteUserLab($lab_id,$oid,$ts){
		$rows = $this->_labModel->updateByWhere(
			array(
				'status' => -1
			),
			array(
				'id' => $lab_id
			)
		);
		
		return $this->_labCacheModel->updateByWhere(array(
			'gmt_modify' => $ts
		),array(
			'uid' => 0,
			'oid' => $oid,
			'group_name' => 'treexml',
		));
	}
	
	
	
	/**
	 * 
	 */
	public function getUserJoinedLabListByOrgination($uid,$oid,$fields = 'lab_id,is_manager'){
		return $this->_labMemberModel->getList(array(
   			'select' => $fields,
   			'where' => array(
   				'uid' => $uid,
   				'oid' => $oid,
   			)
   		));
	}
	
	
	
	
	/**
	 * 获得用户拥有的节点
	 */
	public function getUserOwnedLabs($uid,$oid){
		$list = array();
   		
   		$joinLabs = $this->getUserJoinedLabListByOrgination($uid,$oid);
   		$condition = array(
   			'where' => array(
				'oid' => $oid,
   			),
   			'order' => 'pid ASC , displayorder ASC'
   		);
   		
   		
   		if($uid != $oid){
   			/** 非创始人 */
   			$labIds = array();
   			foreach($joinLabs as $lab){
   				$labIds[] = $lab['lab_id'];
   			}
   			
   			
   			if($labIds){
   				$condition['where_in'] = array(
	   				array('key' => 'id', 'value' => $labIds)
	   			);
   			}
   		}
   		
   		return $this->_labModel->getList($condition);
	}
	
	
	/**
	 * 获取用户某个机构下的加入的实验室列表
	 */
	public function getUserOwnedLabsHTML($uid,$oid){
		
		$tree = $this->makeNodeReachable($this->getUserOwnedLabs($uid,$oid));
		
		return self::$CI->phptree->makeTreeForHtml($tree,array(
			'primary_key' => 'id',
			'parent_key' => 'pid',
			'expanded' => true
		));
		
	}
	
	/**
	 * 使得节点相关关联
	 */
	public function makeNodeReachable($nodeList){
		$tempTree = array();
		
		$this->_labModel->_parentList = array();
		
		
		if($nodeList){
			//获得 祖先，才能将树构建起来 重要
			//由于是递归产生，始终返回的是 _parentList
			foreach($nodeList as $node){
				$tempTree = $this->_labModel->getParents($node['id']);
			}
		}
		
		return $tempTree;
	}
	
	
	/**
	 * 
	 */
	public function getTreeXML($uid,$oid,$group_name = 'treexml'){
		
		
   		/**
   		 * 与总的树比较 更新的时间点,整课树形
   		 * uid = 0 表示该机构下的总树xml缓存
   		 */
   		$fullTreeCache = $this->_labCacheModel->getById(array(
   			'select' => 'expire,gmt_modify',
   			'where' => array(
   				'uid' => 0,
   				'oid' => $oid,
   				'group_name' => $group_name
   			)
   		));
   		
   		$cacheExpire = false;
   		
   		if($uid){
   			//按用户缓存的
   			$cache = $this->_labCacheModel->getById(array(
	   			'where' => array(
	   				'uid' => $uid,
	   				'oid' => $oid,
	   				'group_name' => $group_name
	   			)
	   		));
	   		
	   		
	   		if(empty($cache)){
	   			$cacheExpire = true;
	   		}else{
	   			//先与整课树比较是否已经过期
	   			if($fullTreeCache['gmt_modify'] > $cache['gmt_modify']){
	   				$cacheExpire = true;
	   			}
	   			
	   			if(!$cacheExpire && $cache['expire'] < 0){
					$cacheExpire = true;
				}
	   		}
	   		
   		}else{
   			//全局缓存
   			if(empty($fullTreeCache)){
   				$cacheExpire = true;
   			}else{
   				if($fullTreeCache['expire'] < 0){
   					$cacheExpire = true;
   				}
   			}
   			
   			if(!$cacheExpire){
   				$cache = $fullTreeCache;
   			}
   		}
   		
   		if(!$cacheExpire){
   			return $cache['content'];
   		}else{
   			$output = $this->getUserLabXML($uid,$oid);
   			
   			$this->cacheUserLabXML($uid,$oid,$output,$group_name);
   			return $output;
   		}
	}
	
	
	/**
	 * 获得用户某一个实验室的 XML 
	 */
	public function getUserLabXML($uid,$oid){
		
		$str = array();
		$str[] = '<?xml version="1.0" ?><tree id="0">';
   		
		$tree = $this->makeNodeReachable($this->getUserOwnedLabs($uid,$oid));
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
	public function cacheUserLabXML($uid,$oid,$xml,$groupName = 'treexml'){
		return $this->_labCacheModel->_add(array(
			'uid' => $uid,
			'oid' => $oid,
			'content' => $xml,
			'group_name' => $groupName
        ),true);
	}
	
    /**
     * 是否是管理员
     */
    public function isLabManager($uid,$labId,$oid){
    	
   		$isLabManager = false;
   		
   		if($uid == $oid){
			return true;
		}
		
		/**
		 * 父级只要出现有是管理员的 就有权限删除
		 */
		$list = $this->_labModel->getParents($labId);
		
		$ids = array_keys($list);
		$ids[] = $labId;
		
		if(!$isLabManager && $this->getLabManager($uid,$ids,$oid)){
			$isLabManager = true;
		}
   		
   		return $isLabManager;
   	}
    
    
    /**
     * 获得用户 是管理员的实验室
     */
    public function getLabManager($uid,$lab_id,$oid){
    	if(!is_array($lab_id)){
    		$lab_id = (array)$lab_id;
    	}
    	
    	return $this->_labMemberModel->getList(array(
    		'where' => array(
    			'is_manager' => 'y',
    			'uid' => $uid,
    			'oid' => $oid
    		),
    		'where_in' => array(
    			array('key' => 'lab_id' , 'value' => $lab_id)
    		)
    	));
    }
    
    
    /**
     * 根据条件 获得成员列表 并将附加信息 添加进来
     */
    public function getLabMemberListByCondition($condition,$searchCondition,$oid){
    	
    	
    	if($searchCondition){
    		$userList = self::$memberModel->getList($searchCondition,'uid');
    		
    		if(empty($userList)){
    			$condition['where']['1 = 2'] = null;
    		}else{
    			$conditionp['where_in'][] = array('key' => 'uid' , 'value' => array_keys($userList));
    		}
    	}
    	
    	$data = $this->_labMemberModel->getList($condition);
    	
        $uids = array();
        $labIds = array();
        
        $memberList = array();
        $labList = array();
            
		foreach($data['data'] as $user){
			$roles[] = $user['role_id'];
			$uids[] = $user['uid'];
			$labIds[] = $user['lab_id'];
		}
		
		$uids = array_unique($uids);
		if($uids){
			$memberList = self::$memberModel->getList(
				array(
					'select' => 'uid,username,mobile,qq,email',
					'where_in' => array(
						array('key' => 'uid','value' => $uids)
					)
				),'uid'
			);
		}
		
		$labIds = array_unique($labIds);
		if($labIds){
			$labList = $this->_labModel->getList(
				array(
					'select' => 'id,name,address',
					'where_in' => array(
						array('key' => 'id','value' => $labIds)
					)
				),'id'
			);
		}
		
    	return array('list' => $data,'member' => $memberList,'lab' => $labList);
    }
    
    
    //--------------------------------------以下角色相关---------------------------------
    
    
}
