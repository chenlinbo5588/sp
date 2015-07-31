<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'int',
'category_id' => 'int',
'category_name' => 'varchar',
'title' => 'varchar',
'notice_board' => 'varchar',
'max_member' => 'smallint',
'current_num' => 'smallint',
'logo_url' => 'varchar',
'leader_name' => 'varchar',
'leader_uid' => 'mediumint',
'joined_type' => 'tinyint',
'd1' => 'mediumint',
'd2' => 'mediumint',
'd3' => 'mediumint',
'd4' => 'mediumint',
'credits' => 'int',
'games' => 'int',
'victory_game' => 'int',
'fail_game' => 'int',
'draw_game' => 'int',
'victory_rate' => 'int',
'owner_uid' => 'mediumint',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
