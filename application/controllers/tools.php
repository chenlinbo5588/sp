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
		
		$sex = array('M','F');
		
		//2016/9/1 12:16:25
		//2016/9/13 12:16:25
		$timestamp = array(1472703385,time());
		
		$this->load->model('Goods_Recent_Model');
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
				'goods_size' => mt_rand(10,48),
				'goods_color' => $color[mt_rand(0,count($color) - 1)],
				'quantity' => mt_rand(1,10),
				'sex' => $sex[mt_rand(0,count($sex) - 1)],
				'price_min' => mt_rand(0,2000) * mt_rand(0,1),
				'uid' => mt_rand(1,200),
				'date_key' => date("Ymd",$gmtcreate),
				'gmt_create' => $gmtcreate,
				'gmt_modify' => $gmtcreate
			);
			
			$insert['price_max'] = $insert['price_min'] * 2;
			
			$insert['send_day'] = $gmtcreate + mt_rand(0,3) * 86400;
			$insert['ip'] = $this->input->ip_address();
			
			//$insert['kw'] = $insert['goods_name'].'_'.$insert['goods_code'].'_'.$insert['goods_size'];
			$insert['cnum'] = mt_rand(0,$insert['quantity']);
			
			$sql = $this->db->insert_string($this->Goods_Recent_Model->getTableRealName(),$insert);
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
			'15689523612'
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
	
	public function test_hash(){
		$this->load->library('Flexihash');
		
		
		$pm = array(
	'auth_1' => 1,
	'auth_2' => 2,
	'auth_3' => 3,
	'auth_4' => 4,
	'auth_5' => 5,
	'auth_6' => 6,
	'auth_7' => 7,
	'auth_8' => 8,
	'auth_9' => 9,
	'auth_10' => 10);
	
	
		$this->flexihash->addTargets($pm);



		
		$whichTable1 = $constHash->lookup(200);
		/*$whichTable2 = $constHash->lookup(201);
		$whichTable3 = $constHash->lookup(202);
		$whichTable4 = $constHash->lookup(203);
		$whichTable5 = $constHash->lookup(204);
		$whichTable6 = $constHash->lookup(205);
		$whichTable7 = $constHash->lookup(206);
		$whichTable8 = $constHash->lookup(800);
		*/
		
		echo $whichTable1;
		echo "<br/>";
		
		echo $whichTable2;
		echo "<br/>";
		echo $whichTable3;
		echo "<br/>";
		echo $whichTable4;
		echo "<br/>";
		echo $whichTable5;
		echo "<br/>";
		echo $whichTable6;
		echo "<br/>";
		echo $whichTable7;
		echo "<br/>";
		echo $whichTable8;
		
		echo "<br/>";
		
		
	}
	
	public function test_emailconfirm(){
		$this->load->library('Message_service');
		
		$this->message_service->initEmail($this->_siteSetting);
		$this->message_service->sendEmailConfirm('104071152@qq.com','http://wwww.baidu.com');
		
	}
	
	public function test_gethxpsw(){
		$memberList = $this->Member_Model->getList();
		
		$this->load->model('Huanxin_Model');
		
		foreach($memberList as $member){
			$psw = $this->encrypt->decode($member['password']);
			echo "{$member['mobile']}={$psw}=".md5(config_item('encryption_key').$psw)."\n";
			
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
