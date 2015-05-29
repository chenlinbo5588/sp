<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 授权
 */
class Ticket extends MY_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Open_Ticket_Model');
    }
    
    /**
     * 微信服务器发送给服务自身的事件推送（如取消授权通知，Ticket推送等）。
     * 此时，消息XML体中没有ToUserName字段，而是AppId字段，即公众号服务的AppId。
     * 这种系统事件推送通知（现在包括推送component_verify_ticket协议和推送取消授权通知），
     * 服务开发者收到后也需进行解密，接收到后只需直接返回字符串“success” 
     *  测试
     *  ?signature=16fcf86d8da1559e80be70953f1705bc1f0f6e18&timestamp=1417594804&nonce=1799460615&encrypt_type=aes&msg_signature=03adcb4e5db2fa16a34fd1d20dea73631ad99bec
     */
    public function index()
    {
        $weixinConfig = config_item('weixin_open');
        $this->load->file(WEIXIN_PATH.'weixin_open_basic.php');
        $basicApiObj = new Weixin_Open_Basic($weixinConfig);
        
        $this->Open_Ticket_Model->deleteByWhere(array(
        	'gmt_create <=' => time() - 86400
        ));
        
        if($basicApiObj->checkSignature()){
            //file_put_contents("post{$this->reqtime}.txt",print_r($_POST,true));
            //file_put_contents("post{$this->reqtime}.txt",print_r($GLOBALS,true),FILE_APPEND);
            
        	$msgEncryptObj = new WXBizMsgCrypt($weixinConfig['token'],$weixinConfig['EncodingAESKey'],$weixinConfig['appid']);
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            /**
             * for test
             
            $postStr = '<xml>
    <AppId><![CDATA[wx67eecf008bac55a9]]></AppId>
    <Encrypt><![CDATA[KBjOiLxlb8klbnIYick3z6pP/OqPXQoMeaa+6K97DTr/JrgyD5a65i/vkNw0BQvjsmjw+lmSbey7jlvbfQzqv5VyvSXngwNj37vFyi36oH9DmLE5mmlLNK+d8gNKl+5VYpMjwPU8Ssboxj8Rq4y8LJRZAwE0jI+SnkU/NdVwRx8BMoEnIJH8o0tQlsSaESx2F5PZfLcT89H5NOu7zZARE2sbXazSWkJzkqiz6mKtipYJ6Jq63Ph+2wiA8anAMCZ5c/Tt01znjkYpQStHpN5dr3vtCPCACvkTsiWsBCAuAl8hy3cGavunNGSJD5Sbx5y3ltNnRvRQCnXQidTa1rR+MPaUcA5QXc9Um0xszxv+Sky1QHoTfNi2YTueEt+pWelA1BZRMUXKz3YnvgHh/H6IgADrJ8ZVw7NIINKTruvhRwLiypCzvrd4UCz7JM+d5VyiWCbW8+6MThhVPNZQMtrTFA==]]></Encrypt>
</xml>';
            */
            $message = '';
            if(!$postStr){
                $postStr = "<xml></xml>";
            }
            
            $returnCode = $msgEncryptObj->decryptMsg($_GET['msg_signature'],$_GET['timestamp'],$_GET['nonce'],$postStr,$message);
            if($returnCode == ErrorCode::$OK){
                /** 
                 * $message 如下
                 * <xml><AppId><![CDATA[wx67eecf008bac55a9]]></AppId>
<CreateTime>1417594804</CreateTime>
<InfoType><![CDATA[component_verify_ticket]]></InfoType>
<ComponentVerifyTicket><![CDATA[wHm3K6JgcL6OVnp66C4vQGLcI4ZXt1z_P_wr99iCJM14lgjZQuynLRa0rQXir7qD13Ic5XoWGfb2pd2ACSSDrQ]]></ComponentVerifyTicket>
</xml> 
                 */
                
                $rs = $basicApiObj->parseServerTokenPush($message);
                if( 0 === $rs['code'] ){
                    // @todo delete history token
                    $row = $this->Open_Ticket_Model->add(array('access_token' => $rs['token'],'gmt_create' => $rs['createtime']));
                    if($row){
                        echo 'success';
                    }else{
                        echo "failed";
                    }
                }else{
                    echo "failed";
                }
                
            }else{
                $basicApiObj->log(config_item('log_threshold'));
                echo "failed";
            }
        }else{
            echo "failed";
        }
    }
    
}
