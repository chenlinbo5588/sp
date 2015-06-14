<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium extends Ydzj_Admin_Controller {
	
	//public $_controllerUrl;
	
	public function __construct(){
		parent::__construct();
		//$this->_controllerUrl = admin_site_url();
        
        $this->load->library('Stadium_Service');
	}
	
	
	public function index()
	{
		$this->seo('体育场馆');
		$this->display('stadium/index');
	}
	
	public function add(){
        
        $allMetaGroups = $this->stadium_service->getAllMetaGroups();
        //print_r($allMetaGroups);
        $this->assign('allMetaGroups',$allMetaGroups);
        $this->assign('formAttr',array('data-ajax' => 'false' ));
		$this->seo('添加体育场馆');
        
        if($this->isPostRequest()){
            $this->form_validation->set_error_delimiters('<div class="error">','</div>');
            $this->_commonRules();
            $this->_addRules();
            
            if($this->form_validation->run()){
                
                $id = $this->stadium_service->addOneStadium($_POST);
                
                if($id > 0){
                    $this->form_validation->reset_validation();
                    $this->assign('message', '添加'.$this->input->post('title').'成功');
                    unset($_POST);
                }
            }
        }
        
        $this->display('stadium/add');
        
	}
    
    private function _commonRules(){
        
        $this->form_validation->set_rules('title','场馆名称',array('required'),
            array(
                'required' => '请输入场馆名称'
            )
        );
        
        $this->form_validation->set_rules('address','场馆地址',array('required'),
            array(
                'required' => '请输入场馆地址'
            )
        );
        
        $this->form_validation->set_rules('mobile','手机号码',array('required','valid_mobile'),
            array(
                'required' => '请输入联系人手机号码',
                'valid_mobile' => '手机号码无效'
            )
        );
        
    }
    private function _addRules(){
        
        
    }
    
    private function _validateAddForm(){
        
        
    }
	
}
