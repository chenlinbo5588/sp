<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Feetype extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service','Basic_data_service'));
		
		$this->_moduleTitle = '費用类型';
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
		$search['year'] = $this->input->get_post('year');
	
		if($search['name']){
			$condition['like']['resident_name'] = $search['name'];
		}
		
		if($search['year']){
			$condition['where']['year'] = $search['year'];
		}
		
		
		$list = $this->Feetype_Model->getList($condition);
		
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
			
			$this->Feetype_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功');
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
		$basicData = $this->basic_data_service->getAssocBasicDataTree();
		
		$this->assign(array(
			'feeTypeList' => $this->basic_data_service->getTopChildList('费用类型'),
		));
	}
	
	
	
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			
				
			for($i = 0; $i < 1; $i++){
				
				$this->wuye_service->addFeeTypeRules();
				
				$this->_addYearRule();
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
	
				$info = array_merge((array)$info,(array)$_POST,$this->_prepareData(),$this->addWhoHasOperated('add'));
				
				$residentInfo = $this->Resident_Model->getFirstByKey($info['resident_name'],'name','id');
				$info['resident_id']= $residentInfo["id"];
				
				$feeTypeList = $this->basic_data_service->getTopChildList('费用类型');
				$info['fee_id'] = $feeTypeList[$info['name']]['id'];
				
				$newid =$this->Feetype_Model->_add(array_merge($info,$_POST,$this->_prepareData(),$this->addWhoHasOperated('add')));
				$error = $this->Feetype_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput('当前费用已添加');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			
			$this->_commonPageData();
			$this->display();
		}
		
	}
	
	
	private function _addYearRule(){
		$this->form_validation->set_rules('year','缴费年份','required|is_natural_no_zero');
	}
	
	
	/**
	 * 编辑页面
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$info = $this->Feetype_Model->getFirstByKey($id);
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_addYearRule();
			$this->wuye_service->addFeeTypeRules();
			
			for($i = 0; $i < 1; $i++){
				$info = array_merge($info,$_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$residentInfo = $this->Resident_Model->getFirstByKey($info['resident_name'],'name','id');
				$info['resident_id']= $residentInfo["id"];
				
				$feeTypeList = $this->basic_data_service->getTopChildList('费用类型');
				$info['fee_id'] = $feeTypeList[$info['name']]['id'];
				
				$this->Feetype_Model->update(array_merge($info,$_POST,$this->addWhoHasOperated('edit')),array('id' => $id));
				$error = $this->Feetype_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput('当前费用已存在');
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
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			switch($fieldName){
				case 'name':
					$this->form_validation->set_rules('name','费用类型名称','in_db_list['.$this->Basic_Data_Model->getTableRealName().'.show_name]');
					break;
				case 'price';
					$this->form_validation->set_rules('price','每平方单价','required|is_numeric');	
					break;
				case 'displayorder';
					$this->form_validation->set_rules('displayorder','排序',"required|is_natural|less_than[256]");
					break;
				default:
					break;
			}
			
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				if($this->Feetype_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
	}
	
	public function getResidentName(){
		
		$searchKey = $this->input->get_post('term');
		
		$return = array();
		
		if($searchKey){
			$residentList = $this->Resident_Model->getList(array(
				'like' => array(
					'name' => $searchKey
				),
				'limit' => 50
			));
			
			foreach($residentList as $feetypeItem){
				$return[] = array(
					'id' =>$feetypeItem['id'],
					'label' => $feetypeItem['name'],	
				);
			}
		}
		
		//echo json_encode($return);
		$this->jsonOutput2('',$return,false);
		
	}
	
	
	
	/**
	 * 导入输出
	 */
	private function _importOutput($result){
		
		$str = array();
		foreach($result as $key => $line){
			$str[] = "<tr class=\"{$line['classname']}\"><td>{$line['name']}</td><td>{$line['year']}</td><td>{$line['price']}</td><td>{$line['message']}</td></tr>";
		}
		
		return implode('',$str);
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
	    		
	    		$this->_initPHPExcel();
		        
	    		try {
	    			
	    			$excelFile = $_FILES['excelFile']['tmp_name'];
	    			$objPHPexcel = PHPExcel_IOFactory::load($excelFile);
					$objWorksheet = $objPHPexcel->getActiveSheet(0); 
					
					$startRow = 2;
					$highestRow = $objWorksheet->getHighestRow();
					
					$importMaxLimit = config_item('excel_import_limit');
					
					if(($highestRow + 1) > $importMaxLimit){
						$highestRow = $importMaxLimit + 1;
					}
					
					$result = array();
					$successCnt = 0;
					
					
					$feeTypeList = $this->basic_data_service->getTopChildList('费用类型');
					$provinceIdcard = config_item('province_idcard');
					
					//$this->basic_data_service->getTopChildList('费用类型'),
					$residentList = $this->Resident_Model->getList(array(),'name');
					
					$currentYear = date('Y');
					
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['classname'] = 'failed';
						$tmpRow['name'] = getCleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue());
						$tmpRow['year'] = getCleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue());
						$tmpRow['resident_name'] = getCleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue());
						$tmpRow['price'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						
						$this->form_validation->reset_validation();
						
						$this->form_validation->set_data($tmpRow);
						
						
						$this->_addYearRule();
						$this->wuye_service->addFeeTypeRules();
						
						if(!$this->form_validation->run()){
							$tmpRow['message'] = $this->form_validation->error_first_html();
							$result[] = $tmpRow;
							continue;
						}
						
						$insertData = array_merge(array(
							'name' => $tmpRow['name'],
							'year' => $tmpRow['year'],
							'resident_id' => $residentList[$tmpRow['resident_name']]['id'],
							'resident_name' => $tmpRow['resident_name'],
							'price' => $tmpRow['price'],
							'fee_id' => $feeTypeList[$tmpRow['name']]['id'],					
						),$this->addWhoHasOperated('add'));
						
						$this->Feetype_Model->_add($insertData);
						
						$error = $this->Feetype_Model->getError();
						
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '数据库错误';
							if($error['code'] == MySQL_Duplicate_CODE){
								$tmpRow['message'] = '费用类型已经存在';
							}
						}else{
							$tmpRow['message'] = '导入成功';
							$tmpRow['classname'] = 'ok';
							$successCnt++;
						}
						
						$result[] = $tmpRow;
					}
					
					
					$feedback = getSuccessTip('导入完成,导入'.$successCnt.'条,失败'.(count($result) - $successCnt).'条');
					
					$this->assign(array(
						'output' => '<table class="table">'.$this->_importOutput($result).'</table>',
						'successCnt' => $successCnt,
					));
					
					
	    			@unlink($excelFile);
	    		}catch(Exception $e){
	    			$feedback = '导入错误,请检查文件格式是否正确';
	    		}
    		}
    		
    		$this->assign(array(
    			'feedback' => $feedback,
    		));
    		
    		$this->display('common/import_resp');
    		
    	}else{
    		$this->display();
    	}
    	
    }
}
