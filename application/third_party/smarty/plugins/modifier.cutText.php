<?php
/**
*字符串截取类,过滤html
*/
function smarty_modifier_cutText($str, $len, $mode = true) {
	$str = strip_tags($str);
	$str = html_entity_decode($str,ENT_COMPAT, 'UTF-8');
	$str = cut($str, $len, $mode);
	$str = htmlentities($str,ENT_COMPAT, 'UTF-8');
	return $str;
}

?>