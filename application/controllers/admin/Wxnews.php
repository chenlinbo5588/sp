<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Wxnews extends Ydzj_Admin_Controller {
	
	private $_nav = null;
	
    public function __construct(){
		parent::__construct();
		
		$this->assign('module_title', '微信新闻');
		
		$this->_nav = array(
			'index' => array(
				'url' => base_url(strtolower(get_class($this))."/index"),
				'title' => '列表'
			),
			'add' => array(
				'url' => base_url(strtolower(get_class($this))."/add"),
				'title' => '添加'
			)
		);
		
		$this->load->model('News_Model');
		
		$this->assign('submenu',$this->_nav);
	
    }
    
    public function index()
    {
        if($_POST['id']){
        	if('发布到公众平台' == $_POST['operator']){
        		$this->_sendWeixinPlatform();
        	}elseif('群发关注者' == $_POST['operator']){
        		$this->_sendWeixinSubscribe();
        	}
        }
        
        $this->_getPageData();
        $this->assign('current_nav','列表');
        $this->display();
    }
    
    /**
     * 群发关注着
     */
    private function _sendWeixinSubscribe(){
    	$this->load->model('Customer_Model');
    	
    	$allUsers = $this->Customer_Model->getList(array(
    		'field' => 'openid'
    	));
    	
    	
    	if($allUsers['data']){
    		$d = $this->News_Model->getList(array(
				'where_in' => array(
					array(
						'key' => 'id' , 'value' => $_POST['id']
					)
				)
	    	));
	    	
	    	$list = array();
	    	foreach($allUsers['data'] as $user){
	    		$list[] = "{$user['openid']}";
	    	}
	    	
	    	$this->load->file(WEIXIN_PATH.'weixin_mp_message_api.php');
	    	$mpMessageApiObj = new Weixin_Mp_Message_Api(config_item('weixin_mp'));
	    	
	    	foreach($d['data'] as $dt){
	    		$result = $mpMessageApiObj->sendMessageByOpenIds($dt['msg_media_id'],$list);
	    		
	    		$this->News_Model->updateMass($dt['id'],$result);
	    	};
    	}
    	
    }
    
    /**
     * 发布到公众平台
     */
    private function _sendWeixinPlatform(){
    	$d = $this->News_Model->getList(array(
			'where_in' => array(
				array(
					'key' => 'id' , 'value' => $_POST['id']
				)
			)
    	));
    	
    	$this->load->file(WEIXIN_PATH.'weixin_mp_media_api.php');
    	$this->load->file(WEIXIN_PATH.'weixin_mp_message_api.php');
    	$mpMediaApiObj = new Weixin_Mp_Media_Api(config_item('weixin_mp'));
    	$mpMessageApiObj = new Weixin_Mp_Message_Api(config_item('weixin_mp'));
    	
    	foreach($d['data'] as $dt){
    		if(empty($dt['media_id'])){
    			$file = str_replace('http://'.$_SERVER['SERVER_NAME'].'/','',$dt['preview_url']);
        		$result = $mpMediaApiObj->uploadMedia(ROOT_DIR.'/'.$file,'image');
        		
        		if($result['media_id']){
        			$this->News_Model->updateMedia($dt['id'],$result);
        			$dt['media_id'] = $result['media_id'];
        		}
    		}
    		
    		if(0 == $dt['publish']){
    			$result2 = $mpMessageApiObj->uploadnews($dt);
    			$this->News_Model->updatePublish($dt['id'],$result2);
    		}
    	};
    	
    }
    
    
    public function _getPageData(){
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
            
            /*
            if(!empty($_GET['project_no'])){
                $condition['like']['project_no'] = $_GET['project_no'];
            }
            
            if(!empty($_GET['name'])){
                $condition['like']['name'] = $_GET['name'];
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
            
            $condition['where']['status'] = 'normal';
            $data = $this->News_Model->getList($condition);
            $this->assign('page',$data['pager']);
            $this->assign('data',$data);
            
        }catch(Exception $e){
            //@todo error code here
        }
    }
    
    
    public function wxpreview(){
    	$id = $this->uri->segment(4);
    	$info = $this->News_Model->queryById($id);
    	
    	$this->load->file(WEIXIN_PATH.'weixin_mp_message_api.php');
    	$mpMessageApiObj = new Weixin_Mp_Message_Api(config_item('weixin_mp'));
    	$result = $mpMessageApiObj->preview($info['msg_media_id']);
    	
    	print_r($result);
    	echo 'Please Watch at mobile';
    }
    
    
    private function _addRules(){
    	$this->form_validation->set_rules('title', '标题', 'required');
        $this->form_validation->set_rules('preview_url', '配图', 'required|valid_url');
        $this->form_validation->set_rules('digest', '描述', 'required');
        
        if(!empty($_POST['jump_url'])){
        	$this->form_validation->set_rules('jump_url', '阅读原文地址', 'valid_url');
        }
        
        $this->form_validation->set_rules('content', '内容', 'required');
    }
    
    
    
    public function edit(){
    	$message = array();
    	$result = 'failed';
    	
    	if($this->isPostRequest()){
    		$this->form_validation->set_rules('id', '新闻ID', 'required');
	        $this->_addRules();
	        
	        $info = $this->News_Model->queryById($_POST['id']);
	        
	        if($this->form_validation->run()){
	            $affectRow = $this->News_Model->update($_POST);
	            if($affectRow){
	            	$info = $this->News_Model->queryById($_POST['id']);
	            	
	            	$message[] = "修改成功";
	            	$result = 'success';
	            	
	                $this->assign('result',$result);
	            }
	        }else{
	        	$message[] = validation_errors_html();
	        }
    	}else{
    		$id = $this->uri->segment(4);
    		$info = $this->News_Model->queryById($id);
    	}
    	
    	$this->assign('info',$info);
    	$this->assign('action','edit');
    	$this->assign('message',$message);
    	
    	$this->display('add');
    	
    }
    
    public function add(){
    	
    	$message = array();
    	$result = 'failed';
    	
    	if($this->isPostRequest()){
    		
    		$this->_addRules();
	        
	        if($this->form_validation->run()){
	            $affectRow = $this->News_Model->add($_POST);
	            if($affectRow){
	            	$message[] = "创建成功";
	            	$result = 'success';
	            	
	                $this->assign('result',$result);
	            }
	        }else{
	        	$message[] = validation_errors_html();
	        }
    	}
    	
    	$this->assign('action','add');
    	$this->assign('message',$message);
    	$this->assign('current_nav','添加');
    	$this->display();
    }
    
}
