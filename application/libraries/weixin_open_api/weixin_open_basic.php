<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Weixin_Open_Basic extends Weixin_Open_Api {
    
    public function __construct($config){
        parent::__construct($config);
    }
    
    /**
     * 获取服务器推送的 token
     * @param type $xml 
     * 
     * sample
     * 
     * <xml><AppId><![CDATA[wx67eecf008bac55a9]]></AppId>
        <CreateTime>1417594804</CreateTime>
        <InfoType><![CDATA[component_verify_ticket]]></InfoType>
        <ComponentVerifyTicket><![CDATA[wHm3K6JgcL6OVnp66C4vQGLcI4ZXt1z_P_wr99iCJM14lgjZQuynLRa0rQXir7qD13Ic5XoWGfb2pd2ACSSDrQ]]></ComponentVerifyTicket>
        </xml>
     */
    public function parseServerTokenPush($xmlstr){
        
        try {
			$xml = new DOMDocument();
			$xml->loadXML($xmlstr);
			$array_e = $xml->getElementsByTagName('CreateTime');
			$array_a = $xml->getElementsByTagName('ComponentVerifyTicket');
			$time = $array_e->item(0)->nodeValue;
			$token = $array_a->item(0)->nodeValue;
			return array('code' => 0,'token' => $token, 'createtime' => $time);
		} catch (Exception $e) {
			//print $e . "\n";
			return array('code' => ErrorCode::$ParseXmlError, 'token' => null, 'createtime' =>  null);
		}
        
    }
    
}
