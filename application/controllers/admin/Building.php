<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Building extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service'));
		
		$this->_moduleTitle = '建筑物';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
			array('url' => $this->_className.'/import','title' => '导入'),
		);
		
		
		$this->form_validation->set_error_delimiters('<div>','</div>');
	}
	
	
	/**
	 * 
	 */
	public function index(){
	
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		$search['name'] = $this->input->get_post('name');
		$search['address'] = $this->input->get_post('address');
		
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		
		if($search['address']){
			$condition['like']['address'] = $search['address'];
		}
		
		
		$list = $this->Building_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
		
	}
	
	
	/**
	 * 验证
	 */
	private function _getRules(){
		$this->form_validation->set_rules('resident_id','小区名称','required|in_db_list['.$this->Resident_Model->getTableRealName().'.id]');
		$this->form_validation->set_rules('address','小区地址','required|min_length[2]|max_length[200]');
		$this->form_validation->set_rules('nickname','建筑物别名','min_length[2]|max_length[100]');
		
		$this->form_validation->set_rules('unit_num','建筑物单元数量','is_natural|less_than_equal_to[255]');
		$this->form_validation->set_rules('yezhu_num','入驻业主数量','is_natural');
		
		$this->form_validation->set_rules('max_plies','最高层数','is_natural|less_than_equal_to[200]');
		$this->form_validation->set_rules('floor_plies','地下层数','is_natural|less_than_equal_to[20]');
		
		$this->form_validation->set_rules('longitude','经度','is_numeric');
		$this->form_validation->set_rules('latitude','纬度','is_numeric');
	}
	
	
	
	/**
	 * 
	 */
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			//@todo 
			$this->jsonOutput('删除失败，待开发完善',$this->getFormHash());
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	
	/**
	 * 准备页面数据
	 */
	private function _preparePageData(){
		
		$residentList = $this->Resident_Model->getList(array(
			'select' => 'id,name,address,lng,lat',
			'order' => 'displayorder DESC'
		),'id');
		
		$this->assign(array(
			'residentList' => $residentList,
			'residentJson' => json_encode($residentList)
		));
		
	}
	
	
	/**
	 * 
	 */
	private function _prepareData(){
		$data['nickname'] = $this->input->post('nickname');
		$data['unit_num'] = $this->input->post('unit_num');
		$data['max_plies'] = $this->input->post('max_plies');
		$data['floor_plies'] = $this->input->post('floor_plies');
		$data['total_num'] = $this->input->post('total_num');
		$data['yezhu_num'] = $this->input->post('yezhu_num');
		$data['displayorder'] = $this->input->post('displayorder');
		
		return array(
			'nickname' => $data['nickname'] ? $data['nickname'] : '',
			'unit_num' => $data['unit_num'] ? $data['unit_num'] : 0,
			'max_plies' => $data['max_plies'] ? $data['max_plies'] : 1,
			'floor_plies' => $data['unit_num'] ? $data['floor_plies'] : 0,
			'total_num' => $data['total_num'] ? $data['total_num'] : 0,
			'yezhu_num' => $data['yezhu_num'] ? $data['yezhu_num'] : 0,
			'displayorder' => $data['displayorder'] ? $data['displayorder'] : 255
		);
	}
	
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		//print_r($_FILES);
		
		$residentId = $this->input->get_post('resident_id');
		
		if($this->isPostRequest()){
			
			$this->_getNameRule();
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$newid =$this->Building_Model->_add(array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add')));
				$error = $this->Building_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('name').'名称已被占用');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/edit?id='.$newid)));
			}
		}else{
			
			if($residentId){
				$this->assign('info', array('resident_id' => $residentId));
			}
			$this->_preparePageData();
			$this->display();
		}
		
	}
	
	
	/**
	 * 检查数据合法性
	 * 检查建筑物名称开头文字必须以小区名称开头
	 */
	public function checkName($name,$residentId = 0){
		
		if(empty($residentId)){
			return true;	
		}
		
		$residentInfo = $this->Resident_Model->getFirstByKey($residentId,'id','name');
		
		if(!preg_match("/^{$residentInfo['name']}/is",$name)){
			$this->form_validation->set_message('checkName',$this->_moduleTitle.'名称必须包含小区名称并且以小区名称开始');
			return false;
		}
		
		return true;
		
	}
	
	
	
	
	/**
	 * 获取
	 */
	private function _getNameRule($id = 0){
		if($id){
			$residentInfo = $this->Building_Model->getFirstByKey($id,'id','resident_id');
			
			$this->form_validation->set_rules('name','建筑物名称','required|min_length[2]|max_length[200]|callback_checkName['.$residentInfo['resident_id'].']|is_unique_not_self['.$this->Building_Model->getTableRealName().".name.id.{$id}]");
		}else{
			$this->form_validation->set_rules('name','建筑物名称','required|min_length[2]|max_length[200]|callback_checkName|is_unique['.$this->Building_Model->getTableRealName().".name]");
		}
		
	}
	
	
	/**
	 * 编辑页面
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		$residentId = $this->input->get_post('resident_id');
		
		$info = $this->Building_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getNameRule($id);
			$this->_getRules();
			for($i = 0; $i < 1; $i++){
				$info = array_merge($info,$_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$this->Building_Model->update(array_merge($info,$_POST,$this->addWhoHasOperated('edit')),array('id' => $id));
				
				$error = $this->Building_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('name').'名称已存在');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
		}else{
			
			$this->assign('info',$info);
			
			$this->_preparePageData();
			$this->display($this->_className.'/add');
			
		}
		
	}
	
	
	
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		
		$this->form_validation->set_error_delimiters('','');
		
		
		switch($fieldName){
			case 'name':
				$this->_getNameRule($id);
				break;
			case 'displayorder';
				$this->form_validation->set_rules('displayorder','排序',"required|is_natural|less_than[256]");
				break;
			default:
				break;
		}
		
		$data = array(
	        $fieldName => $newValue,
		);
		
		$message = '修改失败';
		
		$this->form_validation->set_data($data);
		if(!$this->form_validation->run()){
			$message = $this->form_validation->error_html();
		}else{
			
			if($this->Building_Model->update($data,array('id' => $id)) < 0){
				$message = '数据修改失败';
			}else{
				$message = '修改成功';
			}
		}
		
		$this->jsonOutput($message);
		
	}
	
	
	
	/**
     * 导入excel
     */
    public function import(){
    	$feedback = '';
    	$this->form_validation->set_error_delimiters('','');
    	
    	$residentList = $this->Resident_Model->getList(array(
			'order' => 'displayorder DESC'
		),'id');
		
    
    	if($this->isPostRequest()){
       		
    		for($i = 0; $i < 1; $i++){
    			
    			if(0 != $_FILES['excelFile']['error']){
    				$feedback = getErrorTip('请上传文件');
	    			break;
	    		}
	    		
	    		$this->form_validation->reset_validation();
	    		$this->form_validation->set_rules('resident_id','所在小区','required|is_natural_no_zero');
	    		
	    		if(!$this->form_validation->run()){
	    			$feedback = getErrorTip($this->form_validation->error_html());
	    			break;
	    		}
	    		
	    		require_once PHPExcel_PATH.'PHPExcel.php';
	    		
	    		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
		        $cacheSettings = array( 'dir'  => ROOTPATH.'/temp' );
		        PHPExcel_Settings::setLocale('zh_CN');
		        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		        
		        
	    		try {
	    			
	    			$excelFile = $_FILES['excelFile']['tmp_name'];
	    			$objPHPexcel = PHPExcel_IOFactory::load($excelFile);
					$objWorksheet = $objPHPexcel->getActiveSheet(0); 
					
					$startRow = 2;
					
					$highestRow = $objWorksheet->getHighestRow();
					
					if($highestRow > 1000){
						$highestRow = 1000;
					}
					
					//选中的小区ID
					$residentId = $this->input->post('resident_id');
					$residentInfo = $this->Resident_Model->getFirstByKey($residentId);
					
					$result = array();
					$successCnt = 0;
					
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['name'] = getCleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue());
						$tmpRow['address'] = getCleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue());
						$tmpRow['nickname'] = getCleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue());
						$tmpRow['unit_num'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['max_plies'] = getCleanValue($objWorksheet->getCell('E'.$rowIndex)->getValue());
						$tmpRow['floor_plies'] = getCleanValue($objWorksheet->getCell('F'.$rowIndex)->getValue());
						$tmpRow['total_num'] = getCleanValue($objWorksheet->getCell('G'.$rowIndex)->getValue());
						$tmpRow['yezhu_num'] = getCleanValue($objWorksheet->getCell('H'.$rowIndex)->getValue());
						
						
						$this->form_validation->reset_validation();
						$this->form_validation->set_data($tmpRow);
						
						$this->form_validation->set_rules('name','建筑物名称','required|min_length[2]|max_length[200]|callback_checkName['.$residentId.']');
						$this->form_validation->set_rules('address','地址','required');
						$this->form_validation->set_rules('nickname','建筑物别名','min_length[1]|max_length[200]');
						$this->form_validation->set_rules('unit_num','建筑物单元数量','is_natural');
						$this->form_validation->set_rules('max_plies','最高层数','is_natural');
						$this->form_validation->set_rules('floor_plies','地下层数','is_natural');
						$this->form_validation->set_rules('total_num','总套数','is_natural');
						$this->form_validation->set_rules('yezhu_num','地下层数','is_natural');
						
						if(!$this->form_validation->run()){
							//print_r($this->form_validation->error_array());
							$tmpRow['message'] = $this->form_validation->error_html();
							
							/*
							if('development' == ENVIRONMENT){
								$tmpRow['message'] = $this->form_validation->error_html();
							}else{
								$tmpRow['message'] = '数据校验失败';
							}
							*/
							
							$result[] = $tmpRow;
							continue;
						}
						
						
						$insertData = array_merge(array(
							'resident_id' => $residentId,
							'name' => $tmpRow['name'],
							'nickname' => empty($tmpRow['nickname']) == true ? '': $tmpRow['nickname'],
							'address' => $tmpRow['address'],
							'unit_num' => intval($tmpRow['unit_num']),
							'max_plies' => intval($tmpRow['max_plies']),
							'floor_plies' => intval($tmpRow['floor_plies']),
							'total_num' => intval($tmpRow['total_num']),
							'yezhu_num' => intval($tmpRow['yezhu_num']),
							'lng' => $residentInfo['lng'],
							'lat' => $residentInfo['lat'],
						),$this->addWhoHasOperated('add'));
						
						
						
						$this->Building_Model->_add($insertData);
						
						$error = $this->Building_Model->getError();
						
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '数据库错误';
							if($error['code'] == MySQL_Duplicate_CODE){
								$tmpRow['message'] = $this->_moduleTitle.'已经存在';
							}
						}else{
							$tmpRow['message'] = '导入成功';
							$tmpRow['classname'] = 'successText';
							$successCnt++;
						}
						
						$result[] = $tmpRow;
					}
					
					$feedback = getSuccessTip('导入完成');
					
					//print_r($result);
	    			$this->assign(array(
						'result' => $result,
						'successCnt' => $successCnt,
						
					));
					
	    			@unlink($excelFile);
	    		}catch(Exception $e){
	    			$feedback = '导入错误,请检查文件格式是否正确';
	    		}
    		}
    	}
    	
    	$this->assign(array(
    		'feedback' => $feedback,
    		'residentList' => $residentList
    	));
    	
    	$this->display();
    	
    }
}