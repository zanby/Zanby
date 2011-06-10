<?php

/**
 * Smarty form function for element <input type=text>
 *
 * @package Smarty
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Ivan Meleshko
 * @author Dmitry Kostikov
 *
 * @todo multiple selected feature
 */

function smarty_function_form_select($params, &$smarty)
{
	Warecorp::addTranslation('/plugins/function.form_select.php.xml');
    require_once $smarty->_get_plugin_filepath('modifier','escape');
    // form object verify
    if (!isset($smarty->_tpl_vars['wf_form_object'])) return Warecorp::t('Form object not found.');
    $form = $smarty->_tpl_vars['wf_form_object'];

    // element name verify
    if (!isset($params['name'])) return Warecorp::t('Name for field not found.');
	
	// set element id
	if (!isset($params['id'])) $params['id'] = $params['name'];
    
    if (isset($params['escape'])) {
        $escape = $params['escape'];
        unset($params['escape']);
    } else {
    	$escape = "";
    }

    // element default value
    $selected = null;
    if (isset($form->_defaults[$params['name']])) $selected = $form->_defaults[$params['name']];
    if (isset($params['selected'])) $selected = $params['selected'];
    if (isset($params['defaults'])) $selected = $params['defaults'];

    $divWidth = null;
    $divClass = 'znbFormOuter';
    if ( isset($params['style']) ) {
    	if ( preg_match_all('/width\s{0,}:\s{0,}[0-9]{1,}(%|px){0,1}/mi', $params['style'], $matches) ) {
            $params['style'] = preg_replace('/width\s{0,}:\s{0,}[0-9]{1,}(?:[%|px]){0,1}\s{0,};\s{0,}/mi', '', $params['style']);
            $params['style'] = trim($params['style']);
    		$divWidth = $matches[0][0];
    	}
    	if ( preg_match_all('/float\s{0,}:\s{0,}[a-zA-Z]{1,}/mi', $params['style'], $matches) ) {
            $params['style'] = preg_replace('/float\s{0,}:\s{0,}[a-zA-Z]{1,}\s{0,};\s{0,}/mi', '', $params['style']);
            $params['style'] = trim($params['style']);
    		$divFloat = $matches[0][0];
    	}
    	if ( $params['style'] == '' ) unset($params['style']);
    }
    
    $rules = $form->getRules();
    if (!empty($rules)){
        if (isset($rules[$params['name']])){
            foreach ($rules[$params['name']] as $k => $rule){
                if ($rule['error'] == 1) {
                    $params['class'] = 'znbErrorRow';
                    $divClass = 'znbFormErrorOuter';
                }
            }
        }
    }
    
    $_style = array();
    if ( $divWidth ) $_style[] = $divWidth;
    if ( $divFloat ) $_style[] = $divFloat;
    $_style = join(';', $_style);
    $content = '<select';
    foreach($params as $k => &$v)
        if ('options' != $k && 'selected' != $k) $content .= ' '.$k.'="'.$v.'"';
    $content .= '>';

    if (isset($form->_options[$params['name']])) {
        foreach($form->_options[$params['name']] as $k => $v){
            $content .= '<option value="'.$k.'"';
            if (is_array($selected)){
                $sel = false;
                foreach ($selected as $s) if ($s == $k) $sel = true;
                if ($sel) $content .= ' selected = "selected"';
            } else {
                if ($selected == $k) $content .= ' selected = "selected"';
            }
            $content .= '>'.smarty_modifier_escape($v,$escape).'</option>';
        }
    }

    if (isset($params['options']) && is_array($params['options'])) {
        $options = $params['options'];
        foreach($options as $k => $v){
            $content .= '<option value="'.$k.'"';
            if (is_array($selected)){
                $sel = false;
                foreach ($selected as $s) if ($s == $k) $sel = true;
                if ($sel) $content .= ' selected = "selected"';
            } else {
                if ($selected == $k) $content .= ' selected = "selected"';
            }
            $content .= '>'.smarty_modifier_escape($v,$escape).'</option>';
        }
    }

    $content .= '</select>';

    return $content;
}