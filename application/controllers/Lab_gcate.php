<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * 实验室货品分类
 */
 
class Lab_Gcate extends MyYdzj_Controller {
	
	private $_cacheKey = "gcate";
	
    public function __construct(){
		parent::__construct();
		
    }
    
    private function _getTreeData($moreCondition = array()){
    	
    	$condition = array(
    		'where' => array(
    			'oid' => $this->_currentOid
    		),
    		'order' => 'pid ASC, displayorder ASC'
    	);
    	
    	
    	$list = $this->Lab_Gcate_Model->getList(array_merge($condition,$moreCondition));
    	
    	//print_r($list);
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
    	
    	$name = $this->input->get_post('name');
    	if($name){
    		$this->assign('list',$this->_getTreeData(array(
				'like' => array(
					'name' => $name
				)
			)));
    	}else{
    		$this->assign('list',$this->_getTreeData());
    	}
		
        $this->display();
    }
    
    
  
    public function compare($pid){
    	$id = $this->input->post('id');
    	
    	$subIds = $this->Lab_Gcate_Model->getListByTree($id);
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
    
    
    public function edit(){
		
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('id','分类id',  'required');
			$this->form_validation->set_rules('name','分类名称','required|is_unique_not_self['.$this->Lab_Gcate_Model->getTableRealName().".name.id.{$id}]");
			$this->form_validation->set_rules('pid','父级分类',  'required|callback_compare');
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$data = array(
					'name' => $this->input->post('name'),
					'displayorder' => $this->input->post('displayorder'),
					'pid' => $this->input->post('pid'),
				);
				
				$rows = $this->Lab_Gcate_Model->update(array_merge($_POST,$this->addWhoHasOperated('edit')),array('id' => $id));
				
				if($rows >= 0){
					$this->jsonOutput('保存成功',array('redirectUrl' => site_url('lab_gcate/edit?id='.$id)));
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$info = $this->Lab_Gcate_Model->getFirstByKey($id);
			$this->assign('list',$this->_getTreeData());
			$this->assign('info',$info);
        	$this->display('lab_gcate/add');
		}
    }
    
    public function delete(){
    	$id = $this->input->post('id');
    	
    	if(!empty($id) && $this->isPostRequest()){
    		
    		if(is_array($id)){
    			$id = $id[0];
    		}
    		
    		$subIds = $this->Lab_Gcate_Model->getListByTree($id);
    		
    		$ids = array();
    		$ids[] = $id;
    		if($subIds){
    			foreach($subIds as $item){
    				$ids[] = $item['id'];
    			}
    		}
    		
    		$ids = array_unique($ids);
    		$rows =  $this->Lab_Gcate_Model->deleteByCondition(
	    		array(
	    			'where_in' => array(
	    				array('key' => 'id','value' => $ids) 
	    			)
	    		)
	    	);
    		
    		if($rows){
    			$this->jsonOutput('删除成功');
    		}else{
    			$this->jsonOutput($this->db->get_error_info());
    		}
    		
    	}else{
    		$this->jsonOutput('请求参数错误');
    	}
    }
    
    
     private function _addRules(){
     	
    	$displayorder = $this->input->post('displayorder');
    	if(!empty($displayorder)){
    		$this->form_validation->set_rules('displayorder','排序',  'required|is_natural_no_zero|less_than_equal_to[9999]');
    	}
    }
    
    public function checkName($name,$id = 0){
		$info = $this->Lab_Gcate_Model->getById(array(
			'where' => array(
				'name' => $name,
				'oid' => $this->_currentOid
			)
		));
		
		if($info){
			if($id && $info['id'] == $id){
				//如果是自己就通过
				return true;
			}
			
			$this->form_validation->set_message('checkName', "名称{$name}已经存在");
			return false;
		}else{
			return true;
		}
	}
	
    
    
    public function add()
    {
		if($this->isPostRequest()){
			$this->form_validation->set_rules('name','分类名称',  'required|max_length[30]|callback_checkName');
     	
			$this->_addRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;	
				}
				
				$newid = $this->Lab_Gcate_Model->_add(array_merge($_POST,$this->addWhoHasOperated()));
				if($newid > 0){
					$this->jsonOutput('保存成功',array('redirectUrl' => site_url('lab_gcate/edit?id='.$newid)));
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
		
			$this->assign('list',$this->_getTreeData());
        	$this->display();
		}
    }
    
}
