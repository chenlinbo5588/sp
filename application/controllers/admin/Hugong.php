<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(dirname(__FILE__).'/Staff.php');

class Hugong extends Staff {
	public function __construct(){
		
		$this->_moduleTitle = '护工';
		$this->_className = strtolower(get_class());
		
		parent::__construct();
	}
	
	
}
