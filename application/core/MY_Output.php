<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Output extends CI_Output {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	//����һ��������ʱ�䡣����ֵ������ҳ�汾�������ߵ�������ҳ��ĸ���Ƶ�ʣ��˴�Ϊ3600��(1Сʱ)��
	public function http_cache($seconds = 3600,$zone = 'public'){
		
		$curentTs = $_SERVER['REQUEST_TIME'];
		
		header("Pragma: {$zone}");
		
		//����Last-Modifiedͷ�꣬�����ĵ������ĸ������ڡ� 
		header ("Last-Modified: " .gmdate("D, d M Y H:i:s", $curentTs )." GMT"); 
		 
		//����Expiresͷ�꣬���õ�ǰ������ĵ�����ʱ�䣬GMT��ʽ�� 
		header ("Expires: " .gmdate("D, d M Y H:i:s", ($curentTs+$seconds) )." GMT"); 
		 
		//����Cache_Controlͷ�꣬����xx���Ժ��ĵ���ʱ,���Դ���Expires�����ͬʱ���֣�max-age���ȡ� 
		header ("Cache-Control: max-age={$seconds}"); 

			
	}
}
