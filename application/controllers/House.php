<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class House extends MyYdzj_Controller {
	
	
	private $_mapConfig ;
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Building_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_profile['basic']);
		
		$this->load->config('cljz_config');
		$this->load->config('arcgis_server');
		
		
		$mapGroup = config_item('mapGroup');
		$mapUrlConfig = config_item($mapGroup);
		
		$this->_mapConfig = $mapUrlConfig;
		
		
	}
	
	
	//@todo 自动加入所在村条件
	private function _prepareCondition(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$pageParam = array(
			'page_size' => config_item('page_size'),
			'current_page' => $currentPage,
			'call_js' => 'search_page',
			'form_id' => '#formSearch'
		);
		
		$searchCondition = array(
			'pager' => $pageParam,
			'order' => 'hid DESC',
		);
		
		return $searchCondition;
	}
	
	public function index()
	{
		$condition = $this->_prepareCondition();
		$where = array();
		
		$where['qlrName'] = $this->input->get_post('qlr_name');
		$where['idNo'] = $this->input->get_post('id_no');
		
		
		$condition['where'] = array(
			'owner_village_id' => $this->_profile['basic']['village_id']
		);
		
		
		if($where['qlrName']){
			$condition['like']['owner_name'] = $where['qlrName'];
		}
		
		if($where['idNo']){
			$condition['like']['id_no'] = $where['idNo'];
		}
		
		$ownerId = $this->input->get_post('owner_id');
		
		if($ownerId){
			$condition['where']['owner_id'] = $ownerId;
		}
		
		$results = $this->House_Model->getList($condition);
		
		$this->assign('list',$results['data']);
		$this->assign('page',$results['pager']);
		
		$this->_breadCrumbs[] = array(
			'title' => '房屋建筑管理',
			'url' => $this->uri->uri_string
		);
		
		
		$this->assign('illegalList',array(
			'待定','全部合法','部分违法','全部违法'
		));
		$this->assign('dealWayList',array(
			'待定','暂缓','补办','没收','拆除'
		));
		
		
		$this->display();
	}
	
	
	public function delete(){
		
		$houseList = $this->input->post('id');
		
		if($houseList && $this->isPostRequest()){
			$this->building_service->setArcgisUrl(config_item('arcgis_server'),$this->_mapConfig['编辑要素']['标准建筑点']);
			
			$houseId = $houseList[0];
			$returnVal = $this->building_service->deleteHouseById($houseId);
			
			
			if($returnVal > 0){
				$this->jsonOutput('删除成功');
			}else{
				$this->jsonOutput('删除出错');
			}
			
		}else{
			$this->jsonOutput('参数错误');
		}
		
	}
	
	
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$feedback = '';
		$hid = $this->input->get_post('hid');
		
		$info = $this->building_service->getHouseInfo($hid);
		
		$this->building_service->setArcgisUrl(config_item('arcgis_server'),$this->_mapConfig['编辑要素']['标准建筑点']);
		
		$info = $this->building_service->autoSetXY($info);
		
		
		$villageList = $this->building_service->getTownVillageList(config_item('site_town'));
		
		if($this->isPostRequest()){
			for($i = 0 ; $i < 1; $i++){
				$this->form_validation->reset_validation();
				$data = $this->_addRules();
				
				$this->form_validation->set_rules('hid','房屋ID','required|is_natural_no_zero');
				
				if(!$this->form_validation->run()){
					$this->jsonOutput("数据校验失败",array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				
				$person = $this->Person_Model->getFirstByKey($info['owner_id'],'id');
				
				$data['hid'] = $this->input->get_post('hid');
				$data['object_id'] = $info['object_id'];
				
				if($data['village_id']){
					$data['village'] = $villageList[$data['village_id']]['xzqmc'];
				}
				
				
				$addResult = $this->building_service->editPersonBuilding($person,array_merge($data,$this->addWhoHasOperated('edit')));
				
				$this->jsonOutput('保存成功',array());
			}
		}else{
			
			$houseList = $this->House_Model->getList(array(
				'where' => array(
					'owner_id' => $info['owner_id']
				)
			));
			
			$this->_breadCrumbs[] = array(
				'title' => '修改房屋建筑信息',
				'url' => $this->uri->uri_string
			);
			
			
			$this->assign('fileList',$info['photos']);
			$this->assign('mapUrlConfig',$this->_mapConfig);
			$this->assign('villageList',$villageList);
			$this->assign('info',$info);
			$this->assign('feedback',$feedback);
			$this->display('house/add');
		}
		
	}
	
	
	
	public function detail(){
		$feedback = '';
		$hid = $this->input->get_post('hid');
		
		$info = $this->building_service->getHouseInfo($hid);
		$villageList = $this->building_service->getTownVillageList(config_item('site_town'));
		
		$this->building_service->setArcgisUrl(config_item('arcgis_server'),$this->_mapConfig['编辑要素']['标准建筑点']);
		$info = $this->building_service->autoSetXY($info);
		
		if($this->isPostRequest()){
			
		}else{
			
			$houseList = $this->House_Model->getList(array(
				'where' => array(
					'owner_id' => $info['owner_id']
				)
			));
			
			$this->_breadCrumbs[] = array(
				'title' => '修改房屋建筑信息',
				'url' => $this->uri->uri_string
			);
			
			
			$this->assign('fileList',$info['photos']);
			$this->assign('mapUrlConfig',$this->_mapConfig);
			$this->assign('villageList',$villageList);
			$this->assign('info',$info);
			$this->assign('feedback',$feedback);
			$this->display();
		}
	}
	
	
	private function _addRules(){
		
		$data = array();
		
		$houseValidation = config_item('house_validation');
		$fields = $houseValidation['rule_list'];
		
		foreach($fields as $key => $f){
			
			if($f['required']){
				$this->form_validation->set_rules($key,$f['title'],$f['rule']);
				$data[$key] = trim($this->input->post($key));
			}else{
				$temp = trim($this->input->post($key));
				
				
				if($f['condition']){
					if(empty($temp)){
						$data[$key] = $f['defaultValue'];
					}else{
						$this->form_validation->set_rules($key,$f['title'],$f['rule']);
						$data[$key] = $temp;
					}
				}
			}
		}
		
		return $data;
	}
	
	
	
	
	
	public function add(){
		
		$ownerId = $this->input->get_post('owner_id');
		$person = $this->Person_Model->getFirstByKey($ownerId,'id');
		$villageList = $this->building_service->getTownVillageList(config_item('site_town'));
		
		
		if($this->isPostRequest()){
			
			for($i = 0 ; $i < 1; $i++){
				$this->form_validation->reset_validation();
				$data = $this->_addRules();
				
				if(!$this->form_validation->run()){
					$this->jsonOutput("数据校验失败",array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$fileIdList = $this->input->post('file_id');
				
				if($data['village_id']){
					$data['village'] = $villageList[$data['village_id']]['xzqmc'];
				}
				
				$this->building_service->setArcgisUrl(config_item('arcgis_server'),$this->_mapConfig['编辑要素']['标准建筑点']);
				$addResult = $this->building_service->addPersonBuilding($person,array_merge($data,$this->addWhoHasOperated('add')),$fileIdList);
				
				$this->jsonOutput('保存成功,正在刷新页面',array(
					'redirectUrl' => site_url('house/edit?hid='.$addResult['hid'])
				));
			}
			
			
		}else{
			$info = array(
				'owner_id' => $person['id'],
				'owner_name' => $person['qlr_name'],
			);
			
			$this->assign('mapUrlConfig',$this->_mapConfig);
			$this->_breadCrumbs[] = array(
				'title' => '房屋建筑管理',
				'url' => 'house/index'
			);
			$this->_breadCrumbs[] = array(
				'title' => '添加建筑点',
				'url' => $this->uri->uri_string
			);
			
			
			
			$this->assign('villageList',$villageList);
			$this->assign('info',$info);
			$this->display();
		}
	}
	
	
	/**
	 * 添加房屋照片
	 */
	public function addimg(){
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		$fileData = $this->attachment_service->addImageAttachment('Filedata',array('without_db' => true),0,'house');
		
		$info['error'] = 1;
		$info['msg'] = '参数错误';
		
		if($fileData){
			$resizeData = $this->attachment_service->resize($fileData['file_url'],array('b','m'));
			$fileData = array_merge($fileData,$resizeData);
			
			$info = array(
				'person_id' => $this->input->post('owner_id') ? $this->input->post('owner_id') : 0,
				'house_id' => $this->input->post('house_id') ? $this->input->post('house_id') : 0,
				'image_url' => $fileData['file_url'],
				'image_url_b' => !empty($fileData['img_b']) ? $fileData['img_b'] : '',
				'image_url_m' => !empty($fileData['img_m']) ? $fileData['img_m'] : '',
			);
			
			$imageId = $this->House_Images_Model->_add(array_merge($info,$this->addWhoHasOperated('add')));
			if($imageId){
				$info['error'] = 0;
				$info['msg'] = '上传成功';
				$info['id'] = $imageId;  //照片ID
				$info['image_url'] = base_url($info['image_url']);
				$info['image_url_b'] = base_url($info['image_url_b']);
				$info['image_url_m'] = base_url($info['image_url_m']);
				
			}else{
				$info['error'] = 2;
				$info['msg'] = '系统异常';
				
				//出错了，则删除
				$this->attachment_service->deleteByFileUrl(array(
					$fileData['file_url'],
					$fileData['img_b'],
					$fileData['img_m'],
				));
			}
			
		}else{
			$json['msg'] = $this->attachment_service->getErrorMsg('','');
		}
		
		$this->jsonOutput($info['msg'],$info);
		
	}
	
	
	public function delimg(){
		$file_id = $this->input->get_post('file_id');
		$person_id = $this->input->get_post('person_id');
		$house_id = $this->input->get_post('house_id');
		
		$info = $this->House_Images_Model->getById(array(
			'where' => array(
				'id' => $file_id,
				'person_id' => $person_id
			)
		));
		
		if($info){
			$this->House_Images_Model->deleteByCondition(array(
				'where' => array(
					'id' => $file_id,
					'person_id' => $person_id
				)
			));
			
			//文件删除
			$this->attachment_service->deleteByFileUrl(array(
				$info['image_url'],
				$info['image_url_b'],
				$info['image_url_m'],
			));
			
			
			//如果在编辑页面,则同步修改
			if($house_id == $info['house_id']){
				$imageList = $this->House_Images_Model->getList(array(
					'select' => 'id,image_url,image_url_b,image_url_m',
					'where' => array(
						'house_id' => $house_id
					)
				));
				
				$this->House_Model->update(array('photos' => json_encode($imageList)),array('hid' => $house_id));
			}
		}
		
		$this->jsonOutput('成功',$this->getFormHash());
	}
	
	
	
	/**
	 * 
	 */
	public function map(){
		
		$this->_breadCrumbs[] = array(
			'title' => '地图浏览',
			'url' => $this->uri->uri_string
		);
			
		$this->assign('mapUrlConfig',$this->_mapConfig);
		$this->display();
	}
	
}
