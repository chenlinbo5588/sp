<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Http_Client {
    
    public $_baseURL = 'https://api.weixin.qq.com';
    //public $_CI = null;
    
    public function __construct(){
        //$this->_CI = & get_instance();
    }
    
    public function request($param, $return = true){
        
        $curl = curl_init();
        
        /*
        $useragent = array(
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1',
            'Opera/9.27 (Windows NT 5.2; U; zh-cn)',
            'Opera/8.0 (Macintosh; PPC Mac OS X; U; en)',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13 ',
            'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13'
        );
        */
        
        /*
		 * 强制转换为boolean类型，这里不使用(boolean)与settype
		 */
        if (false === isset($param['header'])) {
			$param['header'] = false;
		} else {
			$param['header'] = true;
		}
		@curl_setopt($curl, CURLOPT_HEADER, $param['header']);
        //curl_setopt($curl, CURLOPT_USERAGENT, array_rand($useragent));
        /*
		 * 处理302
		 */
		if (false === isset($param['location'])) {
			$param['location'] = false;
		} else {
			$param['location'] = true;
		}
		@curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $param['location']);

		unset($param['location']);
        
        /*
		 * exec执行结果是否保存到变量中
		 */
		if (true === $return) {
			@curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		}

        if(strpos($param['url'],'http') === false){
            $param['url'] = $this->_baseURL . $param['url'];
        }
        
        if (false !== strstr($param['url'], 'https://', true)) {
            curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
            
            if($param['ssl_verifypeer']){
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            }else{
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
            }

            if($param['ssl_verifyhost']){
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
            }else{
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
		}
        
        $extraParam = array();
        
        if(strtolower($param['method']) == 'post'){
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param['data']);
            
        }else{
            if($param['data']){
                foreach($param['data'] as $key => $value){
                    $extraParam[] = $key.'='.urlencode($value);
                }
            }
            
        }
        
        if($extraParam){
            if(substr($param['url'],-1,1) == '&'){
                curl_setopt($curl, CURLOPT_URL, $param['url'] .implode('&',$extraParam));
            }else{
                if(strpos($param['url'] , '?') === false){
                    curl_setopt($curl, CURLOPT_URL, $param['url'] .'?'.implode('&',$extraParam));
                }else{
                    curl_setopt($curl, CURLOPT_URL, $param['url'] .'&'.implode('&',$extraParam));
                }
            }
        }else{
            curl_setopt($curl, CURLOPT_URL, $param['url']);
        }
        
		curl_setopt($curl, CURLOPT_TIMEOUT, empty($param['timeout']) ? 10 :  $param['timeout']);
        $result = curl_exec($curl);
        curl_close($curl);
        
        return $result;
    }
    
}
