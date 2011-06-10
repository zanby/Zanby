<?php

/**
 * Smarty form function for element <input type=hidden>
 *
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Ivan Meleshko
 * @author Dmitry Kostikov
 */
function smarty_function_form_hidden($params, &$smarty)
{
	Warecorp::addTranslation('/plugins/function.form_hidden.php.xml');
    // form object verify
    if (!isset($smarty->_tpl_vars['wf_form_object'])) return Warecorp::t('Form object not found.');
    $form = $smarty->_tpl_vars['wf_form_object'];

    // element name verify
    if (!isset($params['name'])) return Warecorp::t('Name for field not found.');

    // element default value
    if (isset($form->_defaults[$params['name']]))
        $params['value'] = $form->_defaults[$params['name']];
        
    $content = '<input type=hidden';
    foreach ($params as $k => &$v) $content .= ' '.$k.'="'.$v.'"';
    $content .= '>';
    
    return $content;
}