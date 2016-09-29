<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter URL Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/url_helper.html
 */

// ------------------------------------------------------------------------


if ( ! function_exists('resource_url'))
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
	function resource_url($uri = '', $protocol = NULL)
	{
		if(strpos($uri,'http') !== false){
			return $uri;
		}
		
		if(strpos($uri,'static/') !== false){
			return get_instance()->config->base_url($uri, $protocol);
		}else{
			return get_instance()->config->base_url('static/'.$uri, $protocol);
		}
		
		
	}
}



if ( ! function_exists('admin_site_url'))
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
	function admin_site_url($uri = '', $protocol = NULL)
	{
		return get_instance()->config->site_url('admin/'.$uri, $protocol);
	}
}



if ( ! function_exists('js_redirect'))
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
	function js_redirect($uri = '',$method = '',$wait = 1,$title = '')
	{
		header("HTTP/1.1 200 OK");
		header("Content-Type: text/html");
		
		if (!preg_match('#^(\w+:)?//#i', $uri))
		{
			$uri = site_url($uri);
		}
		header("Content-Type: text/html;charset=utf-8");
		
		if(empty($title)){
			$title = '正在为你跳转,请稍后...';
		}
		echo "<p style=\"text-align:center;\">{$title}</p>";
		
		switch($method){
			case 'top':
				echo '<script type="text/javascript" >setTimeout(function(){window.top.location.href = "'. $uri .'";},'.($wait * 1000).');</script>';
				break;
			default:
				echo '<script type="text/javascript" >setTimeout(function(){location.href = "'. $uri .'";},'.($wait * 1000).');</script>';
				break;
		}
		
		exit;
		
	}
}


if ( ! function_exists('urlToPath')) {
	function urlToPath($url){
		//兼容处理
		if(strpos($url,'http://') !== false){
			$tempUrl = parse_url($url);
			
			if(substr($tempUrl['path'],0,1) == '/'){
				$tempUrl['path'] = substr($tempUrl['path'],1);
			}
			
			return $tempUrl['path'];
		}else{
			return $url;
		}
	}
}





