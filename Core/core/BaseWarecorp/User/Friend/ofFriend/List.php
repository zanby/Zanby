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
 * @package Warecorp_User_Friend
 * @copyright Copyright (c) 2006
 * @author Eugene Kirdzei
 */
class BaseWarecorp_User_Friend_ofFriend_List extends Warecorp_User_Friend_List 
{
    /**
     * retutn friends of friends
     *
     * @return array
     * @author Eugene Kirdzei
     */
    public function getList()
    {
    	$limit = '';
    	$user_userId = $this->_db->quoteInto('user_id = ?', $this->getUserId());
        $friend_userId = $this->_db->quoteInto('friend_id = ?', $this->getUserId());
        $what = $this->_db->quoteInto("IF (user_id = ?, friend_id, user_id)", $this->getUserId());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
        	$page     = ($this->getCurrentPage() > 0)     ? $this->getCurrentPage()     : 1;
            $rowCount = ($this->getListSize() > 0) ? $this->getListSize() : 10;
            $limitCount  = (int) $rowCount;
            $limitOffset = (int) $rowCount * ($page - 1);
            $limit = "LIMIT $limitOffset, $limitCount";
        }
        
    	$query = <<<_SQL
                    SELECT DISTINCT $what
                    FROM zanby_users__friends
                    WHERE ( user_id IN (
                                        SELECT IF( $user_userId, friend_id, user_id )
                                        FROM zanby_users__friends
                                        WHERE ( $user_userId OR $friend_userId)
                                       ) 
                          )
                    OR   ( friend_id IN (
                                        SELECT IF( $user_userId, friend_id, user_id )
                                        FROM zanby_users__friends
                                        WHERE ( $user_userId OR $friend_userId)
                                       )
                         )
                    $limit
_SQL;
    	if ( $this->isAsAssoc() ) {
    	   $items = $this->_db->fetchCol($query);
    	} else {
    	   $items = $this->_db->fetchCol($query);
           foreach ( $items as &$item ) $item = new Warecorp_User('id', $item);
    	}
        return $items;
    }
    
    /**
     * Return count of friends of friends
     *
     * @return int
     * @author Eugene Kirdzei
     */
    public function getCount()
    {
        $user_userId = $this->_db->quoteInto('user_id = ?', $this->getUserId());
        $friend_userId = $this->_db->quoteInto('friend_id = ?', $this->getUserId());
        $query = <<<_SQL
                    SELECT COUNT(*)
                    FROM zanby_users__friends
                    WHERE ( user_id IN (
                                        SELECT IF( $user_userId, friend_id, user_id )
                                        FROM zanby_users__friends
                                        WHERE ( $user_userId OR $friend_userId)
                                       ) 
                          )
                    OR   ( friend_id IN (
                                        SELECT IF( $user_userId, friend_id, user_id )
                                        FROM zanby_users__friends
                                        WHERE ( $user_userId OR $friend_userId)
                                       )
                         )
_SQL;
        return $this->_db->fetchOne($query);
    }
}

?>
