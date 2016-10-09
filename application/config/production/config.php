<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['cache_driver'] = 'redis';

$config['log_threshold'] = 1;

$config['base_url'] = '';
$config['site_domain'] = $_SERVER['SERVER_NAME'];

//$config['csrf_protection'] = FALSE;
$config['page_size'] = 30;


// 货品 90 分钟过期
$config['hp_expired'] = 5400;
$config['hp_pub_freezen'] = 300;

// 这个配合比较重要，建议不要小于60, 过小会造成系统负荷过重
$config['pmcheck_interval'] = 90;


$config['compress_output'] = true;

