<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium_Service extends Base_Service {

    private $_stadiumModel;
    private $_stadiumMetaModel;
    private $_stadiumPhotosModel;
    //private $_stadiumAlbumsModel;
	
	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Stadium_Model');
		$this->CI->load->model('Stadium_Meta_Model');
		$this->CI->load->model('Stadium_Photos_Model');
		//$this->CI->load->model('Stadium_Albums_Model');
		
        $this->CI->load->model('Sports_Category_Model');
        
        $this->_stadiumModel = $this->CI->Stadium_Model;
        $this->_stadiumMetaModel = $this->CI->Stadium_Meta_Model;
        $this->_stadiumPhotosModel = $this->CI->Stadium_Photos_Model;
        //$this->_stadiumAlbumsModel = $this->CI->Stadium_Albums_Model;
        $this->_sportsCategoryModel = $this->CI->Sports_Category_Model;
	}
    
    public function getAllMetaGroups(){
        
        $all = $this->_stadiumMetaModel->getList(array(
        	'where' => array(
        		'status >= ' => 0
        	),
            'order' => 'gname ASC ,id ASC'
            
        ));
        
        $data = array();
        
        foreach($all as $key => $item){
            if(!isset($data[$item['gname']])){
                $data[$item['gname']] = array();
            }
            
            $data[$item['gname']][] = $item;
        }
        
        return $data;
    }
    
    
    public function getStadiumMeta($condition = array()){
    	$all = $this->_stadiumMetaModel->getList($condition);
        
        return $all;
    	
    }
    
    
	/**
	 * 获得活动分类
	 */
	public function getSportsCategory($condition = array())
	{
		$condition['where']['status'] = 0;
		return $this->_sportsCategoryModel->getList($condition);
	}
	
	/**
	 * 获得场馆信息
	 */
	public function getStadiumInfo($id){
		$info['basic'] = $this->_stadiumModel->getById(array(
			'where' => array('stadium_id' => $id)
		));
		
		//图片列表
		
		$info['photos'] = $this->_stadiumPhotosModel->getList(array(
			'where' => array('stadium_id' => $id)
		));
		
		
		return $info;
	}
	
	
	public function getPagerData($search = array(),$page = 1, $pageSize = 10)
	{
		$search['pager'] = array(
			'current_page' => $page,
			'page_size' => $pageSize
		);
		
		$data = $this->_stadiumModel->getList($search);
		
		
		return $data;
	}
	
	/**
	 * 馆长
	 */
	public function isManager($stadium,$userInfo){
		$canManager = false;
		
		$uid = $stadium['basic']['owner_uid'];
		if(!$uid){
			$uid = $stadium['basic']['reporter_uid'];
		}
		
		if($userInfo['uid'] == $uid){
			$canManager = true;
		}
		
		return $canManager;
	}
    
    /**
     * 获得开发程度
     */
    public function getOpenType(){
		return array(
			'1' => '完全对外开放',
			'2' => '部分设施对外开放',
			'3' => '仅对内部人员开放'
		);
	}
    
    /**
     * 解析 地址名称到 id
     */
    private function _getDistrictIdFromName($stadiumParam , $param){
    	
    	//浙江省, 宁波市, 慈溪市, 孙塘南路, 126-～130
    	$addressInfo = array();
    	if(!empty($param['province'])){
    		$stadiumParam['dname1'] = $addressInfo['level1'] = trim($param['province']);
    	}
    	
    	if(!empty($param['city'])){
    		$stadiumParam['dname2'] = $addressInfo['level2'] = trim($param['city']);
    	}
    	
    	if(!empty($param['district'])){
    		$stadiumParam['dname3'] = $addressInfo['level3'] = trim($param['district']);
    	}
    	
    	if(!empty($param['street'])){
    		$stadiumParam['dname4'] = $addressInfo['level4'] = trim($param['street']);
    	}
    	
    	if(!empty($param['street_number'])){
    		$stadiumParam['street_number'] = trim($param['street_number']);
    	}
    	
    	if(is_array($addressInfo)){
    		$dList = $this->_districtModel->getList(array(
    			'select' => 'id,name,level',
    			'where_in' => array(
    				array('key' => 'name' , 'value' => $addressInfo)
    			)
    		));
    		
    		foreach($dList as $dk => $dname){
    			if($dname['name'] == $addressInfo['level'.$dname['level']]){
    				$stadiumParam['d'.$dname['level']] = $dname['id'];
    			}
    		}
    	}
    	
    	return $stadiumParam;
    }
    
    /**
     * 维护场馆基本信息
     */
    private function _stadiumBasicInfo($param , $images , $user){
    	
    	$stadiumParam = array(
    		'owner_type' => $param['owner_type'],
    		'open_type' => $param['open_type'],
    		'title' => $param['title'],
    		'address' => $param['address'],
    		'longitude' => $param['longitude'],
    		'latitude' => $param['latitude'],
    		
    	);
    	
    	if(!empty($param['remark'])){
    		$stadiumParam['remark'] = cutText($param['remark'],60);
    	}
    	
    	if($param['is_mine'] == 'y'){
    		$stadiumParam['owner'] = $stadiumParam['contact'] = empty($user['username']) == true ? $user['nickname'] : $user['username'];
    		$stadiumParam['owner_uid'] = $user['uid'];
    		$stadiumParam['mobile'] = $user['mobile'];
    	}else{
    		$stadiumParam['contact'] = empty($param['contact']) == true ? '' : $param['contact'];
    		$stadiumParam['mobile'] = empty($param['mobil']) == true ? '' : $param['mobil'];
    		$stadiumParam['tel'] = empty($param['tel']) == true ? '' : $param['tel'];
    	}
    	
    	if($images['aid']){
    		$stadiumParam = array_merge($stadiumParam,$images);
    	}
    	
    	
    	return $stadiumParam;
    }
    
    /**
     * 添加体育场馆
     * 
     * @param  param  基本信息
     * @param  images 图片
     * @param  user   用户
     */
    public function addStadium($param,$images , $user){
    	
    	$this->_stadiumModel->transStart();
    	
    	$addParam = $this->_stadiumBasicInfo($param, $images[0],$user);
    	$addParam = $this->_getDistrictIdFromName($addParam,$param);
    	
    	$addParam['reporter'] = empty($user['username']) == true ? $user['nickname'] : $user['username'];
    	$addParam['reporter_uid'] = $user['uid'];
    	
    	$stadiumId = $this->_stadiumModel->_add($addParam);
    	
    	if ($this->_stadiumModel->transStatus() === FALSE){
			$this->_stadiumModel->transRollback();
			return false;
		}
		
		//插入图片
		$now = time();
		$insertImage = array();
		foreach($images as $ik => $img){
			$newImag = array();
			$newImag['stadium_id'] = $stadiumId;
			$newImag['aid'] = $img['aid'];
			$newImag['avatar_large'] = $img['avatar_large'];
			$newImag['avatar_big'] = $img['avatar_big'];
			$newImag['avatar_middle'] = $img['avatar_middle'];
			$newImag['gmt_create'] = $now;
			$newImag['gmt_modify'] = $now;
			
			$insertImage[] = $newImag;
		}
		
    	$this->_stadiumPhotosModel->batchInsert($insertImage);
    	
    	if ($this->_stadiumModel->transStatus() === FALSE){
			$this->_stadiumModel->transRollback();
			return false;
		}
    	
    	$this->_stadiumModel->transCommit();
		$this->_stadiumModel->transOff();
    	
    	return $stadiumId;
    	
    }
    
    /**
     * 编辑场馆
     * @param  stadium  场馆信息
     * @param  param  新提交的信息
     * @param  images 当前图片信息
     * @param  user   用户
     * 
     */
    public function editStadium($stadium,$param,$images,$user){
    	
    	$useTrans = false;
    	$stadiumId = $stadium['basic']['stadium_id'];
    	
    	$oldPhotos = array();
    	foreach($stadium['photos'] as $photo){
    		$oldPhotos[] = $photo['aid'];
    	}
    	
    	
    	$currentPhotos = array();
    	foreach($images as $photo){
    		$currentPhotos[] = $photo['aid'];
    	}
    	
    	sort($oldPhotos,SORT_NUMERIC);
    	sort($currentPhotos,SORT_NUMERIC);
    	
		if (implode(',',$currentPhotos) != implode(',',$oldPhotos)){
			$useTrans = true;
		}
    	
    	if($useTrans){
    		$this->_stadiumModel->transStart();
    	}
    	
    	$updateParam = $this->_stadiumBasicInfo($param, $images[0],$user);
    	$updateParam = $this->_getDistrictIdFromName($updateParam,$param);
    	
    	$rows = $this->_stadiumModel->update($updateParam , array('stadium_id' => $stadiumId));
    	
    		
    	if ($useTrans && $this->_stadiumModel->transStatus() === FALSE){
			$this->_stadiumModel->transRollback();
			return false;
		}
		
		
		$deletedRows = $this->_stadiumPhotosModel->deleteByWhere(array(
			'stadium_id' => $stadiumId
		));
		
		
		if ($useTrans && $this->_stadiumModel->transStatus() === FALSE){
			$this->_stadiumModel->transRollback();
			return false;
		}
		
		//插入图片
		$now = time();
		$insertImage = array();
		foreach($images as $ik => $img){
			$img['stadium_id'] = $stadiumId;
			$img['gmt_create'] = $now;
			$img['gmt_modify'] = $now;
			
			$insertImage[] = $img;
		}
		
    	$this->_stadiumPhotosModel->batchInsert($insertImage);
    	
    	if ($useTrans && $this->_stadiumModel->transStatus() === FALSE){
			$this->_stadiumModel->transRollback();
			return false;
		}
    	
    	if($useTrans){
    		$this->_stadiumModel->transCommit();
			$this->_stadiumModel->transOff();
    	}
    	
    	return $rows;
    	
    }
	
}
