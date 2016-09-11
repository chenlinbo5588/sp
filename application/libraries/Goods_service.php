<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Goods_service extends Base_service {

	private $_goodsRecent;
	
	
	public function __construct(){
		parent::__construct();
		self::$CI->load->model('Goods_Recent_Model');
		
		
	}
	
	
}
