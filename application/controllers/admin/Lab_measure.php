<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Lab_Measure extends Ydzj_Admin_Controller {
    public function __construct(){
		parent::__construct();
		$this->load->model('Measure_Model');
		
		$this->assign('action',$this->uri->rsegment(2));
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
            $condition['order'] = "gmt_create DESC";
            $condition['pager'] = array(
                'page_size' => config_item('page_size'),
                'current_page' => $page,
                'query_param' => '',
                'call_js' => 'search_page',
				'form_id' => '#formSearch'
            );
            
            if(!empty($_GET['name'])){
                $condition['like']['name'] = $_GET['name'];
            }
            
            $condition['where']['status'] = '正常';
            $data = $this->Measure_Model->getList($condition);
            $this->assign('page',$data['pager']);
            $this->assign('data',$data);
            
        }catch(Exception $e){
            //@todo error code here
        }
    }
    
    
    public function compare($pid){
    	$id = $_POST['id'];
    	$info = $this->Goods_Category_Model->queryById($id);
    	if ($pid == $info['id'])
        {
            $this->form_validation->set_message('compare', '%s 不能为自己');
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
			$this->form_validation->set_rules('name','度量单位名称',   'required|max_length[20]|is_unique_not_self['.$this->Measure_Model->getTableRealName().'.name.id.'.$id.']');
			
			
			for($i = 0; $i < 1; $i++){
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$_POST['updator'] = $this->_adminProfile['basic']['name'];
				
				$flag = $this->Measure_Model->update($_POST,array('id' => $id));
				
				if($flag < 0){
					$this->jsonOutput($this->db->get_error_info());
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
			
		}else{
			$info = $this->Measure_Model->getFirstByKey($id);
			
			$this->assign('info',$info);
        	$this->display('lab_measure/add');
		}
    }
    
    public function delete(){
    	$id = $this->input->post('id');
    	
    	if($id && $this->isPostRequest()){
    		if(is_array($id)){
    			$rows = $this->Measure_Model->deleteByCondition(array(
    				'where_in' => array(
    					array('key' => 'id','value' => $id)
    				
    				)
    			));
    		}else{
    			$rows = $this->Measure_Model->delete(array('id' => $id));
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
		if($this->isPostRequest()){
			$this->form_validation->set_rules('name','度量名称',  'required|max_length[20]|is_unique['.$this->Measure_Model->getTableRealName().'.name]');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$_POST['creator'] = $this->_adminProfile['basic']['name'];
				$flag = $this->Measure_Model->_add($_POST);
				
				if($flag > 0){
					$this->jsonOutput('保存成功',array('redirectUrl' => admin_site_url('lab_measure/add')));
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$this->display();
		}
    }
    
}
