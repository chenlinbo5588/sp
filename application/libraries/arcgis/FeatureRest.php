<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FeatureRest extends Http_Client {
	
	private $_featureUrl = '';
	
	public function __construct(){
    	parent::__construct();
    }
	
    /**
     * 设置API URL
     */
    public function setUrl($baseUrl,$featureUrl){
    	$this->_baseURL = $baseUrl;
    	$this->setFeatureUrl($featureUrl);
    	
    }
    
    
    public function setFeatureUrl($url){
    	$this->_featureUrl = $url;
        
        if(substr($this->_featureUrl, -1, 1) != '/'){
        	$this->_featureUrl .= '/';
        }
    }
    
    
    
    ///http://192.168.5.100/arcgis/rest/services/zd2cad/GPServer/zd2cad/jobs/j97d3a8a066fc44f0a361beb8a75d194e?f=json
    public function getJobStatus($jobId){
    	
    	$default = array(
        	'f' => 'json',
        );
        
        $data = array_merge($default,$data);
        
    	$param = array(
            'url' => $this->_baseURL.$this->_featureUrl.'jobs/'.$jobId,
            'method' => 'post',
            'data' => $data
        );
       
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        if($result['jobStatus'] == 'esriJobSucceeded'){
        	return true;
        }else{
        	
        }
        
        //file_put_contents("job.txt",print_r($result,true),FILE_APPEND);
    	
    	
    }
    
    
    public function submitJob($data){
    	
    	$default = array(
        	'f' => 'json',
        );
        
        $data = array_merge($default,$data);
        
    	$param = array(
            'url' => $this->_baseURL.$this->_featureUrl.'submitJob',
            'method' => 'post',
            'data' => $data
        );
       
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        //file_put_contents("job.txt",print_r($result,true),FILE_APPEND);
    	return $result;
    	
    }
    
    
    public function query($data){
    	$default = array(
        	'f' => 'json',
        	'outFields' => '*',
			'returnGeometry' => 'true'
        );
        
        $data = array_merge($default,$data);
        
    	$param = array(
            'url' => $this->_baseURL.$this->_featureUrl.'query',
            'method' => 'get',
            'data' => $data
        );
        //file_put_contents("query.txt",print_r($param,true));
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        //file_put_contents("query.txt",print_r($result,true),FILE_APPEND);
    	return $result;
    	
    }
    
    
    
    /**
     * 
     */
    public function applyEdits($data){
        
    	$this->_featureUrl = str_replace('/MapServer','/FeatureServer',$this->_featureUrl);
    	
    	$param = array(
            'url' => $this->_baseURL.$this->_featureUrl.'applyEdits',
            'method' => 'post',
            'data' => array(
            	'f' => 'json',
            	'edits' => json_encode(array($data))
            
            )
        );
        
        //file_put_contents("query.txt",print_r($param['data'],true));
        //file_put_contents("query.txt",print_r($param,true),FILE_APPEND);
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        //file_put_contents("query.txt",print_r($result,true),FILE_APPEND);
        
    	return $result;
        
    }
    
    
    
    
    /**
     * 删除要素
     */
    public function deleteFeature($data){
    	
    	$json = array(
        	'f' => 'json',
        );
    	
    	$data = array_merge($json,$data);
    	
    	$this->_featureUrl = str_replace('/MapServer','/FeatureServer',$this->_featureUrl);
    	
    	$param = array(
            'url' => $this->_baseURL.$this->_featureUrl.'deleteFeatures',
            'method' => 'post',
            'data' => $data
        );
        
        //file_put_contents("delete.txt",print_r($data,true));
        //file_put_contents("delete.txt",print_r($param,true),FILE_APPEND);
        
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        
        //file_put_contents("delete.txt",print_r($respone,true),FILE_APPEND);
        //file_put_contents("delete.txt",print_r($result,true),FILE_APPEND);
    	return $result;
    }
    
    
    /**
     * 添加Feature
     */
    public function addFeatures($data){
    	
    	$json = array(
        	'f' => 'json',
        	'features' => json_encode($data)
        );
            
        //file_put_contents("json.txt",$json);
        $this->_featureUrl = str_replace('/MapServer','/FeatureServer',$this->_featureUrl);
        
        
    	$param = array(
            'url' => $this->_baseURL.$this->_featureUrl.'addFeatures',
            'method' => 'post',
            'data' => $json
        );
        
        
        //file_put_contents("addFeatures.txt",print_r($param,true));
        $respone = $this->request($param);
        
        //file_put_contents('addFeatures.txt',print_r($respone,true),FILE_APPEND);
        $result = json_decode($respone,true);
        
    	return $result;
    	
    }
    
    /**
     * 修改Feature
     */
    public function updateFeatures($data){
    	
    	//file_put_contents("updateFeatures1.txt",print_r($data,true));
    	
    	$json = array(
        	'f' => 'json',
        	'features' => json_encode($data)
        );
        
        
        $this->_featureUrl = str_replace('/MapServer','/FeatureServer',$this->_featureUrl);
            
    	$param = array(
            'url' => $this->_baseURL.$this->_featureUrl.'updateFeatures',
            'method' => 'post',
            'data' => $json
        );
        
        //file_put_contents("updateFeatures.txt",print_r($param,true));
        $respone = $this->request($param);
        
        //file_put_contents('updateFeatures.txt',print_r($respone,true),FILE_APPEND);
        $result = json_decode($respone,true);
        
    	return $result;
    	
    }
    
}
