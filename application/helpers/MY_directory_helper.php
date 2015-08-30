<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('make_dir')){

	function make_dir($path){
		if(!is_dir($path)){
			$str = dirname($path);
			if($str){
				make_dir($str.'/');
				@mkdir($path,0777);
				chmod($path,0777);
				write_file($path.'index.html','Access Denied');
			}
		}
	}
}
