<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * api 密钥配置
 */
$config['api_secret'] = 'b53209f0e4d2b2335d726e4e381511d59d22a31f';
$config['allowed_img_types'] = 'jpg|jpeg';

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
	'b' => array('width' => 400,'height' => 300 , 'maintain_ratio' => false,'quality' => 90),
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
 * |                   | 
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


$config['navs'] = array(
	'main' => array(
		'dashboard' => array(
			'title' => '控制台',
			'url' => 'dashboard/welcome'
		),
		'setting' => array(
			'title' => '设置',
			'url' => 'setting/base'
		),
		'member' => array(
			'title' => '会员',
			'url' => 'member/index'
		),
		'goods' => array(
			'title' => '货品',
			'url' => 'goods/index'
		),
		'weixin' => array(
			'title' => '微信',
			'url' => 'weixin/index'
		),
		'authority' => array(
			'title' => '权限',
			'url' => 'authority/role'
		),
	),
	'side' => array (
		'index' => 'dashboard',
		'dashboard' => array(
			array('title' => '欢迎页面','url' => 'dashboard/welcome'),
			array('title' => '关于我们','url' => 'dashboard/aboutus')
		),
		'setting' => array(
			array('title' => '站点设置','url' => 'setting/base'),
			array('title' => '上传设置','url' => 'upload/param'),
			array('title' => 'SEO设置','url' => 'seo/index'),
			array('title' => '消息通知','url' => 'message/email'),
		),
		'goods' => array(
			array('title' => '今日求货','url' => 'goods/index'),
			array('title' => '历史求货','url' => 'goods/history'),
			array('title' => '品牌管理','url' => 'brand/index'),
			array('title' => '颜色管理','url' => 'color/index'),
			array('title' => '货品分类','url' => 'goods_class/category'),
		),
		'brand' => 'goods',
		'goods_class' => 'goods',
		'color' => 'goods',
		'message' => 'setting',
		'upload' => 'setting',
		'seo' => 'setting',
		
		'member' => array(
			array('title' => '会员管理','url' => 'member/index'),
		),
		'authority' => array(
			array('title' => '角色','url' => 'authority/role'),
		)
	)
);

