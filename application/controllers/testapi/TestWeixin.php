<?php
/*
 * Created on 2019-5-22
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class TestWeixin extends Ydzj_Controller {
	
    public function __construct(){
        parent::__construct();
        
    	$this->load->library('Weixin_mp_api');
    }
    
    
	
    public function testApi(){
    	
    	$mpConfig = config_item('mp_xcxTdkc');
    	
        $this->weixin_mp_api->initSetting($mpConfig);
        
        
        //https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=ACCESS_TOKEN
        
        
        $param = array(
	        'url' => '/cgi-bin/wxaapp/createwxaqrcode?access_token='.$this->weixin_mp_api->fetchToken(),
	        'method' => 'POST'
	    );
	    
	    $param['data'] = json_encode(array(
	    	'path' => '/test',
	    ));
	    


		print_r($param);

		$respone = $this->weixin_mp_api->request($param);
		
	    //echo $respone;

		file_put_contents("qrCode.jpg",$respone);


    
    }

}