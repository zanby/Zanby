<?php
/**
* @desc This script helps loadbalancer to detect mysql\nfs problems
*/

    require_once realpath(dirname( __FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'Initializing.php');
    $application->bootstrap(array('Defines', 'Databases'));
    
    $host = isset($_GET['host']) ? $_GET['host'] : null;
    if (!$host) { echo 'host param required!';exit;}
    
    $return = 0;
    
    $query = false;
    try {
        $db = Zend_Registry::get('DB');
        $query = $db->fetchOne($db->select()->from('zanby_users__accounts','id')->limit(1,0));
    }catch (Exception $e) {
        $query = false;
    }
    
    $nfs = null;
    $output = null;
    exec("rpcinfo -p $host 2>/dev/null | grep nfs &>/dev/null",$output,$nfs);
    if ($nfs == 0) {
        echo ($query !== false) ? "0" : "1";
    }else{
        echo ($query !== false) ? "2" : "3";
    }
    exit;
