<?php
    ini_set("soap.wsdl_cache_enabled", "0");
    
    /* Init Core and required constants */
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'Initializing.php';     
    $application->bootstrap(array('Defines', 'Databases'));
        
    $soapServer = new SoapServer( BASE_URL."/wsdl.php?t=service", array() );
    $soapServer->setObject(new Warecorp_SOAP_Callback());
    $soapServer->handle();