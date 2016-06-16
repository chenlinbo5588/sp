<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public function __construct(){
		parent::__construct();

	}
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	
	public function genfile(){
		
		if(!$this->input->is_cli_request()){
			die(0);
		}
		
		$_siteNum = intval($_SERVER['argv'][3]);
		if(empty($_siteNum)){
			die(0);
		}
		
		$destPath = SMARTY_TPL_PATH.'ydzj/templates/index/';
		
		$siteConfig = array(
			's{PAGENUM}.txcf188.com',
			's{PAGENUM}.ddy168.com',
			's{PAGENUM}.ddyzbj.com',
			's{PAGENUM}.ddyzbs.com',
		);
		
		$configTemplate = "'{TPLNAME}' => array(
		'registeOkText' => 'registerOk_text1',
		'jumUrlType' => 'website',
		'rules' => array('username','mobile','mobile_auth_code'),
	),";
		
		$configValue = array();
		
		$mainTple = '';
		
		foreach($siteConfig as $key => $site){
			
			$tplName = str_replace('{PAGENUM}',$_siteNum,$site);
			
			if($key == 0){
				$mainTple = $tplName;
			}
			
			$configValue[] = str_replace('{TPLNAME}',$tplName,$configTemplate);
			
			if(file_exists($destPath.$tplName.'.tpl')){
				echo $destPath.$tplName.".tpl  已经存在\n";
			}else{
				$writeSize = file_put_contents($destPath.$tplName.'.tpl',"{include file=\"{$mainTple}.tpl\"}");
				
				if($writeSize) {
					echo $destPath.$tplName.".tpl  创建成功\n";
				}else{
					echo $destPath.$tplName.".tpl  创建失败\n";
				}
			}
			
		}
		
		echo "\n";
		
		foreach($configValue as $aconfig){
			echo $aconfig ."\n";
		}
		
	}
}
