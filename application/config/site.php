<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * api 密钥配置
 */
$config['api_secret'] = 'b53209f0e4d2b2335d726e4e381511d59d22a31f';


$config['allowed_img_types'] = 'jpg|jpeg|png';

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
		'team' => array(
			'title' => '队伍',
			'url' => 'team/index'
		),
		'stadium' => array(
			'title' => '场馆',
			'url' => 'stadium/index'
		),
		'goods' => array(
			'title' => '商品',
			'url' => 'goods/index'
		),
		'article' => array(
			'title' => '网站',
			'url' => 'article/index'
		),
		'trade' => array(
			'title' => '交易',
			'url' => 'trade/index'
		),
		'cms' => array(
			'title' => 'CMS',
			'url' => 'cms_article/index'
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
			array('title' => '欢迎页面','url' => 'dashboard/welcome')
		),
		
		'setting' => array(
			array('title' => '站点设置','url' => 'setting/base'),
			array('title' => '上传设置','url' => 'upload/param'),
			array('title' => 'SEO设置','url' => 'setting/seoset'),
			array('title' => '消息通知','url' => 'message/email'),
		),
		'message' => 'setting',
		'upload' => 'setting',
		'member' => array(
			array('title' => '实验室管理','url' => 'lab/index'),
			array('title' => '添加实验室','url' => 'lab/add'),
			array('title' => '导出实验室','url' => 'lab/export'),
		),
		'lab_user' => array(
			array('title' => '实验员管理','url' => 'lab_user/index'),
			array('title' => '添加实验员','url' => 'lab_user/add'),
			array('title' => '导出实验员','url' => 'lab_user/export'),
		),
		'goods' => array(
			array('title' => '货品','url' => 'goods/index'),
			array('title' => '货品分类','url' => 'goods_category/index'),
		),
		'goods_category' => 'goods',
		'lab_measure' => array(
			array('title' => '度量单位','url' => 'lab_measure/index'),
		),
		'authority' => array(
			array('title' => '角色','url' => 'authority/role'),
		)
	)
);

