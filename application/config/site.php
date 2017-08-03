<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * api 密钥配置
 */
$config['api_secret'] = 'b53209f0e4d2b2335d726e4e381511d59d22a31f';

$config['aliyun_dm'] = array(
	'account' => 'service@xinqf.cn',
	'api_key' => 'LTAIDhv8e7NJWiR1',
	'api_secret' => 'mS2THl6kWbsQ7RTo4NDpe5wdNi4U7J'
);


$config['allowed_img_types'] = 'jpg|jpeg|png';

$config['mail_list'] = array(
	'sina.com','126.com','163.com','gmail.com','qq.com','vip.qq.com','hotmail.com','sohu.com','139.com','vip.sina.com','21cn.cn','189.cn','sina.cn'
);



$config['SMS_apikey'] = '';


$config['fronted_admin'] = array(
	
);


$config['notify_ways'] = array(
	'站内信',
	/*'聊天窗口',*/
	'邮件',
	/*'短信'*/
);

//
$config['yunxin'] = array(
	'url' => 'https://api.netease.im/nimserver/',
	'appkey' => 'a54919705abd0863a8d3cc49cf8eb14e',
	'secret' => 'e4294723dba9'
);

$config['huanxin'] = array(
	'url' => 'https://a1.easemob.com/',
	'appid' => 1,
	'appkey' => 'shonex#sh1',
	'client_id' => 'YXA6-XsmMH2XEeaP9mOxWdAA3Q',
	'secret' => 'YXA6gvDBESUMtptDHPZearXT4d8YdVE',
	'open_type' => 1
);


/**
 * 默认图片大小
 */
$config['default_img_size'] = array(
	'b' => array('width' => 800,'height' => 600 , 'maintain_ratio' => false,'quality' => 90),
	'm' => array('width' => 400,'height' => 300 , 'maintain_ratio' => false,'quality' => 90)
);

/**
 * h short for huge
 * b short for big
 * m short for middle
 * s short for small
 * 
 * 头像图片参数
 * 
 * 4:3 或 1:1
 */
$config['avatar_img_size'] = array(
	'm' => array('width' => 200,'height' => 200,'maintain_ratio' => false,'quality' => 100),
	's' => array('width' => 100,'height' => 100,'maintain_ratio' => false,'quality' => 100)
);

/**
 * 队伍图片参数 16:9
 */
$config['team_img_size'] = array(
	'h' => array('width' => 960,'height' => 540, 'maintain_ratio' => false,'quality' => 90),
	'b' => array('width' => 640,'height' => 360 , 'maintain_ratio' => false,'quality' => 100),
	'm' => array('width' => 320,'height' => 180,'maintain_ratio' => false,'quality' => 100),
);

/**
 * 场馆图片参数 16:9
 */
$config['stadium_img_size'] = array(
	'h' => array('width' => 960,'height' => 540, 'maintain_ratio' => true,'quality' => 90),
	'b' => array('width' => 640,'height' => 360 , 'maintain_ratio' => true,'quality' => 100),
	'm' => array('width' => 320,'height' => 180,'maintain_ratio' => false,'quality' => 100),
);

/**
 * 文章配图 图片参数 16:9
 * 微信消息文章配图
 * 
 * 屏幕显示如下
 * ---------------------
 * |大图消息1 360x200     |
 * |                     | 
 * ---------------------
 * |小图消息1 200x200     | 
 * ---------------------
 * |小图消息2 200x200     |
 * ---------------------
 * |小图消息3 200x200     |
 * ---------------------
 * |小图消息4 200x200     |
 * ---------------------
 * 
 */
$config['weixin_img_size'] = array(
	'm' => array('width' => 360,'height' => 200 , 'maintain_ratio' => false,'quality' => 100),
	's' => array('width' => 200,'height' => 200,'maintain_ratio' => false,'quality' => 100),
);


$config['id_type'] = array(
	'1' => '身份证',
	'2' => '工商营业执照'
);

$config['sex_type'] = array(
	'1' => '男' ,
	'2' => '女'
);

//$config['site_town'] = '掌起镇';
$config['site_town'] = '坎墩街道';

