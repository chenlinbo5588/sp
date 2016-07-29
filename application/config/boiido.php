<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['pageConf'] = array(
	'走进标度' => array(
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
			'客户服务' => site_url('service/customer'),
			'产品资料' => site_url('doc/product_list'),
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