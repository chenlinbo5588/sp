
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yewu extends Wx_Tdkc_Controller {
	
	private $_isBind = false;
	public function __construct(){
		parent::__construct();
    	
    	$this->load->library('Yewu_service','Admin_pm_service','Basic_data_service');

	}

	public function _remap($method)
	{
		
	    if (11 == strlen($this->userInfo['mobile']))
	    {
	        $this->$method();
	    }
	    else
	    {
	        $this->jsonOutput2('请先绑定用户再进行操作',array('isbingding' => false));
	    }
	}


	public function setYewu(){
		extract($this->postJson,EXTR_OVERWRITE);
		for($i = 0;$i < 1; $i++){
			//getBasicData
			$serviceAreaList = $this->basic_data_service->getTopChildList('服务区域');
			
			$this->form_validation->set_data($this->postJson);

/*	  			$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
	  			$this->form_validation->set_rules('real_name','办证使用名字','required');*/
  			$this->form_validation->set_rules('yewu_describe','业务描述','max_length[255]');
  			$this->form_validation->set_rules('service_area','测绘区域','required|in_list['.implode(',',array_keys($serviceAreaList)).']');
  			
  			if(!$this->form_validation->run()){
				$this->jsonOutput2($this->form_validation->error_first_html());
				break;
			}
		
			$groupInfo = $this->yewu_service->getGroupInfo($serviceAreaList[$service_area]['id']);
			
			$companyInfo = $this->Company_Model->getFirstByKey($companyName,'name');
			$acceptNumber = $this->yewu_service->generateSerialNumber(date('Y'),$service_area,$service_area);
			 
			$yewuInfo = array(
  				'mobile' => $mobile,
  				'year' => date('Y'),
  				'real_name' => $real_name,
  				'yewu_describe' => $yewu_describe,
  				'service_area' => $serviceAreaList[$service_area]['id'],
  				'user_id' => $this->userInfo['uid'],
  				'user_name' => $this->userInfo['name'],
  				'user_mobile' => $this->userInfo['mobile'],
				'add_uid'	=>  $this->userInfo['uid'],
				'add_username'	=>  $this->userInfo['name'],
				'status' => Operation::$submit,
				'group_id' => $groupInfo['id'],
				//'company_name' => $company_name
				'accept_number' => $acceptNumber['accept_number'],
				'encryption_number' => $acceptNumber['encryption'],
			);

			$title = $service_area.'新业务';
			$newYewuId = $this->Yewu_Model->_add($yewuInfo);
			if($newYewuId){
				$this->yewu_service->addYewuDetail($this->userInfo,Operation::$submit,$newYewuId);
				$this->admin_pm_service->addYewuMessage($yewuInfo,$newYewuId,$title);
				$this->jsonOutput2(RESP_SUCCESS);
				break;
			}
			
			$this->jsonOutput2(RESP_ERROR);
		}
			
	}

	
	
	
	public function getYewuList(){

		$yewuId  = $this->postJson['yewu_id'];
		$id = $this->userInfo['uid'];
		$status = $this->postJson['status'];
		if(!is_array($status) && $status){
			$status[] = $status;
		}
		$search = $this->postJson['yewu_name'];
		$data = $this->yewu_service->getYewuList($id,$status,$this->userInfo['group_id'],$search,$this->userInfo['user_type'],$yewuId);
		
		$yewuList = array(
			'data' =>$data,
		);
		if($yewuList){
			$this->jsonOutput2(RESP_SUCCESS,$yewuList);
		}else{
			$this->jsonOutput2(RESP_ERROR);
		}
		
	}
	
	
	
	public function getUserType(){
		
		$type = array(
			'type' => $this->userInfo['user_type'],
			'name' => $this->userInfo['name'],
		);
		$this->jsonOutput2(RESP_SUCCESS,$type);
	}
	

	
	
	public function accessAll(){
		$id = $this->postJson['id'];
		
		$data =$this->Yewu_Model->getFirstByKey($id,'id');
		
		if($data){
			$usermobileName =array(
				'user_name' => $data['user_name'],
				'user_mobile' => $data['user_mobile'],
			);
			$workmobileName =array(
				'user_name' => $data['worker_name'],
				'user_mobile' => $data['worker_mobile'],
			);
			$this->jsonOutput2(RESP_SUCCESS,array('user' => $usermobileName , 'worker' => $workmobileName));
		}else{
			$this->jsonOutput2(RESP_ERROR);
		}
	}
	
	
	

	/**
	 * 获得业务类型
	 */
	public function getYewuWorkCategory(){

		$workCategory = $this->basic_data_service->getTopChildList('工作类别');
		$workCategory = array_values($workCategory);
		 if(is_array($workCategory)){
		 	$this->jsonOutput2(RESP_SUCCESS,array('workCategory' => $workCategory));
		 }else{
		 	$this->jsonOutput2(RESP_ERROR);
		 }

	}
	
	
	
	
	public function getAllGroupList(){
		$this->jsonOutput2(RESP_SUCCESS,array('groupList' => $this->Work_Group_Model->getList()));
		
	}
	



	/**
	 * 业务转让申请
	 */
	public function transferApply(){
		if(3 == $this->userInfo['user_type']){
			$groupId = $this->postJson['group_id'];
			$groupInfo = $this->Work_Group_Model->getFirstByKey($groupId,'id');
			$yewuId = $this->postJson['yewu_id'];
			$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId,'id');
			if($yewuInfo['status'] == Operation::$submit);{
				if($groupInfo && $yewuInfo){		
					$this->Yewu_Model->beginTrans();
				 	$this->Yewu_Model->updateByCondition(
						array(
							'group_id' => $groupInfo['id'],
							'status' => 10,
							'edit_uid' => $this->userInfo['uid'],
							'edit_username' => $this->userInfo['name'],
							'gmt_modify' => time(),
							
						),
						array('where' => array('id' => $yewuId))
					);
					
					$this->Yewu_Transfer_Model->_add(array(
						'yewu_id' => $yewuId,
						'group_id_from' => $this->userInfo['group_id'],
						'group_name_from' => $this->userInfo['group_name'],
						'group_id_to' => $groupInfo['id'],
						'group_name_to' => $groupInfo['group_name'],
						'status' => 1,
						'add_uid' => $this->userInfo['uid'],
						'add_username' => $this->userInfo['name'],
						'gmt_create' => time(),
					));
					if($this->Yewu_Model->getTransStatus() === FALSE){
						$this->Yewu_Model->rollBackTrans();
						$this->jsonOutput2(RESP_ERROR);
					}else{
						$this->Yewu_Model->commitTrans();
						$this->jsonOutput2(RESP_SUCCESS);
					}
				}else{
					$this->jsonOutput2(RESP_ERROR,array('status' => '请选择业务和移交小组'));
				}
			}
		}else{
			$this->jsonOutput2('只有组长才能转让小组');
		}
	}
	
	
	
	public function transferHandle(){
		if(3 == $this->userInfo['user_type']){
			$yewuInfo = $this->Yewu_Model->getFirstByKey($this->postJson['yewu_id'],'id');
			$groupInfo = $this->Work_Group_Model->getFirstByKey($this->postJson['group_id'],'id');		
			$thansferInfo = $this->Yewu_Transfer_Model->getList(array('where' => array(
				'yewu_id' => $this->postJson['yewu_id'],
				'status' => 1,
				'group_id_to' => $this->postJson['group_id'],
			)));
			$fromGroupInfo = $this->Yewu_Model->getFirstByKey($thansferInfo['group_id_from'],'id');
			if($thansferInfo && $yewuInfo && $fromGroupInfo){
				if($this->yewu_service->changeTansfer($this->postJson,$this->userInfo,$fromGroupInfo)){
					$this->jsonOutput2(RESP_SUCCESS);
				}
			}
		}
	}
	
	
	/**
	 * 撤销转让
	 */
	 public function revokeTransfer(){
		if(3 == $this->userInfo['user_type']){
			$groupInfo = $this->Work_Group_Model->getFirstByKey($this->postJson['group_id'],'id');		
			$thansferInfo = $this->Yewu_Transfer_Model->getList(array('where' => array(
				'yewu_id' => $this->postJson['yewu_id'],
				'status' => 1,
				'group_id_from' => $this->postJson['group_id'],
			)));
			$fromGroupInfo = $this->Yewu_Model->getFirstByKey($thansferInfo['group_id_from'],'id');
			if($thansferInfo && $fromGroupInfo){
				$this->Yewu_Model->updateByCondition(
					array(
						'status' => Operation::$submit,
						'current_group' => $fromGroupInfo['id']
					),
					array('where' => array('id' => $this->postJson['yewu_id']))
				);
				$this->Yewu_Transfer_Model->delete(array('where' => array('id' => $thansferInfo['id'])));
			}
		}
	 }
	
	

 	public function setYewuMoney(){
		if($this->userInfo['user_type'] == 3){
			$yewuId = $this->postJson['yewu_id'];
			$money = $this->postJson['money'];
			$this->form_validation->set_data($this->postJson);
  			$this->form_validation->set_rules('money','缴费金额','required|numeric');
  			if(!$this->form_validation->run()){
				$this->jsonOutput2($this->form_validation->error_first_html());
			}else{
				if($yewuId){
					$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId);
					if(!$yewuInfo['is_payed']){
						$result = $this->yewu_service->setYewuMoney($yewuId,$money,$this->userInfo);
						if($result){
							$this->jsonOutput2(RESP_SUCCESS,array('title' =>$result));
						}else{
							$this->jsonOutput2(RESP_ERROR);
						}
					}else{
						$this->jsonOutput2("该业务已缴费不能修改金额");
					}
	
				}
			}
		}else{
			$this->jsonOutput2('只有组长才能设置金额');
		}

	}
	
	public function getIfEvaluate(){
		$yewuId = $this->postJson['yewu_id'];
		$judge = array();
		$yewuDetailList = $this->Yewu_Detail_Model->getList(array('where' => array('yewu_id' => $yewuId ,'operation' => Operation::$payment)));
		$evaluateInfo = $this->Evaluate_Model->getFirstByKey($yewuId,'yewu_id');
		if($yewuDetailList){
			$time = time() - $yewuDetailList[0]['gmt_create'];
  			$day = intval($time / 86400);
  			if($day < 30 && !($evaluateInfo && ($evaluateInfo['gmt_create'] != $evaluateInfo['gmt_modify']))){
  				$judge['if_Evaluate'] = true;
  			}else{
  				$judge['if_Evaluate'] = false;
  			}
		}else{
			$judge['if_Evaluate'] = false;
		}
		if($evaluateInfo){
			$judge['is_Evaluate'] = true;
			$judge['time'] = $evaluateInfo['gmt_create'];
		}else{
			$judge['is_Evaluate'] = false;
		}
		$this->jsonOutput2(RESP_SUCCESS,array('evaluate' => $judge));
	}
	
	
	
	 public function getEvaluateType(){
	 	$evaluateTypeList = array(
	 		array('name' => '工作效率','field_name' => 'work_efficiency'),
	 		array('name' => '服务态度','field_name' => 'service_attitude'),
	 		array('name' => '成果质量','field_name' => 'outcome_quality'),
	 	);
	 	
	 	$this->jsonOutput2(RESP_SUCCESS,array('evaluateTypeList' => $evaluateTypeList));
	 }
	 
	 
	
	  
	  /**
	   * 设置评价
	   */
	public function setEvaluate(){
  		$yewuId = $this->postJson['yewu_id'];
  		$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId,'id');
  		$score = $this->postJson['score'];
  		$content = $this->postJson['content'];
  		if($yewuInfo){
  			$result = $this->Evaluate_Model->_add(array(
  				'yewu_id' => $yewuId,
  				'worker_id' => $yewuInfo['worker_id'],
  				'worker_name' => $yewuInfo['worker_name'],
  				'work_efficiency' => $score[0],
  				'service_attitude' => $score[1],
  				'outcome_quality' => $score[2],
  				'content' => $content,
				'add_uid' => $this->userInfo['uid'],
				'add_username' => $this->userInfo['name'],
				'gmt_create' => time(),
  			));
  			if($result){
  				$this->jsonOutput2(RESP_SUCCESS);
  			}else{
  				$this->jsonOutput2(RESP_ERROR);
  			}
  		}

	}
	
	
	public function editEvaluate(){
  		$yewuId = $this->postJson['yewu_id'];
  		$evaluateInfo = $this->Evaluate_Model->getFirstByKey($yewuId,'yewu_id');
  		$score = $this->postJson['score'];
  		$time = time() - $evaluateInfo['gmt_create'];
  		$day = intval($time / 86400);
  		$content = $this->postJson['content'];
  		if($evaluateInfo && $day < 30 && $evaluateInfo['gmt_create'] == $evaluateInfo['gmt_modify']){
  			$result = $this->Evaluate_Model->updateByCondition(
				array(
					'content' => $content,
					'work_efficiency' => $score[0],
					'service_attitude' => $score[1],
					'outcome_quality' => $score[2],
					'gmt_modify' => time(),
				),
				array('where' => array('id' => $evaluateInfo['id']))
			);
  			if($result){
  				$this->jsonOutput2(RESP_SUCCESS);
  			}else{
  				$this->jsonOutput2(RESP_ERROR);
  			}

  		}else{
  			$this->jsonOutput2(RESP_ERROR);
  		}

  		

	}
	
	
	 public function getEvaluateByYewuId(){
	 	$yewuId = $this->postJson['yewu_id'];
	 	$evaluateInfo = $this->Evaluate_Model->getFirstByKey($yewuId,'yewu_id');
	 	$evaluateInfo['score'] = array(
	 		array('name' => '工作效率','score' => $evaluateInfo['work_efficiency']),
	 		array('name' => '服务态度','score' => $evaluateInfo['service_attitude']),
	 		array('name' => '成果质量','score' => $evaluateInfo['outcome_quality']),
	 	);
	 	if($evaluateInfo){
			$this->jsonOutput2(RESP_SUCCESS,array('evaluateInfo' => $evaluateInfo));
		}else{
			$this->jsonOutput2('该业务用户暂未评价');
		}
	 }


	public function setCompany(){
		$result = $this->yewu_service->addCompany($this->postJson,$this->userInfo);
		if($result){
			$this->jsonOutput2(RESP_SUCCESS);
		}else{
			$this->jsonOutput2(RESP_ERROR);
		}
	  	
	}
	  
	  
	  /**
	   * 获得服务区域
	   */
	public function getarea(){
		$area = $this->basic_data_service->getTopChildList('服务区域');
		$area = array_values($area);
		if($area){
			$data = array(
			'data' =>$area ,
		);
			$this->jsonOutput2(RESP_SUCCESS,$data);
		}else{
			$this->jsonOutput2(RESP_ERROR);
		}

	}
	
	
	
	/**
	 * 获得业务过程
	 */
	public function getYewuDetail(){

		$id = $this->postJson['id'];
		$yewuDetailList = $this->Yewu_Detail_Model->getList(array('order' => 'operation','where' => array('yewu_id' => $id)));
		$OperationList = Operation::$typeName;
		foreach($yewuDetailList as $key => $item){
			$yewuDetailList[$key]['operation'] = $OperationList[$item['operation']];
			$yewuDetailList[$key]['name'] = mask_name($yewuDetailList[$key]['name']);
			$yewuDetailList[$key]['mobile'] = mask_mobile($yewuDetailList[$key]['mobile']);
			if('发起业务' == $yewuDetailList[$key]['operation']){
				$yewuDetailList[$key]['identity'] = '申请人';
			}elseif('结款' == $yewuDetailList[$key]['operation']){
				$yewuDetailList[$key]['identity'] = '付款人';
			}elseif('开票' == $yewuDetailList[$key]['operation']){
				$yewuDetailList[$key]['identity'] = '开票人';
			}elseif('审核' == $yewuDetailList[$key]['operation']){
				$yewuDetailList[$key]['identity'] = '审核操作人';
			}else{
				$yewuDetailList[$key]['identity'] = '作业人员';
			}
		}
		$this->jsonOutput2(RESP_SUCCESS,array('yewuDetailList' => $yewuDetailList));

	}
	
	
	
	/**
	 * 根据id获得业务
	 */
	public function getYewuById(){

		$id = $this->postJson['id'];
		$basicData = $this->basic_data_service->getBasicDataList();
		$yewuInfo = $this->Yewu_Model->getFirstByKey($id,'id');
		$yewuInfo['work_category'] = $basicData[$yewuInfo['work_category']]['show_name'];
		$yewuInfo['service_area'] = $basicData[$yewuInfo['service_area']]['show_name'];
		$yewuInfo['user_name'] = $yewuInfo['user_name'];
		$yewuInfo['user_mobile'] = mask_mobile($yewuInfo['user_mobile']);
		$yewuInfo['worker_name'] = $yewuInfo['worker_name'];
		$yewuInfo['worker_mobile'] = mask_mobile($yewuInfo['worker_mobile']);
		$yewuInfo['plan_money'] = $yewuInfo['plan_money'] / 100;
		$yewuInfo['receivable_money'] = $yewuInfo['receivable_money'] / 100;
		$yewuInfo['status_name'] = Operation::$typeName[$yewuInfo['status']];
		if($yewuInfo['is_payed']){
			$yewuInfo['is_payed'] = '已缴费';
		}else{
			$yewuInfo['is_payed'] = '未缴费';
		}
		if($yewuInfo['is_invoice']){
			$yewuInfo['is_invoice'] = '已开票';
		}else{
			$yewuInfo['is_invoice'] = '未开票';
		}
		
		$this->jsonOutput2(RESP_SUCCESS,array('yewuInfo' => $yewuInfo));

	}
	
	
	
	
	/**
	 * 进行下一阶段
	 */
	public function nextStage(){
		$yewuId = $this->postJson['yewu_id'];
		$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId);
		if($yewuInfo['status'] > Operation::$submit && $yewuInfo['status'] < Operation::$examine){
			$status = $yewuInfo['status'] + 1;
			$this->Yewu_Model->updateByCondition(
				array(
					'status' => $status,
					'worker_name' => $this->userInfo['name'],
					'worker_mobile' => $this->userInfo['mobile'],	
				),
				array('where' => array('id' => $yewuId))
			);
			if($this->yewu_service->addYewuDetail($this->userInfo,$status,$yewuId)){
				$this->jsonOutput2(RESP_SUCCESS);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}
		}
			
	}
	


	/**
	 * 业务受理
	 */
	public function yewuAcceptance(){

		$yewuID = $this->postJson['yewu_id'];
		$workCategory = $this->postJson['work_category'];
		$yewuName = $this-> postJson['yewu_name'];
		for($i = 0;$i < 1; $i++){
			//getBasicData
			$workCategoryList = $this->basic_data_service->getTopChildList('工作类别');
			
			$this->form_validation->set_data($this->postJson);

  			$this->form_validation->set_rules('yewu_name','业务名称','required');
  			$this->form_validation->set_rules('work_category','工作类别','required|in_list['.implode(',',array_keys($workCategoryList)).']');
  			
  			if(!$this->form_validation->run()){
				$this->jsonOutput2($this->form_validation->error_first_html());
				break;
			}
			if($yewuID){
				
				$result = $this->Yewu_Model->updateByCondition(
					array(
						'yewu_name' => $yewuName,
						'status' => Operation::$accept,
						'worker_name' => $this->userInfo['name'],
						'worker_mobile' => $this->userInfo['mobile'],
						'work_category' => $workCategoryList[$workCategory]['id'],	
					),
					array('where' => array('id' => $yewuID))
				);
				$this->yewu_service->addYewuDetail($this->userInfo,Operation::$accept,$yewuID);
				if($result){
					$this->jsonOutput2(RESP_SUCCESS);
				}
				
			}
		}
	}



	/**
	 * 设置公司发票信息
	 */
	public function setInvoice(){

		$result = $this->invoiceRule();
		extract($this->postJson,EXTR_OVERWRITE);
		if($invoice_company && $invoice_no && $result){
			$result = $this->Invoice_Model->_add(array(
				'user_id' => $this->userInfo['uid'],
				'invoice_company' => $invoice_company,
				'invoice_no' => $invoice_no,
				'address' => $address,
				'mobile' => $mobile,
				'deposit_bank' => $deposit_bank,
				'deposit_account' => $deposit_account,
				'type' => $type,
				'add_uid' => $this->userInfo['uid'],
				'add_username' => $this->userInfo['name'],
			));
			$this->jsonOutput2(RESP_SUCCESS);
		}

	}
	
	
	public function getInvoiceList(){
		$invoiceList = $this->Invoice_Model->getList(array('where' => array('user_id' => $this->userInfo['uid'])));
		$this->jsonOutput2(RESP_SUCCESS,array('invoiceList' => $invoiceList));

	}
	
	
	
	public function getInvoiceInfoById(){
			$id = $this->postJson['id'];
			$invoiceInfo = $this->Invoice_Model->getFirstByKey($id);
			$this->jsonOutput2(RESP_SUCCESS,array('invoiceInfo' => $invoiceInfo));

	}
	
	public function editInvoice(){			
		$resuit = $this->invoiceRule();	
		
		if($resuit){
			$id = $this->postJson['id'];
			extract($this->postJson,EXTR_OVERWRITE);
			$this->Invoice_Model->updateByCondition(
				array(
				'user_id' => $this->userInfo['uid'],
				'invoice_company' => $invoice_company,
				'invoice_no' => $invoice_no,
				'address' => $address,
				'mobile' => $mobile,
				'deposit_bank' => $deposit_bank,
				'deposit_account' => $deposit_account,
				'type' => $type,
				'edit_uid' => $this->userInfop['uid'],
				'edit_name' => $this->userInfo['name'],
				
				),
				array('where' => array('id' => $id))
			);
			$this->jsonOutput2(RESP_SUCCESS);		
			}
	}
	
	
	private function invoiceRule(){
		$type = $this->postJson['type'];
			for($i = 0;$i < 1; $i++){
				$this->form_validation->set_data($this->postJson);
	
				$this->form_validation->set_rules('type','类型','required|in_list_unstrict[1,2]',array(
					'in_list' => '发票类型错误.'
		        ));
				
				$this->form_validation->set_rules('invoice_company','公司名称','required');
				$this->form_validation->set_rules('invoice_no','税号','required|alpha_numeric');
				$invoiceNo = $this->postJson['invoice_no'];
				if(strlen($invoiceNo) != 15 && strlen($invoiceNo) != 17 && strlen($invoiceNo) != 18 &&strlen($invoiceNo) != 20){
					$this->jsonOutput2('纳税人识别号输入错误');
					break;
				}
				if(15 == strlen($invoiceNo)){
					$regioncode = substr ($invoiceNo,2);
				}
				if($type == 2){
					$this->form_validation->set_rules('address','地址','required');
					$this->form_validation->set_rules('mobile','电话','required');
					$this->form_validation->set_rules('deposit_bank','开户行','required');
					$this->form_validation->set_rules('deposit_account','开户账号','required|numeric');
				}
	  			if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_first_html());
					break;
				}
				return true;
			}
			return false;
		
	}
	
	
	/**
	 * 撤销申请
	 */
	public function  revokeYewu(){

		$yewuId = $this->postJson['yewu_id'];
		$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId);
		if($yewuInfo['status'] == Operation::$submit || $yewuInfo['status'] == Operation::$transfer){
			$this->Yewu_Model->updateByCondition(
				array(
					'status' => Operation::$revoke,
				),
				array('where' => array('id' => $yewuId))
			);
			$this->jsonOutput2(RESP_SUCCESS);
		}else{
			$this->jsonOutput2('只有在受理之前才能撤销申请');
		}
	}
	
	/**
	 * 开票
	 */
	 public function relationYewuInvoice(){
	 	$yewuId = $this->postJson['yewu_id'];
	 	$name = $this->postJson['invoice_name'];
	 	$invoiceId = $this->postJson['invoice_id'];
	 	$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId);
	 	$invoiceInfo = $this->Invoice_Model->getFirstByKey($invoiceId);
	 	$orderInfo = $this->Order_Model->getFirstByKey($yewuId,'yewu_id');
	 	for($i = 0;$i < 1;$i++){
	 		if($yewuInfo && $orderInfo['status'] == 2){
				$addData = array(
					'yewu_id' => $yewuId,
					'invoice_name' => $name,
					'amount_receive' => $yewuInfo['receivable_money'],
					'pay_time' => $orderInfo['pay_time_end'],
					'amount_payed' => $orderInfo['amount'],
					'order_id' => $yewuInfo['order_id'],
					'add_uid' => $this->userInfo['uid'],
					'add_username' => $this->userInfo['name'],
				);
			}else{
				break;
			}
			if($invoiceInfo){
				$addData['tyoe'] = $invoiceInfo['type'];
				$addData['invoice_id'] = $invoiceId;
				$addData['invoice_name'] = $invoiceInfo['invoice_company'];
			}
			$result = $this->Yewu_Invoice_Model->_add($addData);
	 	}
	 	if($result){
	 		$this->yewu_service->addYewuDetail($this->userInfo,Operation::$invoice,$yewuId);
	 		$this->Yewu_Model->updateByWhere(array('is_invoice' => Operation::$invoice),array('id' =>$yewuId));
	 		$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId);
	 		$title = $yewuInfo['yewu_name'].'开票';
	 		$this->admin_pm_service->addYewuMessage($yewuInfo,$yewuId,$title);
	 		$this->jsonOutput2('开票申请已接收');
	 	}else{
	 		$this->jsonOutput2('申请失败,请重新申请');
	 	}
	 }
	 

	 
	 /**
	  * 设置已缴费
	  */
	 public function setUpPayed(){
	 	$yewuId = $this->postJson['yewu_id'];
	 	$type = $this->userInfo['user_type'];
	 	$payed = $this->postJson['payed'];
	 	$invoiced = $this->postJson['invoiced'];
	 	if(3 == $type){
	 		if($payed){
	 			$result = $this->Yewu_Model->updateByCondition(
				array(
					'is_payed' => Operation::$payment,
				),
				array('where' => array('id' => $yewuId))
				);
				$this->yewu_service->addYewuDetail($this->userInfo,Operation::$payment,$yewuId);
	 		}
	 		if($invoiced){
	 			$result = $this->Yewu_Model->updateByCondition(
				array(
					'is_invoice' => Operation::$invoice,
				),
				array('where' => array('id' => $yewuId))
				);
				$this->yewu_service->addYewuDetail($this->userInfo,Operation::$invoice,$yewuId);
	 		}
			if($result){
				$this->jsonOutput2(RESP_SUCCESS);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}	
	 	}else{
	 		$this->jsonOutput2('只有工作组组长才能设置已缴费');
	 	}
	 }
	 
	 /**
	  * 审核
	  */
	 
	 public function examine(){
	 	$yewuId = $this->postJson['yewu_id'];
	 	$money = $this->postJson['money'];
		$this->form_validation->set_data($this->postJson);
		$this->form_validation->set_rules('money','缴费金额','required|numeric');
		if(!$this->form_validation->run()){
			$this->jsonOutput2($this->form_validation->error_first_html());
		}else{
	 		$result = $this->Yewu_Model->updateByCondition(
				array(
					'receivable_money' => $money * 100,
					'status' => Operation::$complete,
				),
				array('where' => array('id' => $yewuId))
			);
			$this->yewu_service->addYewuDetail($this->userInfo,Operation::$examine,$yewuId);
			if($result){
				$this->jsonOutput2(RESP_SUCCESS);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}
		}
	 }
	 
	 public function getInvoicedList(){
	 	$userId = $this->userInfo['uid'];
	 	$userIds[0] = $userId;
	 	$invoiceList = $this->Yewu_Invoice_Model->getList(array(
			'where' => array('add_uid' => $userId),
			'order' => 'gmt_create DESC',
		));
	 	
	 	foreach($invoiceList as $key => $item){
 			if($item['invoice_id']){
 				if($item['type'] == 1){
 					$invoicedList[$key]['type'] = '普通发票';
 				}else{
 					$invoicedList[$key]['type'] = '增值税发票';
 				}
 			}else{
				$invoicedList[$key]['type'] = '个人发票';
 			}
 			$invoicedList[$key]['invoice_name'] = $item['invoice_name'];
 			$invoicedList[$key]['amount'] = $item['amount_payed'];
 			$time = $item['pay_time'];
 			$time = substr($time,0,4).'年'.substr($time,4,2).'月'.substr($time,6,2).'日'.substr($time,8,2).':'.substr($time,10,2);
 			$invoicedList[$key]['pay_time'] = $time;
	 	}
	 	if($invoicedList){
	 		$this->jsonOutput2(RESP_SUCCESS,array('invoiceList' => $invoicedList));	
	 	}else{
	 		$this->jsonOutput2(RESP_SUCCESS,array('invoiceList' => ''));
	 	}
	 
	 }
	 
	 public function isInvoice(){
	 	$yewuId = $this->postJson['yewu_id'];
	 	$yewuInvoiceInfo = $this->Yewu_Invoice_Model->getFirstByKey($yewuId,'yewu_id');
	 	if($yewuInvoiceInfo){
	 		$this->jsonOutput2('发票只能申请一次');	
	 	}else{
	 		$this->jsonOutput2(RESP_SUCCESS);
	 	}
	 }
	 

}

