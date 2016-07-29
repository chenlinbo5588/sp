<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium_service extends Base_service {

    private $_stadiumModel;
    private $_stadiumMetaModel;
    private $_stadiumPhotosModel;
    //private $_stadiumAlbumsModel;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Stadium_Model');
		self::$CI->load->model('Stadium_Meta_Model');
		self::$CI->load->model('Stadium_Photos_Model');
		//self::$CI->load->model('Stadium_Albums_Model');
        
        $this->_stadiumModel = self::$CI->Stadium_Model;
        $this->_stadiumMetaModel = self::$CI->Stadium_Meta_Model;
        $this->_stadiumPhotosModel = self::$CI->Stadium_Photos_Model;
        //$this->_stadiumAlbumsModel = self::$CI->Stadium_Albums_Model;
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
	 * 获得场馆信息
	 */
	public function getStadiumInfo($id){
		$info['basic'] = $this->_stadiumModel->getById(array(
			'where' => array('id' => $id)
		));
		
		//图片列表
		
		$info['photos'] = $this->_stadiumPhotosModel->getList(array(
			'where' => array('id' => $id)
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
			'1' => '完全开放',
			'2' => '部分开放',
			'3' => '仅对内部开放'
		);
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
	 * 公共规则
	 */
	public function commonStadiumRules(){
		
		//位置信息
		
		$param['longitude'] = self::$CI->input->post('longitude');
		$param['latitude'] = self::$CI->input->post('latitude');
		$param['dname1'] = self::$CI->input->post('province');
		$param['dname2'] = self::$CI->input->post('city');
		$param['dname3'] = self::$CI->input->post('district');
		$param['dname4'] = self::$CI->input->post('street');
		$param['street_number'] = self::$CI->input->post('street_number');
		
		
		$param['name'] = self::$CI->input->post('name');
		$param['full_name'] = self::$CI->input->post('full_name');
		$param['address'] = self::$CI->input->post('address');
		$param['owner_username'] = self::$CI->input->post('owner_username');
		$param['contact'] = self::$CI->input->post('contact');
		$param['mobile'] = self::$CI->input->post('mobile');
		$param['mobile2'] = self::$CI->input->post('mobile2');
		$param['tel'] = self::$CI->input->post('tel');
		$param['open_type'] = self::$CI->input->post('open_type');
		$param['owner_type'] = self::$CI->input->post('owner_type');
		
		$param['remark'] = self::$CI->input->post('remark');
		$param['status'] = self::$CI->input->post('status');
		$param['displayorder'] = self::$CI->input->post('displayorder');
		$param['aid'] = self::$CI->input->post('aid');
		$param['avatar'] = self::$CI->input->post('avatar');
		
		foreach($param as $pk => $pv){
			$param[$pk] = trim($pv) ? trim($pv) : '';
		}
		
		$arrayValue = array('category_name' => '场馆类型','ground_type' => '地面材质','charge_type' => '收费类型','support_sports' => '支持的运动项目');
		foreach($arrayValue as $ke => $val){
			
			$tempAr = self::$CI->input->post($ke);
			
			if(is_array($tempAr)){
				$param[$ke] = implode(',',$tempAr);
			}else{
				$param[$ke] = '';
			}
			
			self::$form_validation->set_rules("{$ke}[]",$val, 'required');
		}
		
		self::$form_validation->set_rules('name','场馆名称', 'required|max_length[30]');
		
		if($param['full_name']){
			self::$form_validation->set_rules('full_name','全称', 'required|max_length[40]');
		}
		
		self::$form_validation->set_rules('address','地址','required|max_length[100]');
		self::$form_validation->set_rules('owner_type','权属类型','required');
		self::$form_validation->set_rules('open_type','开放类型','required');
		
		if($param['owner_username']){
			self::$form_validation->set_rules('owner_username','权属人名称','required|max_length[30]');
		}
		
		if($param['contact']){
			self::$form_validation->set_rules('contact','联系人','required|max_length[10]');
		}
		
		if($param['mobile']){
			self::$form_validation->set_rules('mobile','联系人手机','required|valid_mobile');
		}
		
		if($param['mobile2']){
			self::$form_validation->set_rules('contact','联系人备用手机','required|valid_mobile');
		}
		
		self::$form_validation->set_rules('address','地址','required|max_length[100]');
		self::$form_validation->set_rules('avatar','主图','required|valid_url');
		
		if($param['status']){
			self::$form_validation->set_rules('remark','备注', 'required|max_length[60]');
		}
		
		if($param['status']){
			self::$form_validation->set_rules('status','审核状态', 'required|in_list[1,-1]');
		}else{
			unset($param['status']);
		}
		
		return $param;
	}
    
    /**
     * 增加场馆验证规则
     */
    public function stadiumAddRules(){
    	self::$form_validation->reset_validation();
    	
    	$param = $this->commonStadiumRules();
    	//self::$form_validation->set_rules('');
    	
    	return $param;
    }
    
    
    /**
     * 添加体育场馆
     * 
     * @param  param  基本信息
     * @param  images 图片
     * @param  user   用户
     */
    public function addStadium($param,$images = array(),$user = array()){
    	
    	self::$dbInstance->trans_start();
    	
    	$dIds = $this->getDistrictIdByNames(array(
			$param['dnam1'],
			$param['dnam2'],
			$param['dnam3'],
			$param['dnam4']
		));
    	
    	foreach($dIds as $dk => $dv){
    		$param['d'.($dk + 1)] = $dv;
    	}
    	
    	$stadiumId = $this->_stadiumModel->_add($param);
    	
    	if (self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			return false;
		}
		
		if(empty($images)){
			//默认插入主图
			$this->_stadiumPhotosModel->_add(array(
				'stadium_id' => $stadiumId,
				'aid' => $param['aid'],
				'avatar' => $param['avatar'],
				'avatar_h' => $param['avatar_h'],
				'avatar_b' => $param['avatar_b'],
				'avatar_m' => $param['avatar_m'],
				'remark' => '封面图',
				'uid' => $param['add_uid']
			));
		}else{
			//插入图片
			$insertImage = array();
			foreach($images as $ik => $img){
				$newImag = array();
				$newImag['stadium_id'] = $stadiumId;
				$newImag['aid'] = $img['aid'];
				$newImag['avatar_h'] = $img['avatar_h'];
				$newImag['avatar_b'] = $img['avatar_b'];
				$newImag['avatar_m'] = $img['avatar_m'];
				$newImag['uid'] = $img['add_uid'];
				
				$insertImage[] = $newImag;
			}
			
	    	$this->_stadiumPhotosModel->batchInsert($insertImage);
		}
		
    	
    	if (self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			return false;
		}
    	
    	self::$dbInstance->trans_commit();
		self::$dbInstance->trans_off();
    	
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
    	$stadiumId = $stadium['basic']['id'];
    	
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
    		self::$dbInstance->trans_start();
    	}
    	
    	$updateParam = $this->_stadiumBasicInfo($param, $images[0],$user);
    	$updateParam = $this->_getDistrictIdFromName($updateParam,$param);
    	
    	$rows = $this->_stadiumModel->update($updateParam , array('id' => $stadiumId));
    	
    		
    	if ($useTrans && self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			return false;
		}
		
		
		$deletedRows = $this->_stadiumPhotosModel->deleteByWhere(array(
			'id' => $stadiumId
		));
		
		
		if ($useTrans && self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			return false;
		}
		
		//插入图片
		$now = time();
		$insertImage = array();
		foreach($images as $ik => $img){
			$img['id'] = $stadiumId;
			$img['gmt_create'] = $now;
			$img['gmt_modify'] = $now;
			
			$insertImage[] = $img;
		}
		
    	$this->_stadiumPhotosModel->batchInsert($insertImage);
    	
    	if ($useTrans && self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			return false;
		}
    	
    	if($useTrans){
    		self::$dbInstance->trans_commit();
			self::$dbInstance->trans_off();
    	}
    	
    	return $rows;
    	
    }
	
}
