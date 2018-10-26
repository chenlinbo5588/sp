<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Ydzj_Admin_Controller {
	
	public $_moduleTitle;
	public $_className;
	public $payMethod;
	
	public function __construct(){
		parent::__construct();
		
		$this->payMethod  = config_item('payment');
		
		$this->load->library(array('Wuye_service','Order_service','Basic_data_service'));
		
		$this->order_service->setDataModule($this->_dataModule);
		
    	
		
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
			array('url' => $this->_className.'/add','title' => '添加手工单'),
			array('url' => $this->_className.'/unpay','title' => '未支付'),
			array('url' => $this->_className.'/payed','title' => '已支付'),
			array('url' => $this->_className.'/closed','title' => '已关闭'),
			array('url' => $this->_className.'/deleted','title' => '已删除'),
			array('url' => $this->_className.'/export','title' => '导出'),
			array('url' => $this->_className.'/bill','title' => '对账单'),
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
		$search['username'] = $this->input->get_post('username') ? $this->input->get_post('username') : '';
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
		
		if($search['username']){
			$condition['where']['username'] = $search['username'];
		}
		
		$list = $this->order_service->search($this->_moduleTitle,$condition);
		
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
	 * 检验手机号码
	 */
	public function checkDate($edate,$sdate = ''){
		
		$edateTs = strtotime($edate);
		$sdateTs = strtotime($sdate);
		
		
		if(empty($edate)){
			$this->form_validation->set_message('checkDate','结束日期非法');
			return false;
		}
		
		if(empty($sdate)){
			$this->form_validation->set_message('checkDate','开始日期非法');
			return false;
		}
		
		if(date('Y',$edateTs) != date('Y',$sdateTs)){
			$this->form_validation->set_message('checkDate','日期不能跨年');
			return false;
		}
		
		
		if($sdateTs >= $edateTs){
			$this->form_validation->set_message('checkDate','缴费结束日期不能小于开始日期');
			return false;
		}
		
		return true;
		
	}
	
	
	public function add(){
		$payMethodList =  $this->payMethod['method']['手工单'];
		$year = date('Y',$_POST['star_time']);
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_rules('address','地址','required');
				$this->form_validation->set_rules('amount','订单金额','required|is_numeric|greater_than[0]');
				$this->form_validation->set_rules('start_date','缴费开始日期','required|valid_date');
				$this->form_validation->set_rules('end_date','缴费结束日期','required|valid_date|callback_checkDate['.$_POST['start_date'].']');
				
				
				if(!$this->form_validation->run()){			
					$this->jsonOutput('数据校验失败',array('errors' =>$this->form_validation->error_array()));
					break;
				}
				
				
				$houseItem = $this->order_service->search('房屋',array(
					'select' => 'id,yezhu_id,yezhu_name,uid',
					'where' => array(
						'address' => $this->input->post('address')
					)
				));
				
				
				if(empty($houseItem)){
					$this->jsonOutput('物业信息不存在');
					break;
				}
				
				$sdateTs = strtotime($this->input->post('start_date'));
				$edateTs = strtotime($this->input->post('end_date'));
				$sdateTs = strtotime(date('Y-m',$sdateTs).' first day of this month');
				$edateTs = strtotime(date('Y-m',$edateTs).' last day of this month');
				
				$param = array(
					'amount' => $_POST['amount'],
					'end_month' => date('m',$edateTs),
					'house_id' => $houseItem[0]['id'],
					'uid2' => $houseItem[0]['uid'],
					'utype' => Utype::$handwork,
					'order_typename' => $_POST['wuye_type'],
					'year' => date('Y',$edateTs),
					'month' => date('m',$edateTs) - date('m',$sdateTs) + 1,
					'pay_time' => time(),
					'pay_channel' => $this->payMethod['channel']['手工单'],
					'pay_method' => $_POST['pay_method'],
				);
				
				$message = '';
				
				$memberInfo = $this->Member_Model->getFirstByKey($houseItem[0]['uid'],'uid');
				
				if(empty($memberInfo)){
					$memberInfo = array('uid' => 0 , 'username' => $houseItem[0]['yezhu_name']);
				}
				$this->order_service->setWeixinAppConfig(config_item('mp_xcxCswy'));
				$this->Plan_Model->beginTrans();
				
				$result = $this->order_service->createWuyeOrder('house_id',$param,$memberInfo,$message,'Backstage');

				if($this->Plan_Model->getTransStatus() === FALSE){
					$this->Plan_Model->rollBackTrans();
					
					$this->jsonOutput('创建失败');
					
					return FALSE;
				}else{
					$flag = $this->Plan_Model->commitTrans();
					
					if($flag){
						$this->jsonOutput('创建成功');
					}else{
						$this->jsonOutput('创建失败');
					}
				}
				$this->jsonOutput($message);
			}
		}else{
			$this->assign('payMethodList',$payMethodList);
			$this->display();
		}
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
		
		
		$showExpire = false;
		if(in_array($info['order_typename'],array('物业费','能耗费','车位费'))){
			$showExpire = true;
		}
		
		$payChannelList = $this->payMethod['channel'];
		$payMethodList = $this->payMethod['method'];
		$payChannelList = array_flip($payChannelList);
		$payMethodList = array_flip($payMethodList[$payChannelList[$info['pay_channel']]]);	
		$this->assign(array(
			'info' => $info,
			'showExpire' => $showExpire,
			'extraItem' => $this->order_service->extraInfoToArray($info),
			'payChanne' => $payChannelList[$info['pay_channel']],
			'payMethod' => $payMethodList['pay_method'] 
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
    			
    			$search = $this->input->post(array('status','order_id','order_typename','username','mobile','order_time_s','order_time_e','old_amount_s','old_amount_e','page'));
				
				if($search['status']){
					$condition['where_in'][] = array('key' => 'status', 'value' => $search['status']);
				}
				
    			if($search['order_typename']){
    				$condition['where']['order_typename'] = $search['order_typename'];
    			}
    			if($search['order_id']){
    				$condition['where']['order_id'] = $search['order_id'];
    			}
    			
    			
    			if($search['username']){
    				$condition['where']['username'] = $search['username'];
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
    		//'K' => array('db_key' => 'refund_amount','width' => 10 ,'title' => '退款金额'),
    		//'L' => array('db_key' => 'refund_cnt','width' => 10 ,'title' => '退款次数'),
    		'K' => array('db_key' => 'username','width' => 15 ,'title' => '客户姓名'),
    		'L' => array('db_key' => 'mobile','width' => 15 ,'title' => '电话号码'),
    		'M' => array('db_key' => 'fee_start','width' => 20 ,'title' => '缴费生效日期'),
    		'N' => array('db_key' => 'fee_expire','width' => 20 ,'title' => '缴费过期日期'),
    		'O' => array('db_key' => 'pay_time_end','width' => 20 ,'title' => '订单支付完成时间'),
    		//'P' => array('db_key' => 'verify_username','width' => 10 ,'title' => '审核人'),
    		//'Q' => array('db_key' => 'verify_time','width' => 20 ,'title' => '审核时间'),
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
        
        //$this->load->library(array('Basic_data_service'));
        //$basicData = $this->basic_data_service->getBasicData();
        
		$payChannelList = $this->payMethod['channel'];
		$payMethodList = $this->payMethod['method'];
		$payChannelList = array_flip($payChannelList);
		$payMethodList = array_flip($payMethodList);

    	foreach($list as $rowId => $order){
    		foreach($colConfig as $colKey => $colItemConfig){
    			
    			$val = $order[$colItemConfig['db_key']];
    			
    			
    			switch($colItemConfig['title']){
    				case '性别':
    					$val = $val == 1 ? '男':'女';
    					break;
    				case '支付渠道':
    					$val =  $payChannelList[$val];
    					break;
    				case '支付方式':
						foreach($this->payMethod['method'] as $key => $item){
							foreach($this->payMethod['method'][$key] as $keys => $valus){
								if($valus == $val){
									$val = $keys;
								}
							}

						}
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
    				case '缴费生效日期':
    				case '缴费过期日期':
    					if($val){
    						$val = date('Y-m-d',$val);
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

	
	/**
	 * 对账单 下载
	 */
	public function bill(){
		
		$message = '';
    	
    	if($this->isPostRequest()){
    		try {
    			
    			$this->form_validation->set_rules('bill_date','账单日期','required|valid_date');
    			$this->form_validation->set_rules('bill_type','账单类型','required|in_list[ALL,SUCCESS,REFUND,RECHARGE_REFUND]');
    			
    			
    			for($i = 0; $i < 1; $i++){
    				if(!$this->form_validation->run()){
    					$this->display();
	    				break;
	    			}
	    			
	    			$this->_billDownload($_POST);
    			}
    			
    		}catch(Exception $e){
    			//出错信息
    			$message = $e->getMessage();
    		}
    		
    	}else{
    		$this->display();
    	}
		
	}
	
	/**
	 * 下载对账单
	 */
	private function _billDownload($param){
		$this->_initPHPExcel();
    	
        $objPHPExcel = new PHPExcel();
        $list = $this->order_service->getWeixinPayBill(str_replace('-','',$param['bill_date']),$param['bill_type']);
        
        
        if($list){
        	
        	
        	$this->load->config('bank');
        	
        	$banKList = config_item('bank');
        	
        	$lines = explode("\n",str_replace("\r\n","\n",$list));
        	
        	$maxColunm = 0;
        	
        	$colConfig = array();
        	
        	
        	
	        foreach($lines as $row => $aline){
	        	$column = explode(',',str_replace('`','',$aline));
	        	
	        	
	        	if(0 == $row){
	        		$maxColunm = count($column);
	        	}
	        	
	        	foreach($column as $colIndex => $colVal){
	        		if(0 == $row){
	        			$colConfig[$colVal] = $colIndex;
	        		}else{
	        			
	        			if($colIndex == $colConfig['付款银行']){
	        				$colVal = $banKList[$colVal];
	        			}
	        		}
	        		
	        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colIndex, $row + 1,$colVal);
	        	}
	        }
	        
	        $endColName = 'A';
	        
	        if($maxColunm > 26){
	        	$endColName = 'A'.chr(ord('A') + $maxColunm);
	        }else{
	        	$endColName = chr(ord('A') + $maxColunm);
	        }
	        
	        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$endColName.count($lines))->applyFromArray(
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
        
        
    	$format = $this->input->post('format');
    	
    	$fileRealName = md5(uniqid());
    	$fileExt = '.xlsx';
    	
    	if('Excel2007' == $format){
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    	}else{
    		$fileExt = '.xls';
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	}
    	
    	$downloadName = '对账单'.$fileExt;
        $filePath = ROOTPATH.'/temp/'.$fileRealName.$fileExt;
        
        
        $objWriter->save($filePath);
        $objPHPExcel->disconnectWorksheets(); 
        
        unset($objPHPExcel,$objWriter);
        
        force_download($downloadName,  file_get_contents($filePath));
		
	}
}
