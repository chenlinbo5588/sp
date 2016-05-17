<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Captcha extends Ydzj_Controller {
	
    public function __construct(){
		parent::__construct();
    }
    
    public function index()
    {
        $this->load->helper('captcha');
        
        
        
		$vals = array(
	        'img_path'      => ROOTPATH.'/static/img/captcha/',
	        'img_url'       => base_url('/static/img/captcha/'),
	        'img_width'     => $this->input->get('w') ? $this->input->get('w') : 150,
        	'img_height'    => $this->input->get('h') ? $this->input->get('h') : 30,
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
		
		/*
		 * 使用session 存储了 不使用 db 存储
		$this->load->model('Captcha_Model');
		
		//清空数据
		$this->Captcha_Model->deleteByCondition(array(
			'where' => array('captcha_time <= ' => $this->input->server('REQUEST_TIME') - 7200),
			'limit' => 10
		));
		
		$this->Captcha_Model->_add($data);
		*/
		
		//使用 Session 的存储方式,防止ip 取值不正确导致验证码错误的问题
		$this->session->set_userdata('captcha', $cap['word']);
		
		header('Pragma:no-cache');
		header('Cache-control:no-cache');
		header("ContentType: image/jpeg");
		
		echo file_get_contents(ROOTPATH.'/static/img/captcha/'.$cap['filename']);
    }

}
