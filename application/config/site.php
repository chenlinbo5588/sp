<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * api 密钥配置
 */
$config['api_secret'] = 'b53209f0e4d2b2335d726e4e381511d59d22a31f';
$config['img_size'] = array(
	'h' => array('width' => 800,'height' => 600, 'maintain_ratio' => true,'quality' => 90),
	'b' => array('width' => 400,'height' => 300 , 'maintain_ratio' => true,'quality' => 90),
	'm' => array('width' => 150,'height' => 150,'maintain_ratio' => false,'quality' => 100),
	's' => array('width' => 100,'height' => 100,'maintain_ratio' => false,'quality' => 100)
);
$config['allowed_img_types'] = 'jpg|jpeg|png';
