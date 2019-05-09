
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yewu extends Wx_Tdkc_Controller {
	
	public function __construct(){
		parent::__construct();
    	
    	$this->load->library('Yewu_service','Admin_pm_service','Basic_data_service');

	}



	public function setYewu(){
		if($this->userInfo){
			

			extract($this->postJson,EXTR_OVERWRITE);

			
			//getBasicData
			$serviceAreaList = $this->basic_data_service->getTopChildList('服务区域');
			
			/*$this->form_validation->set_data($this->postJson);

  			$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
  			$this->form_validation->set_rules('real_name','办证使用名字','required');
  			$this->form_validation->set_rules('yewu_describe','业务描述','max_length[255]');
  			$this->form_validation->set_rules('service_area','服务区域','required|in_list['.implode(',',array_keys($serviceAreaList)).']');
  			
  			if(!$this->form_validation->run()){
				$this->jsonOutput2($this->form_validation->error_first_html());
				break;
			}*/
			
			$groupInfo = $this->yewu_service->getGroupInfo($service_area);
			//$companyInfo = $this->Company_Model->getFirstByKey($companyName,'name');
			 
			$yewuInfo = array(
  				'mobile' => $mobile,
  				'work_category' => $work_category,
  				'real_name' => $real_name,
  				'yewu_describe' => $yewu_describe,
  				'service_area' => $serviceAreaList[$service_area]['id'],
  				'user_id' => $this->userInfo['id'],
  				'user_name' => $this->userInfo['name'],
  				'user_mobile' => $this->userInfo['mobile'],
				'add_uid'	=>  $this->userInfo['id'],
				'add_username'	=>  $this->userInfo['name'],
				'status' => Operation::$submit,
				'group_id' => $groupInfo['id'],
				'company_name' => $company_name,
				//'company_id' => $companyInfo['id'],
			);


			$newYewuId = $this->Yewu_Model->_add($yewuInfo);
			if($newYewuId){
				$this->yewu_service->addYewuDetail($this->userInfo,Operation::$submit,$newYewuId);
				$this->admin_pm_service->addYewuMessage($yewuInfo,$newYewuId);
				$this->jsonOutput2(RESP_SUCCESS);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}
			
		}
	}

	
	
	
	public function getYewuList(){

		if($this->userInfo){
			$id = $this->userInfo['id'];
			$status = $this->postJson['status'];			
			$data = $this->yewu_service->getYewuList($id,$status);
			
			$yewuList = array(
				'data' =>$data ,
			);
			if($yewuList){
				$this->jsonOutput2(RESP_SUCCESS,$yewuList);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}
		}
	}
	
	
	
	public function getUserType(){
		
		$type = array(
			'type' => $this->userInfo['user_type'],
		);
		$this->jsonOutput2(RESP_SUCCESS,$type);
	}
	

	
	
	public function accessAll(){
		$id = $this->postJson['id'];
		
		$data =$this->Yewu_Model->getFirstByKey($id,'id');

		if($data){
			$mobileName =array(
				'name' =>$data['real_name'],
				'mobile' => $data['mobile'],
				'user_name' => $data['user_name'],
				'user_mobile' => $data['user_mobile'],
			);
			$this->jsonOutput2(RESP_SUCCESS,$mobileName);
		}else{
			$this->jsonOutput2(RESP_ERROR);
		}
	}
	
	
	

	/**
	 * 获得业务类型
	 */
	public function getYewuWorkCategory(){
		if($this->userInfo){
			$workCategory = $this->basic_data_service->getTopChildList('工作类别');
			$workCategory = array_values($workCategory);
			 if(is_array($workCategory)){
			 	$this->jsonOutput2(RESP_SUCCESS,array('workCategory' => $workCategory));
			 }else{
			 	$this->jsonOutput2(RESP_ERROR);
			 }
			
		}
	}
	
	
	
	
	public function getAllGroupList(){
		if($this->userInfo){
			$this->jsonOutput2(RESP_SUCCESS,array('groupList' => $this->Work_Group_Model->getList()));
		}
	}
	



	/**
	 * 业务转让申请
	 */
	public function transferApply(){
		if($this->userInfo && 3 == $this->userInfo['user_type']){
			$groupId = $this->postJson['group_id'];
			$groupInfo = $this->Work_Group_Model->getFirstByKey($groupId,'id');
			$yewuId = $this->postJson['yewu_id'];
			$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId,'id');
			if($yewuInfo['status'] == Operation::$submit);{
				if($groupId && $yewuId){		
					$this->Yewu_Model->beginTrans();
				 	$this->Yewu_Model->updateByCondition(
						array(
							'worker_id' => $groupInfo['group_leaderid'],
							'worker_name' => $groupInfo['group_leader_name'],
							'worker_mobile' => $groupInfo['group_leader_mobile'],
							'current_group' => $groupInfo['id'],
							'status' => 10,
							'edit_uid' => $this->userInfo['id'],
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
						'add_uid' => $this->userInfo['id'],
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
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	
	
	public function transferHandle(){
		if($this->userInfo && 3 == $this->userInfo['user_type']){
			$yewuInfo = $this->Yewu_Model->getFirstByKey($this->postJson['yewu_id'],'id');
			$groupInfo = $this->Work_Group_Model->getFirstByKey($this->postJson['group_id'],'id');		
			$thansferInfo = $this->Yewu_Transfer_Model->getList(array('where' => array(
				'yewu_id' => $this->postJson['yewu_id'],
				'status' => 1,
				'group_id_to' => $this->postJson['group_id'],
			)));
			$fromGroupInfo = $this->Yewu_Model->getFirstByKey($thansferInfo['group_id_from'],'id');
			if($thansferInfo && $yewuInfo && $yewuInfo){
				if($this->yewu_service->changeTansfer($this->postJson,$this->userInfo,$fromGroupInfo)){
					$this->jsonOutput2(RESP_SUCCESS);
				}
			}
		}
	}
	
	
	

	public function getopenid(){
		
	 	$code = $_GET["code"];

		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxba86a9496e907b03&secret=9f65076ccd3368ec24fd6b729f9a28e1&code=".$code."&grant_type=authorization_code";
		$openArr=json_decode($this->gettoken($url),true);

		$this->display("yewu/yewu");
	}
	
	
	
	public function gettoken($url){
	  
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
	    curl_setopt($ch, CURLOPT_ENCODING ,'gzip'); //加入gzip解析
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    $output = curl_exec($ch);
		
	
	    curl_close($ch);
	    return $output;
	  }
	  
	  
	  
	  public function setYewuMoney(){
	  	if($this->userInfo){
	  		$yewuId = $this->postJson['yewu_id'];
	  		$money = $this->postJson['money'];
	  		if($yewuId && $money){
	  			$result = $this->yewu_service->setYewuMoney($yewuId,$money,$this->userInfo);
	  			if($result){
	  				$this->jsonOutput2(RESP_SUCCESS);
	  			}else{
	  				$this->jsonOutput2(RESP_ERROR);
	  			}
	  		}
	  	}
	  }
	  
	  
	  
	  /**
	   * 评价
	   */
	  public function setEvaluate(){
	  	if($this->userInfo){
	  		$yewuId = $this->postJson['yewu_id'];
	  		$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId,'id');
	  		$score = $this->postJson['score'];
	  		$content = $this->postJson['content'];
	  		if($yewuInfo && $score){
	  			$result = $this->Evaluate_Model->_add(array(
	  				'yewu_id' => $yewuId,
	  				'worker_id' => $yewuInfo['worker_id'],
	  				'worker_name' => $yewuInfo['worker_name'],
	  				'score' => $score,
	  				'content' => $content,
					'add_uid' => $this->userInfo['id'],
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
	  }
	  
	  
	  
	  public function setCompany(){
	  	if($this->userInfo){
			$result = $this->yewu_service->addCompany($this->postJson,$this->userInfo);
  			if($result){
  				$this->jsonOutput2(RESP_SUCCESS);
  			}else{
  				$this->jsonOutput2(RESP_ERROR);
  			}
	  	}
	  }
	  
	  
	  /**
	   * 获得服务区域
	   */
	  public function getarea(){
	  	if($this->userInfo){
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
	  	}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}

	}
	
	
	
	/**
	 * 获得业务过程
	 */
	public function getYewuDetail(){
	  	if($this->userInfo){
			$id = $this->postJson['id'];
			$yewuDetailList = $this->Yewu_Detail_Model->getList(array('order' => 'operation','where' => array('yewu_id' => $id)));
			$OperationList = Operation::$typeName;
			foreach($yewuDetailList as $key => $item){
				$yewuDetailList[$key]['operation'] = $OperationList[$item['operation']];
				$yewuDetailList[$key]['name'] = mask_name($item['name']);
				$yewuDetailList[$key]['mobile'] = mask_mobile($item['mobile']);
				if('发起业务' == $yewuDetailList[$key]['operation']){
					$yewuDetailList[$key]['identity'] = '申请人';
				}else{
					$yewuDetailList[$key]['identity'] = '作业人员';
				}
			}
			$this->jsonOutput2(RESP_SUCCESS,array('yewuDetailList' => $yewuDetailList));
	  	}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	
	
	/**
	 * 根据id获得业务
	 */
	public function getYewuById(){
	  	if($this->userInfo){
			$id = $this->postJson['id'];
			$basicData = $this->basic_data_service->getBasicDataList();
			$yewuInfo = $this->Yewu_Model->getFirstByKey($id,'id');
			$yewuInfo['work_category'] = $basicData[$yewuInfo['work_category']]['show_name'];
			$this->jsonOutput2(RESP_SUCCESS,array('yewuInfo' => $yewuInfo));
	  	}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	
	
	
	/**
	 * 进行下一阶段
	 */
	public function nextStage(){
	  	if($this->userInfo){
			$yewuId = $this->postJson['yewu_id'];
			$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId);
			if($yewuInfo['status'] > Operation::$submit){
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
			
	  	}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	/**
	 * 业务受理
	 */
	public function yewuAcceptance(){
	  	if($this->userInfo){
			$yewuID = $this->postJson['yewu_id'];
			$workCategory = $this->postJson['work_category'];
			
			if($yewuID){
				$result = $this->Yewu_Model->updateByCondition(
					array(
						'status' => Operation::$accept,
						'worker_name' => $this->userInfo['name'],
						'worker_mobile' => $this->userInfo['mobile'],
						'work_category' => $workCategory,	
					),
					array('where' => array('id' => $yewuID))
				);
				$this->yewu_service->addYewuDetail($this->userInfo,Operation::$accept,$yewuID);
				if($result){
					$this->jsonOutput2(RESP_SUCCESS);
				}
				
			}
	  	}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	/**
	 * 设置公司发票信息
	 */
	public function setInvoice(){
		if($this->userInfo){
			extract($this->postJson,EXTR_OVERWRITE);
			if($invoice_company && $invoice_no){
				$this->Invoice_Model->_add(array(
					'invoice_company' => $invoice_company,
					'invoice_no' => $invoice_no,
					'address' => $address,
					'mobile' => $mobile,
					'deposit_bank' => $deposit_bank,
					'deposit_account' => $deposit_account,
				));
			}else{
				$this->jsonOutput2('请填写公司名称和税号');
			}
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}

	}
}

