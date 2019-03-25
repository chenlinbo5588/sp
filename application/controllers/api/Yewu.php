
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yewu extends Wx_Tdkc_Controller {
	
	public function __construct(){
		parent::__construct();
    	
    	$this->load->library('Yewu_service','Admin_pm_service');

	}

	public function setYewu(){
		if($this->userInfo){
			
			$workCategoryList = WorkCategory::$typeName;
			$address = $this->postJson['address'];
			$mobile = $this->postJson['mobile'];
			$workCategory = $this->postJson['work_category']; 
			$realName = $this->postJson['real_name']; 
			$yewuDescribe = $this->postJson['yewu_describe']; 
			$serviceArea = $this->postJson['service_area'];
			$companyName = $this->postJson['company_name'];
			//getBasicData
			$serviceAreaList = $this->basic_data_service->getTopChildList('服务区域');
		
			/*$this->form_validation->set_data($this->postJson);
  			$this->form_validation->set_rules('workCategory','工作类别','required|in_list['.implode(',',array_keys($workCategoryList)).']');
  			$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
  			$this->form_validation->set_rules('real_name','办证使用名字','required');
  			$this->form_validation->set_rules('yewu_describe','业务描述','max_length[255]');
  			$this->form_validation->set_rules('service_area','服务区域','required|in_list['.implode(',',array_keys($serviceAreaList)).']');
  			
  			if(!$this->form_validation->run()){
				$this->jsonOutput2($this->form_validation->error_first_html());
				break;
			}*/
			
			$groupInfo = $this->yewu_service->getGroupInfo($serviceArea);
			//$companyInfo = $this->Company_Model->getFirstByKey($companyName,'name');
			 
			$yewuInfo = array(
  				'mobile' => $mobile,
  				'work_category' => $workCategory,
  				'real_name' => $realName,
  				'yewu_describe' => $yewuDescribe,
  				'service_area' => $serviceAreaList[$serviceArea]['id'],
  				'user_id' => $this->userInfo['uid'],
				'add_uid'	=>  $this->userInfo['uid'],
				'add_username'	=>  $this->userInfo['name'],
				'group_id' => $groupInfo['id'],
				//'company_name' => $companyName,
				//'company_id' = $companyInfo['id'],
			);


			$newYewuId = $this->Yewu_Model->_add($yewuInfo);
			if($newYewuId){
				$this->admin_pm_service->addYewuMessage($yewuInfo,$newYewuId);
				$this->jsonOutput2(RESP_SUCCESS);
			}
			
		}
	}
	public function getyewu(){
		$this->jsonOutput2(RESP_SUCCESS);
	}
	
	
	public function getYewuList(){
		$id = $this->userInfo['uid'];
		if($this->userInfo){
			$data = $this->Yewu_Model->getList(array(
								'where' => array(
									'user_id' => $id 
								)
							));
			foreach($data  as $key => $item){
				$data[$key]['time'] =date("Y-m-d H:i",$data[$key]['gmt_create']) ;
				$data[$key]['mobile'] =mask_mobile($data[$key]['mobile']);
				$data[$key]['real_name'] =mask_name($data[$key]['real_name']);
			}
			$yewuList = array(
				'data' =>$data ,
			);
			if($yewuList){
				$this->jsonOutput2(RESP_SUCCESS,$yewuList);
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
		echo urlencode("www.nbxinn.com");
		$data =$this->Yewu_Model->getFirstByKey($id,'id');

		if($data){
			$mobileName =array(
				'name' =>$data['real_name'],
				'mobile' => $data['mobile'],
			);
			$this->jsonOutput2(RESP_SUCCESS,$mobileName);
		}
	}

	/**
	 * 获得业务类型
	 */
	public function getYewuWorkCategory(){
		if($this->userInfo){
			$workCategory = $this->basic_data_service->getTopChildList('工作类别');
			 if(is_array($workCategory)){
			 	$this->jsonOutput2(RESP_SUCCESS,$workCategory);
			 }
			
		}
	}
	public function getAllGroupList(){
		if($this->userInfo){
			$this->jsonOutput2(RESP_SUCCESS,$this->Work_Group_Model->getList());
		}
	}
	

	/**
	 * 业务转让申请
	 */
	public function transferApply(){
		if($this->userInfo && 3 == $this->userInfo['user_type']){
			$groupId = $this->postJson['group_id'];
			$groupInfo = $this->Work_Group_Model->getFirstByKey($groupId,'id');
			$yewuId = $mobile = $this->postJson['id'];
			$yewuInfo = $this->Yewu_Model->getFirstByKey($yewuId,'id');
			if($groupId && $yewuId){		
				$this->Yewu_Model->beginTrans();
			 	$this->Yewu_Model->updateByCondition(
					array(
						'worker_id' => $groupInfo['group_leaderid'],
						'worker_name' => $groupInfo['group_leader_name'],
						'worker_mobile' => $groupInfo['group_leader_mobile'],
						'current_group' => $groupInfo['id'],
						'status' => 10,
						'edit_uid' => $this->userInfo['uid'],
						'edit_username' => $this->userInfo['name'],
						'gmt_modify' => time(),
						
					),
					array('where' => array('id' => $yewuId))
				);
				$this->Yewu_Transfer_Model->add(array(
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

}

