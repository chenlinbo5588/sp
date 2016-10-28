<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lab_menu extends MyYdzj_Controller {
	
    public function __construct(){
		parent::__construct();
		
		$this->load->library('Tree_service');
		$this->tree_service->setTargetModel($this->Lab_Fn_Model,'id','parent_id','name');
    }
    
    
    private function _getMenu(){
		$list = $this->tree_service->getListByTree(0,0,array(
			'order' => 'parent_id ASC, displayorder DESC'
		));
		
		return $list;
	}
	
    
    public function index()
    {
    	$this->assign('lab_menu',$this->_getMenu());
        $this->display();
    }
    
    
    /**
     * 权属
     */
    public function checkowner($pid){
    	
    	if($this->_loginUID != $this->_currentOid){
    		$id = $this->input->post('id');
    		
	    	$info = $this->Lab_Model->getFirstByKey($id);
	    	
	    	
	    	
	    	
	    	$allowIds[] = $info['pid'];
    	
    		if(!in_array($pid,$allowIds)){
    			$this->form_validation->set_message('checkowner', '%s 不能选择不在自己管辖的实验室为父级');
            	return FALSE;
    		}
    	}else{
    		return true;
    	}
    }
    
    public function compare($pid){
    	$id = $this->input->post('id');
    	
    	$subIds = $this->Lab_Model->getListByTree($id);
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
    
    
    
    private function _addRules(){
    	$displayorder = $this->input->post('displayorder');
    	if(!empty($displayorder)){
    		$this->form_validation->set_rules('displayorder','排序',  'required|is_natural_no_zero|less_than_equal_to[9999]');
    	}
    }
    
	
	/**
	 * 编辑实验室
	 */
    public function edit(){
		
		$id = $this->input->get_post('id');
		
		$feedback = '';
		
		if($id && $this->isPostRequest()){
			$this->form_validation->set_rules('id','id',  'required');
			$this->form_validation->set_rules('name','名称',  'trim|required|min_length[1]|max_length[15]');
			$this->form_validation->set_rules('pid','父级',  'is_natural|callback_checkowner|callback_compare');
			
			$this->_addRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$flag = $this->Lab_Menu_Model->update(array(
					'name' => $this->input->post('name'),
					'pid' => $this->input->post('pid'),
					'displayorder' => $this->input->post('displayorder'),
				),array('id' => $id));
				
				if($flag >= 0){
					$this->jsonOutput('保存成功',array('redirectUrl' => site_url('lab_menu/edit?id='.$id)));
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$info = $this->Lab_Model->getFirstByKey($id);
			
			$this->assign('info',$info);
	        $this->display('lab_menu/add');
		}
    }
    
    /**
     * 删除
     */
    public function delete(){
    	
    	if($this->isPostRequest()){
    		for($i = 0; $i < 1; $i++){
    			
    			$id = $this->input->post('id');
    			
	    		$rows = $this->Lab_Fn_Model->deleteByWhere(array(
	    			'id' => $id,
	    		));
	    		
	    		
	    		// 把子树删除
	    		
	    		
	    		
	    		
	    		if($rows < 0){
	    			$this->jsonOutput($this->db->get_error_info());
	    			break;
	    		}
	    		
	    		$this->jsonOutput('删除成功');
    		}
    		
    	}else{
    		$this->jsonOutput('请求参数错误');
    	}
    }
    
    /**
     * 添加实验室
     */
    public function add()
    {
    	
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','名称',  'trim|required|min_length[1]|max_length[15]');
			$this->form_validation->set_rules('pid','父级','is_natural');
			$this->_addRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$flag = $this->Lab_Fn_Model->_add($_POST);
				
				if($flag > 0){
					$this->jsonOutput('保存成功',array('redirectUrl' => site_url('lab_menu/index')));
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
		}else{
			
			$this->assign('lab_menu',$this->_getMenu());
       	 	$this->display();
		}
    }
    
}
