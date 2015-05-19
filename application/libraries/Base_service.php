<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Service {

	protected $CI ;
	
	public function __construct(){
		$this->CI = & get_instance();
	}
	
}
