<?php
    define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : null));

    if (APPLICATION_ENV === null) {
        throw new Exception("APPLICATION_ENV must be set.");
    }

    $implCfgPath = $home.'/configs/cfg.baseImplementation.xml';
    if (is_readable($implCfgPath) && (($xml = simplexml_load_file($implCfgPath)) !== false)) {
        $applicationEnv = APPLICATION_ENV;
        $implementationTag = $xml->$applicationEnv;
        if (!$implementationTag) throw new Exception('No environment '. $applicationEnv. ' in  cfg.baseImplementation.xml');
        $baseImplementationPath = (string)$implementationTag->baseImplementation;
    } else {
        throw new Exception('Error parse cfg.baseImplementation.xml');
    }


    require_once realpath($baseImplementationPath.'/init/Initializing.php');
    error_reporting(E_ALL);
    $application->bootstrap(array('FileCache', 'Defines', 'Databases', 'CssJsImagesPaths', 'Session'));
    error_reporting(E_ALL);
