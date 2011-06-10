<?php

/**
 * Smarty form function for element <input type=checkbox>
 *
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Ivan Meleshko
 * @author Dmitry Kostikov
 */
function smarty_function_form_checkbox($params, &$smarty)
{
	Warecorp::addTranslation('/plugins/function.form_checkbox.php.xml');
    // form object verify
    if (!isset($smarty->_tpl_vars['wf_form_object'])) return Warecorp::t('Form object not found.');
    $form = $smarty->_tpl_vars['wf_form_object'];

    // element name verify
    if (!isset($params['name'])) return Warecorp::t('Name for field not found.');
	
	// set element id
	if (!isset($params['id'])) $params['id'] = $params['name'];
	
    // element business logic default value
    $value = (isset($form->_defaults[$params['name']])) ? $form->_defaults[$params['name']] : null;

    $content = '<input type="checkbox" class="prCheckBox prAutoWidth prNoBorder' . (empty($params['class']) ? '' : " {$params['class']}") . '"';
    foreach ($params as $k => &$v)
        if ('checked' == $k)
            $checked = $v;
        else
            $content .= ' '.$k.'="'.$v.'"';
    
    if ($value) {
        if ($params['value'] == $value) $content .=  ' checked="checked"';
    } else {
        if ($params['value'] == $checked) $content .=  ' checked="checked"';
    }

    $content .= ' />';
    
    return $content;
}