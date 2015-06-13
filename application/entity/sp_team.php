<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'int',
'category_id' => 'int',
'team_title' => 'varchar',
'max_memebr' => 'smallint',
'current_num' => 'smallint',
'logo_url' => 'varchar',
'leader_name' => 'varchar',
'leader_uid' => 'mediumint',
'joined_type' => 'tinyint',
'district_id' => 'int',
'credits' => 'int',
'games' => 'int',
'victory_games' => 'int',
'fail_games' => 'int',
'draw_game' => 'int',
'victory_rate' => 'int',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
