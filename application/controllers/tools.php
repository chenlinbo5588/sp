<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function com_poi(){
		$file = 'poi.m.csv.txt';
		
		for($i = 1; $i <= 9; $i++){
			echo "sed /1,\$/p poi.csv{$i}.txt >> {$file} <br/>";
		}
		
	}
	
	public function com_address(){
		$file = 'address.m.csv.txt';
		
		for($i = 1; $i <= 36; $i++){
			echo "sed /1,\$/p address.csv{$i}.txt >> {$file} <br/>";
		}
		
	}
	
	
	
	
	
	
	public function image_rename(){
		
		$sourcePath = 'D60825';
		$destPath = 'D60825-1';
		//$destPath = '/mnt/hgfs/code/PoiPhoto_ZS/';
		
		$this->load->helper('directory');
		$list = directory_map($sourcePath);
		
		
		
		foreach($list as $file){
			if(strtolower(substr($file,-3)) == 'jpg'){
				$newfile = str_replace('IMAGE-'.$sourcePath,'IMAGE-'.$destPath,$file);
				
				copy($sourcePath.'/'.$file  , $destPath.'/'.$newfile);
			}
			
			
		}
		
	}
	
	
	public function image_cut()
	{
		
		set_time_limit(0);
		$this->load->library('image_lib');
		
		$sourcePath = '/mnt/hgfs/code/PoiPhoto_SP/';
		$destPath = '/mnt/hgfs/code/PoiPhoto_ZS/';
		
		
		$this->load->helper('directory');
		
		$list = directory_map($sourcePath);
		
		
		$resize['image_library'] = 'gd2';
		$resize['create_thumb'] = false;
		$resize['maintain_ratio'] = false;
		
		$resize['width'] = 800;
		$resize['height'] = 600;
		
		foreach($list as $img){
			$resize['source_image'] = $sourcePath.$img;
			$resize['new_image'] = $destPath.$img;
			
			$this->image_lib->initialize($resize);
			echo $img;
			
			if(!$this->image_lib->resize()){
				echo ' 处理失败';
			}else{
				echo ' 处理成果';
			}
			echo '<br/>';
		}
		
	}
	
	
	/**
	 * 首页
	 */
	public function index()
	{
        
        
        $this->load->helper("file");
        
		$tables = $this->db->list_tables();

        
        $tables = $this->db->list_tables();
        
        $tableName = $this->input->get('table');

        
        foreach($tables as $table){
        	
        	if($tableName && $table != $tableName){
        		continue;
        	}
        	
        	
        	if(preg_match('/^sp_plan\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	if(preg_match('/^sp_plan_detail\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	
        	if(preg_match('/^sp_push_chat\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	if(preg_match('/^sp_email\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	if(preg_match('/^sp_pm_message\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	if(preg_match('/^sp_admin_pm\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	
        	if(preg_match('/^sp_hp_pub\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	if(preg_match('/^sp_hp_batch\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	if(preg_match('/^sp_member_color\d+$/i',$table,$match)){
        		continue;
        	}
        	
            $fields = $this->db->field_data($table);
            
            $str = array();
            foreach($fields as $field){
                $str[] = "'{$field->name}' => '{$field->type}'";
            }

            $tempStr = implode(",\n",$str);

            $phpEntity = <<<EOT
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

\$entity = array(
{$tempStr}
);

EOT;


            write_file(APPPATH.'entity/'.$table.'.php', $phpEntity);
            
        }
	}
	
	
	
	/**
	 * 
	 */
	public function splitTablePm(){
		
		$config = $this->load->get_config('split_pm');
		
		foreach($config as $tableIndex) {
			
			$table = <<< EOF
	CREATE TABLE `sp_pm_message{$tableIndex}` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uid` int(8) unsigned NOT NULL DEFAULT '0',
	  `from_uid` int(8) unsigned NOT NULL DEFAULT '0',
	  `site_msgid` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '系统消息id',
	  `msg_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = 用户消息  -1=系统消息 1=站内信',
	  `readed` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1=已读',
	  `msg_direction` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0=接收 1=发送',
	  `title` varchar(200) NOT NULL DEFAULT '',
	  `content` text,
	  `gmt_create` int(10) unsigned NOT NULL DEFAULT '0',
	  `gmt_modify` int(10) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`),
	  KEY `idx_uid` (`uid`,`msg_type`),
	  KEY `idx_ctime` (`gmt_create`),
	  KEY `idx_read` (`uid`,`readed`),
	  KEY `idx_dir` (`msg_direction`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
			
			echo $table.'<br/>';
		}
		
	}
	
	
	/**
	 * 
	 */
	public function splitTableEmail(){
		
		$config = $this->load->get_config('split_email');
		
		foreach($config as $tableIndex) {
			
			$table = <<< EOF
CREATE TABLE `sp_email{$tableIndex}` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `msg_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '-1 后台系统消息 0=用户消息 1=货品匹配信息',
  `uid` int(9) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `retry` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_send` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gmt_create` int(10) unsigned NOT NULL DEFAULT '0',
  `gmt_modify` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_send` (`is_send`),
  KEY `idx_uid` (`msg_type`,`uid`),
  KEY `idx_retry` (`retry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
			
			echo $table.'<br/>';
		}
		
	}
	
	
	
	
	/**
	 * 
	 */
	public function splitTableAdminPm(){
		
		
		$config = $this->load->get_config('split_admin_pm');
		
		foreach($config as $tableIndex) {
			
			//echo "drop table sp_admin_pm{$tableIndex}".';<br/>';
			
			$table = <<< EOF
CREATE TABLE `sp_admin_pm{$tableIndex}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(8) unsigned NOT NULL DEFAULT '0',
  `from_uid` int(8) unsigned NOT NULL DEFAULT '0',
  `site_msgid` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '系统消息id',
  `msg_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '1=主动前台广告消息  2=主动广播消息 3=被动广播消息',
  `readed` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1=已读',
  `msg_direction` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0=接收 1=发送',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text,
  `gmt_create` int(10) unsigned NOT NULL DEFAULT '0',
  `gmt_modify` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`,`msg_type`),
  KEY `idx_ctime` (`gmt_create`),
  KEY `idx_read` (`uid`,`readed`),
  KEY `idx_dir` (`msg_direction`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;
			
			echo $table.'<br/>';
		}
		
		
	}

}
