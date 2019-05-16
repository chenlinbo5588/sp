<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * api 密钥配置
 */
$config['api_secret'] = 'b53209f0e4d2b2335d726e4e381511d59d22a31f';

$config['aliyun_dm'] = array(
	/*
	'account' => 'system@safetyfst.com',
	'api_key' => 'LTAIljFXelniCvtz',
	'api_secret' => 'sodR7uUrudMNXetEyhKTussLH67gIO'
	*/
);

$config['aliyun_SMS'] = array(
	'signName' => '慈溪市勘测院',
	'templates' => array(
		'用户注册验证码' => 'SMS_160425032'
	)
);


$config['forground_image_allow_ext'] = 'jpg|jpeg|png';
$config['background_image_allow_ext'] = 'jpg|jpeg|png';
$config['image_max_filesize'] = '2048';


$config['mail_list'] = array(
	'sina.com','126.com','163.com','gmail.com','qq.com','vip.qq.com','hotmail.com','sohu.com','139.com','vip.sina.com','21cn.cn','189.cn','sina.cn'
);


$config['notify_ways'] = array(
	'站内信',
	'短信'
);



/**
 * 默认图片大小
 */
$config['default_img_size'] = array(
	'b' => array('width' => 800,'height' => 600 , 'maintain_ratio' => false,'quality' => 90),
	'm' => array('width' => 400,'height' => 300 , 'maintain_ratio' => false,'quality' => 90),
	's' => array('width' => 120,'height' => 120 , 'maintain_ratio' => false,'quality' => 90)
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


//
$config['province_idcard'] = array(
	'110000' => '北京市',
	'120000' => '天津市',
	'130000' => '河北省',
	'140000' => '山西省',
	'150000' => '内蒙古自治区',
	'210000' => '辽宁省',
	'220000' => '吉林省',
	'230000' => '黑龙江省',
	'310000' => '上海市',
	'320000' => '江苏省',
	'330000' => '浙江省',
	'340000' => '安徽省',
	'350000' => '福建省',
	'360000' => '江西省',
	'370000' => '山东省',
	'410000' => '河南省',
	'420000' => '湖北省',
	'430000' => '湖南省',
	'440000' => '广东省',
	'450000' => '广西壮族自治区',
	'460000' => '海南省',
	'500000' => '重庆市',
	'510000' => '四川省',
	'520000' => '贵州省',
	'530000' => '云南省',
	'540000' => '西藏自治区',
	'610000' => '陕西省',
	'620000' => '甘肃省',
	'630000' => '青海省',
	'640000' => '宁夏回族自治区',
	'650000' => '新疆维吾尔自治区',
	'710000' => '台湾省',
	'810000' => '香港特别行政区',
);


$config['payment'] = array(
	'channel' => array(
		'微信支付' => 1,
		'支付宝支付' => 2,
		'POS刷卡支付' => 3,
		'现金支付' => 4,
		'手工单' => 5
	),
	
	'method' => array(
		'微信支付' => array(
			'小程序支付' => 1001,
			'公众号支付' => 1002,
			'原生扫码支付' => 1003,
			'app支付' => 1004,
		),
		'支付宝支付' => array(
			'小程序支付' => 2001,
			'公众号支付' => 2002,
			'原生扫码支付' => 2003,
			'app支付' => 2004
		),
		'手工单' => array(
			'现金支付' => 5001,
			'刷卡支付' => 5002,
			'微信转账' => 5003,
			'支付宝转账' => 5004
		)
		
	)
);

//
$config['excel_export_limit'] = 5000;
$config['excel_import_limit'] = 5000;
