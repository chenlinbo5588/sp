<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium extends Ydzj_Controller {
	
	//public $_controllerUrl;
	
	public function __construct(){
		parent::__construct();
		//$this->_controllerUrl = admin_site_url();
        
        $this->load->library('Stadium_Service');
	}
	
	
	public function index()
	{
		
		$this->setRightNavLink('<a id="rightBarLink" class="bar_button" href="'.site_url('stadium/add').'">+创建场馆</a>');
		
		$title = '场馆';
		
		$this->setTopNavTitle($title);
		$this->seo('篮球馆，体育场馆');
		$this->display('stadium/index');
	}
	
	public function add(){
        
        if(!$this->isLogin()){
        	
        	$this->assign('returnUrl',site_url('stadium/add'));
        	$this->display('member/login');
        	
        }else{
        	$allMetaGroups = $this->stadium_service->getAllMetaGroups();
	        //print_r($allMetaGroups);
	        $this->assign('allMetaGroups',$allMetaGroups);
	        
			$this->seo('添加体育场馆');
	        
	        if($this->isPostRequest()){
	            //$this->form_validation->set_error_delimiters('<div class="error">','</div>');
	            $this->_commonRules();
	            $this->_addRules();
	            
	            //print_r($_FILES);
	            
	            if($this->form_validation->run()){
	                
	                $this->load->library('Attachment_Service');
		            $fileInfo = $this->attachment_service->addImageAttachment('cover_img');
		            
		            for($i = 1; $i <= $this->input->post('other_image_count'); $i++){
		            	$otherFile = $this->attachment_service->addImageAttachment('other_img'.$i);
		            }
	            
	                $param = $_POST;
	                
	                if($fileInfo['file_url']){
	                	$param['cover_img'] = $fileInfo['file_url'];
	                }
	                
	                $id = $this->stadium_service->addOneStadium($param);
	                
	                if($id > 0){
	                    $this->form_validation->reset_validation();
	                    $this->assign('message', '添加'.$this->input->post('title').'成功');
	                    unset($_POST);
	                }
	            }
	        }
	        
	        $this->display('stadium/add');
        	
        	
        }
        
        
        
        
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
