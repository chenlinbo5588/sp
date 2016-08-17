<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lab_Category extends Ydzj_Admin_Controller {
	
	private $_cacheKey = "lab_category";
	
    public function __construct(){
		parent::__construct();
		
		$this->assign('action',$this->uri->rsegment(2));
		$this->load->library('Goods_service');
   		
    }
    
    public function index()
    {
        $this->display();
    }
    
    
   	public function getTreeXML(){
   		
   		
   		header("Content-type:text/xml");
   		
   		$str = $this->Lab_Cache_Model->getFirstByKey($this->_cacheKey,'key_id');
   		if(empty($str)){
   			$str = $this->_writeCache();
   			echo $str;
   		}else{
   			echo $str['content'];
   		}
   	}
    
    
    public function compare($pid){
    	$id = $_POST['id'];
    	
    	$subIds = $this->Goods_Category_Model->getListByTree($id);
		$ids = array();
		$ids[] = $id;
		if($subIds){
			foreach($subIds as $item){
				$ids[] = $item['id'];
			}
		}
		$ids = array_unique($ids);
    	
    	
    	if (in_array($pid,$ids))
        {
            $this->form_validation->set_message('compare', '%s 不能为自己以及其子类别');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    
    private function _writeCache(){
    	$xml = $this->goods_service->goodsCategoryXML();
   		$this->Lab_Cache_Model->addByKey($this->_cacheKey,$xml);
   		
   		return $xml;
    }
    
    public function edit(){
		
		$this->assign('act','edit');
		if($this->isPostRequest()){
			$this->form_validation->set_rules('id','类别id',  'required');
			$this->form_validation->set_rules('name','类别名称',  'required');
			$this->form_validation->set_rules('pid','父类别',  'required|callback_compare');
			
			if($this->form_validation->run()){
				$_POST['updator'] = $this->_userProfile['name'];
				$flag = $this->Goods_Category_Model->update($_POST);
				
				if($flag){
					
					$this->_writeCache();
					$this->assign('success','1');
					$this->assign('message','修改成功');
				}else{
					$this->assign('message','修改失败');
				}
				
			}else{
				$this->assign('message','数据不能通过校验,修改失败');
			}
			
			
			$id = $_POST['id'];
			$info = $this->Goods_Category_Model->queryById($id);
			$info['name'] = $_POST['name'];
			
			
		}else{
			$id = $this->uri->segment(4);
			$info = $this->Goods_Category_Model->queryById($id);
		}
		
		
		$this->assign('info',$info);
		$this->assign('gobackUrl', $this->getGobackUrl());
        $this->display('add');
    }
    
    public function delete(){
    	
    	//$id = $this->uri->segment(4);
    	
    	if($this->isPostRequest()){
    		
    		$id = $_POST['id'];
    		
    		$subIds = $this->Goods_Category_Model->getListByTree($id);
    		
    		$ids = array();
    		$ids[] = $id;
    		if($subIds){
    			
    			foreach($subIds as $item){
    				$ids[] = $item['id'];
    			}
    		}
    		$ids = array_unique($ids);
    		
    		$this->Goods_Category_Model->fake_delete(array('id' => $ids , 'updator' => $this->_userProfile['name']));
    		
    		$this->_writeCache();
    		$this->sendFormatJson('success',array('operation' => 'delete','id' => $id , 'text' => '删除成功'));
    	}else{
    		$this->sendFormatJson('failed',array('text' => '请求错误'));
    	}
    }
    
    
    public function add()
    {
    	$this->assign('act','add');
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','类别名称',  'required');
			
			if($this->form_validation->run()){
				$_POST['creator'] = $this->_userProfile['name'];
				$flag = $this->Goods_Category_Model->add($_POST);
				
				if($flag){
					$this->_writeCache();
					$this->assign('success','1');
					$this->assign('message','添加成功');
				}else{
					$info = $_POST;
					$this->assign('message','添加失败');
				}
				
			}else{
				$info = $_POST;
				$this->assign('message','数据不能通过校验,添加失败');
			}
			
		}
		
		$this->assign('info',$info);
		$this->assign('gobackUrl', $this->getGobackUrl());
        $this->display();
    }
    
}
