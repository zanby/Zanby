<?php
    /* Init Core and required constants */
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'Initializing.php';     
    $application->bootstrap(array('Defines', 'Databases', 'Session'));

    $ret = isset($_GET['ret']) && !empty($_GET['ret']) ? $_GET['ret'] : '';
    if ( !$ret && isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ) {
        $url = parse_url($_SERVER['HTTP_REFERER']);
        $ret = $url['scheme'].'://'.$url['host'];
    } elseif ( !$ret ) $ret = BASE_URL;
    
    $objUser = new Warecorp_User();
    $objUser->logout();
    
    setcookie("wpsso_username", '', time()+2592000, "/",'.'.BASE_HTTP_HOST); //  60*60*24*30 = 2592000
    setcookie("wpsso_password", '', time()+2592000, "/",'.'.BASE_HTTP_HOST);
    setcookie("wpsso_rememberme", '', time()+2592000, "/",'.'.BASE_HTTP_HOST);
    
    if ( $ret != 'noreturn' ) Header("Location: ".urldecode($ret));