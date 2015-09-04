<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Captcha extends Ydzj_Controller {
	
    public function __construct(){
		parent::__construct();
    }
    
    public function index()
    {
        $this->load->helper('captcha');
		$vals = array(
	        'img_path'      => ROOTPATH.'/img/captcha/',
	        'img_url'       => base_url('/img/captcha/'),
	        'img_width'     => 150,
        	'img_height'    => 30,
        	'expiration'    => 180,
        	'word_length'   => 4,
        	'font_size'     => 16,
        	'font_path'     => SYSDIR.'/fonts/arial.ttf',
		);

		$cap = create_captcha($vals);
		$data = array(
		        'captcha_time'  => $cap['time'],
		        'ip_address'    => $this->input->ip_address(),
		        'word'          => $cap['word']
		);
		
		$this->load->model('Captcha_Model');
		$this->Captcha_Model->_add($data);
		
		
		header('Pragma:no-cache');
		header('Cache-control:no-cache');
		header("ContentType: image/jpeg");
		
		echo file_get_contents(ROOTPATH.'/img/captcha/'.$cap['filename']);
    }

}
