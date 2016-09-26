<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'uid' => 'mediumint',
'email' => 'varchar',
'username' => 'varchar',
'password' => 'varchar',
'mobile' => 'varchar',
'qq' => 'char',
'sex' => 'char',
'nickname' => 'varchar',
'status' => 'tinyint',
'aid' => 'mediumint',
'avatar_m' => 'varchar',
'avatar_s' => 'varchar',
'avatar_status' => 'tinyint',
'email_status' => 'tinyint',
'inviter_uid' => 'mediumint',
'expired' => 'int',
'last_login' => 'int',
'last_loginip' => 'varchar',
'reg_date' => 'int',
'reg_ip' => 'varchar',
'reg_status' => 'tinyint',
'sid' => 'varchar',
'credits' => 'int',
'newpm' => 'smallint',
'district_bind' => 'tinyint',
'd1' => 'mediumint',
'd2' => 'mediumint',
'd3' => 'mediumint',
'd4' => 'mediumint',
'allowtalk' => 'char',
'freeze' => 'char',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
