<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lab_Measure extends Lab_Admin_Controller {
    public function __construct(){
		parent::__construct();
		$this->load->model('Measure_Model');
    }
    
    public function index()
    {
    	$this->_getPageData();
        $this->display();
    }
    
    
    private function _getPageData(){
    	try {
            
            if(empty($_GET['page'])){
                $_GET['page'] = 1;
            }
            
            //$condition['select'] = 'a,b';
            $condition['order'] = "gmt_create DESC";
            $condition['pager'] = array(
                'page_size' => config_item('page_size'),
                'current_page' => $_GET['page'],
                'query_param' => ''
            );
            
            if(!empty($_GET['name'])){
                $condition['like']['name'] = $_GET['name'];
            }
            
            /*
            if(!empty($_GET['project_no'])){
                $condition['like']['project_no'] = $_GET['project_no'];
            }
            
            $condition['where'] = array(
                'status !=' => '已删除'
            );
            
            if(!empty($_GET['status'])){
                $condition['where']['status'] = $_GET['status'];
            }
            
            if(!empty($_GET['type'])){
                $condition['where']['type'] = $_GET['type'];
            }
            
            if(!empty($_GET['sdate'])){
                $condition['where']['createtime >='] = strtotime($_GET['sdate']);
            }
            
            if(!empty($_GET['edate'])){
                $condition['where']['createtime <='] = strtotime($_GET['edate']) + 86400;
            }
            
            if(!empty($_GET['region_name'])){
                $condition['where']['region_name'] = trim($_GET['region_name']);
            }
            
            if($_GET['view'] == 'my'){
                $condition['where']['user_id'] = $this->_userProfile['id'];
            }
            */
            
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
    	$this->assign('action','edit');
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('name','度量名称',   'required|max_length[20]|is_unique_not_self['.$this->Measure_Model->_tableName.'.name.id.'.$_POST['id'].'.status.正常]');
			
			if($this->form_validation->run()){
				$_POST['updator'] = $this->_userProfile['name'];
				$flag = $this->Measure_Model->update($_POST);
				
				if($flag){
					$this->assign('success','1');
					$this->assign('message','修改成功');
				}else{
					$info = $_POST;
					$this->assign('message','修改失败');
				}
				
			}else{
				$this->assign('message','数据不能通过校验,修改失败');
			}
			
			
			$id = $_POST['id'];
			$info = $this->Measure_Model->queryById($id);
			$info['name'] = $_POST['name'];
			
		}else{
			$id = $this->uri->segment(4);
			$info = $this->Measure_Model->queryById($id);
		}
		
		$this->assign('info',$info);
		$this->assign('gobackUrl', $this->getGobackUrl());
        $this->display('add');
    }
    
    public function delete(){
    	$id = $this->uri->segment(4);
    	
    	if($this->isPostRequest()){
    		$this->Measure_Model->delete(array('id' => $id , 'updator' => $this->_userProfile['name']));
    		$this->sendFormatJson('success',array('operation' => 'delete','id' => $id , 'text' => '删除成功'));
    	}else{
    		$this->sendFormatJson('failed',array('text' => '请求错误'));
    	}
    }
    
    public function add()
    {
    	$this->assign('action','add');
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('name','度量名称',  'required|max_length[20]|is_unique_by_status['.$this->Measure_Model->_tableName.'.name.status.正常]');
			
			if($this->form_validation->run()){
				$_POST['creator'] = $this->_userProfile['name'];
				$flag = $this->Measure_Model->add($_POST);
				
				if($flag){
					$this->assign('success','1');
					$this->assign('message','添加成功');
				}else{
					$info = $_POST;
					$this->assign('message','添加失败');
				}
				
			}else{
				$this->assign('message','数据不能通过校验,添加失败');
			}
		}
		
		$this->assign('gobackUrl', $this->getGobackUrl());
        $this->display();
    }
    
}
