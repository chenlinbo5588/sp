<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class Lab_User extends MyYdzj_Controller {
	
	
    public function __construct(){
		parent::__construct();
    }
    
    public function index()
    {
    	$this->assign('queryStr',$_SERVER['QUERY_STRING']);
    	$this->_getPageData();
        $this->display();
    }
    
    private function _getExcelColumnConfig(){
		return array(
    		array(
    			'col' => 'A',
    			'name' => '账号',
    			'width' => 20,
    			'db_key' => 'account'
    		),
    		array(
    			'col' => 'B',
    			'name' => '名称',
    			'width' => 20,
    			'db_key' => 'name'
    		),
    		array(
    			'col' => 'C',
    			'name' => '创建时间',
    			'width' => 20,
    			'db_key' => 'gmt_create'
    		)
    	);
		
	}    


    public function export(){
    	
    	
    	if($this->isPostRequest()){
    		$this->load->helper('download');
    	
	    	//$condition['select'] = 'a,b';
	        $condition['order'] = "gmt_create DESC";
	        
	        
	        if(!empty($_GET['name'])){
	            $condition['like']['name'] = $_GET['name'];
	        }
	        
	        $condition['where']['status'] = '正常';
	        if($this->_loginUID != LAB_FOUNDER_ID){
	        	$condition['where_in'][] = array('key' => 'uid', 'value' => $this->session->userdata('user_ids'));
	        }
	        
	        $data = $this->Member_Model->getList($condition);
	    	
	    	//$list = $this->Goods_Model->getList();
	    	
	    	require_once PHPExcel_PATH.'PHPExcel.php';
	    	
	    	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
	        $cacheSettings = array( 'dir'  => PHPExcel_TEMP_PATH );
	        PHPExcel_Settings::setLocale('zh_CN');
	        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	        
	        $objPHPExcel = new PHPExcel();
	    	$objPHPExcel->setActiveSheetIndex(0);
	    	
	    	
	    	$title = "operator";
	    	
	        $objPHPExcel->getActiveSheet()->setTitle($title);
	    	
	    	
	    	//$this->_writeTitleLine($objPHPExcel);
	    	$this->_writeDetailTitleLine($objPHPExcel,1,$this->_getExcelColumnConfig());
	    	$this->_writeDetailLine($objPHPExcel,2,$data);
	    	
	    	
	    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	    	
	    	$showFilename = "{$title}.xls";
	        $filename = "{$title}".$this->_loginUID.".xls";
	        
	        $objWriter->save(PHPExcel_TEMP_PATH.$filename);
	        $objPHPExcel->disconnectWorksheets(); 
	        unset($objPHPExcel,$objWriter); 
	        force_download($showFilename,  file_get_contents(PHPExcel_TEMP_PATH.$filename));
    		
    	}else{
    		$this->display();
    		
    	}
    	
    }
    
    /*
    private function _writeTitleLine($objPHPExcel){
    	$objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
    	$objPHPExcel->getActiveSheet()->setCellValue('A1', '用户列表');
    	
    	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
    	
    	$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
                array(
                    'alignment' => array(
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'font'    => array(
                        'bold'      => true,
                        'size'     => 16
                    )
                )
        );
    	
    }
    */
    
    private function _writeDetailTitleLine($objPHPExcel, $start , $title){
    	foreach($title as $value){
    		$objPHPExcel->getActiveSheet()->setCellValue($value['col'].$start, $value['name']);
    		$objPHPExcel->getActiveSheet()->getColumnDimension($value['col'])->setWidth($value['width']);
    	}
    	
    	$objPHPExcel->getActiveSheet()->getStyle('A'.$start.':'.$title[count($title) -1 ]['col'].$start)->applyFromArray(
                array(
                    'font'    => array(
                        'bold'      => true,
                        'size'     => 12
                    )
                )
        );
    }
    
    private function _writeDetailLine($objPHPExcel, $row_start , $list){
    	if(empty($list)){
    		return;
    	}
    	
    	
        $i = 0;
        foreach($list as $p){
            $current_row = $i + $row_start;
            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$current_row,$p['account']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$current_row,$p['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$current_row,date("Y-m-d",$p['gmt_create']));
            $i++;
        }
        
        /*
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
                array(
                    'font'    => array(
                        'bold'      => true,
                        'size'     => 20
                    )

                )
        );
        */
        
        
        $objPHPExcel->getActiveSheet()->getStyle('A'.($row_start - 1).':C'.$current_row)->applyFromArray(
                array(
                    'alignment' => array(
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FF000000')
                        )
                    )
                )
        );
        
    }
    
    
    private function _getPageData(){
    	try {
            $page = $this->input->get_post('page');
            $page_size = $this->input->get_cookie('page_size');
            
            if(!$page_size){
            	$page_size = config_item('page_size');
            }
            
            if($page_size > 100){
            	$page_size = config_item('page_size');
            }
            
            if(empty($page)){
                $page = 1;
            }
            
            //$condition['select'] = 'a,b';
            
            $condition['where']['oid'] = $this->_currentOid;
            $condition['order'] = "gmt_create DESC";
            $condition['pager'] = array(
                'page_size' => $page_size,
                'current_page' => intval($page),
                'query_param' => '',
                'call_js' => 'search_page',
				'form_id' => '#formSearch'
            );
            
            $name = trim($this->input->get_post('username'));
            
            if(!empty($name)){
                $searchCondition['where']['username'] = $name;
                $memberInfo = $this->Member_Model->getFirstByKey($name,'username','uid');
                
                if($memberInfo){
                	$condition['where']['uid'] = $memberInfo['uid'];
                }else{
                	$condition['where']['1 = 2'] = null;
                }
            }
            
            if($this->_loginUID != $this->_currentOid){
            	$condition['where_in'][] = array('key' => 'uid', 'value' => $this->session->userdata('user_labs'));
            }
            
            $data = $this->lab_service->getLabMemberListByCondition($condition,$searchCondition);
            $this->assign('page',$data['list']['pager']);
            $this->assign($data);
            
        }catch(Exception $e){
            //@todo error code here
        }
    }
    
    
    
    public function edit(){
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('account', '操作员登陆账号', 'required|min_length[2]|max_length[15]|alpha_dash|is_unique_not_self['.$this->Member_Model->getTableRealName().'.account.id.'.$id.'.status.正常]');
			$this->_addRules();
			
			for($i = 0 ; $i < 1;  $i++){
				if($id == LAB_FOUNDER_ID && $this->_loginUID != LAB_FOUNDER_ID){
					$this->jsonOutput('对不起，您无权修改创始人资料');
					break;		
				}
				
				
				if($this->_loginUID != LAB_FOUNDER_ID){
					if(!in_array($id,$this->session->userdata('user_ids'))){
						$this->jsonOutput('对不起，不能修改不在自己管辖实验室的用户');
						break;
					}
				}
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				
				$_POST['updator'] = $this->_profile['basic']['name'];
				if($this->_loginUID != LAB_FOUNDER_ID){
					unset($_POST['is_manager']);
				}
				
				$flag = $this->Member_Model->updateInfo($_POST);
				if($flag < 0){
					$this->jsonOutput($this->db->get_error_info());
					break;
				}
				
				$this->_setUserLabs($id , $_POST['lab_id']);
				
				$this->jsonOutput('保存成功');
			}
			
		}else{
			$info = $this->Member_Model->getFirstByKey($id);
			
			$labIds = $this->lab_service->getUserJoinedLabList($id);
			$ids = array();
			foreach($labIds as $lab){
				$ids[] = $lab['lab_id'];
			}
			
			$this->assign('lab_id',implode(',',$ids));
			$this->assign('edit_user_labs',$labIds);
			
			$this->assign('roleList',$this->_getRoleAllowList());
			
			
			// 当前登陆用户拥有的实验室
			$this->assign('user_labs',$this->session->userdata('user_labs'));
			$this->assign('info',$info);
			
	        $this->display('lab_user/add');
		}
		
		
    }
    
    public function delete(){
    	
    	$id = $this->input->get_post('id');
    	
    	if($this->isPostRequest()){
    		
    		for($i = 0; $i < 1; $i++){
    			
    			/**
	    		 * 只能由创始人操作删除
	    		 */
	    		if($id == LAB_FOUNDER_ID){
	    			$this->jsonOutput('创始人不能删除');
	    			break;
	    		}
	    		
	    		
	    		// 首先查看 当前登陆用户是那几个实验室的管理员
	    		$manager_labs = $this->Lab_Member_Model->getList(array(
	    			'where' => array(
	    				'user_id' => $this->_loginUID,
	    				'is_manager' => 'y'
	    			)
	    		));
	    		
	    		$labs = array();
	    		
	    		foreach($manager_labs as $lab){
	    			$labs[] = $lab['lab_id'];
	    		}
	    		
	    		if(empty($labs)){
	    			// 不是任何一个实验室的管理员
	    			$this->jsonOutput('您不是实验室管理员，无权删除');
	    			break;
	    		}
	    		
	    		// 再检查 当前将删除的用户 是否在 管辖下
	    		$userList = $this->Lab_Member_Model->getList((array(
	    			'where' => array(
	    				'user_id' => $id,
	    			),
	    			'where_in' => array(
	    				array('key' => 'lab_id' , 'value' => $labs)
	    			)
	    		)));
	    		
	    		if($this->_loginUID != LAB_FOUNDER_ID && empty($userList)){
	    			$this->jsonOutput('您不是该用户所在实验室管理员，无权删除');
	    			break;
	    		}
	    		
	    		//清楚这个用户的成员记录
	    		$this->Lab_Member_Model->deleteByUserId($id);
	    		$this->Member_Model->updateByWhere(array('status' => '已删除', 'updator' => $this->_profile['basic']['name']),array('id' => $id));
	    		
	    		$this->jsonOutput('删除成功');
    		}
    		
    		
    	}else{
    		$this->jsonOutput('请求错误');
    	}
    }
    
    
    public function checkstatus($val){
    	
    	if($val != '正常'){
    		$this->form_validation->set_message('checkstatus', '%s 无效');
            return FALSE;
    	}else{
    		return true;
    	}
    }
    
    
    private function _getRoleAllowList(){
    	
    	$this->load->model('Role_Model');
    	
    	/**
		 * 只显示自己创建的以及 自己所在角色组
		 */
		$roleList = $this->Role_Model->getList(array(
			'where' => array(
				'add_uid' => $this->_loginUID
			),
			'or_where' => array(
				'id' => $this->_profile['basic']['group_id']
			)
		));
		
		return $roleList;
    	
    }
    
    
    public function checkrole($roleid){
    	
    	$allowRoleList = $this->_getRoleAllowList();
    	
    	$roleIds = array();
    	foreach($allowRoleList as $role){
    		$roleIds[] = $role['id'];
    	}
    	
    	if(in_array($roleid,$roleIds)){
    		return true;
    	}else{
    		$this->form_validation->set_message('checkrole', '%s 参数无效');
    		return false;
    	}
    	
    }
    
    public function checkmanager($val){
    	if($this->_loginUID == LAB_FOUNDER_ID && in_array($val,array('y','n'))){
    		return true;
    	}else{
    		$this->form_validation->set_message('checkmanager', '%s 无效');
    		return false;
    	}
    }
    
    public function checkowner($lab_ids){
    	$allowIds = $this->session->userdata('user_labs');
    	$checked_labs = explode(',',$lab_ids);
    	
    	if(LAB_FOUNDER_ID != $this->_loginUID){
    		foreach($checked_labs as $checked_lab){
    			if(!in_array($checked_lab , $allowIds)){
    				$this->form_validation->set_message('checkowner', '%s 只能勾选您已经加入的实验室');
            		return FALSE;
    			}
    		}
    		
    	}
    	
    	return true;
    }
    
    
    private function _addRules(){
    	$this->form_validation->set_rules('name','操作员名称',  'required|max_length[20]');
			
		if(!empty($_POST['psw'])){
            $this->form_validation->set_rules('psw', '密码', 'required|min_length[6]|max_length[10]|alpha_dash|matches[psw2]');
            $this->form_validation->set_rules('psw2', '密码确认', 'required|min_length[6]|max_length[10]|alpha_dash');
        }
        
        $this->form_validation->set_rules('lab_id','归属实验室',  'required|callback_checkowner');
        
        if(!empty($_POST['status'])){
        	$this->form_validation->set_rules('status', '重新激活', 'callback_checkstatus');
        }
        
        if(!empty($_POST['is_manager'])){
        	$this->form_validation->set_rules('is_manager','设为管理员',  'callback_checkmanager');
        }
        
        
        $this->form_validation->set_rules('group_id','角色分组',  'callback_checkrole');
        
        
        if(!empty($_POST['group_id'])){
        	$this->form_validation->set_rules('group_id','角色分组',  'in_db_list['.$this->Role_Model->getTableRealName().'.id]');
        }
    }
    
    private function _setUserLabs($user_id , $lab_id , $is_manager  = 'n'){
    	$lab_id = str_replace('root,','',$lab_id);
    	
		$this->Lab_Member_Model->deleteByUserId($user_id);
		
		if($is_manager != 'y'){
			$is_manager = 'n';
		}
		
		$this->Lab_Member_Model->addUserLabs($user_id,explode(',', $lab_id),$is_manager,$this->_loginUID,$this->_profile['basic']['name']);
    }
    
    
    public function add()
    {
		if($this->isPostRequest()){
			$this->form_validation->set_rules('account','操作员登陆账号',  'required|min_length[2]|max_length[15]|alpha_dash|is_unique_by_status['.$this->Member_Model->getTableRealName().'.account.status.正常]');
			
			$this->_addRules();
			for($i = 0 ; $i < 1;  $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$_POST['creator'] = $this->_profile['basic']['name'];
				if($this->_loginUID != LAB_FOUNDER_ID){
					$_POST['is_manager'] = 'n';
				}
				
				$uid = $this->Member_Model->add($_POST);
				
				if($uid <= 0){
					$this->jsonOutput($this->db->get_error_info());
					break;
				}
				
				$this->_setUserLabs($uid , $_POST['lab_id'] );
				//$this->jsonOutput('保存成功',array('redirectUrl' => admin_site_url('lab_user/add')));
				$this->jsonOutput('保存成功',array('redirectUrl' => admin_site_url('lab_user/add')));
			}
			
		}else{
			
			$currentLabIds = $this->session->userdata('user_labs');
			
			$this->assign('roleList',$this->_getRoleAllowList());
			
			$this->assign('lab_id',implode(',',$currentLabIds));
			// 当前登陆用户拥有的实验室
			$this->assign('user_labs',$currentLabIds);
			
	        $this->display();
		}
    }
    
}
