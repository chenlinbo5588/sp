<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium_Service extends Base_Service {

    private $_stadiumModel;
    private $_stadiumMetaModel;
	
	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Stadium_Model');
		$this->CI->load->model('Stadium_Meta_Model');
        
        $this->_stadiumModel = $this->CI->Stadium_Model;
        $this->_stadiumMetaModel = $this->CI->Stadium_Meta_Model;
        
	}
	
}
