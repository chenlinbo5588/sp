<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lab extends MyYdzj_Controller {
	
    public function __construct(){
		parent::__construct();
		
		$this->_breadCrumbs[] = array(
			'title' => '实验室管理中心',
			'url' => 'lab/index'
		);
		
    }
    
    public function index()
    {
    	
        $this->display();
    }
    
    /**
     * 导出列
     */
    private function _getExcelColumnConfig(){
		return array(
    		array(
    			'col' => 'A',
    			'name' => '实验室地址',
    			'width' => 100,
    			'db_key' => 'address'
    		),
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
            
            $condition['where']['uid'] = $this->_loginUID;
            $condition['order'] = "gmt_create DESC";
            $condition['pager'] = array(
                'page_size' => $page_size,
                'current_page' => intval($page),
                'query_param' => '',
                'call_js' => 'search_page',
				'form_id' => '#formSearch'
            );
            
            $data = $this->lab_service->getOrginationByCondition($condition,$this->_loginUID);
            $this->assign('page',$data['pager']);
            $this->assign('list',$data['data']);
            
        }catch(Exception $e){
            //@todo error code here
        }
    }
    
	/**
	 * 
	 */
	public function orglist(){
		
		$this->_breadCrumbs[] = array(
			'title' => '加入的组织机构',
			'url' => $this->uri->uri_string
		);
		
		
		//update status
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$id = $this->input->post('id');
				
				$this->Orgination_Model->update(array(
					'is_default' => 0
				),array('uid' => $this->_loginUID));
				
				$this->Orgination_Model->update(array(
					'is_default' => 1
				),array('oid' => $id,'uid' => $this->_loginUID));
				
				
				$this->_initUserParam();
			}
			
		}
		
		
		$this->_getPageData();
		$this->display();
	}
    
    
    /**
     * 导出数据
     */
    public function export(){
    	if($this->isPostRequest()){
    		$this->load->helper('download');
    	
	    	//$condition['select'] = 'a,b';
	        $condition['order'] = "id  ASC , pid ASC";
	        
	        $name = trim($this->input->get_post('name'));
	        
	        if(!empty($name)){
	            $condition['like']['name'] = $name;
	        }
	        
	        $condition['where']['status'] = '0';
	        
	        if(!$this->isOrginationFounder()){
	        	$condition['where_in'][] = array(
	        		'key' => 'id',
	        		'value' => $this->session->userdata('user_labs')
	        	);
	        }
	        
	        $this->Lab_Model->clearMenuTree();
	        
	        
	        // @todo 防止超大记录 ，先预判记录数量 做判断 防止溢出
	        $data = $this->Lab_Model->getList($condition);
	
	    	$this->load->file(PHPExcel_PATH.'PHPExcel.php');
	    	
	    	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
	        $cacheSettings = array( 'dir'  => PHPExcel_TEMP_PATH );
	        PHPExcel_Settings::setLocale('zh_CN');
	        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	        
	        $objPHPExcel = new PHPExcel();
	    	$objPHPExcel->setActiveSheetIndex(0);
	    	
	    	$title = "lab";
	    	
	        $objPHPExcel->getActiveSheet()->setTitle($title);
	    	
	    	
	    	$this->_writeTitleLine($objPHPExcel);
	    	$this->_writeDetailTitleLine($objPHPExcel,2,$this->_getExcelColumnConfig());
	    	$this->_writeDetailLine($objPHPExcel,3,$data);
	    	
	    	
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
    
    private function _writeTitleLine($objPHPExcel){
    	//$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
    	//$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
    	$objPHPExcel->getActiveSheet()->setCellValue('A1', '实验室列表');
    	
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
    
    private function _writeDetailTitleLine($objPHPExcel, $start , $title){
    	foreach($title as $value){
    		$objPHPExcel->getActiveSheet()->setCellValue($value['col'].$start, $value['name']);
    		$objPHPExcel->getActiveSheet()->getColumnDimension($value['col'])->setWidth($value['width']);
    	}
    }
    
    private function _writeDetailLine($objPHPExcel, $row_start , $list){
    	
    	if(empty($list)){
    		return;
    	}
    	
        $i = 0;
        foreach($list as $p){
            $current_row = $i + $row_start;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$current_row,$p['address']);
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
        
        $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray(
                array(
                    'font'    => array(
                        'bold'      => true,
                        'size'     => 12
                    )
                )
        );
        
        $objPHPExcel->getActiveSheet()->getStyle('A2:A'.$current_row)->applyFromArray(
                array(
                    'alignment' => array(
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
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
    
    
    
    
    
    /**
     * 
     */
   	public function getTreeXML(){
   		header("Content-type:text/xml");
   		
   		echo $this->lab_service->getTreeXML($this->_loginUID,$this->_currentOid,'treexml');
   	}
    
    /**
     * 权属
     */
    public function checkowner($pid){
    	
    	if($this->_loginUID != $this->_currentOid){
    		$id = $this->input->post('id');
    		
	    	$info = $this->Lab_Model->getFirstByKey($id);
	    	$userLabs = $this->session->userdata('user_labs');
	    	
	    	foreach($userLabs as $lab){
	    		$allowIds[] = $lab['lab_id'];
	    	}
	    	
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
    
    
    /**
     * 更新缓存
     */
    private function _updateCache(){
    	//只需将 机构全树  即 uid=0 更新即可
    	return $this->lab_service->expireCacheByCondition(array(
	    		'gmt_modify' => $this->_reqtime 
	    	),
	    	array(
	    		'where' => array(
	    			'oid' => $this->_currentOid,
	    			'uid' => 0,
	    			'group_name' => 'treexml'
	    		)
	    	)
	    );
    }
    
    
    private function _addRules(){
    	$displayorder = $this->input->post('displayorder');
    	if(!empty($displayorder)){
    		$this->form_validation->set_rules('displayorder','排序',  'required|is_natural_no_zero|less_than_equal_to[9999]');
    	}
    }
    
	
	/**
	 * 
	 */
	private function _getMemberList($lab_id){
		
		$userIds = array();
		$users = array();
		
		$userList = $this->Lab_Member_Model->getLabUserList($lab_id);
		
		if($userList){
			foreach($userList as $user){
				$userIds[$user['uid']] = $user;
			}
		}
		
		if($userIds){
			$users = $this->Member_Model->getList(array(
				'select' => 'uid,username',
				'where_in' => array(
					array(
						'key' => 'uid','value' => array_keys($userIds)
					)
				),
				'order' => 'uid ASC'
			));
			
			foreach($users as $uk => $user){
				$userIds[$user['uid']]['username'] = $user['username'];
			}
		}
		
		return $userIds;
	}

	
	/**
	 * 编辑实验室
	 */
    public function edit(){
		
		$id = $this->input->get_post('id');
		$isLabManager = $this->lab_service->isLabManager($this->_loginUID,$id,$this->_currentOid);
		
		$feedback = '';
		
		$this->_breadCrumbs[] = array(
			'title' => '修改实验室',
			'url' => $this->uri->uri_string.'?id='.$id
		);
		
		
		if($id && $this->isPostRequest()){
			$this->form_validation->set_rules('id','实验室id',  'required');
			$this->form_validation->set_rules('name','实验室名称',  'trim|required|min_length[1]|max_length[15]|is_unique_not_self['.$this->Lab_Model->getTableRealName().'.name.id.'.$id.']');
			$this->form_validation->set_rules('address','实验室地址',  'trim|required|min_length[1]|max_length[15]');
			$this->form_validation->set_rules('pid','父级',  'is_natural|callback_checkowner|callback_compare');
			
			$this->_addRules();
			
			for($i = 0; $i < 1; $i++){
				
				if(!$isLabManager){
					$this->jsonOutput('对不起，您不是该实验室的管理员，无权修改');
					break;
				}
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$flag = $this->Lab_Model->update(array(
					'name' => $this->input->post('name'),
					'address' => $this->input->post('address'),
					'pid' => $this->input->post('pid'),
					'displayorder' => $this->input->post('displayorder'),
					'updator' => $this->_profile['basic']['username']
				),array('id' => $id));
				
				
				if($flag > 0){
					$this->_updateCache();
				}
				
				if($flag >= 0){
					$this->jsonOutput('保存成功',array('redirectUrl' => site_url('lab/edit?id='.$id)));
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$info = $this->Lab_Model->getFirstByKey($id);
			
			$this->assign('memberList', $this->_getMemberList($id));
			$this->assign('isManager',$isLabManager);
			
			$this->assign('info',$info);
	        $this->display('lab/add');
		}
    }
    
    
    
    private function _searchUser(){
    	
    	$page = $this->input->get_post('page');
    	if(empty($page)){
            $page = 1;
        }
        
        $lab_id = $this->input->get_post('id');
        $isManager = false;
        
        //$condition['select'] = 'a,b';
        
        $condition['where']['oid'] = $this->_currentOid;
        $condition['order'] = "gmt_create DESC";
        $condition['pager'] = array(
            'page_size' => 10,
            'current_page' => $page,
            'query_param' => '',
            'shortStyle' => true
        );
        
        $username = trim($this->input->get_post('username'));
        $searchMember = array();
        $uids = array();
        
        if(!empty($username)){
            $searchCondition = array(
	        	'where' => array(
	        		'username' => $username
	        	)
	        );
	        
	        $searchMember = $this->Member_Model->getFirstByKey($username,'username','uid,username,email');
	        if(!empty($searchMember)){
	        	$condition['where']['uid'] = $searchMember['uid'];
	        }else{
	        	$condition['where'][' 1 = 2 '] = null;
	        }
        }
        
        $memberList = array();
    	$labUsers = $this->Lab_Member_Model->getList($condition);
    	
    	if($this->lab_service->isLabManager($this->_loginUID,$lab_id,$this->_currentOid)){
			$isManager = true;
		}
		
		if(empty($labUsers['data'])){
			//没有就去 member 表中搜索该用户在不在，在的话就显示
			if($searchMember){
				//$memberList[$searchMember['uid']] = $searchMember;
			}
			
		}else{
			foreach($labUsers['data'] as $u){
	    		$uids[] = $u['uid'];
	    		
	    		//移除管理员
	    		$u['can_drop_manager'] = false;
	    		//移除成员
	    		$u['can_drop'] = true;
	    		
	    		if($u['uid'] == $this->_loginUID){
	    			//自己不能取消自己
	    			if($u['is_manager'] == 'y'){
	    				$u['can_drop_manager'] = false;
	    			}
	    			
	    			$u['can_drop'] = false;
	    		}elseif($u['add_uid'] == $this->_loginUID){
	    			//该记录是自己设置的，就可以进行管理
	    			$u['can_drop_manager'] = true;
	    		}
	    		
	    		
	    		//创始人
	    		if($this->_loginUID == $this->_currentOid){
	    			if($u['uid'] == $this->_loginUID){
	    				$u['can_drop_manager'] = false;
	    				$u['can_drop'] = false;
	    			}else{
	    				$u['can_drop_manager'] = true;
	    				$u['can_drop'] = true;
	    			}
	    		}
	    		
	    		$memberList[$u['uid']] = $u;
	    	}
	        
	        
	        if($uids){
	        	$tempList = $this->Member_Model->getList(array(
	        		'select' => 'uid,username,email',
		        	'where_in' => array(
		        		array('key' => 'uid' , 'value' => $uids)
		        	)
		        ));
		        
		        foreach($tempList as $member){
		        	$memberList[$member['uid']] = array_merge($memberList[$member['uid']],$member);
		        }
	        }
		}
		
		
		$labUsers['pager']['shortStyle'] = true;
	    $labUsers['pager']['call_js'] = "searchUser";
		
		$this->assign('isManager',$isManager);
        $this->assign('page',$labUsers['pager']);
        $this->assign('memberList',$memberList);
    	
        ///$data['pager']['shortStyle'] = true;
        
    }
    
    
    
    /**
     * 
     */
    public function manager_lab_user(){
    	
    	$id = $this->input->get_post('id');
    	
    	if($this->isPostRequest()){
    		
    		for($i = 0; $i < 1; $i++){
    			if(!$this->isOrginationFounder() && !$this->Lab_Member_Model->getLabManager($this->_loginUID,$id)){
	    			$this->jsonOutput('对不起，您不是这个实验室的管理员，无权管理');
	    			break;
	    		}
	    		
	    		$userNeedAdd = array();
	    		$userNeedDelete = array();
	    		$changed = false;
	    		
	    		//新进成员
	    		if(is_array($_POST['user_id'])){
	    			foreach($_POST['user_id'] as $user_id){
	    				$userNeedAdd['u_'.$user_id] = 'n';
	    			}
	    		}
	    		
	    		//新管理员
	    		if(is_array($_POST['be_manager'])){
	    			foreach($_POST['be_manager'] as $user_id){
	    				$userNeedAdd['u_'.$user_id] = 'y';
	    			}
	    		}
	    		
	    		//即将删除管理员
	    		if(is_array($_POST['drop_manager'])){
	    			foreach($_POST['drop_manager'] as $user_id){
	    				$userNeedAdd['u_'.$user_id] = 'n';
	    			}
	    		}
	    		
	    		
	    		$insData = array();
	    		foreach($userNeedAdd as $k => $v){
	    			$uid = intval(str_replace('u_','',$k));
	    			$insData[$uid] = array(
	    				'user_id' => $uid,
		    			'lab_id' => $id,
		    			'is_manager' => $v,
		    			'uid' => $this->_loginUID,
		    			'creator' => $this->_profile['basic']['name']
	    			);
	    		}
	    		
	    		
	    		if($insData){
	    			//$this->Lab_Member_Model->batch
	    			$rows = $this->Lab_Member_Model->addMultiUserLabs($insData);
	    			$changed = $rows > 0 ? true : false;
	    		}
	    		
	    		
	    		if($_POST['drop_user_id'] && is_array($_POST['drop_user_id'])){
	    			/**
	    			 * 删除lab 成员
	    			 */
	    			$dropRow = $this->Lab_Member_Model->deleteUsersByLabId($id,$_POST['drop_user_id'],array($this->_loginUID));
	    		}
	    		
	    		$this->jsonOutput('操作成功');
    		}
    	}else{
    		
    		$this->_searchUser();
    		$this->display('lab_user/search_popup');
    	}
    	
    }
    
    
    /**
     * 删除
     */
    public function delete_lab_user(){
    	if($this->isPostRequest()){
    		$id = $this->input->post('id');
    		$user_id = $this->input->post('user_id');
    		
    		for($i = 0; $i < 1; $i++){
    			
    			if($user_id == 1){
    				$this->jsonOutput('对不起，无法从实验室删除创始人');
    				break;
	    		}
	    		
	    		if(!$this->lab_service->getLabManager($this->_loginUID,$id)){
	    			$this->jsonOutput('对不起，您不是这个实验室的管理员，无权删除');
	    		}
	    		
	    		$this->Lab_Member_Model->deleteByUserIdAndLabId($user_id,$id);
	    		$this->jsonOutput('删除成功');
    		}
    	}else{
    		$this->jsonOutput('请求参数错误');
    	}
    }
    
    
    /**
     * 移动
     */
    public function move(){
    	
		if($this->isPostRequest()){
			
			$id = $this->input->post('id');
			$toId = $this->input->post('pid');
			
			$labInfos = $this->Lab_Model->getList(array(
				'where_in' => array(
					array('key' => 'id', 'value' => array($id,$toId))
				)
			));
			
			$srcInfo = array();
			$toInfo = array();
			///print_r($labInfos);
			
			foreach($labInfos as $labInfo){
				if($labInfo['id'] == $id){
					$srcInfo = $labInfo;
				}else if($labInfo['id'] == $toId){
					$toInfo = $labInfo;
				}
			}
			
			$this->form_validation->set_rules('pid','父级',  'required|callback_compare');
			$this->form_validation->set_rules('id','实验室id',  'required');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_string(' ',' '));
					break;
				}
				
				$row = 0;
				$updateData = array(
					'updator' => $this->_profile['basic']['name'],
					'pid' => $toId
				);
				
				$tipText = "改变父级";
				
				//父级没有变,优先级调整
				if($srcInfo['pid'] == $toId){
					$maxRecord = $this->Lab_Model->getList(array(
						'where' => array('pid' => $toId,'status' => 0),
						'order' => 'displayorder ASC , id ASC',
						'limit' => 1
					));
					
					//print_r($maxRecord);
					//存在 并且不是自己
					if($maxRecord[0] && $maxRecord[0]['id'] != $id){
						$tipText = '更改优先级';
						$updateData['displayorder'] = $maxRecord[0]['displayorder'] - 1;
						
						if($updateData['displayorder'] < 0){
							$updateData['displayorder'] = 0;
						}
					}
				}
				
				$row = $this->Lab_Model->update($updateData,array('id' => $id));
				
				if($row >= 0){
					if($row > 0){
						$this->_updateCache();
					}
					
					$this->jsonOutput($tipText.'成功');
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$this->jsonOutput('请求参数错误');
		}
    }
    
    
    /**
     * 实验室删除
     */
    public function delete(){
    	
    	if($this->isPostRequest()){
    		for($i = 0; $i < 1; $i++){
    			
    			$id = $this->input->post('id');
				$isLabManager = $this->lab_service->isLabManager($this->_loginUID,$id,$this->_currentOid);
				
				if(!$isLabManager){
					$this->jsonOutput('对不起，您不是该实验室的管理员，无权删除');
					break;
				}
				
	    		$rows = $this->lab_service->deleteUserLab($id,$this->_currentOid ,$this->_reqtime);
	    		if($rows < 0){
	    			$this->jsonOutput($this->db->get_error_info());
	    			break;
	    		}
	    		
	    		$this->_updateCache();
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
    	
    	$this->_breadCrumbs[] = array(
			'title' => '添加实验室',
			'url' => $this->uri->uri_string
		);
    	
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('name','实验室名称',  'trim|required|min_length[1]|max_length[15]|is_unique['.$this->Lab_Model->getTableRealName().'.name]');
			$this->form_validation->set_rules('address','实验室地址',  'trim|required|min_length[1]|max_length[15]');
			
			$this->form_validation->set_rules('pid','父级','is_natural|callback_checkowner');
			$this->_addRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$flag = $this->lab_service->addLab($_POST,$this->_profile['basic'],$this->_currentOid);
				
				
				if($flag > 0){
					$this->_updateCache();
					$this->jsonOutput('保存成功',array('redirectUrl' => site_url('lab/index')));
					
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
		}else{
			
       	 	$this->display();
		}
    }
    
}
