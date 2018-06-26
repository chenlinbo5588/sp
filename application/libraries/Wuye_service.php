<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 物业核心服务
 */
class Wuye_service extends Base_service {
	
	private $_residentModel;
	private $_buildingModel;
	private $_roomModel;
	private $_memberModel;
	private $_wxCustomerModel;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'Resident_Model',/*'Building_Model','Room_Model',*/
			'Member_Model','Wx_Customer_Model'
		
		));
		
		
		$this->_residengtModel = self::$CI->Resident_Model;
		/*
		$this->_workerImagesModel = self::$CI->Worker_Images_Model;
		$this->_staffModel = self::$CI->Staff_Model;
		$this->_staffModel = self::$CI->Staff_Model;
		$this->_staffImagesModel = self::$CI->Staff_Images_Model;
		*/
		
	}
	
	
}
