<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 货品验证规则
 */

 
$config['person_validation'] = array(
	/* 单个添加  */
	'person_add' => array(
		'qlr_name',
		'id_type',
		'id_no',
		'address',
		'name',
		'sex',
		'mobile',
		'family_num',
	),
	/* 导入   */
	'person_import' => array(
		'qlr_name' => array('required' => true),
		'id_type' => array('required' => true),
		'id_no' => array('required' => true),
		'address' => array('condition' => true, 'whenSelfNotEmpty' => true , 'defaultValue' => ''),
		'name' => array('condition' => true, 
						'whenFields' => array(
								array('key' => 'id_type', 'value' => '工商营业执照')
						)),
		'sex' => array('optional' => true, 'defaultValue' => 1),
		'mobile' => array('condition' => true, 'whenSelfNotEmpty' => true , 'defaultValue' => ''),
		'family_num' => array('optional' => true, 'defaultValue' => 1),
	),
	
	'rule_list' => array(
		'qlr_name' => array(
			'title' => '权利人名称',
			'rules' => 'required|min_length[1]|max_length[120]'
		),
		'village' => array(
			'title' => '所在村',
			'rules' => 'required|min_length[1]|max_length[20]'
		),
		'id_type' => array(
			'title' => '证件类型',
			'rules' => 'required|in_list[1,2,居民身份证,工商营业执照]'
		),
		'id_no' => array(
			'title' => '证件号码',
			'rules' => 'required|min_length[1]|max_length[20]'
		),
		'address' => array(
			'title' => '地址',
			'rules' => 'required|min_length[1]|max_length[100]'
		),
		'name' => array(
			'title' => '联系人名称',
			'rules' => 'required|min_length[1]|max_length[20]'
		),
		'sex' => array(
			'title' => '性别',
			'rules' => 'required|in_list[1,2,男,女]'
		),
		'mobile' => array(
			'title' => '手机号码',
			'rules' => 'required|valid_mobile'
		),
		'family_num' => array(
			'title' => '家庭在册人口数量',
			'rules' => 'required|is_natural_no_zero'
		),
		
	)
);


$config['house_validation'] = array(
	'rule_list' => array(
		'x' => array('title' => 'X坐标' , 'required' => true , 'rule' => 'required|numeric' ),
		'y' => array('title' => 'Y坐标' , 'required' => true , 'rule' => 'required|numeric' ),
		'owner_id' => array('title' => '权力人ID' , 'required' => true , 'rule' => 'required|is_natural_no_zero'),
		'owner_name' => array('title' => '权力人名称' , 'required' => true , 'rule' => 'required|max_length[200]'),
		'village_id' => array('title' => '房屋坐落所在村' , 'condition' => true  , 'defaultValue' => 0, 'rule' => 'required|is_natural_no_zero'),
		'address' => array('title' => '地址' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => '',  'rule' => 'required|max_length[80]'),
		
		'land_oa' => array('title' => '土地权属性质' , 'required' => true , 'rule' => 'required|in_list[1,2]'),
		'land_no' => array('title' => '土地使用权证号' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => '', 'rule' => 'required|max_length[50]'),
		'zddh' => array('title' => '宗地地号' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => '', 'rule' => 'required|max_length[50]'),
		'land_cate' => array('title' => '土地类别' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => '', 'rule' => 'required|max_length[50]'),
		
		'jzw_ydmj' => array('title' => '用地面积' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => 0, 'rule' => 'required|numeric|greater_than_equal_to[0]'),
		'jzw_jzzdmj' => array('title' => '建筑占地面积' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' =>0, 'rule' => 'required|numeric|greater_than_equal_to[0]'),
		'jzw_jzmj' => array('title' => '建筑面积' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => 0, 'rule' => 'required|numeric|greater_than_equal_to[0]'),
		'jzw_plies' => array('title' => '建筑层数' ,'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => '', 'rule' => 'required|max_length[30]'),
		'jzw_jg' => array('title' => '房屋结构' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => '' , 'rule' => 'required|max_length[20]'),
		'jzw_unit' => array('title' => '幢单元数' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => 0, 'rule' => 'required|is_natural_no_zero'),
		'purpose' => array('title' => '用途' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => '' , 'rule' => 'required|max_length[50]'),

		'sp_new' => array('title' => '审批时间(新建)' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => NULL , 'rule' => 'required|valid_date'),
		'sp_ycyj' => array('title' => '审批时间(原拆原建)' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => NULL , 'rule' => 'required|valid_date'),
		'sp_ydmj' => array('title' => '批准用地面积' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => 0, 'rule' => 'required|numeric|greater_than_equal_to[0]'),
		'sp_jzmj' => array('title' => '批准建筑面积' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => 0, 'rule' => 'required|numeric|greater_than_equal_to[0]'),
		'illegal' => array('title' => '违法现象' , 'required' => true , 'rule' => 'required|in_list[0,1,2,3]'),
		
		'wf_wjsj' => array('title' => '违建时间' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => NULL , 'rule' => 'required|valid_date'),
		'wf_wjmj' => array('title' => '违建占地面积' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => 0, 'rule' => 'required|numeric|greater_than_equal_to[0]'),
		'wf_jzmj' => array('title' => '违建建筑面积' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => 0, 'rule' => 'required|numeric|greater_than_equal_to[0]'),
		
		'cate' => array('title' => '使用分类' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => 0, 'rule' => 'required|in_list[1,2,3,4]'),
		'deal_way' => array('title' => '分类处置办法' ,'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => 0, 'rule' => 'required|in_list[1,2,3,4]')   ,
		'remark' => array('title' => '备注' , 'condition' => true , 'whenSelfNotEmpty' => true, 'defaultValue' => '', 'rule' => 'required|max_length[500]' ),
	)
);

$config['import_per_limit'] = 500;
$config['import_excel_column'] = array(
	array( 'col' => 'A', 'width' => 20, 'db_key' => 'qlr_name' ),
	array( 'col' => 'B', 'width' => 10, 'db_key' => 'id_type' ),
	array( 'col' => 'C', 'width' => 15, 'db_key' => 'id_no' ),
	array( 'col' => 'D', 'width' => 15, 'db_key' => 'address' ),
	array( 'col' => 'E', 'width' => 15, 'db_key' => 'name' ),
	array( 'col' => 'F', 'width' => 8, 'db_key' => 'sex' ),
	array( 'col' => 'G', 'width' => 10, 'db_key' => 'mobile' ),
	array( 'col' => 'H', 'width' => 10, 'db_key' => 'family_num' ),
);

