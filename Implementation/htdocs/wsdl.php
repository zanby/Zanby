<?php   
    /* Init Core and required constants */
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'Initializing.php';     
    $application->bootstrap(array('Defines'));

    if ( !isset($_GET['t']) || !$_GET['t'] ) throw new Exception('Incorrect request');
    
    $dom = new DOMDocument('1.0', 'UTF-8');

    switch ( $_GET['t'] ) {
        case 'service' : 
            $dom->load('wsdl/service.wsdl');
            $element = $dom->getElementsByTagNameNS('http://schemas.xmlsoap.org/wsdl/soap/', 'address');
            $element->item(0)->setAttribute('location', BASE_URL . '/soap.mailsrv.callback.service.php');
            break;
        case 'sso' : 
            $dom->load('wsdl/sso.wsdl');
            $element = $dom->getElementsByTagNameNS('http://schemas.xmlsoap.org/wsdl/soap/', 'address');
            $element->item(0)->setAttribute('location', BASE_URL . '/soap.sso.server.php');
            break;
        default :
            throw new Exception('Incorrect request');
    }
    $dom->formatOutput = false;
    header("Content-type: text/xml");
    print $dom->saveXML();