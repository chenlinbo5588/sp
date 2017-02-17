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
    	$this->_featureUrl = $featureUrl;
    }
    
    
    public function setFeatureUrl($url){
    	$this->_featureUrl = $url;
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
     * 添加Feature
     */
    public function addFeatures($data){
    	
    	$json = array(
        	'f' => 'json',
        	'features' => json_encode($data)
        );
            
        //file_put_contents("json.txt",$json);
            
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
            
        //file_put_contents("json.txt",print_r($json,true));
            
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
