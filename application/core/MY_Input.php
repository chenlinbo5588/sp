<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Input extends CI_Input {
	private $_inApp = false;
	private $_allowInterval = 0.3;

	public function __construct(){
		parent::__construct();
		
		if($this->server('HTTP_APP_SP')){
			$this->_inApp = true;
		}
	}
	
	public function get_cookie($index = NULL, $xss_clean = NULL)
	{
		$prefix = config_item('cookie_prefix');
		return $this->_fetch_from_array($_COOKIE, $prefix.$index, $xss_clean);
	}
	
	public function is_flash_request(){
		
		if(strpos($_SERVER['HTTP_X_REQUESTED_WITH'],'ShockwaveFlash') !== false){
			return true;
		}
		
		return false;
	}
	
	
	
	/**
	 * 
	 */
	protected function _sanitize_globals()
	{
		parent::_sanitize_globals();
		
		if($this->is_cli_request()){
			return;
		}
		
		if($this->is_ajax_request() || $this->is_flash_request()){
			$this->_allowInterval = 2;
		}
		
		/*
		if(!$this->is_ajax_request() && !$this->is_cli_request()){
			//@TODO 优化 ，需要更加安全的逻辑
			if(strpos($_SERVER['HTTP_X_REQUESTED_WITH'],'ShockwaveFlash') === false){
				$this->begin_protect();
			}
		}
		*/
	}
	
	
	public function begin_protect(){
		$securitySetting = config_item('security');
		
		if(is_string($securitySetting['attackevasive'])) {
			$attackevasive_tmp = explode('|', $securitySetting['attackevasive']);
			$attackevasive = 0;
			foreach($attackevasive_tmp AS $key => $value) {
				$attackevasive += intval($value);
			}
			unset($attackevasive_tmp);
		} else {
			$attackevasive = $securitySetting['attackevasive'];
		}
		
		$encryptObject = & load_class('Encrypt');
		$lastVisitKey = $securitySetting['visitkey'];
		$visitCode = $securitySetting['visitcode'];
		
		//echo $this->_lastVisitKey;
		
		$nowstamp = microtime(TRUE);
		
		$lastrequest = $this->get_cookie($lastVisitKey);
		$lastrequest = !empty($lastrequest) ? $encryptObject->decode($lastrequest,$securitySetting['authkey']) : '';
		
		if($attackevasive & 1 || $attackevasive & 4) {
			$this->set_cookie($lastVisitKey,$encryptObject->encode($nowstamp,$securitySetting['authkey']), 86400);
		}
		
		if($attackevasive & 1) {
			if(($nowstamp - $lastrequest) < $this->_allowInterval) {
				$this->securitymessage('attackevasive_1_subject', 'attackevasive_1_message');
			}
		}
		
		if(($attackevasive & 2) && ($_SERVER['HTTP_X_FORWARDED_FOR'] ||
			$_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] ||
			$_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_CACHE_INFO'] ||
			$_SERVER['HTTP_PROXY_CONNECTION'])) {
				$this->securitymessage('attackevasive_2_subject', 'attackevasive_2_message', FALSE);
		}
		
		if($attackevasive & 4) {
			//10 分钟没有访问页面
			if(empty($lastrequest) || $nowstamp - $lastrequest > 600) {
				$this->securitymessage('attackevasive_4_subject', 'attackevasive_4_message');
			}
		}
		
		if($attackevasive & 8) {
			//echo $encryptObject->decode($this->get_cookie($visitCode), $securitySetting['authkey']);
			list($visitcode, $visitcheck, $visittime) = explode('|', $encryptObject->decode($this->get_cookie($visitCode), $securitySetting['authkey']));
			//var_dump($visitcode, $visitcheck, $visittime);
			//echo $nowstamp - $visittime;
			
			if($visitcode && !$visitcheck && ($nowstamp - $visittime) > 14400 ) {
				if(empty($_POST['secqsubmit']) || ($visitcode != md5($_POST['answer']))) {
					$answer = 0;
					$question = '';
					for ($i = 0; $i< rand(2, 4); $i ++) {
						$r = rand(1, 20);
						$question .= $question ? ' + '.$r : $r;
						$answer += $r;
					}
					$question .= ' = ?';
					
					$this->set_cookie($visitCode,$encryptObject->encode(md5($answer).'|0|'.$nowstamp,$securitySetting['authkey']), 86400);
					//dsetcookie('visitcode', authcode(md5($answer).'|0|'.TIMESTAMP, 'ENCODE'), TIMESTAMP + 816400, 1, true);
					$this->securitymessage($question, '<input type="text" name="answer" size="8" maxlength="150" /><input type="submit" name="secqsubmit" class="button" value="确定" />', FALSE, TRUE);
				} else {
					$this->set_cookie($visitCode,$encryptObject->encode($visitcode.'|1|'.$nowstamp,$securitySetting['authkey']), 86400);
					//dsetcookie('visitcode', authcode($visitcode.'|1|'.TIMESTAMP, 'ENCODE'), TIMESTAMP + 816400, 1, true);
				}
			}else{
				$this->set_cookie($visitCode,$encryptObject->encode($visitcode.'|0|'.$nowstamp,$securitySetting['authkey']), 86400);
			}
		}
	}
	
	
	public function securitymessage($subject, $message, $reload = TRUE, $form = FALSE) {
		
		$scuritylang = array(
			'attackevasive_1_subject' => '频繁刷新限制',
			'attackevasive_1_message' => '您访问本站速度过快或者刷新间隔时间小于1秒！请等待页面自动跳转 ...',
			'attackevasive_2_subject' => '代理服务器访问限制',
			'attackevasive_2_message' => '本站现在限制使用代理服务器访问，请去除您的代理设置，直接访问本站。',
			'attackevasive_4_subject' => '页面重载开启',
			'attackevasive_4_message' => '欢迎光临本站，页面正在重新载入，请稍候 ...'
		);
	
		$subject = $scuritylang[$subject] ? $scuritylang[$subject] : $subject;
		$message = $scuritylang[$message] ? $scuritylang[$message] : $message;
		if($this->is_ajax_request()) {
			$this->security_ajaxshowheader();
			//echo '<div id="attackevasive_1" class="popupmenu_option"><b style="font-size: 16px">'.$subject.'</b><br /><br />'.$message.'</div>';
			echo json_encode(array('code' => 500,'message' =>$message, 'data' => array()));
			//$this->security_ajaxshowfooter();
		} else {
			echo '<html>';
			echo '<head>';
			echo '<title>'.$subject.'</title>';
			echo '</head>';
			echo '<body bgcolor="#FFFFFF">';
			if($reload) {
				echo '<script type="text/javascript">';
				echo 'function reload() {';
				echo '	document.location.reload();';
				echo '}';
				echo 'setTimeout("reload()", 1001);';
				echo '</script>';
			}
			if($form) {
				echo '<form action="'.$this->server('PHP_SELF').'" method="post" autocomplete="off">';
			}
			echo '<table cellpadding="0" cellspacing="0" border="0" width="700" align="center" height="85%">';
			echo '  <tr align="center" valign="middle">';
			echo '    <td>';
			echo '    <table cellpadding="10" cellspacing="0" border="0" width="80%" align="center" style="font-family: Verdana, Tahoma; color: #666666; font-size: 11px">';
			echo '    <tr>';
			echo '      <td valign="middle" align="center" bgcolor="#EBEBEB">';
			echo '     	<br /><br /> <b style="font-size: 16px">'.$subject.'</b> <br /><br />';
			echo $message;
			echo '        <br /><br />';
			echo '      </td>';
			echo '    </tr>';
			echo '    </table>';
			echo '    </td>';
			echo '  </tr>';
			echo '</table>';
			if($form) {
				echo '</form>';
			}
			echo '</body>';
			echo '</html>';
		}
		exit();
	}
	
	public function security_ajaxshowheader() {
		$charset = config_item('charset');
		ob_end_clean();
		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
		header("Content-type: application/json");
		
		/*echo "<?xml version=\"1.0\" encoding=\"".$charset."\"?>\n<root><![CDATA[";*/
	}
	
	/*
	public function security_ajaxshowfooter() {
		echo ']]></root>';
		exit();
	}
	*/
}
