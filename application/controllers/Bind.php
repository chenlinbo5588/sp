<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 业务
 */
class Bind extends Ydzj_Controller {
    public function __construct(){
        parent::__construct();
        
        $this->load->library('encrypt');
    }
    
    public function index()
    {
        $this->display();
    }
    
    public function step1(){
        $openid = $_GET['openid'];
        
        $this->assign('openid',$openid);
        $this->display();
    }
    
    public function step2(){
    	
        $openid = $_GET['openid'];
        if($this->isPostRequest()){
        	$this->form_validation->set_rules('openid', 'id参数', 'required');
	        $this->form_validation->set_rules('mobile', '手机号码', 'required|valid_mobile');
	        if(!empty($_POST['virtual_no'])){
	            $this->form_validation->set_rules('virtual_no', '虚拟号码', 'numeric');
	        }
	        
	        if($this->form_validation->run()){
	            $this->load->model('Wx_Customer_Model');
	            $realOpenId = $this->encrypt->decode($_POST['openid']);
	            if(empty($realOpenId)){
	                $realOpenId = '';
	            }
	            $affectRow = $this->Wx_Customer_Model->bind($realOpenId,$_POST['mobile'],$_POST['virtual_no']);
	            if($affectRow){
	                $this->assign('result','success');
	            }else{
	                $this->assign('result','failed');
	            }
	            $this->display('bind/bind_result');
	        }else{
	        	$this->assign('openid',$_POST['openid']);
	        	$this->display();
	        }
        }else{
            $this->assign('openid',$openid);
            $this->display();
        }
    }
    
    
}
