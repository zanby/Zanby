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
 * @package    Warecorp_User
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */
class BaseWarecorp_User_BookmarkService 
{
    /**
     * DB Connection object
     */
	private $_db;
	/**
     * user id
     */
	private $_userId;
	/**
	 * service id
	 */
    private $_serviceId;

    /**
     * set user id
     * @param int $userId
     * @return Warecorp_User_BookmarkService
     * @author Artem Sukharev
     */
    public function setUserId($userId)
    {
    	$this->_userId = $userId;
    }
    
    /**
     * get user id
     * @return int userId
     * @author Artem Sukharev
     */
    public function getUserId()
    {
    	if ( $this->_userId === null ) throw new Zend_Exception('User ID is not set');
    	return $this->_userId;
    }
    
    /**
     * set user id
     * @param int $userId
     * @return Warecorp_User_BookmarkService
     * @author Artem Sukharev
     */
    public function setServiceId($serviceId)
    {
        $this->_serviceId = $serviceId;
        return $this;
    }
    
    /**
     * get user id
     * @return int userId
     * @author Artem Sukharev
     */
    public function getServiceId()
    {
    	if ( $this->_serviceId === null ) throw new Zend_Exception('Service ID is not set');
        return $this->_serviceId;
    }
    
    /**
     * Constructor
     */
    public function __construct($userId = null, $serviceId = null)
    {
    	$this->_db = Zend_Registry::get('DB');
    	if ( $userId !== null ) $this->setUserId($userId);
    	if ( $serviceId !== null ) $this->setServiceId($serviceId);
    	
    }
    
    /**
     * save new service for user
     * @return void
     * @author Artem Sukharev
     */
    public function save()
    {
    	$data = array();
    	$data['user_id']       = $this->getUserId();
    	$data['service_id']    = $this->getServiceId();
        $result = $this->_db->insert('zanby_bookmark__users', $data);
        return $result;
    }

    /**
     * remove all services for user
     * @return void
     * @author Artem Sukharev
     */
    public function removAll()
    {
        $result = $this->_db->delete('zanby_bookmark__users', $this->_db->quoteInto('user_id= ?', $this->getUserId()));
        return $result;
    }
}
