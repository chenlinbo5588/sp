<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Yezhu extends Ydzj_Admin_Controller {
	
	private $_idTypeList;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service','Staff_service'));
		
		$this->_moduleTitle = '业主';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
			array('url' => $this->_className.'/import','title' => '导入'),
			array('url' => $this->_className.'/export','title' => '导出'),
		);
		
		
		$this->_idTypeList = $this->staff_service->getTopChildList('证件类型');
		
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
		
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		
		$list = $this->Yezhu_Model->getList($condition);
		
		
		$this->_commonPageData();
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		
		$this->display();
		
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
	 * 
	 */
	private function _prepareData(){
		$data['displayorder'] = $this->input->post('displayorder');
		return array(
			'displayorder' => $data['displayorder'] ? $data['displayorder'] : 255
		);
	}
	
	
	/**
	 * 
	 */
	private function _commonPageData(){
		$basicData = $this->staff_service->getAssocBasicDataTree();
		
		$this->assign(array(
			'province_idcard' => json_encode(config_item('province_idcard')),
			'idTypeList' => $this->staff_service->getTopChildList('证件类型'),
			'jiguanList' => $this->staff_service->getTopChildList('籍贯'),
		));
	}
	
	
	
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			
			$this->wuye_service->addYezhuRules($this->staff_service->getTopChildList('证件类型'),$this->input->post('id_type'), 0);
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$newid =$this->Yezhu_Model->_add(array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add')));
				$error = $this->Yezhu_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('id_no').'已被占用');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/edit?id='.$newid)));
			}
		}else{
			
			$this->_commonPageData();
			$this->display();
		}
		
	}
	
	/**
	 * 编辑页面
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$info = $this->Yezhu_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			
			$this->wuye_service->addYezhuRules($this->staff_service->getTopChildList('证件类型'),$this->input->post('id_type'),$info['id']);
			
			for($i = 0; $i < 1; $i++){
				$info = array_merge($info,$_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$this->Yezhu_Model->update(array_merge($info,$_POST,$this->addWhoHasOperated('edit')),array('id' => $id));
				$error = $this->Yezhu_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput($this->input->post('id_no').'已存在');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
		}else{
			
			$this->assign('info',$info);
			
			$this->_commonPageData();
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
				$this->form_validation->set_rules('name','业主名称','required|min_length[2]|max_length[20]');
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
			
			if($this->Yezhu_Model->update($data,array('id' => $id)) < 0){
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
    	$messageType = 'error';
    	
    	
    	$this->form_validation->set_error_delimiters('','');
    	
    
    
    	if($this->isPostRequest()){
       		
    		for($i = 0; $i < 1; $i++){
    			
    			if(0 != $_FILES['excelFile']['error']){
    				$feedback = getErrorTip('请上传文件');
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
					
					
					$result = array();
					$successCnt = 0;
					
					
					$idTypeList = $this->staff_service->getTopChildList('证件类型');
					$jiguanList = $this->staff_service->getTopChildList('籍贯');
					
					$provinceIdcard = config_item('province_idcard');
					
					
					$currentYear = date('Y');
					
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['name'] = getCleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue());
						$tmpRow['id_type'] = getCleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue());
						$tmpRow['id_no'] = getCleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue());
						$tmpRow['mobile'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						
						$this->form_validation->reset_validation();
						
						
						if(('身份证' == $tmpRow['id_type'] || '驾驶证' == $tmpRow['id_type']) && strlen($tmpRow['id_no']) >= 15){
							$sex = intval(substr($tmpRow['id_no'],-2,1));
							$tmpRow['sex'] = $sex % 2 == 0 ? '2' : '1';
							
							$birthday = substr($tmpRow['id_no'],6,8);
							$tmpRow['birthday'] = substr($birthday,0,4). '-'.substr($birthday,4,2).'-' .substr($birthday,6,2);
							
							$tmpRow['age'] = $currentYear - intval(substr($birthday,0,4));
							
							$provinceName = $provinceIdcard[substr($tmpRow['id_no'],0,3)."000"];
							//$tmpRow['jiguan'] = $jiguanList[$provinceName]['id'];
							
							$tmpRow['jiguan'] = $provinceName;
						}
						
						$this->form_validation->set_data($tmpRow);
						$this->wuye_service->addYezhuRules($idTypeList,$tmpRow['id_type'],0);
						
						
						//重新设置籍贯
						$this->form_validation->set_rules('jiguan','籍贯','required|in_list['.implode(',',array_values($provinceIdcard)).']');
						
						if(!$this->form_validation->run()){
							//print_r($this->form_validation->error_array());
							
							if('development' == ENVIRONMENT){
								$tmpRow['message'] = $this->form_validation->error_html();
							}else{
								$tmpRow['message'] = '数据校验失败';
							}
							
							$result[] = $tmpRow;
							continue;
						}
						
						$insertData = array_merge(array(
							'name' => $tmpRow['name'],
							'mobile' => $tmpRow['mobile'],
							'id_type' => $idTypeList[$tmpRow['id_type']]['id'],
							'id_no' => $tmpRow['id_no'],
							'sex' => $tmpRow['sex'],
							'age' => $tmpRow['age'],
							'birthday' => $tmpRow['birthday'],
							'jiguan' => $jiguanList[$provinceName]['id']
							
						),$this->addWhoHasOperated('add'));
						
						$this->Yezhu_Model->_add($insertData);
						
						$error = $this->Yezhu_Model->getError();
						
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '数据库错误';
							if($error['code'] == MySQL_Duplicate_CODE){
								$tmpRow['message'] = '业主已经存在';
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
    	));
    	
    	$this->display();
    	
    }
}
