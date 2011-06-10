<?php
    ini_set("soap.wsdl_cache_enabled", "0");
    
    /* Init Core and required constants */
    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'Initializing.php';     
    $application->bootstrap(array('Defines', 'Databases', 'Session', 'CssJsImagesPaths'));
    
	$soap = new DOMDocument();
	$soap->load('php://input');

	$wss = new Warecorp_SOAP_Server_WSSecurity( $soap );
	$wss->setUsernameToken( new Warecorp_SOAP_Server_WSSecurity_SSO_UsernameToken() );
	$soapServer = new SoapServer( 'http://'.$_SERVER['SERVER_NAME'].'/wsdl.php?t=sso', array() );		

	try { $response = $wss->process(); }
	catch ( Exception $e ) { $wss->failed($e->getMessage()); }
	if ( !$response ) { $wss->failed('Cannot process soap request'); }

	$soapServer->setObject(new Warecorp_SOAP_Service_SSO());
	$soapServer->handle($wss->saveXML());
	