<?php

/**
 * Smarty form function for element <textarea></textarea>
 *
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Ivan Meleshko
 * @author Dmitry Kostikov
 */
function smarty_function_form_textarea($params, &$smarty)
{
	Warecorp::addTranslation('/plugins/function.form_textarea.php.xml');
    // form object verify
    if (!isset($smarty->_tpl_vars['wf_form_object'])) return Warecorp::t('Form object not found.');
    $form = $smarty->_tpl_vars['wf_form_object'];

    // element name verify
    if (!isset($params['name'])) return Warecorp::t('Name for field not found.');

    // element default value
    $value = isset($form->_defaults[$params['name']]) ? $form->_defaults[$params['name']] : '';
    $value = isset($params['value']) ? $params['value'] : $value;

    $rules = $form->getRules();
    if (!empty($rules)){
        if (isset($rules[$params['name']])){
            foreach ($rules[$params['name']] as $k => $rule){
                if ($rule['error'] == 1) {
                    $params['class'] = $params['class'].' prFormErrors';
                }
            }
        }
    }
    $content = '<textarea';
    foreach ($params as $k => &$v) if ('value' != $k) $content .= ' '.$k.'="'.$v.'"';
    $content .= '>'.$value.'</textarea>';

    return $content;
}