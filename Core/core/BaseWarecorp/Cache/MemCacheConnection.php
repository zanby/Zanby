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
 * Base class for processing of connection to MemCache.
 * 
 * @author Mihail Pianko
 */
class BaseWarecorp_Cache_MemCacheConnection
{
   /**
    * variable _rangeEndPoints contain array of end points for current connection
    * @access private
    * @var array()
    */
    private $_rangeEndPoints = array();
    
   /**
    * variable _isActive - is active current connection
    * @access private
    * @var bool
    */
    private $_isActive = false;

   /**
    * variable _lastActiveTime - last time of using of current connection
    * @access private
    * @var int 
    */
    private $_lastActiveTime = null;

   /**
    * variable _memCache - pointer to prepeared Zend_Cache object
    * @access private
    * @var Zend_Cache 
    */
    private $_memCache = null;

   /**
    * variable _frontendOptions - array which contain prepeared frontend options for Zend_Cache class
    * @access private
    * @var array
    */
    private $_frontendOptions = null;

   /**
    * variable _frontendOptions - array which contain prepeared backend options for Zend_Cache class
    * @access private
    * @var array
    */
    private $_backendOptions = null;

   /**
    * variable _serverName - name of server
    * @access private
    * @var string
    */
    private $_serverName = null;
    
    /**
     * Class constructor
     * @access public 
     * @param array $frontendOptions options for frontend. Optional. Could be defined by using method setFrontendOptions.
     * @param array $backendOption options for backend. Optional. Could be defined by using method setBeckendOptions.
     * @param array $rangeOptions List of end points for current server. Optional. Could be defined by using method setRangeEndPoints.
     * @param string $name server name. Optional. Could be defined by using method setName.
     */
    public function __construct($frontendOptions = null, $backendOption = null, $rangeOptions=null, $name = null ) 
    {
        if ($frontendOptions !== null)  $this->setFrontendOptions($frontendOptions);
        if ($backendOption !== null)    $this->setBeckendOptions($backendOption);
        if ($rangeOptions !== null)     $this->setRangeEndPoints($rangeOptions);
        if ($name !== null)             $this->setName($name);
        $this->_isActive = true;    
        $this->connect();
    }
    
   /**
     * Method return name of server
     * @access public 
     * @return  string return server name.
     */
    public function getName()
    {
        return $this->_serverName;
    }
    
   /**
     * Set server name
     * @access public 
     * @param string $name server name.
     */
    public function setName($name)
    {
        $this->_serverName = $name;
    }
    
   /**
     * Method return flag _isActive
     * @access public 
     * @return bool is connection active
     */
    public function isActive()
    {
        return $this->_isActive;
    }
       
   /**
     * Method return flag _isActive
     * @access public 
     */
    public function setActive($value)
    {
        $this->_isActive = $value;
    }

   /**
     * Set beckend options for current server
     * @access public 
     * @param array $options Prepeared array for Zend_Cache
     */
    public function setBeckendOptions($options)
    {
        $this->_backendOptions = $options;
    }
    
   /**
     * Set frontend options for current server
     * @access public 
     * @param array $options Prepeared array for Zend_Cache
     */
    public function setFrontendOptions($options)
    {
        $this->_frontendOptions = $options;
    }
    
   /**
     * Method for add end point for current server
     * @access public 
     * @param int $point Additional end point for current server
     */
    public function addRangeEndPoint($point)
    {
        $this->_rangeEndPoints[] = $point;        
    }
    
   /**
     * Method for set end points for current server
     * @access public 
     * @param array $points List of end points for current server
     */
    public function setRangeEndPoints($points)
    {
        $this->_rangeEndPoints = $points;        
    }

   /**
     * Method for getting list of end points for current server
     * @access public 
     * @return array List of end points for current server
     */
    public function getRangeEndPoints()
    {
        return $this->_rangeEndPoints;    
    }                  
    
   /**
     * Get Zend_Cache object for current connection
     * @access public 
     * @return Zend_Cache Prepeared Zend_Cache object for current connection
     */
    public function getConnection()
    {
        if ($this->_memCache === null) $this->connect();          
        return $this->_memCache;
    }
    
   /**
     * Connectiong to MemCache server
     * @access public 
     */
    public function connect()
    {
        
        if (!$this->checkConnection()) 
        {
            $this->setActive(false);
            return false;
        }
        if ($this->_memCache === null) 
            $this->_memCache = Zend_Cache::factory('Core', 'Memcached', $this->_frontendOptions, $this->_backendOptions);
        return true;
    }
    
    /**
     * Check For connection with MemCache Daemon
     * @access public 
     * @return bool return true or false.
     */
    public function checkConnection()
    {
        $_testSocket = @socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
        $connected = @socket_connect( $_testSocket, $this->_backendOptions['servers']['host'], $this->_backendOptions['servers']['port'] );
        if ($connected) socket_close($_testSocket);
        unset($_testSocket);
        return $connected;
    }
}
