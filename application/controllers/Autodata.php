<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 日 周 月 年 数据生成
 */
class Autodata extends Ydzj_Controller {
	
    public function __construct(){
        parent::__construct();
    	$this->load->library('Report_service');
    }
    
    
    private function _checkArg(){
    	
    	print_r($_SERVER['argv']);

    	
    	if(!$this->input->is_cli_request()){
    		die(0);
    	}
    	
    	if(empty($_SERVER['argv'][3])){
    		echo "resident id illegal";
    		die(0);
    	}
    	
    	if(empty($_SERVER['argv'][4]) || preg_match('/^\d\{8\}/i',$_SERVER['argv'][4],$matchCnt)){
    		echo "date illegel";
    		die(0);
    	}
    	
    	echo 'check success';
    	
    	$date = $_SERVER['argv'][4];
    	$time = substr($date,0,4).'-'.substr($date,4,2).'-'.substr($date,6,2);
    	
		if(!strtotime($time)){
			die(0);
		}
    	

    }
    
    
    private function _getDayTimestamp($pDate){
    	$temp = substr($pDate,0,4).'-'.substr($pDate,4,2).'-'.substr($pDate,6,2);
    	
    	return $temp;
    }
    
    
    /**
     * 日数据刷新
     */
    public function day(){
    	
    	$this->_checkArg();
    	
    	$time = $this->_getDayTimestamp($_SERVER['argv'][4]);
    	
    	$this->report_service->sendReportDay($_SERVER['argv'][3],$time);  	
    	//逻辑
    }
    
    /**
     * 周数据刷新
     */
    public function week(){
    	
    	$this->_checkArg();
    	
    	$time = $this->_getDayTimestamp($_SERVER['argv'][4]);
		
		$this->report_service->sendReportWeek($_SERVER['argv'][3],$time);  
    	//逻辑	
    }
        /**
     * 月数据刷新
     */
    public function month(){
    	$this->_checkArg();
    	
    	$time = $this->_getDayTimestamp($_SERVER['argv'][4]);
		
		$this->report_service->sendReportMonth($_SERVER['argv'][3],$time);  
    	//逻辑	
    }
        /**
     * 年数据刷新
     */
    public function year(){
    	$this->_checkArg();
    	
    	$time = $this->_getDayTimestamp($_SERVER['argv'][4]);
		
		$this->report_service->sendReportYear($_SERVER['argv'][3],$time);  
    	//逻辑	
    }
    
    
    
}
