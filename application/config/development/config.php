<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['cache_driver'] = 'redis';

$config['log_threshold'] = 2;


$config['base_url'] = '';
$config['site_domain'] = $_SERVER['SERVER_NAME'];

//$config['csrf_protection'] = FALSE;
$config['page_size'] = 20;


// 货品 90 分钟过期
$config['hp_expired'] = 5400;

//站内信更新检查时间间隔
$config['pmcheck_interval'] = 5;