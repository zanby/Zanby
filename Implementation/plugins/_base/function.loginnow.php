<?php

/**
 * Smarty login now function
 * @param object $smarty
 * @param object $smarty
 * @return template content
 * @author Ivan Khmurchik
 */

function smarty_function_loginnow($params, &$smarty)
{
    $formLogin = new Warecorp_Form('loginForm', 'post', 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
    $smarty->assign('formLogin', $formLogin);
    $_content = $smarty->fetch("_design/menu/loginnow.tpl");
    
    if ($smarty->_tpl_vars['MOD_NAME'] !== 'registration') {
        $_SESSION['login_return_page'] = Warecorp::selfURL();
    }
    return $_content;
}

?>
