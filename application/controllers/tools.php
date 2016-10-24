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
	
	
	public function addImage(){
		
		set_time_limit(0);
		
		$imageFile = file(ROOTPATH.'/pic.txt');
		$curl = new Http_Client;
		//var_dump($curl);
		
		foreach($imageFile as $row){
			$row = str_replace(array("\r\n","\n","\r"),'',$row);
			$fields = explode(',',$row);
			
			$file = ROOTPATH.'/pic_cut/'.$fields[0];
			
			//echo "curl -X POST --form file=@{$file} -d '{\"f\" : \"json\"}' http://192.168.5.100/arcgis/rest/services/duogui/zh/FeatureServer/1/$fields[1]/addAttachment\n";
			
			
			$param = array(
	            'url' => "http://192.168.5.100/arcgis/rest/services/duogui/zh/FeatureServer/1/{$fields[1]}/addAttachment",
	            'timeout' => 60,
	            'method' => 'post',
	            'data' => array(
	            	'f' => 'json',
	            	'attachment' => '@'.$file
	            )
	        );
        	
			$result = $curl->request($param);
			var_dump($result);
			
			$jsonResp = json_decode($result,true);
			
			if($jsonResp['addAttachmentResult']['success'] == 'success'){
				echo "{$fields[1]} {$file} 附件上传  成功  \n";
			}else{
				echo "{$fields[1]} {$file} 附件上传  失败 \n ";
			}
		}
	}
	
	
	public function image_resize(){
		
		set_time_limit(0);
		$config['image_library'] = 'gd2';
		$imageFile = file(ROOTPATH.'/pic.txt');
		
		$this->load->library('image_lib');
		
		foreach($imageFile as $row){
			
			$fields = explode(',',$row);
			$config['source_image'] = ROOTPATH.'/pic/'.$fields[0];
			$size = getimagesize($config['source_image']);
			
			$config['new_image'] = ROOTPATH.'/pic_cut/'.$fields[0];
			$config['maintain_ratio']         = true;
			$config['width']         = 3000;
			$config['height']       = 2000;
			$config['quality']      = 95;
			
			
			if(file_exists($config['source_image'])){
				continue;
			}
			
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			
			//break;
		}
		
		echo 'OK';
		//print_r($imageFile);
		/*
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width']         = 75;
		$config['height']       = 50;
		
		$this->image_lib->resize();
		*/
		
	}
	
	
	public function batch_insert(){
		
		$goodsName = array(
			'卫衣',
			'护腕',
			'护膝',
			'Nike',
			'Adidas',
			'乔丹',
			'匡威',
			'特步',
			'361度',
			'李宁',
			'阿迪王',
			'匹克'
		);
		
		$color = array(
			'黑',
			'灰',
			'银',
			'白',
			'棕',
			'香槟',
			'绿',
			'蓝',
			'紫',
			'红',
			'黄',
			'金',
			'橙',
			'金属金'
		);
		
		$sex = array('0','1');
		
		//2016/9/1 12:16:25
		//2016/9/13 12:16:25
		$timestamp = array(1472703385,time());
		
		$this->load->model('Hp_Recent_Model');
		for($i = 0; $i < 100000; $i++){
			
			/*
			$str = array(
				mt_rand(1,9),
				mt_rand(1,92),
				mt_rand(1,999),
				'00'.mt_rand(0,9)
			);
			
			if($str[1] < 10){
				$str[1] = '0'.$str[1];
			}
			
			if($str[2] < 10){
				$str[2] = '0'.$str[2];
			}
			*/
			$gmtcreate = mt_rand($timestamp[0],$timestamp[1]);
			
			
			//$goodsCode = implode('',$str);
			$goodsCode = random_string('alnum',mt_rand(5,9));
			
			$insert = array(
				'goods_name' => $goodsName[mt_rand(0,(count($goodsName) - 1))],
				'goods_code' => $goodsCode,
				'goods_color' => $color[mt_rand(0,count($color) - 1)],
				'gc_id1' => mt_rand(1,1056),
				'gc_id2' => mt_rand(1,1056),
				'gc_id3' => mt_rand(1,1056),
				'goods_size' => mt_rand(5,48),
				'quantity' => mt_rand(1,10),
				'sex' => mt_rand(0,1),
				'price_max' => mt_rand(0,2000) * mt_rand(0,1),
				'uid' => mt_rand(1,200),
				'date_key' => date("Ymd",$gmtcreate),
				'gmt_create' => $gmtcreate,
				'gmt_modify' => $gmtcreate
			);
			
			//$insert['price_max'] = $insert['price_min'] * 2;
			
			$insert['send_day'] = $gmtcreate + mt_rand(0,3) * 86400;
			$insert['ip'] = $this->input->ip_address();
			
			//$insert['kw'] = $insert['goods_name'].'_'.$insert['goods_code'].'_'.$insert['goods_size'];
			$insert['kw'] = $insert['goods_code'].'#'.$insert['goods_size'];
			//$insert['cnum'] = mt_rand(0,$insert['quantity']);
			
			$sql = $this->db->insert_string($this->Hp_Recent_Model->getTableRealName(),$insert);
			$this->db->query($sql);
			
			$id = $this->db->insert_id();
			if($id){
				echo $id.'<br/>';
			}else{
				
				echo $i.'failed';
			}
		}
		
		
		
	}
	
	
	
	public function hash_code(){
		
		echo base64_encode(md5(mt_rand()));
	}
	
	
	public function com_address(){
		$file = 'address.m.csv.txt';
		
		for($i = 1; $i <= 36; $i++){
			echo "sed /1,\$/p address.csv{$i}.txt >> {$file} <br/>";
		}
		
	}
	
	public function spl_poi(){
		$file = file('poi.csv.txt');
		
		$lines = array();
		
		$i = 1;
		
		$line = array();
		foreach($file as $key => $row){
			if($key > 0 && $key % 1500 == 0){
				file_put_contents("poi.csv{$i}.txt",implode('',$line));
				$i++;
				
				$line = array();
			}
			
			$line[] = $row;
		}
		
		if(!empty($line)){
			file_put_contents("poi.csv{$i}.txt",implode('',$line));
		}
		
	}
	
	public function spl_address(){
		$file = file('address.csv.txt');
		
		$lines = array();
		
		$i = 1;
		
		$line = array();
		foreach($file as $key => $row){
			if($key > 0 && $key % 1500 == 0){
				file_put_contents("address.csv{$i}.txt",implode('',$line));
				$i++;
				
				$line = array();
			}
			
			$line[] = $row;
		}
		
		if(!empty($line)){
			file_put_contents("address.csv{$i}.txt",implode('',$line));
		}
		
	}
	
	public function getxy(){
		$file = file('poi.plist');
		$xyList = array();
		
		$i = 1;
		
		foreach($file as $key => $row){
			$row = str_replace("\r\n","",$row);
			if(trim($row) == '<key>x</key>'){
				$x = str_replace(array('<real>','</real>'),'',trim($file[$key + 1]));
				$y = str_replace(array('<real>','</real>'),'',trim($file[$key + 3]));
				
				$xyList[] = "{$i},{$x},{$y},10";
				$i++;
			}
		}
		
		//print_r($xyList);
		
		file_put_contents("poi.txt",implode("\r\n",$xyList));
	}
	
	public function poi(){
		$output = array();
		
		$output[] = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<array>
EOT;


		//$txtFile = file("4444poi.txt");
		$txtFile = file("poi.m.csv.txt");
		foreach($txtFile as $line){
			
			$line = str_replace("\r\n","",$line);
			$lineDetail = explode(',',$line);

			$output[] = <<< EOT
	<dict>
		<key>attributes</key>
		<dict>
			<key>AREMARK</key>
			<string></string>
			<key>AROADNAME</key>
			<string>孙塘南路综合楼</string>
			<key>AROADNUMBER</key>
			<string>1</string>
			<key>ASUFFIX</key>
			<string>幢</string>
			<key>PLICENSE</key>
			<string></string>
			<key>PNAME</key>
			<string>gxf</string>
			<key>PPHONE</key>
			<string></string>
			<key>PPHOTOS</key>
			<string></string>
		</dict>
		<key>geometry</key>
		<dict>
			<key>spatialReference</key>
			<dict>
				<key>wkid</key>
				<integer>4490</integer>
			</dict>
			<key>x</key>
			<real>{$lineDetail[2]}</real>
			<key>y</key>
			<real>{$lineDetail[1]}</real>
		</dict>
		<key>symbol</key>
		<dict>
			<key>angle</key>
			<real>0.0</real>
			<key>color</key>
			<array>
				<integer>255</integer>
				<integer>255</integer>
				<integer>0</integer>
				<integer>255</integer>
			</array>
			<key>outline</key>
			<dict>
				<key>color</key>
				<array>
					<integer>0</integer>
					<integer>0</integer>
					<integer>0</integer>
					<integer>255</integer>
				</array>
				<key>style</key>
				<string>esriSLSSolid</string>
				<key>type</key>
				<string>esriSLS</string>
				<key>width</key>
				<real>1</real>
			</dict>
			<key>size</key>
			<real>12</real>
			<key>style</key>
			<string>esriSMSCircle</string>
			<key>type</key>
			<string>esriSMS</string>
			<key>xoffset</key>
			<real>0.0</real>
			<key>yoffset</key>
			<real>0.0</real>
		</dict>
	</dict>
EOT;
		}
	
		
		$output[] = <<<EOT
	</array>
</plist>
EOT;


		file_put_contents("poi.plist",implode("\r\n",$output));
		
		
	}
	
	public function address(){
		
		$output = array();
		
		$output[] = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<array>
EOT;


		$txtFile = file("address.m.csv.txt");
		
		//print_r($txtFile);
		foreach($txtFile as $line){
			
			$line = str_replace("\r\n","",$line);
			$lineDetail = explode(',',$line);

			$output[] = <<< EOT
	<dict>
		<key>attributes</key>
		<dict>
			<key>AREMARK</key>
			<string></string>
			<key>AROADNAME</key>
			<string>1</string>
			<key>AROADNUMBER</key>
			<string>8</string>
			<key>ASUFFIX</key>
			<string>号</string>
		</dict>
		<key>geometry</key>
		<dict>
			<key>spatialReference</key>
			<dict>
				<key>wkid</key>
				<integer>4490</integer>
			</dict>
			<key>x</key>
			<real>{$lineDetail[2]}</real>
			<key>y</key>
			<real>{$lineDetail[1]}</real>
		</dict>
		<key>symbol</key>
		<dict>
			<key>angle</key>
			<real>0.0</real>
			<key>color</key>
			<array>
				<integer>0</integer>
				<integer>0</integer>
				<integer>255</integer>
				<integer>255</integer>
			</array>
			<key>outline</key>
			<dict>
				<key>color</key>
				<array>
					<integer>0</integer>
					<integer>0</integer>
					<integer>0</integer>
					<integer>255</integer>
				</array>
				<key>style</key>
				<string>esriSLSSolid</string>
				<key>type</key>
				<string>esriSLS</string>
				<key>width</key>
				<real>1</real>
			</dict>
			<key>size</key>
			<real>12</real>
			<key>style</key>
			<string>esriSMSCircle</string>
			<key>type</key>
			<string>esriSMS</string>
			<key>xoffset</key>
			<real>0.0</real>
			<key>yoffset</key>
			<real>0.0</real>
		</dict>
	</dict>
EOT;
		}
	
		
		$output[] = <<<EOT
	</array>
</plist>
EOT;


		file_put_contents("address.plist",implode("\r\n",$output));
		
		
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
		print_r($list);
		
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
	
	
	
	public function test_sendtext(){
		$pushApi = $this->base_service->getPushObject();
		
		$json = $pushApi->sendText(array(
			'15986867878'
		),random_string('alnum',5),'清风信息系统管理员');
		
		
		
		print_r($json);
	}
	
	
	
	public function update_localpsw(){
		$memberList = $this->Member_Model->getList();
		//var_dump($pushApi);
		
		foreach($memberList as $member){
			$newpassword = $this->encrypt->encode($member['password']);
			$this->Member_Model->update(array(
				'password' => $newpassword
			),array('uid' => $member['uid']));
			
			//$json = $this->->updatePassword($member['uid'],$member['mobile'],$member['password']);
			//print_r($json);
		}
	}
	
	public function update_hxpsw(){
		set_time_limit(0);
		
		$memberList = $this->Member_Model->getList();
		$pushApi = $this->base_service->getPushObject();
		//var_dump($pushApi);
		
		foreach($memberList as $member){
			$psw = $this->encrypt->decode($member['password']);
			$json = $pushApi->updatePassword($member['uid'],$member['mobile'],$psw);
			print_r($json);
			
			sleep(5);
		}
	}
	
	
	public function test_insert_message(){
		
		/*
		set_time_limit(0);
		
		$this->load->library('Message_service');
		
		
		$members = $this->Member_Model->getList();
		
		
		foreach($members as $member){
			for($i = 0; $i < 500;$i++){
				$data = array(
					'title' => random_string('alnum',mt_rand(10,30)),
					'content' => random_string('alnum',mt_rand(80,500)),
					'uid' => $member['uid'],
				);
				
				$insertid = $this->message_service->addSystemPmMessage($data);
			}
			
		}
		*/
	}
	
	
	public function create_member_color(){
		
		$sql = <<< EOF
CREATE TABLE `sp_member_color{i}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `color_name` varchar(10) NOT NULL DEFAULT '',
  `gmt_create` int(10) unsigned NOT NULL DEFAULT '0',
  `gmt_modify` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ucolor` (`uid`,`color_name`),
  KEY `idx_ctime` (`gmt_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

EOF;

		$pm = $this->load->get_config('split_color');
		
		
		print_r($pm);
		
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			$this->Member_Model->execSQL($exexSQL);
		}
		
		
	}
	
	
	
	/**
	 * 用户求货信息发布历史记录表
	 */
	public function create_hp_pub_tables(){
		$sql = <<< EOF
CREATE TABLE `sp_hp_pub{i}` (
  `goods_id` mediumint(10) unsigned NOT NULL,
  `goods_name` varchar(40) NOT NULL DEFAULT '' COMMENT '名称',
  `goods_code` varchar(15) NOT NULL DEFAULT '' COMMENT '用户原始货号',
  `goods_color` varchar(15) NOT NULL DEFAULT '' COMMENT '颜色',
  `gc_id1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '货品一级分类',
  `gc_id2` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '货品二级分类',
  `gc_id3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '货品三级分类',
  `goods_size` float unsigned NOT NULL DEFAULT '0' COMMENT '尺码 纯数字尺码 如 42',
  `goods_csize` varchar(10) NOT NULL DEFAULT '' COMMENT '字母或文字尺码',
  `search_code` varchar(15) NOT NULL DEFAULT '' COMMENT '用于搜索的 去除中划线和下划线 防止分词',
  `kw` varchar(25) NOT NULL DEFAULT '' COMMENT '货号链接上尺寸 成为一个唯一查找的建',
  `quantity` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '数量',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `pub_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0=普通求货 1=批发求货',
  `price_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1=仅自己可见  0=明价求货',
  `price_max` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '期望价格范围',
  `send_zone` varchar(30) NOT NULL DEFAULT '' COMMENT '发货地址',
  `send_day` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '发货时间',
  `uid` int(9) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `qq` varchar(15) NOT NULL DEFAULT '',
  `email` varchar(30) NOT NULL DEFAULT '',
  `mobile` varchar(15) NOT NULL DEFAULT '',
  `date_key` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `gmt_create` int(11) unsigned NOT NULL DEFAULT '0',
  `gmt_modify` int(11) unsigned NOT NULL DEFAULT '0',
  KEY `idx_uid_dk` (`uid`,`date_key`),
  KEY `idx_goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户求货发布表';






EOF;

		$pm = $this->load->get_config('split_hp_pub');
		
		
		print_r($pm);
		
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			$this->Member_Model->execSQL($exexSQL);
		}
		
	}
	
	
	/**
	 * 
	 */
	public function create_push_email_tables(){
		$chat_pm = range(0,9);
		
		foreach($chat_pm as $key => $value){
			echo "'{$key}' => {$value},<br/>";
		}
		
		echo '<br/>';
		
		$sql = <<< EOF
CREATE TABLE `sp_push_email{i}` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;


EOF;

		$pm = $this->load->get_config('split_push_email');
		
		
		print_r($pm);
		
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			$this->Member_Model->execSQL($exexSQL);
		}
		
		
		
	}
	
	/**
	 * 
	 */
	public function create_hp_batch_tables(){
		
		$chat_pm = range(0,29);
		
		foreach($chat_pm as $key => $value){
			echo "'{$key}' => {$value},<br/>";
		}
		
		echo '<br/>';
		
		$sql = <<< EOF
CREATE TABLE `sp_hp_batch{i}` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `action` tinyint(3) unsigned DEFAULT '0' COMMENT '0=标题添加操作 1=刷新操作',
  `batch_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cnt` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_batch` (`batch_id`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;



EOF;

		$pm = $this->load->get_config('split_hp_batch');
		
		
		print_r($pm);
		
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			$this->Member_Model->execSQL($exexSQL);
		}
		
	}
	
	
	
	
	/**
	 * 创建站内聊天信息推送表 ，用户 uid  分表
	 * 
	 * 跑批 
	 */
	public function create_push_chat_tables(){
		
		$sql = <<< EOF
CREATE TABLE `sp_push_chat{i}` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `msg_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '-1 后台系统消息 0=用户消息 1=货品匹配信息',
  `uid` int(9) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `content` text,
  `is_send` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gmt_create` int(10) unsigned NOT NULL DEFAULT '0',
  `gmt_modify` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_send` (`is_send`),
  KEY `idx_uid` (`msg_type`,`uid`),
  KEY `idx_ctime` (`gmt_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

EOF;

		$pm = $this->load->get_config('split_push_chat');
		print_r($pm);
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			$this->Member_Model->execSQL($exexSQL);
		}
		
		
		
	}
	
	
	/**
	 * 创建
	 */
	public function create_lab_tables(){
		
		$sql = <<< EOF
CREATE TABLE `sp_lab{i}` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  `creator` varchar(30) NOT NULL DEFAULT '',
  `updator` varchar(30) NOT NULL DEFAULT '',
  `uid` int(10) unsigned NOT NULL,
  `oid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '构机ID',
  `gmt_create` int(10) unsigned NOT NULL DEFAULT '0',
  `gmt_modify` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_status` (`status`),
  KEY `idx_order` (`displayorder`),
  KEY `idx_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

EOF;

		$pm = $this->load->get_config('split_lab');
		print_r($pm);
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			$this->Member_Model->execSQL($exexSQL);
		}
		
	}
	
	
	
	/**
	 * 
	 */
	public function create_lab_oid_tables(){
		
		$sql = <<< EOF
CREATE TABLE `sp_lab_oid{i}` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `oid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1 表示已退出该机构，后台可用户清理数据',
  `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否用户默认机构',
  `gmt_create` int(10) unsigned NOT NULL,
  `gmt_modify` int(10) unsigned NOT NULL,
  UNIQUE KEY `udx_uid` (`uid`,`oid`),
  KEY `idx_udefault` (`uid`,`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='实验室用户机构表,用于用户存保用户所在组织构机';


EOF;

		$pm = $this->load->get_config('split_orgination');
		print_r($pm);
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			$this->Member_Model->execSQL($exexSQL);
		}
		
		
		
	}
	
	
	
	
	public function get_uid_table(){
		
		$uid = $this->input->get('uid');
		
		$pm = $this->load->get_config('split_pm');
		//$push_chat = $this->load->get_config('split_push_chat');
		$push_email = $this->load->get_config('split_push_email');
		$hp_batch = $this->load->get_config('split_hp_batch');
		$hp_pub = $this->load->get_config('split_hp_pub');
		
		$pmHash = new Flexihash();
		//$pushChatHash = new Flexihash();
		$pushEmailHash = new Flexihash();
		$hpBatchHash = new Flexihash();
		$hpPubHash = new Flexihash();
		
		
		$pmHash->addTargets($pm);
		//$pushChatHash->addTargets($push_chat);
		$pushEmailHash->addTargets($push_email);
		$hpBatchHash->addTargets($hp_batch);
		$hpPubHash->addTargets($hp_pub);
		
		echo 'pm_mesage='.$pmHash->lookup($uid);
		echo '<br/>';
		//echo 'push_chat='.$pushChatHash->lookup($uid);
		echo '<br/>';
		echo 'push_email='.$pushEmailHash->lookup($uid);
		echo '<br/>';
		echo 'hp_batch='.$hpBatchHash->lookup($uid);
		echo '<br/>';
		echo 'hp_pub='.$hpPubHash->lookup($uid);
		
		
		$ar1 = range(0,30);
		$ar2= range(0,15);
		print_r($ar1);
		$ar1 = array_merge($ar1,$ar2);
		print_r($ar2);
	}
	
	
	
	/**
	 * 显示切分方数组
	 */
	public function show_split(){
		$range = range(0,99);
		
		foreach($range as $key => $value){
			echo "'{$key}' => {$value},<br/>";
		}
		
		echo '<br/>';
		
	}
	
	public function create_pm_tables(){
		
		
		$sql = <<< EOF
CREATE TABLE `sp_pm_message{i}` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(8) unsigned NOT NULL DEFAULT '0',
  `from_uid` int(8) unsigned NOT NULL DEFAULT '0',
  `site_msgid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '系统消息id',
  `msg_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = 用户消息  -1=系统消息 1=货品匹配站内信',
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

		$pm = $this->load->get_config('split_pm');
		
		
		print_r($pm);
		
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			$this->Member_Model->execSQL($exexSQL);
		}
	}
	
	
	
	/**
	 * 暂时不分表， 等用户起来了以后再分表， 因为库存更新频率相对较低
	 */
	 
	/*
	public function create_member_inventory(){
		
		
		$sql = <<< EOF
CREATE TABLE `sp_member_inventory{i}` (
  `uid` int(9) unsigned NOT NULL DEFAULT '0',
  `slot_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '货柜号',
  `goods_list` text NOT NULL COMMENT '名称',
  `enable` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '库存可用性 0=不可用',
  `kw` text NOT NULL COMMENT '关键字列表',
  `kw_price` text NOT NULL COMMENT '关键字对应的最低价列表 一一对应',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `gmt_modify` int(11) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `idx_slot` (`uid`,`slot_id`),
  KEY `idx_enable` (`enable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户库存表';


EOF;

		$pm = $this->load->get_config('split_member_inventory');
		
		
		print_r($pm);
		
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			$this->Member_Model->execSQL($exexSQL);
		}
	}
	
	*/
	
	
	public function test_hash(){
		$this->load->library('Flexihash');
		
		
		
		/*
		$pm = array(
	'pm_1' => 1,
	'auth_2' => 2,
	'auth_3' => 3,
	'auth_4' => 4,
	'auth_5' => 5,
	'auth_6' => 6,
	'auth_7' => 7,
	'auth_8' => 8,
	'auth_9' => 9,
	'auth_10' => 10);
		*/
		
		
		$pm = range(0,29);
		
		foreach($pm as $key => $value){
			echo "'{$key}' => {$value},<br/>";
		}
		
		
		print_r($pm);
		echo '<br/>';
		
		
	
		$this->flexihash->addTargets($pm);


		
		
		$memeberList = $this->Member_Model->getList();
		
		
		foreach($memeberList as $member){
			$whichTable1 = $this->flexihash->lookup($member['uid']);
			
			echo $member['uid'] . '='.$whichTable1.'<br/>';
			
			
		}
		
		
		$this->flexihash->addTargets(array(
			30
		));
		
		foreach($memeberList as $member){
			$whichTable1 = $this->flexihash->lookup($member['uid']);
			echo $member['uid'] . '='.$whichTable1.'<br/>';
		}
	}
	
	public function test_emailconfirm(){
		$this->load->library('Message_service');
		
		$this->message_service->initEmail($this->_siteSetting);
		
		//$this->message_service->sendEmailConfirm('104071152@qq.com','http://wwww.baidu.com');
		
		/*
		for($i = 0; $i < 3;$i++){
			$this->message_service->sendEmail('104071152@qq.com',random_string('alnum',mt_rand(5,9)),random_string('alnum',mt_rand(5,9)));
		}
		*/
	}
	
	public function test_gethxpsw(){
		$memberList = $this->Member_Model->getList();
		
		$this->load->model('Huanxin_Model');
		
		foreach($memberList as $member){
			$psw = $this->encrypt->decode($member['password']);
			echo "{$member['msgid']}={$member['username']}={$member['mobile']}={$psw}=".md5(config_item('encryption_key').$psw)."\n";
			
			/*
			$this->Huanxin_Model->_add(array(
				'uid' => $member['uid'],
				'username' => $member['mobile'],
				'nickname' => $member['nickname'],
				'password' => md5(config_item('encryption_key').$psw)
			),true);
			*/
		}
		
	}
	
	public function test_updatepsw(){
		$pushApi = $this->base_service->getPushObject();
		$json = $pushApi->updatePassword(120,'15689523612','654321');
		
		print_r($json);
	}
	
	
	
	
	
	public function test_huanxin(){
		$memberList = $this->Member_Model->getList();
		$pushApi = $this->base_service->getPushObject();
		//var_dump($pushApi);
		
		foreach($memberList as $member){
			//print_r($member);
			//$json = $pushApi->createId($member['uid'],$member['mobile'],$member['nickname'],$member['password']);
			//print_r($json);
		}
		
	}
	
	
	public function test_yunxin(){
		$this->load->config('site');
		$this->load->library(array('Yunxin_api'));
		
		//foreach()
		$resp = $this->yunxin_api->createId('15689895424','15689895424');
		
		var_dump($resp);
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
        
        
        $this->load->helper("file");
        
		$tables = $this->db->list_tables();

        
        foreach($tables as $table){
        	if(preg_match('/^sp_push_chat\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	if(preg_match('/^sp_push_email\d+$/i',$table,$match)){
        		continue;
        	}
        	
        	if(preg_match('/^sp_pm_message\d+$/i',$table,$match)){
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
        	
        	if(preg_match('/^sp_lab\d+$/i',$table,$match)){
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
	
	
	

}
