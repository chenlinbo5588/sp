<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['SMS_apikey'] = 'cb287ff419c6a8adba5d452feed76a03';
$config['registerOk_text1'] = '注册成功';
$config['registerOk_text2'] = '注册成功';
$config['registerOk_text3'] = '注册成功';
$config['maxRegisterIpLimit'] = 30;

$config['jumUrl'] = array(
	'website' => 'http://zhibo.ddy168.com',
	'qqchat' => 'http://crm2.qq.com/page/portalpage/wpa.php?uin=4006222066&aty=0&a=0&curl=&ty=1',
	'qqgroup' => 'http://jq.qq.com/?_wv=1027&k=2FKSrRM',
	'download1' => 'http://www.qcecn.com/uploadfile/soft/hqfx.exe'

);


$config['siteRules'] = array(
	's1.txcf188.com' => array(
			'registeOkText' => 'registerOk_text1',
			'jumUrlType' => 'website',
			'rules' => array('username','mobile','mobile_auth_code'),
		),
	's2.txcf188.com' => array(
			'registeOkText' => 'registerOk_text2',
			'jumUrlType' => 'qqchat',
			'rules' => array('mobile'),
		),
	
	's3.txcf188.com' => array(
			'registeOkText' => 'registerOk_text3',
			'jumUrlType' => 'qqgroup',
			'rules' => array('mobile','auth_code'),
		),
	
	's4.txcf188.com' => array(
			'registeOkText' => 'registerOk_text2',
			'jumUrlType' => 'qqchat',
			'rules' => array('username', 'mobile','auth_code'),
		),
		
	's5.txcf188.com' => array(
			'registeOkText' => 'registerOk_text2',
			'jumUrlType' => 'qqchat',
			'rules' => array( 'mobile','auth_code'),
		),
	's6.txcf188.com' => array(
			'registeOkText' => 'registerOk_text2',
			'jumUrlType' => 'qqchat',
			'rules' => array( 'mobile','auth_code'),
		),
	's7.txcf188.com' => array(
			'registeOkText' => 'registerOk_text1',
			'jumUrlType' => 'download1',
			'rules' => array('username', 'mobile','mobile_auth_code'),
		),
	's8.txcf188.com' => array(
			'registeOkText' => 'registerOk_text1',
			'jumUrlType' => 'website',
			'rules' => array('mobile','mobile_auth_code'),
		),
	's9.txcf188.com' => array(
			'registeOkText' => 'registerOk_text1',
			'jumUrlType' => 'website',
			'rules' => array(), //没有验证
		),
	's10.txcf188.com' => array(
			'registeOkText' => 'registerOk_text1',
			'jumUrlType' => 'website',
			'rules' => array('username','mobile','mobile_auth_code'),
		),
	's11.txcf188.com' => array(
			'registeOkText' => 'registerOk_text1',
			'jumUrlType' => 'download1',
			'rules' => array('stock','mobile'),
		),
	's12.txcf188.com' => array(
			'registeOkText' => 'registerOk_text2',
			'jumUrlType' => 'qqchat',
			'rules' => array( 'mobile','auth_code'),
		),
);



