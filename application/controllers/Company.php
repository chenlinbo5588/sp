<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Company extends Ydzj_Controller {
    public function __construct(){
        parent::__construct();
    }
    
    public function index()
    {
        $this->display();
    }
    
    
}
