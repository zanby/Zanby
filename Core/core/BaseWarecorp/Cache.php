<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/
                 

define ('WITH_MEMCACHE_STORAGE', true);

class BaseWarecorp_Cache
{
    /**   LIFETIME IN SECONDS    **/
    const LIFETIME_30DAYS = 2592000;
    const LIFETIME_20DAYS = 1728000;
    const LIFETIME_15DAYS = 1296000;
    const LIFETIME_10DAYS = 864000;
    const LIFETIME_2HOURS = 7200;
    const LIFETIME_HOUR   = 3600;
    /******************************/

    protected static $fileCache;
    protected static $memCache;
    protected static $memcacheCfg = null;
    
    
    
    /**
     * Class constructor
     */
    private function __construct() 
    {
    
    }
    
    /**
     * Create instance of cange object by mode
     * @param string $mode - file|memory
     * @return Zend_Cache_Frontend
     */
    public static function getCache($mode = null) 
    {
        switch ( strtolower($mode) ) {
            case 'file' :          
                return self::getFileCache();
                break;
            case 'memory' : 
                return self::getMemCache();
                break;
            default :
                return self::getFileCache();
        }
    }
    
    public static function setMemCacheCfgFile($path)
    {
        if (!is_file($path) || !is_readable($path)) die("Memcache config file is not exist!");
        self::$memcacheCfg = $path;
    }
    
    /**
     *  @todo move memcache block to getCache method
     */
    public static function getFileCache($withMem = true)
    {                    
        if ( defined('WITH_MEMCACHE_STORAGE') && WITH_MEMCACHE_STORAGE )
        {
            $memTemp = Warecorp_Cache_MemCacheManager::init(self::$memcacheCfg);
            // if count of memcache servers = 0 try to switch to file cache
            if ($memTemp->getPointsCout() > 0) {
                return $memTemp;
            }
            else{
                unset($memTemp);
            }
        }
        
        if ( null === self::$fileCache ) {
            $frontendOptions = array('lifetime' => 300, 'automatic_serialization' => true);
            $backendOptions = array('cache_dir' => APP_VAR_DIR.'/cache/', 'hashed_directory_level' => 0, 'file_locking' => false);
            self::$fileCache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        }
        return self::$fileCache;
    }
    
    /**
     * 
     */
    public static function getMemCache()
    {
        $memTemp = Warecorp_Cache_MemCacheManager::init(self::$memcacheCfg);
        if ($memTemp->getPointsCout() > 0) {
            return $memTemp;
        }
        else{
            unset($memTemp);
        }
        // if MemCacheManager disabled try to use default memcache server only for sessions
        if ( null === self::$memCache ) {
            $frontendOptions = array('lifetime' => 300, 'automatic_serialization' => true);
            $backendOptions = array('servers' => array(
                'host' => 'localhost',
                'port' => 11211,
                'persistent' => true
            ), 'compression' => true);
            self::$memCache = Zend_Cache::factory('Core', 'Memcached', $frontendOptions, $backendOptions);
        }
        return self::$memCache;
    }
    
}
