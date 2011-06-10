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
class BaseWarecorp_User_Friend_ofFriend_Item extends Warecorp_User_Friend_Item {

    /**
     * Return true if user is friend of friend else false
     *
     * @param int $user_id 
     * @param int $friend_id
     * @return boolean
     * @author Eugene Kirdzei
     */
    static public function isUserFriendOfFriend ($user_id = null, $friend_id = null)
    {
        if (null !== $user_id && null !== $friend_id) {
            $_db = Zend_Registry::get("DB");
            $user_userId = $_db->quoteInto('user_id = ?', $user_id);
            $friend_userId = $_db->quoteInto('friend_id = ?', $user_id);
            $friend_friendId = $_db->quoteInto('friend_id = ?', $friend_id);
            $user_friendId = $_db->quoteInto('user_id = ?', $friend_id);
            $queryStr = <<<_SQL
                    SELECT COUNT( * )
                    FROM zanby_users__friends
                    WHERE ( user_id IN (
                                        SELECT IF( $user_userId, friend_id, user_id )
                                        FROM zanby_users__friends
                                        WHERE ( $user_userId OR $friend_userId)
                                       ) 
                            AND $friend_friendId
                          )
                    OR   ( friend_id IN (
                                        SELECT IF( $user_userId, friend_id, user_id )
                                        FROM zanby_users__friends
                                        WHERE ( $user_userId OR $friend_userId)
                                       ) 
                           AND $user_friendId
                         )
_SQL;
            if ( $_db->fetchOne($queryStr) ) return true;
         }
        
        return false;
    }
	
	
}

?>
