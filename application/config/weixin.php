<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//微信服务号码
$config['mp'] = array(
	'folder' => 'tdkc',
	'EncodingType' => '安全模式',//兼容模式  安全模式
	'appid' => 'wxba86a9496e907b03',
    'app_secret' => '9f65076ccd3368ec24fd6b729f9a28e1',
    'token' => '51fe897ee2d1de42b8986234440bce79', //用于消息签名验证
    'EncodingAESKey' => '3dDKk1ULklHSW6Fx5BJUgWpb6SUyhqX3VBtswp1bAGp', // 消息加解密key
    
    //自动化测试公众号
    'auto_test_account' => array(
        'appid' => 'wx570bc396a51b8ff8',
        'Username' => 'gh_3c884a361561'
    )
);

// 土勘院小程序
$config['mp_xcxTdkc'] = array(
	'folder' => 'xcxtdkc',
	'EncodingType' => '安全模式',//兼容模式  安全模式
	'appid' => 'wxb0296a06cfe15d4c',
    'app_secret' => '58a783ce5f1675bc3fa5f0ddc4a27eee',
    'token' => '41eba4b3e62bdf6bd9720ca4dac430e7', //用于消息签名验证
    'EncodingAESKey' => 'S0hLztiWysdSv12U40d0HtLDfjbWD55MFNkfHuPfl64', // 消息加解密key
);

//城市物业
$config['mp_xcxCswy'] = array(
	'folder' => 'cswy',
	'EncodingType' => '安全模式',//兼容模式  安全模式
	'appid' => 'wxb156d72d0f257ba2',
    'app_secret' => '1b799d65d300b939966e39ecf403427a',
    'token' => '7bec533576c0d3ee0553c9afe92cc252', //用于消息签名验证
    'EncodingAESKey' => 'O1kL9lO5AeIg6Ps72H8zdScbFxpUPu2fMkbtrkh8yE7', // 消息加解密key
    'mch_id' => '1506987511',
    'mch_secret' => 'fa3f6c970f97d8ae7caec504c4dfeebb'
);


// 微信测试账户
$config['mp_test'] = array(
	'folder' => 'tdkcdev',
	'EncodingType' => '明文模式',//兼容模式  安全模式
	'appid' => 'wxd4aed2dc512e842b',
    'app_secret' => 'f0b6887e0e7e5fc3c9057cfb75932f52',
    'token' => 'd40e1b820344c570a6c23d945eb9f3c3', //用于消息签名验证
    'EncodingAESKey' => '3dDKk1ULklHSW6Fx5BJUgWpb6SUyhqX3VBtswp1bAGp', // 消息加解密key
);