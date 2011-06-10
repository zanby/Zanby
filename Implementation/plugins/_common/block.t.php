<?php
function smarty_block_t($params, $content, &$smarty)
{
    if ( $content !== null ) {
        $params['key'] = ( !isset($params['key']) || '' == $params['key'] ) ? $content : $params['key'];
        if ( isset($_SERVER['tBlocksStack']) && is_array($_SERVER['tBlocksStack']) && sizeof($_SERVER['tBlocksStack']) != 0 ) {
            $stack = array_pop($_SERVER['tBlocksStack']); 
            if ( !is_array($stack) || sizeof($stack) == 0 ) $stack = null;
        } else {
            $stack = null;
        }
        //$strReturn = Warecorp::t($params['key'], $content, $stack);
        
        $strReturn = Warecorp_Translate::translate($content, $stack);
        if ( isset($params['var']) && !empty($params['var']) ) {
            $smarty->assign($params['var'], $strReturn);
            return '';
        } else return $strReturn;
    } else {
        if ( !isset($_SERVER['tBlocksStack']) ) $_SERVER['tBlocksStack'] = array();
        $_SERVER['tBlocksStack'][] = array();
    }
}