<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 微信配置 
 */
$config['tdkc'] = array(
	'open' => array(
	    'appid' => 'wx67eecf008bac55a9',
	    'app_secret' => '0c79e1fa963cd80cc0be99b20a18faeb',
	    'token' => '3a3c35b7826901d506322a4623d2d8ce', //用于消息签名验证
	    'EncodingAESKey' => 'f10c3544135bd8d44930f94987637695a5420b43054', // 消息加解密key
	),
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