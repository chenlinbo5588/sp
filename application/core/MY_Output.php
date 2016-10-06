<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Output extends CI_Output {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	//定义一个合理缓存时间。合理值屈居于页面本身、访问者的数量和页面的更新频率，此处为3600秒(1小时)。
	public function http_cache($seconds = 3600,$zone = 'public'){
		
		$curentTs = $_SERVER['REQUEST_TIME'];
		
		header("Pragma: {$zone}");
		
		//发送Last-Modified头标，设置文档的最后的更新日期。 
		header ("Last-Modified: " .gmdate("D, d M Y H:i:s", $curentTs )." GMT"); 
		 
		//发送Expires头标，设置当前缓存的文档过期时间，GMT格式。 
		header ("Expires: " .gmdate("D, d M Y H:i:s", ($curentTs+$seconds) )." GMT"); 
		 
		//发送Cache_Control头标，设置xx秒以后文档过时,可以代替Expires，如果同时出现，max-age优先。 
		header ("Cache-Control: max-age={$seconds}"); 

			
	}
}
