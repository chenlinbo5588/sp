<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Building extends Ydzj_Admin_Controller {
	
	
	private $_villageList;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Building_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']['uid']);
		
		$this->load->config('cljz_config');
		$this->load->config('arcgis_server');
		
		
		$mapGroup = config_item('mapGroup');
		$mapUrlConfig = config_item($mapGroup);
		
		$this->_mapConfig = $mapUrlConfig;
		$this->_villageList = $this->building_service->getTownVillageList(config_item('site_town'));
		
		$this->assign('mapUrlConfig',$this->_mapConfig);
		$this->assign('villageList',$this->_villageList);
		$this->assign('villageListJson',json_encode($this->_villageList));
		$this->assign('PHPSID',$this->session->session_id);
		
	}
	
	
	public function index(){
			
		$this->display();
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
	
	
	/**
	 * 编辑
	 */
	public function detail(){
		
		$feedback = '';
		$hid = $this->input->get_post('hid');
		
		$info = $this->House_Model->getFirstByKey($hid,'hid');
		$person = $this->Person_Model->getFirstByKey($info['owner_id'],'id');
		$villageList = $this->building_service->getTownVillageList(config_item('site_town'));
		
		if($this->isPostRequest()){
			
		}else{
			$houseList = $this->House_Model->getList(array(
				'where' => array(
					'owner_id' => $person['id']
				)
			));
			
			$this->assign('fileList',json_decode($info['photos'],true));
			$this->assign('villageList',$villageList);
			$this->assign('info',$info);
			$this->assign('feedback',$feedback);
			$this->display();
		}
	}
	
}
