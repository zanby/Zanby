<?php 

class Maps_Cache {
    static private $_instance = null;
    
    private function __construct () 
    {
        $frontendOptions = array('lifetime' => 300, 'automatic_serialization' => true);
        $backendOptions = array('cache_dir' => APPLICATION_PATH.'/../var/cache/', 'hashed_directory_level' => 0, 'file_locking' => false);
        self::$_instance = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
    }
    
    public static function getInstance() 
    {
        if ( Maps_Cache::$_instance === null ) {
            $frontendOptions = array('lifetime' => 300, 'automatic_serialization' => true);
            $backendOptions = array('cache_dir' => APPLICATION_PATH.'/var/cache/', 'hashed_directory_level' => 0, 'file_locking' => false);
            self::$_instance = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
            //self::$_instance->clean();
        }
        return Maps_Cache::$_instance;
    }
    
    static function setInstance ($instance) 
    {
        //@todo implement checking of interface
        Maps_Cache::$_instance = $instance;
    }
    

}