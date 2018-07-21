<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test1 extends Wx_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->_inApp){
			//$this->output->set_status_header(400,'Page Not Found');
		}
	}
	
	
	/**
	 * 
	 */
	public function rename_img(){
		
		
		$sql = "SELECT refno, photos FROM sp_house_copy ";
		
		
		$query = $this->db->query($sql);
		
		echo '<table>';
		
		$arr = $query->result_array();
		foreach($arr as $houseItem){
			echo '<tr><td>'.$houseItem['refno'].'</td><td>';
			$imgList = json_decode($houseItem['photos'],true);
			
			$images = array();
			foreach($imgList as $index => $imgItem){
				$images[] = str_replace('static/attach/2017/08/03/'.substr($houseItem['refno'],1),'',$imgItem['image_url_b']);
				
			}
			
			echo implode(',',$images).'</td></tr>';
		}
		echo '</table>';
		
		/*
		$dirName = "static/attach/2017/08/03";
		
		foreach (glob("SOURCE/{$dirName}/*.JPG") as $filename) {
		    //echo "$filename size " . filesize($filename) . "<br/>";
		    $rename = preg_replace('/(\w+_\d+_)/is','',$filename);
		    echo "mv {$filename} {$rename} <br/>";
		}
		*/

	}
	
	
	
	
	public function index(){
		$code = $this->_verify();
		
		$nowstamp = microtime();
		
		echo $nowstamp;
		echo '<br/>';
		echo time();
		
		print_r(explode($nowstamp));
		
		
		//file_put_contents("code.txt",$code);
		$this->load->view('test',array('code' => $code));
	}
	
	
	
	
	/**
	 * 签名
	 */
	public function testSign(){
		
		$this->load->library('Order_service');
		
		$data = array(
			'timeStamp' => '1531808461',
			'nonceStr' => 'sans7qg1xh',
			'package' => 'prepay_id=wx17140846824396ff61eaec350109035401',
			'signType' => 'MD5',
			'appId' => 'wxb156d72d0f257ba2'
		);
		
		$signObj = new WxPayResults();
		
		
		//echo $this->order_service->makeSignature($data,WxPayConfig::KEY);
		//echo '<br/>';
		
		
		$signObj->FromArray($data);
		
		//echo $respXML;
		
		//$signObj->FromXml($respXML);
		
		echo $signObj->MakeSign();
		echo '<br/>';
		
		
	}
	
	
}
