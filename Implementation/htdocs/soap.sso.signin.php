<?php
    /* Init Core and required constants */
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'Initializing.php';     
    $application->bootstrap(array('Defines', 'Databases', 'Session'));
    
    $code = isset($_GET['ssoKey']) && !empty($_GET['ssoKey']) ? $_GET['ssoKey'] : '';
    $ret = isset($_GET['ret']) && !empty($_GET['ret']) ? $_GET['ret'] : '';
    if ( !$ret && isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ) {
        $url = parse_url($_SERVER['HTTP_REFERER']);
        $ret = $url['scheme'].'://'.$url['host'];
    } elseif ( !$ret ) $ret = BASE_URL;
    $rememberme = isset($_GET['rememberme']) && !empty($_GET['rememberme']) ? 1 : 0;

    $cache = Warecorp_Cache::getFileCache();
    if ( $userID = $cache->load('SSO_'.$code) ) {
        $cache->remove('SSO_'.$code);
        
        $objUser = new Warecorp_User('id', $userID);
        $objUser->logout();
        //$objUser->authenticate();
        
        setcookie("wpsso_username", $objUser->getLogin(), time()+2592000, "/",'.'.BASE_HTTP_HOST); //  60*60*24*30 = 2592000
        setcookie("wpsso_password", md5($objUser->getPass()), time()+2592000, "/",'.'.BASE_HTTP_HOST);
        setcookie("wpsso_rememberme", $rememberme, time()+2592000, "/",'.'.BASE_HTTP_HOST);
    }
    
    Header("Location: ".urldecode($ret));
    exit();
