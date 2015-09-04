<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 微信配置
 */
$config['ydzj'] = array(
	'mp' => array(
		'appid' => 'wxba86a9496e907b03',
	    'app_secret' => '9f65076ccd3368ec24fd6b729f9a28e1',
	    'token' => '51fe897ee2d1de42b8986234440bce79', //用于消息签名验证
	    'EncodingAESKey' => '3dDKk1ULklHSW6Fx5BJUgWpb6SUyhqX3VBtswp1bAGp', // 消息加解密key
	    
	    //自动化测试公众号
	    'auto_test_account' => array(
	        'appid' => 'wx570bc396a51b8ff8',
	        'Username' => 'gh_3c884a361561'
	    )
	)
);