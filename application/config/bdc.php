<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['bdc_workflow'] = array(
	'不动产业务' => array(
		'新增登记' => array(
			'statusValue' => 1,
			'preStep' => '',
			'nextStep' => '测绘',
			'nextDeptCondition' => array(
				'where' => array(
					'org_type' => '测绘单位'
				)
			)
		),
		'测绘' => array(
			'statusValue' => 2,
			'preStep' => '新增登记',
			'nextStep' => '初审',
			'nextDeptCondition' => array(
				'where' => array(
					'name' => '慈溪市不动产中心初审'
				)
			)
		),
		'初审' => array(
			'statusValue' => 3,
			'preStep' => '测绘',
			'nextStep' => '复审',
			'nextDeptCondition' => array(
				'where' => array(
					'name' => '慈溪市不动产中心复审'
				)
			)
		),
		'复审' => array(
			'statusValue' => 4,
			'preStep' => '初审',
			'nextStep' => '',
		)
	)
);

