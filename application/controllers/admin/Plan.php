<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service','Basic_data_service'));
		
		$this->wuye_service->setDataModule($this->_dataModule);
		
		
		$this->_moduleTitle = '收费计划';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '汇总'),
			array('url' => $this->_className.'/detail_list','title' => '明细'),
			array('url' => $this->_className.'/add','title' => '生成收费计划'),
			array('url' => $this->_className.'/export','title' => '导出'),
		);
		
	}
	public function index(){
		$this->Plan_Model->setTableId(date('Y'));
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

		
		$search['address'] = $this->input->get_post('address');
		if($search['address']){
			$condition['like']['address'] = $search['address'];
		}
		
		$search['feetype_name'] = $this->input->get_post('feetype_name');
		if($search['feetype_name']){
			$condition['where']['feetype_name'] = $search['feetype_name'];
		}
		
		
		$list = $this->wuye_service->search( $this->_moduleTitle, $condition);
		$this->assign(array(
			'residentList' => $this->wuye_service->getOwnedResidentList(array(
							'select' => 'id,name',
						),'id'),
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $currentPage,
		));
		
		$this->display();
	}
	
	public function add(){
		$data = array();
		
		if($this->isPostRequest()){

			$this->form_validation->set_rules('resident_id','所在小区','required|is_natural_no_zero');
			if(!$this->form_validation->run()){
    			$feedback = getErrorTip($this->form_validation->error_html());
    			break;
	    	}
	    	
	    	$year = $_POST['year'];

			$resident_id = $this->input->get_post('resident_id');	
			$result = $this->wuye_service->generationPlan($resident_id,$this->addWhoHasOperated(),$year);
			$this->Plan_Model->setTableId($year);
		
			$this->jsonOutput('生成成功,'.$result['successCnt'].'条,生成失败'.$result['failedCnt'].'条');
		}else{
    		$this->assign(array(
	    		'residentList' => $this->wuye_service->search('小区',array(
    				'select' => 'id,name',
					'order' => 'displayorder DESC'
				),'id')
	    	));
	    	
    		$this->display();
    	}
		
	}
	
	public function detail(){
		$this->Plan_Model->setTableId(date('Y'));
		$this->Plan_Detail_Model->setTableId(date('Y'));
		$id = $this->input->get_post('id') ? $this->input->get_post('id') : 0;
		
		$planDetail = $this->wuye_service->search('收费计划',array(
			'where' => array('id' => $id,)
		));
		
		$condition = array(
			'order' => 'id DESC',
		);
		
		$condition['where']['address'] = $planDetail[0]['address'];
		
		if('能耗费' == $planDetail[0]['feetype_name'])
		{
			$condition['where']['feetype_name'] = $planDetail[0]['feetype_name'];
			
		}else if('物业费' == $planDetail[0]['feetype_name'])
		{
			$condition['where_in'][] = array(
				'key' => 'feetype_name',
				'value' => array('物业费','车位费')
			);
		}
		$list = $this->wuye_service->search('收费计划详情', $condition);
		$this->assign('list',$list);
		
		$this->display();
		
	}
	public function detail_list(){
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

		
		$search['address'] = $this->input->get_post('address');
		if($search['address']){
			$condition['like']['address'] = $search['address'];
		}
		
		$search['feetype_name'] = $this->input->get_post('feetype_name');
		if($search['feetype_name']){
			$condition['where']['feetype_name'] = $search['feetype_name'];
		}
		
		
		$list = $this->wuye_service->search('收费计划详情', $condition);
		$this->assign(array(
			'residentList' => $this->wuye_service->getOwnedResidentList(array(
							'select' => 'id,name',
						),'id'),
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $currentPage
			
		));
		
		$this->display();
	}
	public function edit_money(){
		$id = $this->input->get_post('id');
		
		$modify_money = $this->input->get_post('modify_money');
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1 ; $i++){
				$this->form_validation->set_rules('modify_money','修改金额','required|is_numeric');
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				$id = explode(',',$id);
				
				if(!$this->changeMoney($id,$modify_money)){
					$this->jsonOutput('操作失败,没有记录被更新');
					break;
				}else{
					$this->jsonOutput('操作成功',array('jsReload' => true));
				}
			}
			
		}else{
			
			$this->assign('id',implode(',',$this->input->get_post('id')));

			$this->display();
		}		
	}
	
	private function changeMoney($ids,$modify){
		$this->Plan_Model->setTableId(date('Y'));
		$this->Plan_Detail_Model->setTableId(date('Y'));
		$this->Plan_Model->beginTrans();
		$planDetailList = $this->wuye_service->search('收费计划详情',array(
			'where_in' => array(
				array('key' => 'id', 'value' => $ids)
			)
		),'id');
		foreach($planDetailList as $key => $valus){
			$planDetailList[$key]['amount_real'] = $modify ;
			$houseIdList[] = $planDetailList[$key]['house_id'];
			
		}

		$this->Plan_Detail_Model->batchUpdate($planDetailList);
		array_unique($houseIdList);
		$planlList = $this->wuye_service->search('收费计划',array(
			'where_in' => array(
				array('key' => 'house_id', 'value' => $houseIdList)
			)
		),'id');
		foreach($planlList as $key => $item){
			$detailList = $this->wuye_service->search('收费计划详情',array(
				'where' => array(
					'house_id' => $item['house_id']
				)
			));
			$wuyeMoney = 0;
			$nenghaoMoney = 0;
			foreach($detailList as $detailkey => $detailItem){
				if('物业费' == $detailItem['feetype_name'] || '车位费' == $detailItem['feetype_name']){
					$wuyeMoney= $detailItem['amount_real']+$wuyeMoney;
				}
			}
			if('物业费' == $item['feetype_name']){
				$planlList[$key]['amount_real'] = $wuyeMoney;
			}
			
		}
		//print_r($planlList);
		$this->Plan_Model->batchUpdate($planlList);
		
		if($this->Plan_Model->getTransStatus() === FALSE){
			$this->Plan_Model->rollBackTrans();
			return false;
		}else{
			$this->Plan_Model->commitTrans();
			return true;
		}
	}
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		
		
		for($i = 0 ; $i < 1; $i++){
			
			$this->form_validation->set_rules('value','实收金额','required|is_numeric');	
	
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				if(!$this->changeMoney($id,$newValue)){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		
		}
	}
	/**
	 * 导出exl表
	 */
  	 public function export(){
  
    	$message = '';
    	
    	if($this->isPostRequest()){
    		
    		try {
    			
    			$search = $this->input->post(array('resident_id','page'));
    			$choose = $this->input->post('choose');
    			$condition = array();
    			if($search['resident_id']){
    				$condition['where']['resident_id'] = $search['resident_id'];
    			}
    			
    			$search['page'] = intval($search['page']) == 0 ? 1 : intval($search['page']);
    			
    			$dataCnt = $this->wuye_service->getRecordCount($this->_moduleTitle,$condition);
    			
    			
    			$perPageSize = config_item('excel_export_limit');
    			
    			if($dataCnt > $perPageSize){
    				$condition['pager'] = array(
						'page_size' => $perPageSize,
						'current_page' => $search['page'],
						'form_id' => '#formSearch'
	    			);
    			}
    			//print_r($this->input->post('resident_id'));
    			$this->_doExport($condition,$choose);
    		}catch(Exception $e){
    			//出错信息
    			$message = $e->getMessage();
    		}
    		
    	}else{
    		$this->assign(array(
	    		'residentList' => $this->wuye_service->search('小区',array(
    				'select' => 'id,name',
					'order' => 'displayorder DESC'
				),'id')
	    	));
	    	
    		$this->display();
    	}
    }
    
   /**
     * 导出数据列
     */
    private function _getPlanExportConfig(){
    	return array(
    		'A' => array('db_key' => 'address','width' => 30 ,'title' => '地址'),
    		'B' => array('db_key' => 'resident_name','width' => 15 ,'title' => '小区名'),
    		'C' => array('db_key' => 'feetype_name','width' => 15 ,'title' => '费用类型'),
    		'D' => array('db_key' => 'year','width' => 15 ,'title' => '年份'),
    		'E' => array('db_key' => 'amount_plan','width' => 15 ,'title' => '计划金额'),
    		'F' => array('db_key' => 'amount_real','width' => 15 ,'title' => '实际计划金额'),
    		'G' => array('db_key' => 'amount_payed','width' => 15 ,'title' => '实收金额'),
    		'H' => array('db_key' => 'pay_method','width' => 25 ,'title' => '付款方式'),
    		'I' => array('db_key' => 'order_id','width' => 15 ,'title' => '订单ID'),
    		'J' => array('db_key' => 'order_status','width' => 15 ,'title' => '订单状态'),
    	);
    	
    }
    
    private function _getPlanDetailExportConfig(){
    	return array(
    		'A' => array('db_key' => 'address','width' => 30 ,'title' => '地址'),
    		'B' => array('db_key' => 'resident_name','width' => 15 ,'title' => '小区名'),
    		'C' => array('db_key' => 'year','width' => 15 ,'title' => '年份'),
    		'D' => array('db_key' => 'feetype_name','width' => 15 ,'title' => '费用类型'),
    		'E' => array('db_key' => 'wuye_type','width' => 15 ,'title' => '类别'),	
    		'F' => array('db_key' => 'jz_area','width' => 15 ,'title' => '面积'),
    		'G' => array('db_key' => 'price','width' => 15 ,'title' => '价格'),
    		'H' => array('db_key' => 'billing_style','width' => 15 ,'title' => '计费方式'),
    		'I' => array('db_key' => 'amount_plan','width' => 15 ,'title' => '计划金额'),
    		'J' => array('db_key' => 'amount_real','width' => 15 ,'title' => '实际计划金额'),
    		'K' => array('db_key' => 'amount_payed','width' => 15 ,'title' => '实收金额'),
    		'L' => array('db_key' => 'pay_method','width' => 15 ,'title' => '付款方式'),
    		'M' => array('db_key' => 'stat_date','width' => 20 ,'title' => '开始时间'),
    		'N' => array('db_key' => 'end_date','width' => 20 ,'title' => '结束时间'),
    		'O' => array('db_key' => 'month','width' => 15 ,'title' => '月份'),
    	);
    	
    }
    
    /**
     * 执行导出动作
     */
    private function _doExport($condition = array(),$choose){
    	
    	$this->_initPHPExcel();
    	
        $objPHPExcel = new PHPExcel();
		$this->Plan_Model->setTableId(date('Y'));
		$this->Plan_Detail_Model->setTableId(date('Y'));
        if('plan' == $choose){
        	$data = $this->wuye_service->search('收费计划',$condition);
        	$colConfig = $this->_getPlanExportConfig();
        }else if('plan_detail' == $choose){
        	$data = $this->wuye_service->search('收费计划详情',$condition);
        	$colConfig = $this->_getPlanDetailExportConfig();
        }  
    	
    	foreach($colConfig as $colKey => $colItemConfig){
    		$objPHPExcel->getActiveSheet()->getCell($colKey.'1')->setValueExplicit($colItemConfig['title'], PHPExcel_Cell_DataType::TYPE_STRING2);
    		$objPHPExcel->getActiveSheet()->getColumnDimension($colKey)->setWidth($colItemConfig['width']);
    	}
    	
    	$colKeys = array_keys($colConfig);
    	
    	$objPHPExcel->getActiveSheet()->getStyle($colKeys[0].'1:'.$colKeys[count($colKeys) - 1].'1')->applyFromArray(
    		array(
                'font'    => array(
                    'bold'      => true,
                    'size'     => 12
                ),

                'fill' => array(
                    'type'       => PHPExcel_Style_Fill::FILL_PATTERN_LIGHTGRAY,
                    'startcolor' => array(
                        'argb' => 'FFC0C0C0'
                    ),
                    'endcolor'   => array(
                        'argb' => 'FFC0C0C0'
                    )
                )
             )
	    );
        
        if($condition['pager']){
        	$list = $data['data'];
        	$objPHPExcel->getActiveSheet()->setTitle('第'.$data['pager']['pageNow'].'页之共'.$data['pager']['pageLastNum'].'页');
        }else{
        	$list = $data;
        }
        $basicData = $this->basic_data_service->getBasicData();
        if('plan' == $choose){     
        
			foreach($list as $rowId => $plan){
				foreach($colConfig as $colKey => $colItemConfig){
					
					$val = $plan[$colItemConfig['db_key']];
					
					switch($colItemConfig['title']){
						case '付款方式':
							$val = $basicData[$val]['show_name'];
							break;
						case '订单状态':
							$val = $val == 0 ? '未支付':'已支付';
							break;
						default:
							break;
							
					}
					$objPHPExcel->getActiveSheet()->getCell($colKey.($rowId + 2))->setValueExplicit($val, PHPExcel_Cell_DataType::TYPE_STRING2);
				}
			}
        }else if('plan_detail' == $choose){     
        
			foreach($list as $rowId => $plan_detail){
				foreach($colConfig as $colKey => $colItemConfig){
					
					$val = $plan_detail[$colItemConfig['db_key']];
					
					switch($colItemConfig['title']){
						case '开始时间':
						case '结束时间':
							$val = date("Y-m-d H:i",$val);
							break;
						default:
							break;
							
					}
					$objPHPExcel->getActiveSheet()->getCell($colKey.($rowId + 2))->setValueExplicit($val, PHPExcel_Cell_DataType::TYPE_STRING2);
				}
			}
        }
    	
    	
    	$objPHPExcel->getActiveSheet()->getStyle($colKeys[0].'1:'.$colKeys[count($colKeys) - 1].(count($list) + 1))->applyFromArray(
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
        
    	
    	$format = $this->input->post('format');
    	
    	$fileRealName = md5(uniqid());
    	$fileExt = '.xlsx';
    	
    	if('Excel2007' == $format){
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    	}else{
    		$fileExt = '.xls';
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	}
    	
    	$downloadName = $this->_moduleTitle.$fileExt;
        $filePath = ROOTPATH.'/temp/'.$fileRealName.$fileExt;
        
        
        $objWriter->save($filePath);
        $objPHPExcel->disconnectWorksheets(); 
        
        unset($objPHPExcel,$objWriter);
        
        force_download($downloadName,  file_get_contents($filePath));
        
    }

}
