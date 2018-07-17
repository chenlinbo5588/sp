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



function mask_string($no,$start = 6, $end = 4, $mask = '*'){
	
	
	if(strlen($no) >= ($start + $end)){
		$startStr = substr($no,0,$start);
		$endStr = substr($no,-$end,$end);
		
		return $startStr.str_repeat($mask,strlen($no) - ($start + $end)).$endStr;
	}else{
		return $no;
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


/** 
* 全角字符转换为半角 
* 
* @param string $str 
* @return string 
*/ 
function sbc_to_dbc($str) 
{ 
	$arr = array( 
		'０'=>'0', '１'=>'1', '２'=>'2', '３'=>'3', '４'=>'4','５'=>'5', '６'=>'6', '７'=>'7', '８'=>'8', '９'=>'9', 
		'Ａ'=>'A', 'Ｂ'=>'B', 'Ｃ'=>'C', 'Ｄ'=>'D', 'Ｅ'=>'E','Ｆ'=>'F', 'Ｇ'=>'G', 'Ｈ'=>'H', 'Ｉ'=>'I', 'Ｊ'=>'J', 
		'Ｋ'=>'K', 'Ｌ'=>'L', 'Ｍ'=>'M', 'Ｎ'=>'N', 'Ｏ'=>'O','Ｐ'=>'P', 'Ｑ'=>'Q', 'Ｒ'=>'R', 'Ｓ'=>'S', 'Ｔ'=>'T', 
		'Ｕ'=>'U', 'Ｖ'=>'V', 'Ｗ'=>'W', 'Ｘ'=>'X', 'Ｙ'=>'Y','Ｚ'=>'Z', 'ａ'=>'a', 'ｂ'=>'b', 'ｃ'=>'c', 'ｄ'=>'d', 
		'ｅ'=>'e', 'ｆ'=>'f', 'ｇ'=>'g', 'ｈ'=>'h', 'ｉ'=>'i','ｊ'=>'j', 'ｋ'=>'k', 'ｌ'=>'l', 'ｍ'=>'m', 'ｎ'=>'n', 
		'ｏ'=>'o', 'ｐ'=>'p', 'ｑ'=>'q', 'ｒ'=>'r', 'ｓ'=>'s', 'ｔ'=>'t', 'ｕ'=>'u', 'ｖ'=>'v', 'ｗ'=>'w', 'ｘ'=>'x', 
		'ｙ'=>'y', 'ｚ'=>'z', 
		'（'=>'(', '）'=>')', '〔'=>'(', '〕'=>')', '【'=>'[','】'=>']', '〖'=>'[', '〗'=>']', '“'=>'"', '”'=>'"', 
		'‘'=>'\'', '\''=>'\'', '｛'=>'{', '｝'=>'}', '《'=>'<','》'=>'>','％'=>'%', '＋'=>'+', '—'=>'-', '－'=>'-', 
		'～'=>'~','：'=>':', '。'=>'.', '、'=>',', '，'=>',', '、'=>',', '；'=>';', '？'=>'?', '！'=>'!', '…'=>'-', 
		'‖'=>'|', '”'=>'"', '\''=>'`', '‘'=>'`', '｜'=>'|', '〃'=>'"','　'=>' ', '×'=>'*', '￣'=>'~', '．'=>'.', '＊'=>'*', 
		'＆'=>'&','＜'=>'<', '＞'=>'>', '＄'=>'$', '＠'=>'@', '＾'=>'^', '＿'=>'_', '＂'=>'"', '￥'=>'$', '＝'=>'=', 
		'＼'=>'\\', '／'=>'/'
	);
	
	return strtr($str, $arr); 
}


function getCleanValue($value){
	$value = preg_replace( '/[\x00-\x1F]/','',$value);
	return trim(sbc_to_dbc(str_replace(array("\r\n","\n","\r",'  ','　',' '),'',$value)));
}


function arrayToXML($assocArray){
	$line = array();
		
	foreach($assocArray as $k => $v){
		$line[] = "<{$k}><![CDATA[{$v}]]></$k>";
	}
	
	return '<xml>'.implode('',$line).'</xml>';
}


function arrayToSting($pAr,$sep = '|'){
	$line = array();
		
	foreach($pAr as $k => $v){
		if(!is_array($v)){
			$line[] = "{$k}={$v}";
		}
	}
	
	return implode($sep,$line);
	
}