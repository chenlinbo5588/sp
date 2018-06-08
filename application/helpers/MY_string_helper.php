<?php

defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('mask_mobile'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function mask_mobile($mobile, $mask = '*')
	{
		$str = $mobile;
		if(preg_match("/^(\+?86)?1[0-9][0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$mobile)){
			$str = substr($mobile,0,-8). $mask.$mask.$mask.$mask.substr($mobile,-4);
		}
		
		return $str;
	}
}


/**
 * 截取字符串
 *
 * @param string $str
 * @param int $len
 * @param int $mode
 *
 * @return string
 */
function cut($str, $len, $mode = true) {
	$tmp = '';
	
	$sLen = mb_strlen($str, 'UTF-8');
	if (($sLen <= $len) || (true === empty($len))) {
		
		return $str;
	} else {
		$tmp = mb_substr($str, 0, $len, 'UTF-8');
		
	}
	return ($mode) ? $tmp . '...' : $tmp;
}


/**
 * 获得属相
 */
function getShuXiang($birthday){
	$year = substr($birthday,0,4); 
	
	$data = array('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪');
	$index = ($year-1900)%12;
 
    return $data[$index];
    
    /*
	$bornTagarray = array('狗', '猪', '鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊','猴', '鸡'); 
	$index = $year%12; 
	$bornTag = $bornTagarray[$index]; 
	
	return $bornTag; */
}		