<?php

/**
 * Smarty form function for element <input type=submit>
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Ivan Meleshko
 * @author Dmitry Kostikov
 */
function smarty_function_form_submit($params, &$smarty)
{
    Warecorp::addTranslation('/plugins/function.form_submit.php.xml');
    // form object verify
    if (!isset($smarty->_tpl_vars['wf_form_object'])) return Warecorp::t('Form object not found.');
    $form = $smarty->_tpl_vars['wf_form_object'];

    if (!isset($params['name']) && isset($params['value'])) $params['name'] = $params['value'];
    if (!isset($params['name']))    $params['name'] = "form_submit";
	if (!isset($params['id']))  	$params['id'] = $params['name'];
    if (!isset($params['value']))   $params['value'] = "Submit";
	
	$out = '<a class="prButton" href="#send" onclick="document.getElementById(\'' . $params['id'] . '\').click();"><span>'.$params['value'].'</span></a><input type="submit" class="prSubmit"';
    foreach ( $params as $key => $value ) $out .= ' '.$key.'="'.$value.'"';
    $out .= '
                />
	';

    return $out;
}