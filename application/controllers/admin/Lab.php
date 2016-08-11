<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lab extends Ydzj_Admin_Controller {
	
	
    public function __construct(){
		parent::__construct();
		
		/*
		if(!$this->_checkIsSystemManager()){
			$this->show_access_deny();
		}
		*/
		
    }
    
    public function index()
    {
    	$this->assign('action','index');
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
    
    public function export(){
    	
    	$this->load->helper('download');
    	
    	
    	//$condition['select'] = 'a,b';
        $condition['order'] = "id  ASC , pid ASC";
        

        if(!empty($_GET['name'])){
            $condition['like']['name'] = $_GET['name'];
        }
        
        $condition['where']['status'] = '正常';
        
        
        if($this->_loginUID != LAB_FOUNDER_ID){
        	$condition['where_in'][] = array(
        		'key' => 'id',
        		'value' => $this->session->userdata['user_labs']
        	);
        }
        
        $this->Lab_Model->clearMenuTree();
        $data = $this->Lab_Model->getList($condition);
    	

    	require_once PHPExcel_PATH.'PHPExcel.php';
    	
    	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
        $cacheSettings = array( 'dir'  => ROOTPATH.'/temp' );
        PHPExcel_Settings::setLocale('zh_CN');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        
        $objPHPExcel = new PHPExcel();
    	$objPHPExcel->setActiveSheetIndex(0);
    	
    	
    	$title = "实验室列表";
    	
        $objPHPExcel->getActiveSheet()->setTitle($title);
    	
    	
    	$this->_writeTitleLine($objPHPExcel);
    	$this->_writeDetailTitleLine($objPHPExcel,2,$this->_getExcelColumnConfig());
    	$this->_writeDetailLine($objPHPExcel,3,$data);
    	
    	
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	
    	$showFilename = "{$title}.xls";
        $filename = "{$title}".$this->_userProfile['id'].".xls";
        
        $objWriter->save(ROOT_DIR.'/temp/'.$filename);
        $objPHPExcel->disconnectWorksheets(); 
        unset($objPHPExcel,$objWriter); 
        force_download($showFilename,  file_get_contents(ROOT_DIR.'/temp/'.$filename));
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
    	
    	if(empty($list['data'])){
    		return;
    	}
    	
        $i = 0;
        foreach($list['data'] as $p){
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
    
    
   	public function getTreeXML(){
   		
   		header("Content-type:text/xml");
   		
   		$cache = $this->Lab_Cache_Model->getFirstByKey($this->lab_service->getCacheXMLUserKey($this->_loginUID),'key_id');
   		
   		if(!empty($cache) && $cache['expire'] >= 0 && ((time() - $cache['gmt_create']) < 3600 )){
   			echo $cache['content'];
   			exit;
   		}
   		
   		$output = $this->lab_service->getUserLabXML($this->_loginUID);
   		$this->lab_service->cacheUserLabXML($this->_loginUID,$output);
   		
   		echo $output;
   		
   	}
    
    /**
     * 权属
     */
    public function checkowner($pid){
    	$id = $this->input->post('id');
    	
    	$info = $this->Lab_Model->getFirstByKey($id);
    	$allowIds = $this->session->userdata('user_labs');
    	$allowIds[] = $info['pid'];
    	
    	if(LAB_FOUNDER_ID != $this->_loginUID){
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
    	//@todo 可能更新很多记录， 优化 取在线的 session_id 去更新
    	$this->Lab_Cache_Model->updateByGroupKey($this->Lab_Cache_Model->getXMLGroupKey(), array('expire' => -1));
    }
    
    
    private function _addRules(){
    	
    	if(!empty($_POST['displayorder'])){
    		$this->form_validation->set_rules('displayorder','排序',  'required|is_natural_no_zero');
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
			$users = $this->Lab_User_Model->getList(array(
				'where_in' => array(
					array(
						'key' => 'id','value' => array_keys($userIds)
					)
				),
				'order' => 'id ASC '
			));
			foreach($users['data'] as $uk => $user){
				$userIds[$user['id']]['name'] = $user['name'];
			}
		}
		
		return $userIds;
	}


    public function edit(){
		
		$this->assign('act','edit');
		
		$isLabManager = false;
		
		if($this->isPostRequest()){
			$id = $_POST['id'];
			if($this->Lab_Member_Model->getLabManager($this->_userProfile['id'],$id)){
				$isLabManager = true;
			}
			
			if($this->_userProfile['id'] == LAB_FOUNDER_ID){
				$isLabManager = true;
			}
			
			$this->form_validation->set_rules('id','实验室id',  'required');
			$this->form_validation->set_rules('address', '实验室地址', 'required|is_unique_not_self['.$this->Lab_Model->_tableName.'.address.id.'.$id.'.status.正常]');
			$this->form_validation->set_rules('pid','父级',  'is_natural|callback_checkowner|callback_compare');
			$this->_addRules();
			
			if($isLabManager && $this->form_validation->run()){
				$_POST['updator'] = $this->_userProfile['name'];
				$flag = $this->Lab_Model->update($_POST);
				
				if($flag >= 0){
					$this->_updateCache();
					$this->assign('success','1');
					$this->assign('message','修改成功');
				}else{
					$this->assign('message','修改失败');
				}
				
				$info = $this->Lab_Model->queryById($id);
			}else{
				
				if($isLabManager){
					$this->assign('message','数据不能通过校验,修改失败');
				}else{
					$this->assign('message','对不起，您不是该实验室的管理员，无权修改');
				}
				
				$info = $_POST;
			}
			
		}else{
			
			$id = $this->uri->segment(4);
			if($this->Lab_Member_Model->getLabManager($this->_userProfile['id'],$id)){
				$isLabManager = true;
			}
			
			$info = $this->Lab_Model->queryById($id);
		}
		
		$this->assign('user_labs',json_encode(array_values($this->session->userdata('user_labs'))));
		$this->assign('userList', $this->_getUserList($id));
		$this->assign('isManager',$isLabManager);
		$this->assign('info',$info);
		$this->assign('gobackUrl', $this->getGobackUrl());
        $this->display('add');
    }
    
    
    public function get_join_info(){
    	
    	
    	$this->display();
    }
    
    
    
    public function manager_lab_user(){
    	if($this->isPostRequest()){
    		
    		$id = $_POST['id'];
    		if($this->_userProfile['id'] != LAB_FOUNDER_ID && !$this->Lab_Member_Model->getLabManager($this->_userProfile['id'],$id)){
    			$this->sendFormatJson('failed',array('text' => '对不起，您不是这个实验室的管理员，无权管理'));
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
    		
    		//删除管理员
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
	    			'uid' => $this->_userProfile['id'],
	    			'creator' => $this->_userProfile['name']
    			);
    		}
    		
    		
    		if($insData){
    			$this->Lab_Member_Model->addMultiUserLabs($insData);
    			$changed = true;
    		}
    		
    		if($_POST['drop_user_id'] && is_array($_POST['drop_user_id'])){
    			$this->Lab_Member_Model->deleteUsersByLabId($id,$_POST['drop_user_id'] , array(LAB_FOUNDER_ID , $this->_userProfile['id']));
    			$changed = true;
    		}
    		
    		if($changed){
    			$this->sendFormatJson('success',array('text' => '操作成功' ,'wait' => 1) , base_url('lab_admin/edit/id/'.$id));
    		}else{
    			$this->sendFormatJson('success',array('text' => '操作成功' , 'do_not_tip' => true));
    		}
    		
    	}else{
    		$this->sendFormatJson('failed',array('text' => '请求错误'));
    	}
    	
    }
    
    
    public function delete_lab_user(){
    	if($this->isPostRequest()){
    		
    		$id = $_POST['id'];
    		$user_id = $_POST['user_id'];
    		
    		if($user_id == LAB_FOUNDER_ID){
    			$this->sendFormatJson('failed',array('text' => '对不起，无法从实验室删除创始人'));
    		}
    		
    		if(!$this->Lab_Member_Model->getLabManager($this->_userProfile['id'],$id)){
    			$this->sendFormatJson('failed',array('text' => '对不起，您不是这个实验室的管理员，无权删除'));
    		}
    		
    		$this->Lab_Member_Model->deleteByUserIdAndLabId($user_id,$id);
    		$this->sendFormatJson('success',array('id' => $user_id , 'text' => '删除成功' ,'operation' => "delete" ));
    		
    	}else{
    		$this->sendFormatJson('failed',array('text' => '请求错误'));
    	}
    	
    }
    
    
    public function move(){
		if($this->isPostRequest()){
			
			$id = $_POST['id'];
			$toId = $_POST['pid'];
			
			
			$srcInfo = $this->Lab_Model->queryById($id);
			$toInfo = $this->Lab_Model->queryById($toId);
			
			$method = "pid";
			if($srcInfo['pid'] != $toInfo['pid']){
				$this->form_validation->set_rules('pid','父级',  'required|callback_compare');
			}else{
				$method = "displayorder";
				$_POST[$method] = $toInfo[$method] + 1;
			}
			
			$this->form_validation->set_rules('id','实验室id',  'required');
			
			if($this->form_validation->run()){
				$_POST['updator'] = $this->_userProfile['name'];
				
				switch($method){
					case 'pid':
						$flag = $this->Lab_Model->update($_POST);
						break;
					case 'displayorder':
						$flag = $this->Lab_Model->updateDisplayorder($_POST);
						break;
					default:
						break;
				}
				
				if($flag >= 0){
					$this->_updateCache();
					$this->sendFormatJson('success',array('id' => $id , 'text' => '操作成功' , 'wait' => 1 ), array('jsReload' => true));
				}else{
					$this->sendFormatJson('failed',array('text' => '数据库出错,修改失败'));
				}
			}else{
				$this->sendFormatJson('failed',array('text' => '数据不能通过校验,修改失败'));
			}
			
		}else{
			$this->sendFormatJson('failed',array('text' => '请求错误'));
		}
    	
    }
    
    public function delete(){
    	
    	//$id = $this->uri->segment(4);
    	
    	if($this->isPostRequest()){
    		
    		$id = $_POST['id'];
    		
    		$this->Lab_Model->clearMenuTree();
    		$subIds = $this->Lab_Model->getListByTree($id);
    		
    		$ids = array();
    		$ids[] = $id;
    		if($subIds){
    			foreach($subIds as $item){
    				$ids[] = $item['id'];
    			}
    		}
    		
    		$ids = array_unique($ids);
    		
    		/**
    		 * 查询是否是 这个实验室的管理管理员, 如果不是这个实验室的管理员
    		 * 不能进行删除
    		 */
    		 
    		if(LAB_FOUNDER_ID != $this->_userProfile['id']){
    			if(!$this->Lab_Member_Model->getLabManager($this->_userProfile['id'],$id)){
	    			$this->sendFormatJson('failed',array('text' => '对不起，您不是这个实验室的管理员，无权删除'));
	    		}
    		} 
    		
    		$this->Lab_Model->fake_delete(array('id' => $ids , 'updator' => $this->_userProfile['name']));
    		$this->_updateCache();
    		
    		if($ids){
    			$this->Lab_Member_Model->deleteAllUserByLabs($ids);
    			$this->load->model('Goods_Model');
    			$this->Goods_Model->deleteByLabIds($ids);
    		}
    		
    		$this->Lab_Model->delete($ids);
    		
    		$this->sendFormatJson('success',array('id' => $id , 'text' => '删除成功' , 'wait' => 1 ), array('jsReload' => true));
    	}else{
    		$this->sendFormatJson('failed',array('text' => '请求错误'));
    	}
    }
    
    /**
     * 添加实验室
     */
    public function add()
    {
		if($this->isPostRequest()){
			$this->form_validation->set_rules('address','实验室地址',  'required|is_unique_by_status['.$this->Lab_Model->getTableRealName().'.address.status.正常]');
			$this->form_validation->set_rules('pid','父级',  'is_natural|callback_checkowner');
			$this->_addRules();
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$info = $_POST;
					$this->assign('message','数据不能通过校验,添加失败');
				}
				
				$flag = $this->lab_service->addLab($_POST,$this->_adminProfile['basic']);
				
				if($flag > 0){
					$this->lab_service->makeLabXMLExpire();
					$this->assign('success','1');
					$this->assign('message','添加成功');
				}else{
					$info = $_POST;
					$this->assign('message','添加失败');
				}
					
					
			}
		}
		
		$this->assign('user_labs',json_encode(array_values($this->session->userdata('user_labs'))));
		$this->assign('info',$info);
        $this->display();
    }
    
}
