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

class BaseWarecorp_Util_UserActivity_ConfigTracker extends Warecorp_Util_UserActivity_Tracker {
    protected $configFileName;
    
    public function __construct($configFileName = 'cfg.userActivityTracker.xml') {
        parent::__construct();
        
        $this->configFileName = $configFileName;

        $this->loadConfig();
    }

    protected function loadConfig() {
        $xml = self::getXmlConfig($this->configFileName);
        $this->setEnable((isset($xml->enable) && $xml->enable == '1')? true : false);
        if (isset($xml->useCustomDatabase) && $xml->useCustomDatabase == '1') {
            $cfgDb = $xml->customDatabase;
            $params = array (
        		'host'     => $cfgDb->host,
        		'username' => $cfgDb->username,
        		'password' => $cfgDb->password,
        		'dbname'   => $cfgDb->name
            );

            try {
                $db = Zend_Db::factory($cfgDb->type, $params);
                $sql = "SET NAMES utf8";
                $db->query($sql);
                $sql = "SET time_zone = 'UTC';";
                $db->query($sql);
                $this->setDb($db);
            } catch (Exception $e) {
                throw new Exception("Cannot connect to user activity tracker custom database: ". $e->getMessage());
            }
        }

        $userTypesTracking = 0;
        if (isset($xml->userTypesTracking->host) && $xml->userTypesTracking->host == '1') {
            $userTypesTracking |= self::USER_TYPE_HOST;
        }
        if (isset($xml->userTypesTracking->coHost) && $xml->userTypesTracking->coHost == '1') {
            $userTypesTracking |= self::USER_TYPE_COHOST;
        }
        if (isset($xml->userTypesTracking->member) && $xml->userTypesTracking->member == '1') {
            $userTypesTracking |= self::USER_TYPE_MEMBER;
        }
        if (isset($xml->userTypesTracking->user) && $xml->userTypesTracking->user == '1') {
            $userTypesTracking |= self::USER_TYPE_USER;
        }

        $this->setUserTypesTracking($userTypesTracking);
    }

    public static function getXmlConfig($filename = 'cfg.userActivityTracker.xml') {
        $cache = Warecorp_Cache::getFileCache();
            
        $cacheId = str_replace('.', '_', $filename);
        
        
        $xml = $cache->load($cacheId);
        if ($xml === false) {
            $xml = new Zend_Config_Xml(CONFIG_DIR."$filename", 'userActivityTracker'); 
            if ($xml === false) throw new Exception("Error parse user activity tracker xml config. Config file: ". $filename);
            $cache->save($xml, $cacheId, array(), 2592000 /* 60*60*24*30 */);
        }
        return $xml;
    }

}
