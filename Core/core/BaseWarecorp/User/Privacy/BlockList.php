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
 * @package    Warecorp_User_Privacy
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */
class BaseWarecorp_User_Privacy_BlockList extends Warecorp_Abstract_List 
{
	/**
	 * User id
	 */
	private $_userId;
	
	/**
	 * set user Id
	 * @param int $userId
	 * @return Warecorp_User_Privacy_BlockList
	 * @author Artem Sukharev
	 */
	public function setUserId($userId)
	{
		$this->_userId = $userId;
		return $this;
	}
	
	/**
	 * return userId
	 * @return int
	 * @author Artem Sukharev
	 */
	public function getUserId()
	{
		if ( $this->_userId === null ) throw new Zend_Exception('User ID not set');
		return $this->_userId;
	}
	
	/**
	 * Constructor
	 */
	public function __construct($userId = null)
	{
		parent::__construct();
		if ( $userId !== null ) $this->setUserId($userId);
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
            $query->from(array('zub' => 'zanby_users__blocks'), $fields);  
            $query->join(array('zua' => 'zanby_users__accounts'), 'zub.blocked_user_id = zua.id');
            $query->where('zub.user_id = ?', $this->getUserId());
        } else {
            $query->from(array('zub' => 'zanby_users__blocks'), 'zua.id');  
            $query->join(array('zua' => 'zanby_users__accounts'), 'zub.blocked_user_id = zua.id');
            $query->where('zub.user_id = ?', $this->getUserId());
        }
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
     * @author Artem Sukharev
     */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('zub' => 'zanby_users__blocks'), new Zend_Db_Expr('COUNT(zub.user_id)'))
              ->where('zub.user_id = ?', $this->getUserId());
        return $this->_db->fetchOne($query);
    }
    
    /**
     * add new record in block list
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Artem Sukharev
     */
    public function add($user)
    {
    	if ( $user instanceof Warecorp_User ) $user = $user->getId();
    	$prop['user_id']           = $this->getUserId();
    	$prop['blocked_user_id']   = $user;
    	return (boolean) $this->_db->insert('zanby_users__blocks', $prop);
    }
    /**
     * add new record in block list
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    public function remove($user)
    {
    	if ( $user instanceof Warecorp_User ) $user = $user->getId();
    	$res = $this->_db->delete('zanby_users__blocks', 
    	       $this->_db->quoteInto('user_id= ?', $this->getUserId()).
    	       $this->_db->quoteInto('AND blocked_user_id = ?', $user )
    	       );
    	return (boolean) $res;
    }
    
    /**
     * check is exist user in block list
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Artem Sukharev
     */
    public function isExist($user)
    {
    	if ( $user instanceof Warecorp_User ) $user = $user->getId();
        $query = $this->_db->select();
        $query->from(array('zub' => 'zanby_users__blocks'), 'zub.user_id')
              ->where('zub.user_id = ?', ( NULL === $this->getUserId() ) ? new Zend_Db_Expr('NULL') : $this->getUserId(), 'INTEGER')
              ->where('zub.blocked_user_id = ?', ( NULL === $user) ? new Zend_Db_Expr('NULL') : $user, 'INTEGER');
        return (boolean) $this->_db->fetchOne($query);
    }

	/**
     * return list of users which block this user
     * @return array of objects
     * @author Vitaly Targonsky
     */
    public function getInvertList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zua.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zua.login' : $this->getAssocValue();
            $query->from(array('zub' => 'zanby_users__blocks'), array());
            $query->join(array('zua' => 'zanby_users__accounts'), 'zub.user_id = zua.id', $fields);
            $query->where('zub.blocked_user_id = ?', $this->getUserId());
        } else {
            $query->from(array('zub' => 'zanby_users__blocks'), array());  
            $query->join(array('zua' => 'zanby_users__accounts'), 'zub.user_id = zua.id', array('zua.id'));
            $query->where('zub.blocked_user_id = ?', $this->getUserId());
        }
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
     * @author Vitaly Targonsky
     */
    public function getInvertCount()
    {
        $query = $this->_db->select();
        $query->from(array('zub' => 'zanby_users__blocks'), new Zend_Db_Expr('COUNT(zub.user_id)'))
              ->where('zub.blocked_user_id = ?', $this->getUserId());
        return $this->_db->fetchOne($query);
    }
    
}
