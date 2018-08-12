<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'uid' => 'int',
'email' => 'char',
'username' => 'varchar',
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
'pm_id' => 'int',
'freeze' => 'tinyint',
'add_uid' => 'int',
'edit_uid' => 'int',
'add_username' => 'varchar',
'edit_username' => 'varchar',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
