<?php
    $objResponse = new xajaxResponse();

    $requestFile = $file;
    $file = APP_HOME_DIR.'/languages'.urldecode($file);
    $pathinfo = pathinfo($file);

    if ( !file_exists($file) ) {
        $objResponse->addAlert('Can not find file. Contact with administrators.');
    } elseif ( !is_readable($file) ) {
        $objResponse->addAlert('Can not read file. Contact with administrators.');
    } elseif ( !is_writable($file) ) {
        $objResponse->addAlert('Can not write file. Contact with administrators.');
    } else {
        $objResponse->addRedirect(BASE_URL.'/'.LOCALE.'/adminarea/translateGet/file/'.urlencode($requestFile).'/');        
    }    
    
    return $objResponse;
?>