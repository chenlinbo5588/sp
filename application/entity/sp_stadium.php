<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'stadium_id' => 'int',
'title' => 'varchar',
'address' => 'varchar',
'contact' => 'varchar',
'mobile' => 'varchar',
'cover_img' => 'varchar',
'district_id' => 'int',
'longitude' => 'varchar',
'latitude' => 'varchar',
'has_coordinates' => 'tinyint',
'stadium_type' => 'tinyint',
'ground_type' => 'tinyint',
'charge_type' => 'tinyint',
'open_type' => 'tinyint',
'remark' => 'varchar',
'owner' => 'varchar',
'score' => 'int',
'can_booking' => 'char',
'status' => 'smallint',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
