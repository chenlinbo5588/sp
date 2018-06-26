<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(dirname(__FILE__).'/Staff.php');

class Yuesao extends Staff {
	public function __construct(){
		
		$this->_moduleTitle = '月嫂';
		$this->_className = strtolower(get_class());
		
		parent::__construct();
	}
	
	
}
