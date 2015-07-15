<?php

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

