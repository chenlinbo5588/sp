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
'emailstatus' => 'tinyint',
'avatarstatus' => 'tinyint',
'videophotostatus' => 'tinyint',
'groupid' => 'smallint',
'groupexpiry' => 'int',
'extgroupids' => 'char',
'regdate' => 'int',
'regip' => 'varchar',
'credits' => 'int',
'notifysound' => 'tinyint',
'timeoffset' => 'char',
'newpm' => 'smallint',
'newprompt' => 'smallint',
'onlyacceptfriendpm' => 'tinyint',
'conisbind' => 'tinyint',
'district_id' => 'int',
'freeze' => 'tinyint',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
