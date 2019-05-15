<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shoes extends MY_Controller {
	
	//抖动屏蔽最长时间
	private $_keepDays = 3;
	
	//记录保留时间
	private $_rememberDays = 7;
	
	
	public function __construct(){
		parent::__construct();
		
		
		$this->load->model('Shoes_Model');
		$this->load->model('Shoes_Tip_Model');
		
	}
	
	public function cleanrow(){
		
		if(!$this->input->is_cli_request()){
    		die(0);
    	}
    	
    	$now = time();
    	
    	//删除15天前的数据
    	$this->Shoes_Model->deleteByCondition(array(
    		'where' => array(
    			'batch_id <= ' => $now - $this->_rememberDays * 86400
    		)
    	));
    	
    	$this->Shoes_Tip_Model->deleteByCondition(array(
    		'where' => array(
    			'batch_id <= ' => $now - $this->_keepDays * 86400
    		)
    	));
    	
	}
	
	
	
	
	public function index()
	{
		//print_r($this->_logTip(time(),array('807471-101','923514-005','asasas-111','123456-002')));
		//$diffInfo = $this->_checkDiff(1540616102);
		
		//print_r($diffInfo);
		/*
		$a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
		$a2=array("e"=>"red","f"=>"black","g"=>"purple");
		$a3=array("a"=>"red","b"=>"black","h"=>"yellow");
		
		$result=array_diff($a1,$a2,$a3);
		print_r($result);
		
		
		$a1=array("a",'b','c','d');
		$a2=array('b','d','e','c','f');
		
		$result=array_diff($a1,$a2);
		print_r($result);
		*/
		//$this->jsonOutput('aaa',array('d1' => 1));
		
	}
	
	
	private function _logTip($pBatchId,$pNewCode){
		
		if(empty($pNewCode)){
			return;
		}
		
		$needTipCode = array();
		
		
		
		// 重复的货号
		$duplicateCode = $this->Shoes_Tip_Model->getList(array(
			'select' => 'code',
			'where' => array(
				'batch_id >=' => $pBatchId - $this->_keepDays * 86400
			),
			'where_in' => array(
				array('key' => 'code','value' => $pNewCode)
			)
		));
		
		//echo "dup code <br/>";
		//print_r($duplicateCode);
		//echo '<br/>';

		if($duplicateCode){
			$duplicateValue = array();
			
			foreach($duplicateCode as $codeValue){
				$duplicateValue[] = $codeValue['code'];
			}
			
			$duplicateValue = array_unique($duplicateValue);
			
			foreach($pNewCode as $newCode){
				if(!in_array($newCode,$duplicateValue)){
					$needTipCode[] = $newCode;
				}
			}
		}else{
			$needTipCode = $pNewCode;
		}
		
		$insertData = array();
		foreach($pNewCode as $newCode){
			$insertData[] = array(
				'batch_id' => $pBatchId,
				'code' => $newCode
			);
		}
		
		//print_r($insertData);
		//echo "<br/>";
		$this->Shoes_Tip_Model->batchInsert($insertData);

		
		return $needTipCode;
	}
	
	/**
	 * 检测差异
	 */
	private function _checkDiff($batchId){
		
		$currentProductList = $this->Shoes_Model->getList(array(
			'select' => 'products,total_page',
			'where' => array(
				'batch_id' => $batchId,
			)
		));
		
		
		if(empty($currentProductList)){
			return array('newProducts' => array(),'bid' => $batchId);
		}
		
		if(count($currentProductList) != 0 && count($currentProductList) != $currentProductList[0]['total_page']){
			//数据异常
			return array('newProducts' => array(),'bid' => $batchId);
		}
		
		
		$lastBatchIdInfo = $this->Shoes_Model->getList(array(
			'select' => 'batch_id,total_page',
			'where' => array(
				'batch_id <' => $batchId,
				'batch_id >=' => $batchId - $this->_rememberDays * 86400
			),
			'order' => 'id DESC',
			'limit' => 1
		));
		
		$lastBatchId = 0;
		$lastTempList = array();
		
		if($lastBatchIdInfo[0]['batch_id']){
			
			$lastBatchId = $lastBatchIdInfo[0]['batch_id'];
			
			$lastTempList = $this->Shoes_Model->getList(array(
				'select' => 'batch_id,products',
				'where' => array(
					'batch_id' => $lastBatchId,
				)
			));
			
			if(count($lastTempList) != $lastBatchIdInfo[0]['total_page']){
				//数据有异常
				$lastTempList = array();
				
				//再往前找一个批次
				$last2BatchIdInfo = $this->Shoes_Model->getList(array(
					'select' => 'batch_id,total_page',
					'where' => array(
						'batch_id <' => $lastBatchId,
						'batch_id >=' => $batchId - $this->_rememberDays * 86400
					),
					'order' => 'id DESC',
					'limit' => 1
				));
				
				
				if($last2BatchIdInfo[0]['batch_id']){
					
					$lastTempList = $this->Shoes_Model->getList(array(
						'select' => 'batch_id,products',
						'where' => array(
							'batch_id' => $last2BatchIdInfo[0]['batch_id'],
						)
					));
					
					
					if(count($lastTempList) != $last2BatchIdInfo[0]['total_page']){
						$lastTempList = array();
					}else{
						
						//上次的 批次号
						$lastBatchId = $last2BatchIdInfo[0]['batch_id'];
					}
				}
				
			}
		}
		
		
		if(empty($lastTempList)){
			return array('newProducts' => array(),'bid' => $batchId,'obid' => $lastBatchId);
		}
		
		$currentProduct = array();
		foreach($currentProductList as $item){
			$currentProduct = array_merge($currentProduct,json_decode($item['products'],true));
		}
		
		$lastProduct = array();
		foreach($lastTempList as $item){
			$lastProduct = array_merge($lastProduct,json_decode($item['products'],true));
		}
	
		$oldProductStr = implode(',',$lastProduct);
		
		//$newProductList = array();
		$newIncome = array();
		
		
		//print_r($currentProduct);
		//echo $oldProductStr;
		
		foreach($currentProduct as $code){
			if(strpos($oldProductStr,$code) === false){
				$newIncome[] = $code;
			}
		}
		
		$newCode = $this->_logTip($batchId, $newIncome);
		
	//file_put_contents('products'.$batchId.'.txt',print_r($newIncome,true));
		return array('newProducts' => $newCode,'bid' => $batchId,'obid' => $lastBatchId);
	}
	
	
	public function addProduct(){
		
		if($this->isPostRequest()){
			
			//file_put_contents('debug.txt',print_r($_POST,true));
			
			$diffInfo = array('newProducts' => array(),'bid' => $this->input->post('batch_id'));
			
			for($i = 0; $i < 1; $i++){
				$products = $this->input->post('products');
				
				/*
				$productList = array();
				
				foreach($products as $productItem){
					$productList[$productItem['code']] = array(
						'av' => $productItem['av'],
						'id' => $productItem['id']
					);
				}
				*/
				
				$newId = $this->Shoes_Model->_add(array(
					'batch_id' => $this->input->post('batch_id'),
					'total_rows' => $this->input->post('total_rows'),
					'current_page' => $this->input->post('current_page'),
					'page_size' => $this->input->post('page_size'),
					'data_cnt' => count($products),
					'total_page' => $this->input->post('total_page'),
					'products' => json_encode($products)
				));
				
				$recordCnt = $this->Shoes_Model->getCount(array(
					'where' => array(
						'batch_id' => $this->input->post('batch_id'),
					)
				));
				
				if($recordCnt != $this->input->post('total_page')){
					break;
				}
				
				$diffInfo = $this->_checkDiff($this->input->post('batch_id'));
			}
			
			$this->jsonOutput('入库成功',$diffInfo);
		}else{
			
			$this->jsonOutput('参数非法');
		}
	}
	

}
