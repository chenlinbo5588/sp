<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['cache_driver'] = 'redis';

$config['log_threshold'] = 2;


$config['base_url'] = '';
$config['site_domain'] = $_SERVER['SERVER_NAME'];

//$config['csrf_protection'] = FALSE;
$config['page_size'] = 4;


//վ���Ÿ��¼��ʱ����
$config['pmcheck_interval'] = 5;
