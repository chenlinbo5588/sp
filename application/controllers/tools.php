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
