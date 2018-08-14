<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Ydzj_Admin_Controller {
	
	public $_moduleTitle;
	public $_className;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Order_service','Basic_data_service'));
		
		$this->_moduleTitle = '订单';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'OrderStatus' => OrderStatus::$statusName,
			'OrderVerify' => OrderVerify::$statusName,
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/unpay','title' => '未支付'),
			array('url' => $this->_className.'/payed','title' => '已支付'),
			array('url' => $this->_className.'/closed','title' => '已关闭'),
			array('url' => $this->_className.'/deleted','title' => '已删除'),
			array('url' => $this->_className.'/export','title' => '导出'),
		);
		
	}
	
	
	
	/**
	 * 查询条件
	 */
	public function _searchCondition($moreSearchVal = array()){
		$search['currentPage'] = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$search['order_id'] = $this->input->get_post('order_id') ? $this->input->get_post('order_id') : '';
		$search['amount_s'] = $this->input->get_post('amount_s') ? $this->input->get_post('amount_s') : '';
		$search['amount_e'] = $this->input->get_post('amount_e') ? $this->input->get_post('amount_e') : '';
			
		$search['mobile'] = $this->input->get_post('mobile') ? $this->input->get_post('mobile') : '';
		$search['add_username'] = $this->input->get_post('add_username') ? $this->input->get_post('add_username') : '';
		$search = array_merge($search,$moreSearchVal);
		
		$condition = array(
			'where' => array_merge(array('is_refund' => 0),$moreSearchVal),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $search['currentPage'],
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		if($search['order_id']){
			$condition['like']['order_id'] = $search['order_id'];
		}
		
		if($search['amount_s']){
			$condition['where']['amount >='] = intval($search['amount_s']*100);
		}
		
		if($search['amount_e']){
			$condition['where']['amount <='] = intval($search['amount_e']*100);
		}
		
		if($search['mobile']){
			$condition['where']['mobile'] = $search['mobile'];
		}
		
		if($search['add_username']){
			$condition['where']['add_username'] = $search['add_username'];
		}
		
		$list = $this->Order_Model->getList($condition);
		
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $search['currentPage']
		));
		
	}
	
	
	/**
	 * 获得列表
	 */
	public function index(){
		
		$this->_searchCondition(array(
			
			'status != ' => OrderStatus::$closed,
			'status   != ' => OrderStatus::$deleted,
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	
	/**
	 * 未支付
	 */
	public function unpay(){
		$this->_searchCondition(array(
			'status' => OrderStatus::$unPayed
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	/**
	 * 已支付
	 */
	public function payed(){
		$this->_searchCondition(array(
			'status' => OrderStatus::$payed
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	
	/**
	 * 已取消
	 */
	public function closed(){
		$this->_searchCondition(array(
			'status' => OrderStatus::$closed
		));
		
		$this->display($this->_className.'/index');
	}
	
	/**
	 * 已删除
	 */
	public function deleted(){
		$this->_searchCondition(array(
			'status' => OrderStatus::$deleted
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	
	/**
	 * 校验退款数据
	 */
	private function _getVerifyRules(){
		
		$this->form_validation->set_rules('id','记录ID必填','required');
		$this->form_validation->set_rules('reason','原因','required|min_length[2]|max_length[100]');
		$this->form_validation->set_rules('remark','备注','required|min_length[2]|max_length[100]');
	}
	
	/**
	 * 批量删除
	 */
	public function batch_delete(){
		
		$ids = $this->input->post('id');
		
		
	
		if($this->isPostRequest() && !empty($ids)){
		
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$affectRow = $this->order_service->updateOrderStatusByIds($ids,OrderStatus::$deleted,OrderStatus::$closed);
			
			if($affectRow){
				$this->jsonOutput('删除成功',array('jsReload' => true));
			}else{
				$this->jsonOutput('删除操作完成,没有订单被删除,只有已关闭的订单可以被删除');
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	/**
	 * 删除
	 */
	public function single_delete(){
		
		$ids = $this->input->get_post('id');
		
		if($this->isPostRequest() && !empty($ids)){
		
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$affectRow = $this->order_service->updateOrderStatusByIds($ids,OrderStatus::$deleted,OrderStatus::$closed);
  			
  			if($affectRow){
				$this->jsonOutput('删除成功',array('jsReload' => true));
			}else{
				$this->jsonOutput('删除操作完成,没有订单被删除,只有已关闭的订单可以被删除',array('jsReload' => true));
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	/**
	 * 批量关闭
	 */
	public function batch_close(){
		
		$ids = $this->input->post('id');
	
		if($this->isPostRequest() && !empty($ids)){
		
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$affectRow = $this->order_service->updateOrderStatusByIds($ids,OrderStatus::$closed,OrderStatus::$unPayed);
			
			if($affectRow){
				$this->jsonOutput('关闭成功',array('jsReload' => true));
			}else{
				$this->jsonOutput('关闭操作完成,没有订单被关闭');
			}
			
		}else{
			
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	/**
	 * 关闭
	 */
	public function single_close(){
		
		$ids = $this->input->get_post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$affectRow = $this->order_service->updateOrderStatusByIds($ids,OrderStatus::$closed,OrderStatus::$unPayed);
			
			if($affectRow){
				$this->jsonOutput('关闭成功',array('redirectUrl' => $this->lastUrl));
			}else{
				$this->jsonOutput('关闭操作完成,没有订单被关闭',array('jsReload' => true));
			}
			
  			

		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
		
	}
	
	
	/**
	 * 详情
	 */
	public function detail(){
		
		
		$id = $this->input->get_post('id');
		
		if(empty($id)){
			$orderId = $this->input->get_post('order_id');
			$info = $this->order_service->getOrderInfoById($orderId,'order_id');
			
			$this->_subNavs[] = array('url' => $this->_className.'/detail?order_id='.$orderId,'title' => $this->_moduleTitle.'详情');
			
		}else{
			$info = $this->order_service->getOrderInfoById($id);
			
			$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id,'title' => $this->_moduleTitle.'详情');
			
		}
		
		$this->assign(array(
			'info' => $info,
			'extraItem' => $this->order_service->extraInfoToArray($info)
		));
		
		
		$this->display();
		
	}
	
	/**
	 * 导出excel
	 */
    public function export(){
    	
    	$message = '';
    	
    	if($this->isPostRequest()){
    		
    		try {
    			
    			$search = $this->input->post(array('status','order_id','order_typename','add_username','mobile','order_time_s','order_time_e','old_amount_s','old_amount_e','page'));
				if($search['status']){
					$condition['where_in'][] = array('key' => 'status', 'value' => $search['status']);
				}
    			if($search['order_typename']){
    				$condition['where']['order_typename'] = $search['order_typename'];
    			}
    			if($search['order_id']){
    				$condition['where']['order_id'] = $search['order_id'];
    			}
				$MemberUid=$this->Member_Model->getById(array(
					'where' => array(
						'username' => $search['add_username']
					)
				));
    			if($MemberUid['uid']){
    				$condition['where']['uid'] = $MemberUid['uid'];
    			}
    			if($search['mobile']){
    				$condition['where']['mobile'] = $search['mobile'];
    			}
    			if($search['order_time_s']){
    				$condition['where']['gmt_create >='] = strtotime($search['order_time_s']);
    			}
    			if($search['order_time_e']){
    				$condition['where']['gmt_create <='] = strtotime($search['order_time_e']) + 86400;
    			}
    			if($search['old_amount_s']){
    				$condition['where']['amount >='] = intval($search['old_amount_s'] * 100);
    			}
    			if($search['old_amount_e']){
    				$condition['where']['amount <='] = intval($search['old_amount_e'] * 100);
    			}
    				
    			
    			$search['page'] = intval($search['page']) == 0 ? 1 : intval($search['page']);
    			
    			$dataCnt = $this->Order_Model->getCount($condition);
    			
    			$perPageSize = config_item('excel_export_limit');
    			
    			if($dataCnt > $perPageSize){
    				$condition['pager'] = array(
						'page_size' => $perPageSize,
						'current_page' => $search['page'],
						'form_id' => '#formSearch'
	    			);
    			}
    			$this->_doExport($condition);
    		}catch(Exception $e){
    			//出错信息
    			$message = $e->getMessage();
    		}
    		
    	}else{
    		
    		$this->display();
    	}
    	
    }

    /**
     * 导出数据列
     */
    private function _getExportConfig(){
    	return array(
    		'A' => array('db_key' => 'order_id','width' => 32 ,'title' => '订单号'),
    		'B' => array('db_key' => 'order_old','width' => 32 ,'title' => '原订单号'),
    		'C' => array('db_key' => 'ref_order','width' => 32 ,'title' => '外部订单号'),
    		'D' => array('db_key' => 'ref_refund','width' => 32 ,'title' => '外部退款单号'),
    		'E' => array('db_key' => 'order_typename','width' => 15 ,'title' => '订单类型'),
    		'F' => array('db_key' => 'pay_channel','width' => 10 ,'title' => '支付渠道'),
    		'G' => array('db_key' => 'pay_method','width' => 15 ,'title' => '支付方式'),
    		'H' => array('db_key' => 'status','width' => 10 ,'title' => '订单状态'),
    		'I' => array('db_key' => 'goods_name','width' => 35 ,'title' => '商品名称'),
    		'J' => array('db_key' => 'amount','width' => 10 ,'title' => '订单金额'),
    		'K' => array('db_key' => 'refund_amount','width' => 10 ,'title' => '退款金额'),
    		'L' => array('db_key' => 'refund_cnt','width' => 10 ,'title' => '退款次数'),
    		'M' => array('db_key' => 'username','width' => 15 ,'title' => '客户姓名'),
    		'N' => array('db_key' => 'mobile','width' => 15 ,'title' => '电话号码'),
    		'O' => array('db_key' => 'time_start','width' => 20 ,'title' => '订单开始时间'),
    		'P' => array('db_key' => 'time_expire','width' => 20 ,'title' => '订单过期时间'),
    		'Q' => array('db_key' => 'pay_time_end','width' => 20 ,'title' => '订单支付完成时间'),
    		'R' => array('db_key' => 'verify_username','width' => 10 ,'title' => '审核人'),
    		'R' => array('db_key' => 'verify_time','width' => 20 ,'title' => '审核时间'),
    	);
    	
    }
    
    /**
     * 执行导出动作
     */
   private function _doExport($condition = array()){
    	
    	$this->_initPHPExcel();
    	
        $objPHPExcel = new PHPExcel();
        
        
        $data = $this->Order_Model->getList($condition);
    	
    	$colConfig = $this->_getExportConfig();
    	
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
    	foreach($list as $rowId => $order){
    		foreach($colConfig as $colKey => $colItemConfig){
    			
    			$val = $order[$colItemConfig['db_key']];
    			
    			
    			switch($colItemConfig['title']){
    				case '性别':
    					$val = $val == 1 ? '男':'女';
    					break;
    				case '支付渠道':
    					$val = $basicData['242']['show_name'];
    					break;
    				case '支付方式':
    					$val = '小程序支付';
    					break;
    				case '订单状态':
    					$val = OrderStatus::$statusName[$val];
    					break;
    				case '订单金额':
    				case '退款金额':
    					$val = $val/100;
    					break;
    				case '审核时间':
    					if($val){
    						$val = date('Y-m-d H:i:s',$val);
    					}
    					else{
    						$val = '未审核';
    					}	
    					break;
    				case '订单开始时间':
    				case '订单过期时间':
    				case '订单支付完成时间':
    					if(strlen($val) == 14){
							$val = substr_replace($val,'-',4,0);
	    					$val = substr_replace($val,'-',7,0);
	    					$val = substr_replace($val,'  ',10,0);
	    					$val = substr_replace($val,':',14,0);
	    					$val = substr_replace($val,':',17,0);
    					}
    					break;
    				default:
    					break;
    			}
    			
    			
    			$objPHPExcel->getActiveSheet()->getCell($colKey.($rowId + 2))->setValueExplicit($val, PHPExcel_Cell_DataType::TYPE_STRING2);
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
        
    	
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $downloadName = $this->_moduleTitle.'.xlsx';
        $fileRealName = md5(uniqid());
        
        $filePath = ROOTPATH.'/temp/'.$fileRealName.'.xlsx';
        
        $objWriter->save($filePath);
        $objPHPExcel->disconnectWorksheets(); 
        
        unset($objPHPExcel,$objWriter);
        
        force_download($downloadName,  file_get_contents($filePath));
        
    }

	
}
