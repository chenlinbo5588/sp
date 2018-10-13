<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_service extends Base_service {

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'Resident_Model','Building_Model','House_Model','Yezhu_Model','Parking_Model',
			'Feetype_Model','Basic_Data_Model','Plan_Detail_Model',
			'Plan_Model','House_Yezhu_Model','Payment_Report_Date_Model','Payment_Report_Week_Model',
			'Payment_Report_Month_Model','Payment_Report_Year_Model'
		));
		
	}
	
	
	
}
