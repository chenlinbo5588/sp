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
function smarty_block_block_html($params, $content, $template, &$repeat)
{
    if (is_null($content)) {
        return;
    }
    if(empty($params['id'])){
        return $content;
    }else{
        
        $CI = &get_instance();
        $CI->load->model('Block_Model');
        
        $blockInfo = $CI->Block_Model->getBlockInfoById($params['id']);
        return $blockInfo['web_html'];
    }
}

?>