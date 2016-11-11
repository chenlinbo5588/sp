<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * api 密钥配置
 */
$config['api_secret'] = 'b53209f0e4d2b2335d726e4e381511d59d22a31f';

$config['aliyun_dm'] = array(
	'account' => 'system@safetyfst.com',
	'api_key' => 'LTAIljFXelniCvtz',
	'api_secret' => 'sodR7uUrudMNXetEyhKTussLH67gIO'
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


$config['pageConf'] = array(
	'走进我们' => array(
		'url' => site_url('about'),
		'sideNav' => array(
			'企业简介' => site_url('about/index'),
			'公司理念' => site_url('about/thinking'),
			'企业风采' => site_url('about/moreintro'),
		)
	),
	'营销招商' => array(
		'url' => site_url('market/agency'),
		'sideNav' => array(
			'经销招商' => site_url('market/agency'),
			'运营特点' => site_url('market/cooperation'),
		)
	),
	'服务中心' => array(
		'url' => site_url('service/customer'),
		'sideNav' => array(
			'客户服务' => site_url('service/customer')
		)
	),
	'联系我们' => array(
		'url' => site_url('contacts/index'),
		'sideNav' => array(
			'售后中心' => site_url('contacts/customer_service'),
			'招商电话' => site_url('contacts/merchants_telephone'),
			'投诉建议' => site_url('contacts/suggestion'),
			'在线地图' => site_url('contacts/map'),
		)
	),
	

);
