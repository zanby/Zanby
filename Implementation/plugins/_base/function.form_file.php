<?php

/**
 * Smarty form function for element <input type=text>
 *
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Ivan Meleshko
 * @author Dmitry Kostikov
 */
function smarty_function_form_file($params, &$smarty)
{
	Warecorp::addTranslation('/plugins/function.form_file.php.xml');
    // form object verify
    if (!isset($smarty->_tpl_vars['wf_form_object'])) return Warecorp::t('Form object not found.');
    $form = $smarty->_tpl_vars['wf_form_object'];

    // element name verify
    if (!isset($params['name'])) return Warecorp::t('Name for field not found.');
	
	// set element id
	if (!isset($params['id'])) $params['id'] = $params['name'];

    $content = '<input type="file"';
    foreach ($params as $k => &$v) $content .= ' '.$k.'="'.$v.'"';
    $content .= ' />';
    
    return $content;
}