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
 * @package    Warecorp_User_BookmarkService
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */
class BaseWarecorp_User_BookmarkService_List extends Warecorp_Abstract_List 
{
    /**
     * user id
     */
    private $_userId;
    
    /**
     * set user id
     * @param int $userId
     * @return Warecorp_User_Avatar_List
     * @author Artem Sukharev
     */
    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }
    
    /**
     * geet user id
     * @return int userId
     * @author Artem Sukharev
     */
    public function getUserId()
    {
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
            $fields[] = ( $this->getAssocKey() === null ) ? 'zbu.service_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zbu.service_id' : $this->getAssocValue();
            $query->from(array('zbu' => 'zanby_bookmark__users'), $fields);  
        } else {
            $query->from(array('zbu' => 'zanby_bookmark__users'), 'zbu.service_id');
        }
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getUserId() !== null ) {
            $query->where('zbu.user_id = ?', $this->getUserId());
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
            foreach ( $items as &$item ) $item = new Warecorp_Bookmark_Item($item);
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
        $query->from(array('zbu' => 'zanby_bookmark__users'), new Zend_Db_Expr('COUNT(zbu.service_id)'));
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getUserId() !== null ) {
            $query->where('zbu.user_id = ?', $this->getUserId());
        }
        return $this->_db->fetchOne($query);
    }
}
