<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
		$this->assign(array(
			'pgClass' => 'aboutPg',
		));
	}
}
