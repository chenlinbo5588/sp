<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'uid' => 'mediumint',
'email' => 'char',
'username' => 'char',
'password' => 'char',
'status' => 'tinyint',
'mobile' => 'char',
'sex' => 'char',
'nickname' => 'varchar',
'email_status' => 'tinyint',
'group_id' => 'smallint',
'group_expiry' => 'int',
'reg_date' => 'int',
'reg_ip' => 'varchar',
'last_login' => 'int',
'last_loginip' => 'varchar',
'sid' => 'varchar',
'newpm' => 'smallint',
'newprompt' => 'smallint',
'freeze' => 'tinyint',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
