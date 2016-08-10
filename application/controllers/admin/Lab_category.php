<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lab_Category extends Ydzj_Admin_Controller {
	
	private $_cacheKey = "lab_category";
	
    public function __construct(){
		parent::__construct();
		
		if(!$this->_checkIsSystemManager()){
			$this->show_access_deny();
		}
		$this->load->model('Goods_Category_Model');
   		
		$this->assign('currentMenu',strtolower(get_class($this)));
    }
    
    public function index()
    {
    	$this->assign('action','index');
    	
    	
    	//$this->_getPageData();
    	
        $this->display();
    }
    
    
   	public function getTreeXML(){
   		
   		
   		header("Content-type:text/xml");
   		
   		/*
   		echo '<?xml version="1.0" ?><tree id="0">';
   		echo '<item text="实验室药品仪器分类" id="lab_categroy" open="1">';
   		$categoryList = $data = $this->Goods_Category_Model->getList(array(
    		'order' => 'pid ASC , id ASC'
    	));
    	
    	
    	$tree = $this->Goods_Category_Model->getRealTree($categoryList['data'],0);
    	$xml = $this->Goods_Category_Model->toXML($tree);
    	echo $xml;
   		echo '</item></tree>';
   		*/
   		
   		$str = $this->Lab_Cache_Model->queryById($this->_cacheKey,Lab_Admin_Controller::$CACHE_KEY_FIELD);
   		if(empty($str)){
   			$str = $this->_writeCache();
   			$str = $this->Lab_Cache_Model->queryById($this->_cacheKey,Lab_Admin_Controller::$CACHE_KEY_FIELD);
   		}
   		
   		echo $str['content'];
   		
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
    	
    	$str[] = '<?xml version="1.0" ?><tree id="0">';
   		$str[] = '<item text="实验室药品仪器分类" id="root" open="1">';
   		$categoryList = $data = $this->Goods_Category_Model->getList(array(
   			'where' => array(
   				'status' => '正常'
   			),
    		'order' => 'pid ASC , id ASC'
    	));
    	
    	
    	$tree = $this->Goods_Category_Model->getRealTree($categoryList['data'],0);
    	if($tree){
    		$str[] = $this->Goods_Category_Model->toXML($tree);
    	}
    	
   		$str[] = '</item></tree>';
   		
   		$this->Lab_Cache_Model->addByKey($this->_cacheKey,implode('',$str));
   		
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
