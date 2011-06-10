<?php
function smarty_block_stringbuilder($params, $content, &$smarty)
{
    if ( $content !== null ) {
        if ( !isset($smarty->StringBuilders) ) $smarty->StringBuilders = array();
        if ( isset($params['vars_count']) && isset($params['iteration']) ) {
            if ( $params['iteration'] <= $params['vars_count'] ) {
                $params['var'] = $params['var'].$params['iteration'];
            } else {
                $del = floor($params['iteration']/$params['vars_count']);
                $i = $params['iteration'] - $params['vars_count']*$del;
                if ( $i == 0 ) $params['var'] = $params['var'].$params['vars_count'];
                else $params['var'] = $params['var'].$i;
            }
        }
        switch ( strtolower($params['mode']) ) {
            case "set" :
                $smarty->StringBuilders[$params['var']] = $content;
                break;
            case "append" :
                $smarty->StringBuilders[$params['var']] = ( !isset($smarty->StringBuilders[$params['var']]) ) ? $content :  $smarty->StringBuilders[$params['var']].$content;
                break;
            case "get" :
                return ( !isset($smarty->StringBuilders[$params['var']]) ) ? "" : $smarty->StringBuilders[$params['var']];
                break;
            case "flush" :
                unset($smarty->StringBuilders[$params['var']]);
                break;
            case "get_flush" :
                $tmp = ( !isset($smarty->StringBuilders[$params['var']]) ) ? "" : $smarty->StringBuilders[$params['var']];
                unset($smarty->StringBuilders[$params['var']]);
                return $tmp;
                break;
        }
    }
}