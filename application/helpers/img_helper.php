<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('getImgPathArray'))
{
	
	function getImgPathArray($srcImgUrl , $size = array()){
		
		$img['avatar'] = $srcImgUrl;
		
		if($size){
			$dotPos = strrpos($srcImgUrl,'.');
			$fileName = substr($srcImgUrl,0,$dotPos);
			$suffixName = substr($srcImgUrl,$dotPos);
			
			foreach($size as $sname){
				$img['avatar_'.$sname] = $fileName.'@'.$sname.$suffixName;
			}
		}
		
		return $img;
	}

}
