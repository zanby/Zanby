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

/**
 * Warecorp_Config_Loader 
 * 
 * @final
 * @singleton
 * @package Warecorp_Config
 * @version 0.1
 * @copyright 1997-2010 Warecorp.com
 * @author Roman Gabrusenok 
 */
class BaseWarecorp_Config_Loader {

    static private $_instance = null;

    private $_loadedConfigs = array();

    private function __construct() {}
    public  function __clone() 
    {  
        trigger_error('Method not allowed, use getInstance() instead', E_USER_ERROR);
        exit;
    }

    /**
     * getInstance 
     * 
     * @static
     * @access public
     * @return Warecorp_Config_Loader
     */
    static public function getInstance()
    {
        if ( NULL === self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * getAppConfig
     * 
     * @param string $path Relative configuration file path begin from CONFIG_DIR
     * @param string|null $section 
     * @access public
     * @return Zend_Config_Xml
     * @throw Warecorp_Exception
     */
    public function getAppConfig($path, $section = null)
    {
        if ( empty($path) ) {
            throw new Warecorp_Exception('Path of config file can\'t be empty');
        }

        if ( NULL === $section ) {
            $section = APPLICATION_ENV;
        }

        $uid = $this->path2uid($path.'_app', $section);

        if ( !empty($this->_loadedConfigs[$uid]) ) {
            return $this->_loadedConfigs[$uid];
        }

        $cache = Warecorp_Cache::getFileCache();
        if ( FALSE == ( $cfg = $cache->load($uid) ) ) {
            $filename = realpath(CONFIG_DIR.DIRECTORY_SEPARATOR.$path);
            try {
                $cfg = new Zend_Config_Xml($filename, $section);
            } catch ( Zend_Config_Exception $e ) {
                if ( APPLICATION_ENV !== 'production' && APPLICATION_ENV !== 'staging' )
                    $cfg = new Zend_Config_Xml($filename, 'development');
                else
                    throw new Warecorp_Exception("You should add section: '$section' to configuration file: '$filename'");
            }
            $cache->save($cfg, $uid, array(), Warecorp_Cache::LIFETIME_30DAYS);
        }

        $this->_loadedConfigs[$uid] = $cfg;

        return $cfg;
    }

    /**
     * getCoreConfig  
     * 
     * @param string $path Relative path to config begin from CORE_CONFIG_DIR
     * @param string|null $section 
     * @access public
     * @return Zend_Config_Xml
     * @throw Warecorp_Exception
     */
    public function getCoreConfig($path, $section = null)
    {
        if ( empty($path) ) {
            throw new Warecorp_Exception('Path of config file can\'t be empty');
        }

        if ( NULL === $section ) {
            $section = APPLICATION_ENV;
        }
        $uid = $this->path2uid($path.'_core', $section);

        if ( !empty($this->_loadedConfigs[$uid]) ) {
            return $this->_loadedConfigs[$uid];
        }

        if (!strstr($path, 'memcache')) {
            $cache = Warecorp_Cache::getFileCache();
        }
        //echo "eeee";
        //exit();

        if ( strstr($path, 'memcache') || FALSE == ( $cfg = $cache->load($uid) ) ) {
            $filename = realpath(CORE_CONFIG_DIR.DIRECTORY_SEPARATOR.$path);
            try {
                $cfg = new Zend_Config_Xml($filename, $section);
            } catch ( Zend_Config_Exception $e ) {
                if ( APPLICATION_ENV !== 'production' && APPLICATION_ENV !== 'staging' )
                    $cfg = new Zend_Config_Xml($filename, 'development');
                else
                    throw new Warecorp_Exception("You should add section: '$section' to configuration file: '$filename'");
            }
            if (!strstr($path, 'memcache')) {
                $cache->save($cfg, $uid, array(), Warecorp_Cache::LIFETIME_30DAYS);
            }
        }

        $this->_loadedConfigs[$uid] = $cfg;


        return $cfg;
    }

    private function path2uid($path, $section = null) {
        $path = trim(str_replace(array(' ', '-', '/', '.'), '_', $path), '_');
        if ( $section === null ) {
            $section = '';
        } else {
            $section = '_' . trim(str_replace(array(' ', '-', '/', '.'), '_', $section), '_');
        }

        return $path.$section;
    }
}

