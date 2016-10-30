<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lab_menu extends MyYdzj_Controller {
	
    public function __construct(){
		parent::__construct();
    }
    
    
    private function _getTreeData(){
		$list = $this->Lab_Fn_Model->getList(array(
			'order' => 'pid ASC, displayorder ASC'
		
		));
		
		$treeHTML = array();
		
		if($list){
			$treeHTML = $this->phptree->makeTreeForHtml($list,array(
				'primary_key' => 'id',
				'parent_key' => 'pid',
				'expanded' => trues
			));
		}
		
		return $treeHTML;
	}
	
    
    public function index()
    {
    	$this->assign('lab_menu',$this->_getTreeData());
        $this->display();
    }
    
    
    public function compare($pid){
    	$id = $this->input->post('id');
    	
    	$subIds = $this->Lab_Fn_Model->getListByTree($id);
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
			$this->form_validation->set_rules('url','链接',  'trim|required|min_length[1]|max_length[60]');
			$this->form_validation->set_rules('pid','父级',  'is_natural|callback_compare');
			
			$this->_addRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$flag = $this->Lab_Fn_Model->update(array(
					'name' => $this->input->post('name'),
					'pid' => $this->input->post('pid'),
					'displayorder' => $this->input->post('displayorder'),
				),array('id' => $id));
				
				if($flag >= 0){
					$this->getCacheObject()->delete('siteFn');
					
					$this->jsonOutput('保存成功');
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$info = $this->Lab_Fn_Model->getFirstByKey($id);
			$this->assign('lab_menu',$this->_getTreeData());
			$this->assign('info',$info);
	        $this->display('lab_menu/add');
		}
    }
    
    /**
     * 删除
     */
    public function delete(){
    	$id = $this->input->post('id');
    	
    	if(is_array($id)){
    		$id = $id[0];
    	}
    	
    	if($this->isPostRequest()){
    		for($i = 0; $i < 1; $i++){
	    		$subIds = $this->Lab_Fn_Model->getListByTree($id);
	    		
	    		$ids = array();
	    		if(!empty($subIds)){
	    			$ids = array_keys($subIds);
	    		}
	    		
				$ids[] = $id;
				
	    		$rows = $this->Lab_Fn_Model->deleteByCondition(array(
	    			'where_in' => array(
	    				array('key' => 'id','value' => $ids)
	    			)
	    		));
	    		
	    		if($rows < 0){
	    			$this->jsonOutput($this->db->get_error_info());
	    			break;
	    		}
	    		
	    		$this->getCacheObject()->delete('siteFn');
	    		
	    		$this->jsonOutput('删除成功',array('redirectUrl' => site_url('lab_menu/index')));
    		}
    		
    	}else{
    		$this->jsonOutput('请求参数错误');
    	}
    }
    
    /**
     * 
     */
    public function add()
    {
    	
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','名称',  'trim|required|min_length[1]|max_length[15]');
			$this->form_validation->set_rules('url','链接',  'trim|required|min_length[1]|max_length[60]');
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
					$this->getCacheObject()->delete('siteFn');
					$this->jsonOutput('保存成功',array('redirectUrl' => site_url('lab_menu/index')));
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
		}else{
			
			$this->assign('lab_menu',$this->_getTreeData());
       	 	$this->display();
		}
    }
    
}
