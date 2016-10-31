<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lab_Measure extends MyYdzj_Controller {
    public function __construct(){
		parent::__construct();
		
		$this->_breadCrumbs[] = array(
			'title' => '度量单位管理',
			'url' => 'lab_measure/index'
		);
    }
    
    public function index()
    {
    	$this->_getPageData();
        $this->display();
    }
    
    
    private function _getPageData(){
    	try {
            $page = $this->input->get_post('page');
            
            if(empty($page)){
                $page = 1;
            }
            
            //$condition['select'] = 'a,b';
            
            $condition['where']['oid'] = $this->_currentOid;
            $condition['order'] = "gmt_create DESC";
            $condition['pager'] = array(
                'page_size' => config_item('page_size'),
                'current_page' => $page,
                'query_param' => '',
                'call_js' => 'search_page',
				'form_id' => '#formSearch'
            );
            
            $name = $this->input->get_post('name');
            
            if(!empty($name)){
                $condition['like']['name'] = $name;
            }
            
            $data = $this->Lab_Measure_Model->getList($condition);
            $this->assign('page',$data['pager']);
            $this->assign('data',$data);
            
        }catch(Exception $e){
            //@todo error code here
        }
    }
    
    
    public function edit(){
		
		$id = $this->input->get_post('id');
		
		$this->_breadCrumbs[] = array(
			'title' => '编辑度量单位',
			'url' => $this->uri->uri_string.'?id='.$id
		);
		
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('name','度量单位名称',   'required|max_length[20]|is_unique_not_self['.$this->Lab_Measure_Model->getTableRealName().'.name.id.'.$id.']');
			
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$flag = $this->Lab_Measure_Model->update(array_merge($_POST,$this->addWhoHasOperated('edit')),array('id' => $id));
				
				if($flag < 0){
					$this->jsonOutput($this->db->get_error_info());
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
			
		}else{
			$info = $this->Lab_Measure_Model->getFirstByKey($id);
			
			$this->assign('info',$info);
        	$this->display('lab_measure/add');
		}
    }
    
    public function delete(){
    	$id = $this->input->post('id');
    	
    	if($id && $this->isPostRequest()){
    		if(is_array($id)){
    			$rows = $this->Lab_Measure_Model->deleteByCondition(array(
    				'where_in' => array(
    					array('key' => 'id','value' => $id)
    				
    				)
    			));
    		}else{
    			$rows = $this->Lab_Measure_Model->delete(array('id' => $id));
    		}
    		
    		if($rows > 0){
    			$this->jsonOutput('删除成功');
    		}else{
    			$this->jsonOutput($this->db->get_error_info());
    		}
    		
    	}else{
    		$this->jsonOutput('请求错误');
    	}
    }
    
    public function add()
    {
    	$this->_breadCrumbs[] = array(
			'title' => '添加度量单位',
			'url' => $this->uri->uri_string
		);
		
    	
		if($this->isPostRequest()){
			$this->form_validation->set_rules('name','度量名称',  'required|max_length[20]|is_unique['.$this->Lab_Measure_Model->getTableRealName().'.name]');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$flag = $this->Lab_Measure_Model->_add(array_merge($_POST,$this->addWhoHasOperated()));
				
				if($flag > 0){
					$this->jsonOutput('保存成功',array('redirectUrl' => site_url('lab_measure/index')));
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$this->display();
		}
    }
}
