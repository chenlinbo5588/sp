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
	public function create_attachment_tables(){
		
		$chat_pm = range(0,9);
		
		foreach($chat_pm as $key => $value){
			echo "'{$key}' => {$value},<br/>";
		}
		
		echo '<br/>';
		
		$sql = <<< EOF
CREATE TABLE `sp_attachment{i}` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `orig_name` varchar(150) NOT NULL DEFAULT '',
  `file_name` varchar(150) NOT NULL,
  `file_type` varchar(50) NOT NULL DEFAULT '' COMMENT 'mine type',
  `file_ext` varchar(10) NOT NULL DEFAULT '',
  `file_url` varchar(150) NOT NULL DEFAULT '',
  `raw_name` char(32) NOT NULL DEFAULT '',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '如果是目录，则表示该目录下包含的文件大小的合计',
  `is_image` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `image_width` int(11) unsigned NOT NULL DEFAULT '0',
  `image_height` int(11) unsigned NOT NULL DEFAULT '0',
  `image_type` varchar(20) NOT NULL DEFAULT '',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0 表示不过期 大于0 到期时间戳',
  `upload_from` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '上传来源为后台 0=前台 1=后台',
  `uid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `mod` varchar(20) NOT NULL DEFAULT '',
  `gmt_create` int(10) unsigned NOT NULL DEFAULT '0',
  `gmt_modify` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_expire` (`expire_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



EOF;
		
		
		
		foreach($chat_pm as $p){
			$exexSQL = str_replace('{i}',$p,$sql);
			
			echo $exexSQL;
			//$this->Member_Model->execSQL($exexSQL);
		}
		
	}
	
	
	
	
	
	
	public function get_uid_table(){
		
		$uid = $this->input->get('uid');
		
		$pm = $this->load->get_config('split_pm');
		$push_email = $this->load->get_config('split_push_email');
		$hp_batch = $this->load->get_config('split_hp_batch');
		$hp_pub = $this->load->get_config('split_hp_pub');
		
		$pmHash = new Flexihash();
		$pushEmailHash = new Flexihash();
		$hpBatchHash = new Flexihash();
		$hpPubHash = new Flexihash();
		
		
		$pmHash->addTargets($pm);
		$pushEmailHash->addTargets($push_email);
		$hpBatchHash->addTargets($hp_batch);
		$hpPubHash->addTargets($hp_pub);
		
		echo 'pm_mesage='.$pmHash->lookup($uid);
		echo '<br/>';
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(8) unsigned NOT NULL DEFAULT '0',
  `from_uid` int(8) unsigned NOT NULL DEFAULT '0',
  `site_msgid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '系统消息id',
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

		$pm = $this->load->get_config('split_pm');
		
		
		print_r($pm);
		
		foreach($pm as $p){
			
			$exexSQL = str_replace('{i}',$p,$sql);
			echo $exexSQL;
			//$this->Member_Model->execSQL($exexSQL);
		}
	}
	
	
	
	
	
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
        	
        	
        	if(preg_match('/^sp_attachment\d+$/i',$table,$match)){
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
