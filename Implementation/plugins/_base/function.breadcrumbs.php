<?php
/**
 * Smarty form function for element <input type=text>
 *
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Ivan Khmurchik
 * Remark: separator set not necessarily
 */

function smarty_function_breadcrumbs($params, &$smarty)
{
	Warecorp::addTranslation('/plugins/function.breadcrumbs.php.xml');
    //require_once $smarty->_get_plugin_filepath('modifier','longwords');
    require_once $smarty->_get_plugin_filepath('modifier','truncate');
    // array verify
    if (!isset($params['_array'])) return Warecorp::t('Array not found.');
    else $breadcrumb_array = $params['_array'];
    // separator verify

    //disabled by komarovski
    /*if (!isset($params['separator'])) $breadcrumb_separator = '&raquo;';
    else $breadcrumb_separator = $params['separator'];*/

    // cycle shaping the links
    foreach ($breadcrumb_array as $_key => $_value)
    {
        //disabled by komarovski
        /*if (!empty($_key) && isset($str)) $str .= $breadcrumb_separator;*/

        //$_key = trim($_key);
        if (!empty($_value)){
            //$str .= '<li><a href=' .$_value. ' >' .smarty_modifier_longwords($_key, 25). '</a></li>';
            $str .= '<li class="znFloatLeft"><a href=' .$_value. ' >' .smarty_modifier_truncate($_key, 40). '</a></li>';
        }else{
            //$str .= '<li>' .smarty_modifier_longwords($_key, 25). '</li>';
            $str .= '<li class="znFloatLeft">' .smarty_modifier_truncate($_key, 40). '</li>';
        }
    }

    return $str;
}
?>
