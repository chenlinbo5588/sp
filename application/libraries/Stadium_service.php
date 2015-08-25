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
    
    
    
	/**
	 * 获得活动分类
	 */
	public function getSportsCategory($condition = array())
	{
		return $this->_sportsCategoryModel->getList($condition);
	}
	
	/**
	 * 获得场馆信息
	 */
	public function getStadiumInfo($id){
		$info['basic'] = $this->_stadiumModel->getById(array(
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
     * 添加体育场馆
     */
    public function addStadium($param,$images , $user){
    	
    	$this->_stadiumModel->transStart();
    	
    	$addParam = array(
    		'category_id' => $param['category_id'],
    		'category_name' => $param['category_name'],
    		'title' => $param['title'],
    		'address' => $param['address'],
    		'longitude' => $param['longitude'],
    		'latitude' => $param['latitude'],
    		'ground_type' => $param['ground_type'],
    		'stadium_type' => $param['stadium_type'],
    		'charge_type' => $param['charge_type'],
    	);
    	
    	if(!empty($param['remark'])){
    		$addParam['remark'] = cutText($param['remark'],60);
    	}
    	
    	if($param['is_mine'] == 'y'){
    		$addParam['mobile'] = $user['mobile'];
    		$addParam['contact'] = empty($user['username']) == true ? $user['nickname'] : $user['username'];
    	}else{
    		$addParam['mobile'] = empty($param['mobil']) == true ? '' : $param['mobil'];
    		$addParam['contact'] = empty($param['contact']) == true ? '' : $param['contact'];
    	}
    	
    	//浙江省, 宁波市, 慈溪市, 孙塘南路, 126-～130
    	$addressInfo = array();
    	$addressCode = array();
    	if(!empty($param['province'])){
    		$addParam['dname1'] = $addressInfo['level1'] = trim($param['province']);
    	}
    	
    	if(!empty($param['city'])){
    		$addParam['dname2'] = $addressInfo['level2'] = trim($param['city']);
    	}
    	
    	if(!empty($param['district'])){
    		$addParam['dname3'] = $addressInfo['level3'] = trim($param['district']);
    	}
    	
    	if(!empty($param['street'])){
    		$addParam['dname4'] = $addressInfo['level4'] = trim($param['street']);
    	}
    	
    	if(!empty($param['street_number'])){
    		$addParam['street_number'] = trim($param['street_number']);
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
    				$addParam['d'.$dname['level']] = $dname['id'];
    			}
    		}
    	}
    	
    	if($images[0]['id']){
    		$addParam['avatar'] = $images[0]['avatar'];
    		$addParam['avatar_large'] = $images[0]['avatar_large'];
    		$addParam['avatar_big'] = $images[0]['avatar_big'];
    		$addParam['avatar_middle'] = $images[0]['avatar_middle'];
    		$addParam['avatar_small'] = $images[0]['avatar_small'];
    	}
    	
    	$stadiumId = $this->_stadiumModel->_add($addParam);
    	
    	if ($this->_stadiumModel->transStatus() === FALSE){
			$this->_stadiumModel->transRollback();
			return false;
		}
		
		//插入图片
		$now = time();
		$insertImage = array();
		foreach($images as $img){
			$insertImage[] = array(
				'aid' => $img['id'],
				'stadium_id' => $stadiumId,
				'gmt_create' => $now,
				'gmt_modify' => $now
			);
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
	
}
