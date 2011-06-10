<?php

/**
 * Smarty form function
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Artem Sukharev
 */
function smarty_function_form_errors_summary($params, &$smarty)
{
	Warecorp::addTranslation('/plugins/function.form_errors_summary.php.xml');
    if (!isset($smarty->_tpl_vars['wf_form_object'])) return Warecorp::t('Form object not found.');
    $form_object = $smarty->_tpl_vars['wf_form_object'];

    //  System and Custom Errors
    $output_errors = array();
    if ( isset($params['id']) ) {
        $output_errors = $form_object->getErrorMessages($params['id']);
        $custom = $form_object->getCustomErrorMessages($params['id']);
    } else {
        $output_errors = $form_object->getErrorMessages();
        $custom = $form_object->getCustomErrorMessages();
    }
    if ( sizeof($custom) != 0 ) {
        foreach ( $custom as $err ) $output_errors[] = $err;
    }

    $_content = '';
    if ( sizeof($output_errors) != 0 ) {
        $smarty->assign('errors', $output_errors);
        if ( isset($params['width']) ) {
        	if ( preg_match('/^[0-9]{1,}$/', $params['width']) ) {
        	   $params['width'] = $params['width'].'px';
        	}
            $smarty->assign('width', $params['width']);
        }
        if ( isset($params['space_before']) ) {
        	if ( preg_match('/^[0-9]{1,}$/', $params['space_before']) ) {
        	   $params['space_before'] = $params['space_before'].'px';
        	}
            $smarty->assign('space_before', $params['space_before']);
        }
        if ( isset($params['space_after']) ) {
        	if ( preg_match('/^[0-9]{1,}$/', $params['space_after']) ) {
        	   $params['space_after'] = $params['space_after'].'px';
        	}
            $smarty->assign('space_after', $params['space_after']);
        }
        
        $_content = $smarty->fetch("_design/form/form_errors_summary.tpl");
    }

    return $_content;
}