<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lab extends MyYdzj_Controller {
	
    public function __construct(){
		parent::__construct();
		
		$this->assign('moduleTitle','实验室');
		$this->_breadCrumbs[] = array('title' => '实验室','url' => 'lab/index');
    }
    
    public function index()
    {
    	$this->_breadCrumbs[] = array('title' => '实验室列表','url' => 'lab/index');
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
	        
	        $condition['where']['status'] = '正常';
	        
	        if($this->_loginUID != LAB_FOUNDER_ID){
	        	$condition['where_in'][] = array(
	        		'key' => 'id',
	        		'value' => $this->session->userdata['user_labs']
	        	);
	        }
	        
	        $this->Lab_Model->clearMenuTree();
	        
	        
	        // @todo 防止超大记录 ，先预判记录数量 做判断 防止溢出
	        $data = $this->Lab_Model->getList($condition);
	
	    	require_once PHPExcel_PATH.'PHPExcel.php';
	    	
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
   		
   		$uid = $this->input->get_post('uid');
   		
   		//空 或者 是 创始人  创始人 整课树可见
   		if(empty($uid) || $this->_loginUID == LAB_FOUNDER_ID){
   			$uid = 0;
   		}else{
   			$uid = $this->_loginUID;
   		}
   		
   		/**
   		 * 与总的树比较 更新的时间点
   		 */
   		$fullTreeCache = $this->Lab_Cache_Model->getById(array(
   			'select' => 'gmt_modify',
   			'where' => array(
   				'key_id' => $this->lab_service->getCacheXMLUserKey(0)
   			)
   		));
   		
   		$cache = $this->Lab_Cache_Model->getFirstByKey($this->lab_service->getCacheXMLUserKey($uid),'key_id');
   		$cacheExpire = false;
   		
   		if(empty($cache)){
   			$cacheExpire = true;
   		}
   		
   		if(!$cacheExpire){
   			if($fullTreeCache){
   				if($fullTreeCache['gmt_modify'] >= $cache['gmt_modify']){
	   				$cacheExpire = true;
	   			}
   			}else{
   				if($cache['expire'] < 0 || (time() - $cache['gmt_modify']) >= CACHE_ONE_DAY){
   					$cacheExpire = true;
   				}
   			}
   		}
   		
   		if(!$cacheExpire){
   			echo $cache['content'];
   		}else{
   			$output = $this->lab_service->getUserLabXML($uid);
   			$this->lab_service->cacheUserLabXML($uid,$output);
   			echo $output;
   		}
   	}
    
    /**
     * 权属
     */
    public function checkowner($pid){
    	
    	if(LAB_FOUNDER_ID != $this->_loginUID){
    		
    		$id = $this->input->post('id');
    	
	    	$info = $this->Lab_Model->getFirstByKey($id);
	    	$allowIds = $this->session->userdata('user_labs');
	    	$allowIds[] = $info['pid'];
	    	
    	
    		if(!in_array($pid,$allowIds)){
    			$this->form_validation->set_message('checkowner', '%s 不能选择不在自己管辖的实验室为父级');
            	return FALSE;
    		}
    	}
    	
    	return true;
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
    
    
    private function _updateCache(){
    	//只需将 labxml_0 过期
    	$this->lab_service->expireCacheByCondition(array(
    		'key_id' => $this->lab_service->getCacheXMLUserKey(0)
    	));
    }
    
    
    private function _addRules(){
    	
    	$displayorder = $this->input->post('displayorder');
    	
    	if(!empty($displayorder)){
    		$this->form_validation->set_rules('displayorder','排序',  'required|is_natural_no_zero|less_than_equal_to[9999]');
    	}
		
    	/*
    	if(!empty($_POST['name'])){
    		$this->form_validation->set_rules('name','学院名称',  'max_length[100]');
    	}
		*/
    }
    
	
	private function _getUserList($lab_id){
		
		$userIds = array();
		$users = array();
		
		$userList = $this->Lab_Member_Model->getLabUserList($lab_id);
		
		if($userList){
			foreach($userList as $user){
				$userIds[$user['user_id']] = $user;
			}
		}
		
		if($userIds){
			$users = $this->Adminuser_Model->getList(array(
				'where_in' => array(
					array(
						'key' => 'id','value' => array_keys($userIds)
					)
				),
				'order' => 'id ASC '
			));
			foreach($users as $uk => $user){
				$userIds[$user['id']]['name'] = $user['name'];
			}
		}
		
		return $userIds;
	}

	
	/**
	 * 编辑实验室
	 */
    public function edit(){
		
		$isLabManager = false;
		$id = $this->input->get_post('id');
		if($this->lab_service->getLabManager($this->_loginUID,$id)){
			$isLabManager = true;
		}
		
		$feedback = '';
		
		if($id && $this->isPostRequest()){
			if($this->_loginUID == LAB_FOUNDER_ID){
				$isLabManager = true;
			}
			
			$this->form_validation->set_rules('id','实验室id',  'required');
			$this->form_validation->set_rules('address', '实验室地址', 'required|is_unique_not_self['.$this->Lab_Model->getTableRealName().'.address.id.'.$id.']');
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
					'address' => $this->input->post('address'),
					'pid' => $this->input->post('pid'),
					'displayorder' => $this->input->post('displayorder'),
					'updator' => $this->_adminProfile['basic']['name']
				),array('id' => $id));
				
				
				if($flag > 0){
					$this->_updateCache();
				}
				
				if($flag >= 0){
					$this->jsonOutput('保存成功');
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$info = $this->Lab_Model->getFirstByKey($id);
			$this->assign('user_labs',json_encode(array_values($this->session->userdata('user_labs'))));
			$this->assign('userList', $this->_getUserList($id));
			
			$this->assign('isManager',$isLabManager);
			
			$this->assign('info',$info);
	        $this->display('lab/add');
		}
    }
    
    
    /**
     * 
     */
    public function manager_lab_user(){
    	
    	$id = $this->input->get_post('id');
    	
    	if($this->isPostRequest()){
    		
    		for($i = 0; $i < 1; $i++){
    			if($this->_loginUID != LAB_FOUNDER_ID && !$this->Lab_Member_Model->getLabManager($this->_loginUID,$id)){
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
		    			'creator' => $this->_adminProfile['basic']['name']
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
	    			$dropRow = $this->Lab_Member_Model->deleteUsersByLabId($id,$_POST['drop_user_id'],array_unique(array(LAB_FOUNDER_ID , $this->_loginUID)));
	    		}
	    		
	    		$this->jsonOutput('操作成功');
    		}
    	}else{
    		$this->jsonOutput('请求参数错误');
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
    			if($user_id == LAB_FOUNDER_ID){
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
					'updator' => $this->_adminProfile['basic']['name'],
					'pid' => $toId
				);
				
				$tipText = "改变父级";
				
				//父级没有变,优先级调整
				if($srcInfo['pid'] == $toId){
					$maxRecord = $this->Lab_Model->getList(array(
						'where' => array('pid' => $toId,'status' => '正常'),
						'order' => 'displayorder DESC , id ASC',
						'limit' => 1
					));
					//print_r($maxRecord);
					//存在 并且不是自己
					if($maxRecord[0] && $maxRecord[0]['id'] != $id){
						$tipText = '更改优先级';
						$updateData['displayorder'] = $maxRecord[0]['displayorder'] + 1;
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
	    		$return = $this->lab_service->deleteUserLab($this->_loginUID,$id,$this->_adminProfile['baisc']);
	    		
	    		if($return['code'] != 'success'){
	    			$this->jsonOutput($return['message']);
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
    	
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('address','实验室地址',  'trim|required|is_unique_by_status['.$this->Lab_Model->getTableRealName().'.address.status.正常]');
			$this->form_validation->set_rules('pid','父级',  'is_natural|callback_checkowner');
			$this->_addRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$flag = $this->lab_service->addLab($_POST,$this->_adminProfile['basic']);
				if($flag > 0){
					$this->_updateCache();
					$this->jsonOutput('保存成功');
					
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
		}else{
			$this->assign('user_labs',json_encode(array_values($this->session->userdata('user_labs'))));
       	 	$this->display();
		}
    }
    
}
