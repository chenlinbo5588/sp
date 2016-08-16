<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class Lab_User extends Ydzj_Admin_Controller {
	
	
    public function __construct(){
		parent::__construct();
    }
    
    public function index()
    {
    	$this->assign('action','index');
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
    	
    	$this->load->helper('download');
    	
    	
    	//$condition['select'] = 'a,b';
        $condition['order'] = "gmt_create DESC";
        
        
        if(!empty($_GET['name'])){
            $condition['like']['name'] = $_GET['name'];
        }
        
        $condition['where']['status'] = '正常';
        if($this->_userProfile['id'] != LAB_FOUNDER_ID){
        	$condition['where_in'][] = array('key' => 'id', 'value' => $this->session->userdata('user_ids'));
        }
        
        $data = $this->Lab_User_Model->getList($condition);
    	
    	//$list = $this->Goods_Model->getList();
    	
    	require_once PHPExcel_PATH.'PHPExcel.php';
    	
    	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
        $cacheSettings = array( 'dir'  => ROOT_DIR.'/temp' );
        PHPExcel_Settings::setLocale('zh_CN');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        
        $objPHPExcel = new PHPExcel();
    	$objPHPExcel->setActiveSheetIndex(0);
    	
    	
    	$title = "用户列表";
    	
        $objPHPExcel->getActiveSheet()->setTitle($title);
    	
    	
    	//$this->_writeTitleLine($objPHPExcel);
    	$this->_writeDetailTitleLine($objPHPExcel,1,$this->_getExcelColumnConfig());
    	$this->_writeDetailLine($objPHPExcel,2,$data);
    	
    	
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	
    	$showFilename = "{$title}.xls";
        $filename = "{$title}".$this->_userProfile['id'].".xls";
        
        $objWriter->save(ROOT_DIR.'/temp/'.$filename);
        $objPHPExcel->disconnectWorksheets(); 
        unset($objPHPExcel,$objWriter); 
        force_download($showFilename,  file_get_contents(ROOT_DIR.'/temp/'.$filename));
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
    	if(empty($list['data'])){
    		return;
    	}
    	
    	
        $i = 0;
        foreach($list['data'] as $p){
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
            
            $condition['where']['status'] = '正常'; 
            
            if($this->_userProfile['id'] != LAB_FOUNDER_ID){
            	$condition['where_in'][] = array('key' => 'id', 'value' => $this->session->userdata('user_ids'));
            }
            
            $data = $this->Lab_User_Model->getList($condition);
            
            $this->assign('page',$data['pager']);
            $this->assign('data',$data);
            
        }catch(Exception $e){
            //@todo error code here
        }
    }
    
    
    
    public function edit(){
    	$this->assign('action','edit');
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('account', '操作员登陆账号', 'required|min_length[2]|max_length[15]|alpha_dash|is_unique_not_self['.$this->Lab_User_Model->_tableName.'.account.id.'.$_POST['id'].'.status.正常]');
			$this->_addRules();
				
			for($i = 0 ; $i < 1;  $i++){
				if($_POST['id'] == LAB_FOUNDER_ID && $this->_userProfile['id'] != LAB_FOUNDER_ID){
					$this->assign('message','权限不足,修改失败');
					break;		
				}
				
				if($this->_userProfile['id'] != LAB_FOUNDER_ID){
					if(!in_array($_POST['id'],$this->session->userdata('user_ids'))){
						$this->assign('message','不能修改不在自己管辖实验室的用户');
						break;
					}
				}
				
				
				if(!$this->form_validation->run()){
					$this->assign('message','数据不能通过校验,修改失败');
					break;
				}
				
				
				$_POST['updator'] = $this->_userProfile['name'];
				
				if($this->_userProfile['id'] != LAB_FOUNDER_ID){
					unset($_POST['is_manager']);
				}
				
				
				$flag = $this->Lab_User_Model->update($_POST);
				if($flag < 0){
					$this->assign('message','修改失败');
					break;
				}
				
				$this->assign('success','1');
				$this->_setUserLabs($_POST['id'] , $_POST['lab_id']);
				$this->assign('message','修改成功');
			}
			
			$id = $_POST['id'];
			$info = $this->Lab_User_Model->queryById($id);
			$info['name'] = $_POST['name'];
			
		}else{
			$id = $this->uri->segment(4);
			$info = $this->Lab_User_Model->queryById($id);
			
		}
		
		$this->assign('edit_user_labs',$this->_getUserLabs($id));
		// 当前登陆用户拥有的实验室
		$this->assign('user_labs',$this->session->userdata('user_labs'));
		
		
		$this->assign('info',$info);
		$this->assign('gobackUrl', $this->getGobackUrl());
        $this->display('add');
    }
    
    public function delete(){
    	
    	$id = $this->uri->segment(4);
    	
    	if($this->isPostRequest()){
    		
    		/**
    		 * 只能由创始人操作删除
    		 */
    		
    		
    		if($id == LAB_FOUNDER_ID){
    			$this->sendFormatJson('failed',array('text' => '创始人不能删除'));
    		}
    		
    		
    		// 首先查看 当前登陆用户是那几个实验室的管理员
    		$manager_labs = $this->Lab_Member_Model->getList(array(
    			'where' => array(
    				'user_id' => $this->_userProfile['id'],
    				'is_manager' => 'y'
    			)
    		));
    		
    		$labs = array();
    		
    		foreach($manager_labs['data'] as $lab){
    			$labs[] = $lab['lab_id'];
    		}
    		
    		if(empty($labs)){
    			// 不是任何一个实验室的管理员
    			$this->sendFormatJson('failed',array('text' => '您不是实验室管理员，不能删除'));
    		}
    		
    		// 再坚持 当前将删除的用户 是否在 管辖下
    		$userList = $this->Lab_Member_Model->getList((array(
    			'where' => array(
    				'user_id' => $id,
    			),
    			'where_in' => array(
    				array('key' => 'lab_id' , 'value' => $labs)
    			)
    		)));
    		
    		if(empty($userList)){
    			$this->sendFormatJson('failed',array('text' => '您不是实验室管理员，不能删除'));
    		}
    		
    		//清楚这个用户的成员记录
    		$this->Lab_Member_Model->deleteByUserId($id);
    		$this->Lab_User_Model->delete(array('id' => $id , 'updator' => $this->_userProfile['name']));
    		
    		
    		$this->sendFormatJson('success',array('operation' => 'delete','id' => $id , 'text' => '删除成功'));
    	}else{
    		$this->sendFormatJson('failed',array('text' => '请求错误'));
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
    
    public function checkmanager($val){
    	if($this->_userProfile['id'] == LAB_FOUNDER_ID && in_array($val,array('y','n'))){
    		return true;
    	}else{
    		$this->form_validation->set_message('checkmanager', '%s 无效');
    		return false;
    	}
    }
    
    public function checkowner($lab_ids){
    	$allowIds = $this->session->userdata('user_labs');
    	
    	$checked_labs = explode(',',$lab_ids);
    	
    	if(LAB_FOUNDER_ID != $this->_userProfile['id']){
    		foreach($checked_labs as $checked_lab){
    			if(!in_array($checked_lab , $allowIds)){
    				$this->form_validation->set_message('checkowner', '%s 不能在不属于自己管辖范围的实验室操作用户');
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
        
    }
    
    
    private function _setUserLabs($user_id , $lab_id , $is_manager  = 'n'){
    	
    	$lab_id = str_replace('root,','',$lab_id);
    	
		$this->Lab_Member_Model->deleteByUserId($user_id);
		
		if($is_manager != 'y'){
			$is_manager = 'n';
		}
		
		$this->Lab_Member_Model->addUserLabs($user_id,explode(',', $lab_id),$is_manager,$this->_userProfile['id'],$this->_userProfile['name']);
    }
    
    
    private function _getUserLabs($user_id){
    	$d = $this->Lab_Member_Model->getList(array(
    		'where' => array(
    			'user_id' => $user_id
    		)
    	));
    	
    	return $d['data'];
    }
    
    
    public function add()
    {
    	$this->assign('action','add');
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('account','操作员登陆账号',  'required|min_length[2]|max_length[15]|alpha_dash|is_unique_by_status['.$this->Lab_User_Model->_tableName.'.account.status.正常]');
			
			$this->_addRules();
			for($i = 0 ; $i < 1;  $i++){
				if(!$this->form_validation->run()){
					$info = $_POST;
					$this->assign('message','数据不能通过校验,添加失败');
					break;
				}
				
				$_POST['creator'] = $this->_userProfile['name'];
				
				if($this->_userProfile['id'] != LAB_FOUNDER_ID){
					$_POST['is_manager'] = 'n';
				}
				
				$uid = $this->Lab_User_Model->add($_POST);
				
				if($uid <= 0){
					$info = $_POST;
					$this->assign('message','添加失败');
					break;
				}
				
				$this->assign('success','1');
				$this->_setUserLabs($uid , $_POST['lab_id'] );
				$this->assign('message','添加成功');
			}
			
		}
		
		$this->assign('user_labs',$this->session->userdata('user_labs'));
		$this->assign('info',$info);
		$this->assign('gobackUrl', $this->getGobackUrl());
        $this->display();
    }
    
    
    private function _searchUser(){
    	if(empty($_GET['page'])){
            $_GET['page'] = 1;
        }
        
        $lab_id = $_GET['id'];
        $isManager = false;
        
        //$condition['select'] = 'a,b';
        $condition['order'] = "gmt_create DESC";
        $condition['pager'] = array(
            'page_size' => 10,
            'current_page' => $_GET['page'],
            'query_param' => ''
        );
        
        if(!empty($_GET['username'])){
            $condition['like']['name'] = $_GET['username'];
        }
        
        $condition['where']['status'] = '正常';
        $data = $this->Adminuser_Model->getList($condition);
        
        $data['pager']['shortStyle'] = true;
        $data['pager']['callJs'] = "ajaxPage";
        
        
        /**
         * 获取已经是当前成员
         */
        $uids = array();
        foreach($data['data'] as $item){
        	$uids[] = $item['id'];
        }
        
        $memberList = array();
        if($uids){
        	$labUsers = $this->Lab_Member_Model->getLabUserList($lab_id , '', $uids);
        	foreach($labUsers as $u){
        		$u['can_drop_manager'] = false;
        		$u['can_drop'] = true;
        		
        		
        		
        		if($u['user_id'] == $this->_userProfile['id']){
        			//自己不能取消自己
        			
        			if($u['is_manager'] == 'y'){
        				$u['can_drop_manager'] = false;
        			}
        			
        			$u['can_drop'] = false;
        		}elseif($u['uid'] == $this->_userProfile['id']){
        			//该记录是自己设置的，就可以进
        			$u['can_drop_manager'] = true;
        		}
        		
        		if($this->_userProfile['id'] == LAB_FOUNDER_ID){
        			$u['can_drop_manager'] = true;
        			$u['can_drop'] = true;
        		}
        		
        		$memberList[$u['user_id']] = $u;
        	}
        }
        
        if($this->Lab_Member_Model->getLabManager($this->_userProfile['id'],$lab_id)){
			$isManager = true;
		}
        
        $this->assign('isManager',$isManager);
        $this->assign('memberList',$memberList);
        $this->assign('page',$data['pager']);
        $this->assign('data',$data);
    }
    
    public function search_popup(){
    	$this->_searchUser();
    	$this->display();
    }
    
    public function search(){
    	$this->_searchUser();
    	$this->display();
    }
    
}
