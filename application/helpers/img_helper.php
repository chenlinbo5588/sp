<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function getImgPathArray($srcImgUrl){
	
	$img['avatar'] = $srcImgUrl;
	$dotPos = strrpos($srcImgUrl,'.');
	$fileName = substr($srcImgUrl,0,$dotPos);
	$suffixName = substr($srcImgUrl,$dotPos);
						
	$img['avatar_large'] = $fileName.'@large'.$suffixName;
	$img['avatar_big'] = $fileName.'@big'.$suffixName;
	$img['avatar_middle'] = $fileName.'@middle'.$suffixName;
	$img['avatar_small'] = $fileName.'@small'.$suffixName;
	
	return $img;
}