<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['default_password'] = '123456';

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


