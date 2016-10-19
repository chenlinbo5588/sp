<?php

defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('mask_mobile'))
{
	/**
	 * @param	string	$mobile
	 * @param	string	$mask
	 * @return	string
	 */
	function mask_mobile($mobile, $mask = '*')
	{
		if(preg_match("/^(\+?86)?1[0-9][0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$mobile)){
			$mobile = substr($mobile,0,-8).str_repeat($mask,4).substr($mobile,-4);
		}
		
		return $mobile;
	}
}

if ( ! function_exists('mask_email'))
{
	/**
	 * @param	string	$mobile
	 * @param	string	$mask
	 * @return	string
	 */
	function mask_email($email, $mask = '*')
	{
		$email_array = explode("@", $email); 
        $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($email, 0, 3); //邮箱前缀 
        $count = 0; 
        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $email, -1, $count); 
        $rs = $prevfix . $str; 
        
        return $rs;
	}
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




/**
 * 输入一组,
 */
function orderValue($searchKeys,$maxSize = 50){
	//尺码
	if(empty($searchKeys[0]) && !empty($searchKeys[1])){
		if($searchKeys[1] > $maxSize){
			$searchKeys[1] = $maxSize;
		}
	}else if(!empty($searchKeys[0]) && empty($searchKeys[1])){
		$searchKeys[1] = $maxSize;
	}else if(!empty($searchKeys[0]) && !empty($searchKeys[1])){
		if($searchKeys[0] > $searchKeys[1]){
			$tmp = $searchKeys[0];
			$searchKeys[0] = $searchKeys[1];
			$searchKeys[1] = $tmp;
		}
	}
	
	if($searchKeys[1] > $maxSize){
		$searchKeys[1] = $maxSize;
	}
	
	
	return $searchKeys;
}



function step_helper($stepText,$step){
	$stepHtml = array();
	$totalStep = count($stepText);
	
	for($i = 1; $i <= $totalStep; $i++){
		$s = '<div class="w-step'.$totalStep . ' ';
		$index = $i - 1;
		if($i == $step){
			if($i == 1){
				$s .= 'w-step-cur';
			}else{
				$s .= 'w-step-past-cur';
			}
		}elseif($i < $step){
			if($i == 1){
				$s .= 'w-step-past';
			}elseif(($step - 1) ==  1 ){
				$s .= 'w-step-past';
			}else{
				$s .= 'w-step-past-past';
			}
		}elseif($i > $step){
			if(($i - $step) ==  1 ){
				$s .= 'w-step-cur-future';
			}else{
				$s .= 'w-step-future-future';
			}
		}
		
		$stepHtml[] = $s."\">".($index + 1)."、".$stepText[$index]."</div>";
	}
	
	return '<div class="w-step-row">'.implode('',$stepHtml).'</div>';
	
}


function trim_newline($str){
	return str_replace(array("\r\n","\n","\r"),'',$str);
	
}
