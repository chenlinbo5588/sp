<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push_Chat extends MY_Controller {


	public function __construct(){
		parent::__construct();
		
		if(!is_cli()){
			exit();
		}
	}
	
	public function user(){
		$param = $this->input->server('argv');
		//print_r($param);
		
		$currentDir = pathinfo(__FILE__, PATHINFO_DIRNAME);
		
		file_put_contents($currentDir.'/log.txt',print_r($_SERVER,true));
		
		if(empty($param[3])){
			exit('table param is not given');
		}
		
		$reqTime = $this->input->server('REQUEST_TIME');
		
		$tableId = $param[3];
		$this->load->model(array('Push_Chat_Model'));
		$this->Push_Chat_Model->setTableId($tableId);
		
		//清空 3 天的记录
		$this->Push_Chat_Model->deleteByCondition(array(
			'where' => array(
				'gmt_create <=' => $reqTime - 259200
			)
		));
		
		$pushList = $this->Push_Chat_Model->getList(array(
			'is_send' => 0,
			'limit' => 3
		));
		
		if($pushList){
			// 这里可批量发送消息,消息系统设计方面 看来可以做优化，另外这里发送消息 在看用户是否在线，如果不在线可考虑不发送消息
			$pushObj = $this->base_service->getPushObject();
			
			$updateId = array();
			
			foreach($pushList as $item){
				$json = $pushObj->sendText(array($item['username']),
					$item['content'],
					'清风信息系统消息'
				);
				
				if($json['data'][$item['username']] == 'success'){
					$updateId[] = $item['id'];
				}
			}
			
			if($updateId){
				$this->Push_Chat_Model->updateByCondition(array('is_send' => 1),array(
					'where_in' => array(
						array('key' => 'id','value' => $updateId)
					)
				));
			}
		}
		
	}
	
	
	
	public function index()
	{
		echo '自动推送聊天窗口';
	}
}
