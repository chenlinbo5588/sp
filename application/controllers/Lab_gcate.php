<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * 实验室货品分类
 */
 
class Lab_Gcate extends MyYdzj_Controller {
	
	private $_cacheKey = "lab_category";
	
    public function __construct(){
		parent::__construct();
		
		$this->load->library('Goods_service');
   		
   		$this->assign('action',$this->uri->rsegment(2));
		$this->assign('topnav',strtolower(get_class()).'/index');
		
    }
    
    public function index()
    {
        $this->display();
    }
    
    private function _updateCache(){
    	$this->lab_service->expireCacheByCondition(array('key_id' => $this->_cacheKey));
    }
    
   	public function getTreeXML(){
   		
   		header("Content-type:text/xml");
   		$cache = $this->Lab_Cache_Model->getFirstByKey($this->_cacheKey,'key_id');
   		
   		if(!empty($cache) && $cache['expire'] >= 0 && ((time() - $cache['gmt_create']) < CACHE_ONE_DAY )){
   			echo $cache['content'];
   		}else{
   			$str = $this->_writeCache();
   			echo $str;
   		}
   		
   	}
    
    
    public function compare($pid){
    	$id = $this->input->post('id');
    	
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
		
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('id','分类id',  'required');
			$this->form_validation->set_rules('name','分类名称',  'required');
			$this->form_validation->set_rules('pid','父级分类',  'required|callback_compare');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$_POST['updator'] = $this->_profile['basic']['name'];
				$rows = $this->Goods_Category_Model->update($_POST,array('id' => $id));
				
				if($rows >= 0){
					if($rows > 0){
						$this->_writeCache();
					}
					
					$this->jsonOutput('保存成功');
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$info = $this->Goods_Category_Model->getFirstByKey($id);
			$this->assign('info',$info);
        	$this->display('goods_category/add');
		}
    }
    
    public function delete(){
    	
    	$id = $this->input->post('id');
    	
    	if($this->isPostRequest()){
    		$subIds = $this->Goods_Category_Model->getListByTree($id);
    		
    		$ids = array();
    		$ids[] = $id;
    		if($subIds){
    			foreach($subIds as $item){
    				$ids[] = $item['id'];
    			}
    		}
    		
    		$ids = array_unique($ids);
    		
    		$this->Goods_Category_Model->updateByCondition(array(
				'status' => '已删除',
				'updator' => $this->_profile['basic']['name']
			),
    		array(
    			'where_in' => array(
    				array('key' => 'id','value' => $ids) 
    			
    			)
    		));
    		
    		$this->_writeCache();
    		$this->jsonOutput('删除成功');
    		
    	}else{
    		$this->jsonOutput('请求参数错误');
    	}
    }
    
    
    
    public function add()
    {
		if($this->isPostRequest()){
			$this->form_validation->set_rules('name','分类名称',  'required|max_length[30]');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;	
				}
				
				$_POST['creator'] = $this->_profile['basic']['name'];
				$newid = $this->Goods_Category_Model->_add($_POST);
				
				if($newid > 0){
					$this->_updateCache();
					$this->jsonOutput('保存成功');
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
        	$this->display();
		}
    }
    
}
