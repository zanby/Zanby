<?php          
    // Define path to application directory
    defined('APPLICATION_PATH') 
    	|| define('APPLICATION_PATH', realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR);
    
    // Define application environment
    defined('APPLICATION_ENV')  
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : NULL));
    
    if (APPLICATION_ENV === null) {
        die(" APPLICATION_ENV must be set. \n For web-based scripts please add 'SetEnv APPLICATION_ENV {env_name}' (example: SetEnv APPLICATION_ENV gabrusenok) into '".dirname(__FILE__)."/.htaccess' file.\n For shell scripts please define environment variable from console or script 'export APPLICATION_ENV={env_name}' (example: export APPLICATION_ENV=gabrusenok)."); 
    }
    //  Implementation config directory
    defined('CONFIG_DIR')
        || define('CONFIG_DIR', APPLICATION_PATH.'configs'.DIRECTORY_SEPARATOR);
    
    defined('APP_VAR_DIR') 
        || define('APP_VAR_DIR', APPLICATION_PATH.'var'.DIRECTORY_SEPARATOR);
    // core.init.php
    //----------------------------------------------------------------------------------------------------------------
    $coreConfig = CONFIG_DIR.'cfg.core.xml';
    if ( is_readable($coreConfig) ) {
        $xml = simplexml_load_file($coreConfig);
    	if( isset($xml->{APPLICATION_ENV}[0]->path) ) {
    		$coreDir = $xml->{APPLICATION_ENV}[0]->path;
            define('APP_HOME_DIR',             APPLICATION_PATH);                                                   //  Application home.
    		define('ENGINE_DIR',               $coreDir.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR);            //  Core library path.
    		define('ZEND_DIR',                 ENGINE_DIR.'Zend'.DIRECTORY_SEPARATOR);                              //  Zend Framework directory.
    		define('WARECORP_DIR',             ENGINE_DIR.'Warecorp'.DIRECTORY_SEPARATOR);                          //  Warecorp Framework path.
            define('BASEWARECORP_DIR',         ENGINE_DIR.'BaseWarecorp'.DIRECTORY_SEPARATOR);                      //  BaseWarecorp Framework path.
    		define('PEAR_DIR',                 ENGINE_DIR.'PEAR'.DIRECTORY_SEPARATOR);                              //  PEAR library path.
    		define('MAGPIE_DIR',               ENGINE_DIR.'magpie'.DIRECTORY_SEPARATOR);                            //  magpie (RSS) library path.
    		define('ABIMPORTER_DIR',           ENGINE_DIR.'abimporter'.DIRECTORY_SEPARATOR);                        //  abimporter library path.
    		define('SMARTY_DIR',               ENGINE_DIR.'Smarty'.DIRECTORY_SEPARATOR);                            //  Smarty library path
            define('FACEBOOK_DIR',             ENGINE_DIR.'facebook'.DIRECTORY_SEPARATOR);		                    //  Facebook API library path
    		define('CORE_CONFIG_DIR',          ENGINE_DIR.'..'.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR);  //  Configuration files path
    	} else die("<p style='font-size:11px;'>Section '".APPLICATION_ENV."' has not been found in file cfg.core.xml</p>");
    }  else die("<p style='font-size:11px;'>File cfg.core.xml - not found. Please, check configuration.</p>");
    //----------------------------------------------------------------------------------------------------------------
    // END : core.init.php
    
    // Ensure core is on include_path
    set_include_path(implode(PATH_SEPARATOR, array(
        realpath(ENGINE_DIR),
        get_include_path(),
    )));

    /* Enable profiling */
    if ( function_exists('xhprof_enable') ) {
        require_once 'Zend/Config/Xml.php';
        $cfg = new Zend_Config_Xml(CONFIG_DIR.'cfg.instance.xml', APPLICATION_ENV);
        if ( $cfg->debug_mode === 'on' ) {
            xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
        }
    }
    /* Enable profiling */
    
    /** Zend_Application */
    require_once 'Zend'.DIRECTORY_SEPARATOR.'Application.php';
    
    // Create application
    $application = new Zend_Application(
        APPLICATION_ENV,
        CONFIG_DIR.'cfg.application.xml'
    );

