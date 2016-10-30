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
    	
    	$this->assign('isFounder',$this->isOrginationFounder());
    	$this->assign('manager_labs',$this->session->userdata('manager_labs'));
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
	        if(!$this->isOrginationFounder()){
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
            	$condition['where_in'][] = array('key' => 'lab_id', 'value' => $this->session->userdata('user_labs'));
            }
       
            $data = $this->lab_service->getLabMemberListByCondition($condition,$searchCondition,$this->_currentOid);
            
            $this->assign('page',$data['list']['pager']);
            $this->assign($data);
            
        }catch(Exception $e){
            //@todo error code here
        }
    }
    
    
    /**
     * 
     */
    public function edit(){
		$id = $this->input->get_post('uid');
		
		if($this->isPostRequest()){
			$this->_addRules();
			
			for($i = 0 ; $i < 1;  $i++){
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$isManager = $this->input->post('is_manager');
				if(!$this->isOrginationFounder()){
					$isManager = 'n';
				}
				
				$roleId = $this->input->post('role_id');
				$postId = $this->input->post('lab_id');
				$postIds = explode(',',$postId);
				
				$this->Lab_Member_Model->deleteByCondition(array(
					'where' => array(
						'oid' => $this->_currentOid,
						'uid' => $id
					)
				));
				
				//构造批量插入数组
				$insertData = array();
				foreach($postIds as $labid){
					$insertData[] = array(
						'uid' => $id,
						'oid' => $this->_currentOid,
						'lab_id' => $labid,
						'is_manager' => $isManager,
						'add_uid' => $this->_profile['basic']['uid'],
						'creator' => $this->_profile['basic']['username'],
					);
				}
				
				$rows = $this->Lab_Member_Model->batchInsert($insertData);
				$this->lab_service->updateOrgination(array('role_id' => $roleId),$id,$this->_currentOid);
				
				if($rows < 0){
					$this->jsonOutput($this->db->get_error_info());
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
			
		}else{
			
			$info = $this->Member_Model->getFirstByKey($id,'uid','uid,username,qq,email,mobile');
			$labIds = $this->lab_service->getUserJoinedLabListByOrgination($id,$this->_currentOid);
			$ids = array();
			foreach($labIds as $labMember){
				$ids[] = $labMember['lab_id'];
			}
			
			$info['is_manager'] = 'n';
			$this->assign('lab_id',implode(',',$ids));
			$this->assign('user_labs',json_encode($ids));
			$this->assign('roleList',$this->_getRoleAllowList());
			$this->assign('info',$info);
			
			
			if($id == $this->_loginUID){
				$this->assign('currentRoleId',$this->_currentRoleId);
			}else{
				//获取
				$roleInfo = $this->lab_service->getOrginationByCondition(array(
					'select' => 'role_id',
					'where' => array(
						'oid' => $this->_currentOid,
						'uid' => $id
					),
					'limit' => 1
				),$id);
				
				if($roleInfo){
					$this->assign('currentRoleId',$roleInfo[0]['role_id']);
				}
				
			}
			
	        $this->display('lab_user/add');
		}
		
		
    }
    
    public function delete(){
    	
    	$id = $this->input->get_post('id');
    	
    	if(!empty($id) && $this->isPostRequest()){
    		
    		for($i = 0; $i < 1; $i++){
    			$isFounder = $this->isOrginationFounder();
	    		$managerLabs = $this->session->userdata('manager_labs');
	    		
	    		
	    		if(!$isFounder && empty($managerLabs)){
	    			// 不是任何一个实验室的管理员
	    			$this->jsonOutput('您不是实验室管理员，无权删除');
	    			break;
	    		}
	    		
	    		/**
	    		 * id[]:144_54
				 * id[]:144_52
				 * id[]:144_51
	    		 */
	    		 
	    		$labUserAssoc = array();
	    		
	    		foreach($id as $aLabMember){
	    			$fields = explode('_',$aLabMember);
	    			if($labUserAssoc[$fields[1]]){
	    				$labUserAssoc[$fields[1]][] = $fields[0];
	    			}else{
	    				$labUserAssoc[$fields[1]] = array($fields[0]);
	    			}
	    		}
	    		
	    		//print_r($labUserAssoc);
	    		$ignoreCount = array();
	    		
	    		foreach($labUserAssoc as $lab_id  => $labMembers){
	    			if($isFounder || in_array($lab_id,$managerLabs)){
	    				if($labMembers){
	    					$this->Lab_Member_Model->deleteByCondition(array(
		    					'where' => array(
		    						'oid' => $this->_currentOid,
		    						'lab_id' => $lab_id,
		    					),
		    					'where_in' => array(
		    						array('key' => 'uid','value' => $labMembers)
		    					)
		    				));
		    				
		    				$this->Lab_Cache_Model->updateByCondition(array(
		    					'expire' => -1
		    				),array(
		    					'where' => array(
		    						'oid' => $this->_currentOid,
		    					),
		    					'where_in' => array(
		    						array('key' => 'uid','value' => $labMembers)
		    					)
		    				));
	    				}
	    			}else{
	    				$ignoreCount[$lab_id] = $labMembers;
	    			}
	    		}
	    		
	    		if(empty($ignoreCount)){
	    			$this->jsonOutput('删除成功');
	    		}else{
	    			$this->jsonOutput('删除成功,某些记录因您非管理员将被忽略该操作');
	    		}
	    		
    		}
    		
    	}else{
    		$this->jsonOutput('请求错误');
    	}
    }
    
    
    /**
     * 查找当前用户可见的角色列表
     */
    private function _getRoleAllowList(){
    	/**
		 * 只显示自己创建的以及 自己所在角色
		 */
		$roleList = array();
		if($this->isOrginationFounder()){
			$roleList = $this->Lab_Role_Model->getList(array(
				'where' => array(
					'add_uid' => $this->_loginUID
				)
			));
		}else{
			//如果不是创始人，则显示给到该用户的角色列表
			$roleList = $this->Lab_Role_Model->getList(array(
				'where' => array(
					'add_uid' => $this->_loginUID,
				),
				'or_where' => array(
					'id' => $this->_currentRoleId
				)
			));
		}
		
		return $roleList;
    	
    }
    
    
    public function checkRole($roleid){
    	$allowRoleList = $this->_getRoleAllowList();
    	$roleIds = array();
    	foreach($allowRoleList as $role){
    		$roleIds[] = $role['id'];
    	}
    	
    	if(in_array($roleid,$roleIds)){
    		return true;
    	}else{
    		$this->form_validation->set_message('checkRole', '%s 参数无效');
    		return false;
    	}
    }
    
    /**
     * 校验只能是自己当前已加入的实验室，
     */
    public function checkOwner($lab_ids){
    	$allowIds = $this->session->userdata('user_labs');
    	$checked_labs = explode(',',$lab_ids);
    	
    	if(!$this->isOrginationFounder()){
    		foreach($checked_labs as $checked_lab){
    			if(!in_array($checked_lab , $allowIds)){
    				$this->form_validation->set_message('checkOwner', '%s 只能勾选您已经加入的实验室');
            		return FALSE;
    			}
    		}
    	}
    	
    	return true;
    }
    
    
    /**
     * 检查用户是否已经加入到当前组织
     */
    public function checkUserJoin($username){
    	$member = $this->Member_Model->getFirstByKey($username,'username','uid');
    	
    	$info = $this->lab_service->getOrginationByCondition(array(
    		'where' => array(
    			'uid' => $member['uid'],
    			'oid' => $this->_currentOid,
    		)
    	),$member['uid']);
    	
    	if($info){
    		//如果已加入的话，再看用户是否再lab_member 中还有对照记录，如果没有，则该用户一个实验室也没有参与，这样允许加入，
    		//否则将出现用户被清空后 无法再加入
    		$hasLabs = $this->Lab_Member_Model->getList(array(
    			'select' => 'lab_id',
    			'where' => array(
	    			'uid' => $member['uid'],
	    			'oid' => $this->_currentOid,
	    		),
	    		'limit' => 1
    		));
    		
    		if($hasLabs){
    			$this->form_validation->set_message('checkUserJoin', '%s 已经加入到该当前组织架构');
            	return FALSE;
    		}
    	}
    	
    	return true;
    }
    
    
    private function _addRules(){
        if(!empty($_POST['is_manager'])){
        	$this->form_validation->set_rules('is_manager','设为实验室管理员',  'required|in_list[y,n]');
        }
        
        $this->form_validation->set_rules('lab_id','归属实验室',  'required|callback_checkOwner');
        $this->form_validation->set_rules('role_id','归属角色',  'required|callback_checkRole');
    }
    
   
    
    /**
     * 添加成员
     */
    public function add()
    {
		if($this->isPostRequest()){
			$this->form_validation->set_rules('username','操作员登陆账号',  'required|in_db_list['.$this->Member_Model->getTableRealName().'.username]|callback_checkUserJoin');
			
			$this->_addRules();
			for($i = 0 ; $i < 1;  $i++){
				
				
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$isManager = $this->input->post('is_manager');
				if(!$this->isOrginationFounder()){
					$isManager = 'n';
				}
				
				$username = $this->input->post('username');
				$memberInfo = $this->Member_Model->getFirstByKey(trim($username),'username','uid,username,email');
				if($memberInfo['uid'] == $this->_loginUID){
					$this->jsonOutput('该用户是创始人，无需添加');
					break;
				}
				
				//以逗号分隔的实验室id 列表
				$labIds = $this->input->post('lab_id');
				$labIdArray = explode(',',$labIds);
				
				
				//构造批量插入数组
				$insertData = array();
				foreach($labIdArray as $labid){
					$insertData[] = array(
						'uid' => $memberInfo['uid'],
						'oid' => $this->_currentOid,
						'lab_id' => $labid,
						'is_manager' => $isManager,
						'add_uid' => $this->_profile['basic']['uid'],
						'creator' => $this->_profile['basic']['username'],
					);
				}
				
				
				$rows = $this->Lab_Member_Model->batchInsert($insertData);
				$currentOrgation = $this->session->userdata('lab');
				
				$this->lab_service->addOrgination($currentOrgation['current']['name'],$memberInfo['uid'],$this->_currentOid,$this->input->post('role_id'),0,true);
				
				
				if($rows <= 0){
					$this->jsonOutput($this->db->get_error_info());
					break;
				}
				
				$data1 = array(
					'msg_type' => 0,
					'uid' => $memberInfo['uid'],
					'from_uid' => $this->_loginUID,
					'title' => '加入新的组织机构提醒',
					'content' => '您已加新的组织机构,请点击 <a href="'.site_url('lab/orglist').'">点击查看</a>'
				);
				
				$this->message_service->pushPmMessageToUser($data1,$memberInfo['uid']);
				$this->message_service->initEmail($this->_siteSetting);
				
				$flag = $this->message_service->sendEmail($memberInfo['email'],
							$data1['title'],
							$data1['content']
						);
				
				$this->jsonOutput('添加成功,用户将收到站内信以及邮件通知',array('redirectUrl' => site_url('lab_user/index')));
			}
			
		}else{
			
			$currentLabIds = $this->session->userdata('user_labs');
			$info['is_manager'] = 'n';
			
			$this->assign('roleList',$this->_getRoleAllowList());
			$this->assign('lab_id',implode(',',$currentLabIds));
			// 当前登陆用户拥有的实验室
			$this->assign('info',$info);
	        $this->display();
		}
    }
    
}
