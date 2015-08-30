<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'mediumint',
'stadium_id' => 'mediumint',
'title' => 'varchar',
'category_id' => 'int',
'category_name' => 'varchar',
'stadium_type' => 'varchar',
'ground_type' => 'varchar',
'charge_type' => 'varchar',
'remark' => 'varchar',
'owner' => 'varchar',
'owner_uid' => 'mediumint',
'reporter' => 'varchar',
'reporter_uid' => 'mediumint',
'score' => 'int',
'can_booking' => 'char',
'status' => 'smallint',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
