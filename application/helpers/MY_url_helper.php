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
	function js_redirect($uri = '',$method = '')
	{
		header("HTTP/1.1 200 OK");
		header("Content-Type: text/html");
		
		if (!preg_match('#^(\w+:)?//#i', $uri))
		{
			$uri = site_url($uri);
		}
		
		
		switch($method){
			case 'top':
				echo '<script type="text/javascript" >window.top.location.href = "'. $uri .'";</script>';
				break;
			default:
				echo '<script type="text/javascript" >location.href = "'. $uri .'";</script>';
				break;
		}
		
		exit;
		
	}
}



