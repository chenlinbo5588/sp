<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'uid' => 'mediumint',
'email' => 'char',
'username' => 'char',
'password' => 'char',
'status' => 'varchar',
'mobile' => 'char',
'sex' => 'char',
'nickname' => 'varchar',
'email_status' => 'tinyint',
'group_id' => 'smallint',
'group_expire' => 'int',
'reg_date' => 'int',
'reg_ip' => 'varchar',
'is_lock' => 'tinyint',
'last_login' => 'int',
'last_loginip' => 'varchar',
'sid' => 'varchar',
'newpm' => 'smallint',
'newprompt' => 'smallint',
'freeze' => 'tinyint',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
