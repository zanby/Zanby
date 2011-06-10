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
 * Warecorp FRAMEWORK
 *
 * @package    Warecorp_Cache
 * @copyright  Copyright (c) 2006, 2008
 */

 
/**
 * Base class for managing of connections to MemCache.
 * 
 * @author Mihail Pianko
 */
class BaseWarecorp_Cache_MemCacheManager
{
   
    /**
    * variable _connections contain array of Warecorp_Cache_MemCacheConnection Objects
    * @access private
    * @var array()
    */
    private $_connections = null;
    
    /**
    * Index which contained [rangePoint] => serverName
    * @access private
    * @var array()
    */
    private $_pointsServers = array(); 
    
    /**
    * Parsed MemCache Config File
    * @access private
    * @var SimpleXML
    */
    private $_config = null;
    
    /**
    * Implementation prefix for Cache ID
    * @access private
    * @var SimpleXML
    */
    private $_prefix = null;
    
    /**
    * Pointer to Warecorp_Cache_MemCacheManager Object
    * @access private
    * @static 
    * @var Warecorp_Cache_MemCacheManager Object
    */
    static private $_memCacheManager = null;

    /**
    * Path to MemCache Config File
    * @access private
    * @static 
    * @var string
    */
    static private $_cfgFile = null;
    
         
         
    /**
    * For debuging and displaing list of active server under concole
    * @access public
    * @return string report text
    */     
    public function printActiveServersName()
    {
        $return = ""; 
        foreach (array_unique(array_values($this->_pointsServers)) as $key => $value)
        {
            $return .= "- ".$value."\n";    
        }
        return $return;
    }      
         
    /**
    * Return points quantity on circle
    * @access public
    * @return int points quantity
    */     
    public function getPointsCout()
    {
        return count(array_unique(array_values($this->_pointsServers)));
    }                              
       
    /**
     * Class constructor.
     * Reading of config file and initialization of child processes for each MemCache server Warecorp_Cache_MemCacheConnection
     * 
     */
    private function __construct() 
    {
        $this->_config = Warecorp_Config_Loader::getInstance()->getCoreConfig(self::$_cfgFile);
        if ($this->_config->main->enabled !== 'yes')
            return;
        
        $connections = $this->_config->servers->connection->toArray();
        
        if ( !empty($connections) && !isset($connections[0]) ) { // if configurred only one server
            $connections = array($connections);
        }
        
        foreach ($connections as $connection) {
            $backendOptions     = $this->getBackendOptions($connection);
            $frontendOptions    = $this->getFrontendOptions($connection);
            $rangeOptions       = $this->getRangeOptions($connection);
            $name               = $this->getServerName($connection);   
            
            if (self::isConnectionExist($name)){
                die ('duplicationg server names');
            }
            $this->_connections[$name] = new Warecorp_Cache_MemCacheConnection($frontendOptions, $backendOptions, $rangeOptions);
            $this->_connections[$name]->setName($name);   
            
            if ( !$this->_connections[$name]->isActive() ) continue;
            $this->setConnectionRanges($name, $rangeOptions);
        }
    }
    
    /**
    * Initialization of MemCacheManager
    * Path to configuration file should be set as $cfgFilePath param or by using method setCfgFilePath()
    * @access public
    * @static 
    * @param string $cfgFilePath path to congiration file
    * @return Warecorp_Cache_MemCacheManager object
    */
    static public function init($cfgFilePath = null)
    {
        if (self::$_memCacheManager === null){
            if (self::$_cfgFile === null && $cfgFilePath === null ) self::$_cfgFile = /*CORE_CONFIG_DIR.*/'cfg.memcache.xml';
            if ( $cfgFilePath !== null ) self::$_cfgFile = $cfgFilePath;
            self::$_memCacheManager = new Warecorp_Cache_MemCacheManager(); 
        }
        return  self::$_memCacheManager;
    }
    
    /**
    * Get hash for ID
    * Path to configuration file should be set as $cfgFilePath param or by using method setCfgFilePath()
    * @access public
    * @static 
    * @param string $id cache identifier
    * @return int crc32 polynomial of a string
    */
    static public function getHash($id)
    {
        return crc32($id);
    }
    
    /**
    * Set end points for ranges for MemCache server. 
    * @access public
    * @param string $name server name
    * @param array $rangeArray array for end pints for current server
    * @return bool Returns a TRUE on success, or FALSE on failure.  
    */
    private function setConnectionRanges ($name, $rangeArray)
    {
        if (!$this->isConnectionExist($name)) die('undefined server');
        if (is_array($rangeArray) && count($rangeArray) > 0)
        {
            foreach ($rangeArray as $key => $value){
                if (isset($this->_pointsServers[$value])) continue;
                $this->_pointsServers[$value] = $name;
            }                                              
        }   
        else{
            return false;
        }
        ksort($this->_pointsServers);  
        return true;
    }


    /**
    * Get server name by ID (ID - crc32 from ID of saved document)
    * @access private
    * @todo required optimization of algoritm! 
    * @param int $id identifier 
    * @return string server name or exception
    */
    private function getServerNameById($id)
    {
        $lastName = null;
        foreach ($this->_pointsServers as $range => $name)
        {
            if ($lastName === null) $lastName = $name; 
            if ($id < $range){
                $lastName = $name;
                break;
            }
        }
        if ( $lastName === null) die("Sorry, the portal is on service. Visit it later (MemCache).");
        return $lastName;    
    }
    
    /**
    * Get pointer to prepeared Zend_Cache object by ID (ID - crc32 from ID of saved document)
    * @access public
    * @param int $id identifier 
    * @return Zend_Cache Return on success prepeared Zend_Cache object or NULL on failure
    */
    private function getConnectionByRangeId($id)
    {
        $lastName = $this->getServerNameById($id);
        if ( $lastName === null) die("Sorry, the portal is on service. Visit it later (MemCache).");
        return $this->getConnectionByName($lastName)->getConnection();
    }
    
   
    /**
    * Get pointer to MemCacheConnection Object by ID (ID - crc32 from ID of saved document)
    * @access public
    * @param int $id identifier  
    * @return Warecorp_Cache_MemCacheConnection Return on success Warecorp_Cache_MemCacheConnection object or NULL on failure
    */
    private function getConnectionObjectByRangeId($id)
    {
        $lastName = $this->getServerNameById($id);
        return $this->getConnectionByName($lastName);
    }
    
    /**
    * Remove corrupted MemCache server from queue.
    * @access private
    * @param string $id identifier of data
    * @return bool Return on success TRUE or FALSE on failure
    */    
    private function removeServerFromQueueByCacheId($id)
    {
        $serverName = $this->getConnectionObjectByRangeId(Warecorp_Cache_MemCacheManager::getHash($id))->getName();
        $this->getConnectionObjectByRangeId(Warecorp_Cache_MemCacheManager::getHash($id))->setActive(false);
        foreach ($this->_pointsServers as $range => $name)
        {
            if ($serverName == $name) unset($this->_pointsServers[$range]);
        }
        // @todo Additional required script for chacking connections servers and if server availeble it should be returned to Queue.
        if (count($this->_pointsServers) == 0)  die("There is no cache servers");
    }
    
    /**
    * Add context of implemetation to Cache ID
    * @todo add support of tags
    * @access public
    * @param string $id Cache Id
    * @return string
    */    
    private function addContext($id)
    {                                   
        if ($this->_prefix === null) {
            $this->_prefix = str_replace('/','_',APP_VAR_DIR); 
            $this->_prefix = str_replace('-','_',$this->_prefix); 
            $this->_prefix = str_replace('.','_',$this->_prefix); 
        }
        $id = str_replace(' ','_',$id);   
        $id = str_replace('-','_',$id);    
        $id = str_replace('.','_',$id);   
        $id = str_replace(',','_',$id);   
        return $this->_prefix.'_'.$id;
    } 
    
    /**
    * Save object to cache. MemCache server will be choosed automatically
    * @todo add support of tags
    * @access public
    * @param void $data data for saving  
    * @param string $id identifier of data
    * @param array $tags tags for. Now unused parameter.
    * @param int $specificLifetime cache lifetime
    * @return bool Return on success TRUE or FALSE on failure
    */    
    public function save($data, $id = null, $tags = array(), $specificLifetime = false)
    {    
        if ($specificLifetime > 2592000) $specificLifetime = 2592000;   
        $id = $this->addContext($id);
        $connection = $this->getConnectionByRangeId(Warecorp_Cache_MemCacheManager::getHash($id));
        
        if ($connection !== null) {
                $result=$connection->save($data,$id,$tags,$specificLifetime);
                if ($result) return $result;
        }
        $this->removeServerFromQueueByCacheId($id);
        return $this->save($data, $id, $tags, $specificLifetime);
    } 

    /**
    * Load object from cache. MemCache server will be choosed automatically
    * @todo add support of tags
    * @access public
    * @param string $id identifier of data
    * @return void Return on success data from cache or FALSE on failure
    */    
    public function load($id = null)
    {
            $id = $this->addContext($id); 
            $result = $this->getConnectionByRangeId(Warecorp_Cache_MemCacheManager::getHash($id))->load($id);   
            return $result;
    } 
    
    /**
    * Remove object from cache. MemCache server will be choosed automatically
    * @todo add support of tags
    * @access public
    * @param string $id identifier of data
    * @return void Return on success data from cache or FALSE on failure
    */    
    public function remove($id)
    {
        $id = $this->addContext($id); 
        return $this->getConnectionByRangeId(Warecorp_Cache_MemCacheManager::getHash($id))->remove($id);
    } 

    /**
    * Test for cache. MemCache server will be choosed automatically
    * @todo add support of tags
    * @access public
    * @param string $id identifier of data
    * @return void Return on success data from cache or FALSE on failure
    */    
    public function test($id)
    {
        $id = $this->addContext($id); 
        return $this->getConnectionByRangeId(Warecorp_Cache_MemCacheManager::getHash($id))->test($id);
    } 
    
    /**
    * Remove all objects from cache.
    * @todo add support of tags
    * @access public
    * @param string $id identifier of data
    * @return void Return on success data from cache or FALSE on failure
    */    
    public function clean()
    {
        foreach ($this->_pointsServers as $range => $name)
        {
            $this->getConnectionByName($name)->getConnection()->Clean();  
        }
    } 
    
    /**
    * Checking for connection by server name
    * @access private
    * @param string $name server name
    * @return bool Return on success TRUE or FALSE on failure
    */ 
    private function isConnectionExist($name)
    {
        if ( isset($this->_connections[$name]) && $this->_connections[$name] !== null ) return true;
        else return false;
    }
    
    /**
    * Get connection by name
    * @access private
    * @param string $name server name
    * @return Warecorp_Cache_MemCacheConnection Return on success Warecorp_Cache_MemCacheConnection object or NULL on failure
    */ 
    private function getConnectionByName($name)
    {
        if ($this->isConnectionExist($name)){
            return $this->_connections[$name];
        }
        return null;    
    }
    
    /**
    * Parse Beckend options from cfg.memcache.xml configuration file for specified server
    * @access private
    * @param SimpleXML $conneciton sub tree from cfg.memcache.xml with configuration of specified server
    * @return array $returnArray Return on success prepeared array for Zend_Cache backend configuration
    */ 
    private function getBackendOptions($connection)
    {
        $returnArray = array();
        if ( isset($connection['backend']['server']) ) {
            $server = $connection["backend"]["server"];
            if (!isset($server['host']) || !isset($server['port']))
                die ("wrong CFG params");
            $returnArray['servers']['host']  =  (string)$server['host'];
            $returnArray['servers']['port']  =  (string)$server['port'];
            $returnArray['servers']['persistent']  =  isset($server['persistent'])?(bool)$server['persistent'] : 0;
            $returnArray['compression'] = isset($connection['backend']['compression'])?(bool)$connection['backend']['compression'] : 0;
            return $returnArray; 
        } 
        else {
            die ("Wrong CFG params range->backend->server is not exist");
        }
    }  
    
    /**
    * Get server name from cfg.memcache.xml for current server
    * @access private
    * @param Simple_XML $conneciton sub tree from cfg.memcache.xml with configuration of specified server
    * @return string Return on success server name
    */ 
    private function getServerName($cfg)
    {
        if (isset($cfg['name'])){
            return (string)$cfg['name'];
        }
        else{
            die ("Wrong CFG params : attribute name isnot exist"); 
        }
    }

    /**
    * Parse Frontend options from cfg.memcache.xml configuration file for specified server
    * @access private
    * @param Simple_XML $conneciton sub tree from cfg.memcache.xml with configuration of specified server
    * @return array $returnArray Return on success prepeared array for Zend_Cache Frontend configuration
    */ 
    private function getFrontendOptions($connection)
    {
        $returnArray = array(); 
        if (isset($connection['frontend'])){
            $server = $connection['frontend'];
            $returnArray['lifetime']  =  isset($server['lifetime'])?(string)$server['lifetime']:300;
            $returnArray['automatic_serialization']  =  isset($server['automatic_serialization'])?(bool)$server['automatic_serialization']:0;
            return $returnArray; 
        } 
        else {
            die ("Wrong CFG params range->frontend is not exist");
        }
    
    }
    
    /**
    * Parse Range options from cfg.memcache.xml configuration file for specified server
    * @access private
    * @param Simple_XML $conneciton sub tree from cfg.memcache.xml with configuration of specified server
    * @return array $returnArray Return on success array with end points
    */ 
    private function getRangeOptions($connection)
    {
        $returnArray = array(); 
        if (isset($connection['range']['point'])){
            if (count($connection['range']['point']) > 1) {
                foreach ($connection['range']['point'] as $point)
                {
                    $returnArray[] = (int)$point;
                }
            }else{
                //var_dump($connection->range->point);
                $returnArray[] = (int)$connection['range']['point'];
            }
            return $returnArray; 
        } 
        else {
//            var_dump($connection->range);
            die ("Wrong CFG params range->point is not exist");
        }
    }
}
