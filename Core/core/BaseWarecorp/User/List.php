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

class BaseWarecorp_User_List extends Warecorp_Abstract_List
{
	/**
	 * status of users
	 */
	private $_status;
	
	/**
	 * set status of users for fetch
	 * @param array @status - allowed status for fetch
	 * @return Warecorp_User_List
	 * @author Artem Sukharev
	 */
	public function setStatus($status)
	{
		if ( !is_array($status) ) $status = array($status);
		$this->_status = $status;
		return $this;
	}
	
	/**
	 * return status of users for fetch
	 */
	public function getStatus()
	{
		if ( $this->_status === null ) return array(Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);
		else return $this->_status;
	}
    /**
     * Constructor.
     * @author Artem Sukharev
     */
    public function __construct()
    {
    	parent::__construct();
    }

    /**
     *  return list of all items
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getList()
    {
    	$query = $this->_db->select();
    	if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zua.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zua.login' : $this->getAssocValue();
            $query->from(array('zua' => 'zanby_users__accounts'), $fields);	
    	} else {
//    	    $query->from(array('zua' => 'zanby_users__accounts'), 'zua.id');
//			выбрать все данные одним запросом
    		$query->from(array('zua' => 'zanby_users__accounts'), 'zua.*');
    	}
        if ( $this->getWhere() ) {
            $query->where($this->getWhere());
        }
    	$query->where('zua.status IN (?)', $this->getStatus());
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        if ( $this->isAsAssoc() ) {
        	$items = $this->_db->fetchPairs($query);
        } else {
//          $items = $this->_db->fetchCol($query);
//	        foreach ( $items as &$item ) $item = new Warecorp_User('id', $item);
        	$items = $this->_db->fetchall($query);
//			выбрать все данные одним запросом
        	foreach ( $items as &$item ) $item = new Warecorp_User(null, $item);
        }
        
        return $items;
    }
    
    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev
     */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('zua' => 'zanby_users__accounts'), new Zend_Db_Expr('COUNT(zua.id)'));
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zua.status IN (?)', $this->getStatus());
        return $this->_db->fetchOne($query);
    }

    /**
     * return list of last registered users
     * @return array of Warecorp_User
     * @author Artem Sukharev
     * @todo delete this
     */
    public function getNewestUsers()
    {
        throw new Zend_Exception('OBSOLETE FUNCTION USED: "Warecorp_User_List::getNewestUsers()". USE Warecorp_User_List::getNewestUsersByLocation()');
    }
    /**
     * return list of last registered users by location
     * @return array of Warecorp_User
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function getNewestUsersByLocation()
    {
    	$this->setOrder('vul.register_date DESC');
    	$query = $this->_db->select();
    	if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'vul.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'vul.login' : $this->getAssocValue();
            $query->from(array('vul' => 'view_users__locations'), $fields);	
    	} else {
    	    $query->from(array('vul' => 'view_users__locations'), 'vul.id');
    	}
    	if ( $this->getWhere() ) $query->where($this->getWhere());
    	$query->where('vul.status IN (?)', $this->getStatus());
        if ( $this->getIncludeIds() ) $query->where('vul.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vul.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        if ( $this->isAsAssoc() ) {
        	$items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
	        foreach ( $items as &$item ) $item = new Warecorp_User('id', $item);
        }
        return $items;
    }
    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function getNewestUsersByLocationCount()
    {
        $query = $this->_db->select();
        $query->from(array('vul' => 'view_users__locations'), new Zend_Db_Expr('COUNT(vul.id)'));
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('vul.status IN (?)', $this->getStatus());
        if ( $this->getIncludeIds() ) $query->where('vul.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vul.id NOT IN (?)', $this->getExcludeIds());
        return $this->_db->fetchOne($query);
    }
    
}
