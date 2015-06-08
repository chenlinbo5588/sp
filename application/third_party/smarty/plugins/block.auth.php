<?php
/**
 * Smarty plugin to priv blocks
 *
 * @package Smarty
 * @subpackage PluginsBlock
 */

/**
 * Smarty {auth}{/auth} block plugin
 *
 * Type:     block function<br>
 * Name:     textformat<br>
 * Purpose:  format text a certain way with preset styles
 *           or custom wrap/indent settings<br>
 * Params:
 * <pre>
 * - name         - string (user+index)
 * </pre>
 *
 * @link http://www.smarty.net/manual/en/language.function.textformat.php {textformat}
 *       (Smarty online manual)
 * @param array                    $params   parameters
 * @param string                   $content  contents of the block
 * @param Smarty_Internal_Template $template template object
 * @param boolean                  &$repeat  repeat flag
 * @return string content re-formatted
 * @author Monte Ohrt <monte at ohrt dot com>
 */
function smarty_block_auth($params, $content, $template, &$repeat)
{
    if (is_null($content)) {
        return;
    }
    if(empty($params['name'])){
        return $content;
    }else{
        
        if(strpos($params['name'],'+') !== false){
            $url = config_item('controller_trigger').'='.substr($params['name'],0,strpos($params['name'],'+')).'&'.config_item('function_trigger').'='.substr($params['name'],strpos($params['name'],'+') + 1);
        }else{
            $url = config_item('controller_trigger').'='.$params['name'].'&'.config_item('function_trigger').'=index';
        }
        
        $CI = &get_instance();
        $user = $CI->getUserProfile();
        $authList = $CI->getAuthList();
        if($user['id'] != 1 && !in_array(md5($url),$authList)){
            return '';
        }else{
            return $content;
        }
    }
}

?>