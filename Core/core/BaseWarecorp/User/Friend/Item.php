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
class BaseWarecorp_User_Friend_Item extends Warecorp_Data_Entity
{
    private $userId;
    private $friendId;
    private $createdDate;
    private $friend;
    /**
     * Class constructor
     *
     * @param int|string $id1 is always users id
     * @param int|string $id2 is always frends id
     * @author Eugene Kirdzei
     */
    function __construct ($user_id = null, $friend_id = null)
    {
        parent::__construct('zanby_users__friends');
        $this->addField('user_id', 'userId');
        $this->addField('friend_id', 'friendId');
        $this->addField('created', 'createdDate');
        
        if (null !== $user_id && null !== $friend_id) {
            $query = $this->_db->select();
            $what= $this->_db->quoteInto(
                                        array("IF (user_id = ?, user_id, friend_id) as user_id",
                                              "IF (user_id <> ?, user_id, friend_id) as friend_id",
                                              "created"), $user_id
                                        );          
            
            $query->from('zanby_users__friends', $what);
            
            $query->where('(user_id = ?', $user_id);
            $query->where('friend_id = ?)', $friend_id);
            $query->orwhere('(user_id = ?', $friend_id);
            $query->where('friend_id = ?)', $user_id);
                                
            $this->loadBySql($query);
        }
    }
    
    /**
     * Return date of creation
     * 
     * @return string
     * @author Eugene Kirdzei
     */
    public function getCreatedDate ()
    {
        return $this->createdDate;
    }
    /**
     * Return friend id
     *
     * @return int
     * @author Eugene Kirdzei
     */
    public function getFriendId ()
    {
        return $this->friendId;
    }
    /**
     * Return user id
     *
     * @return int
     * @author Eugene Kirdzei
     */
    public function getUserId ()
    {
        return $this->userId;
    }
    /**
     * Set date of creation
     * 
     * @param string newVal
     * @author Eugene Kirdzei
     */
    public function setCreatedDate ($newVal)
    {
        if (is_int($newVal))
            $newVal = date('Y-m-d H:i:s', $newVal);
        $this->createdDate = $newVal;
        return $this;
    }
    /**
     * Set friend Id
     * 
     * @param int
     * @author Eugene Kirdzei
     */
    public function setFriendId ($newVal)
    {
        $this->friendId = $newVal;
        return $this;
    }
    /**
     * Set user id
     * 
     * @param int newVal
     * @author Eugene Kirdzei
     */
    public function setUserId ($newVal)
    {
        $this->userId = $newVal;
        return $this;
    }
    /**
     * Return object of friend
     *
     * @return obj
     * @author Eugene Kirdzei
     */
    public function getFriend ()
    {
        if (null !== $this->getFriendId() && null === $this->friend) {
            $this->setFriend();
        }
        return $this->friend;
    }
    /**
     * Set object of friend
     *
     * @return obj Warecorp_User
     * @author Eugene Kirdzei
     */
    public function setFriend ()
    {
        $this->friend = new Warecorp_User('id', $this->getFriendId());
        return $this;
    }
    
    /**
     * Return true if users are friends else false
     *
     * @param int $user_id 
     * @param int $friend_id
     * @return boolean
     * @author Eugene Kirdzei
     */
    static public function isUserFriend ($user_id = null, $friend_id = null)
    {
    	if (null !== $user_id && null !== $friend_id) {
            $_db = Zend_Registry::get("DB");
        	$query = $_db->select();
            $query->from('zanby_users__friends', new Zend_Db_Expr("COUNT(*)"));
            $query->where('(user_id = ?', $user_id);
            $query->where('friend_id = ?)', $friend_id);
            $query->orwhere('(user_id = ?', $friend_id);
            $query->where('friend_id = ?)', $user_id);
    		if ( $_db->fetchOne($query) ) return true;
         }
    	
    	return false;
    }
    /**
     * Save users friends relations
     * replace save method from Warecorp_Data_Entity
     *
     * @author Eugene Kirdzei
     * @todo Мне этот вариант очень не нравится и желательно заменить save на стандартный, однако сейчас Data_Entity такую ситуацию не предусматривает
     */
    public function save ()
    {
        if ($this->getUserId() == $this->getFriendId()) {
            throw new Zend_Exception("You can not add yourself!");
        }

        if (self::isUserFriend($this->getUserId(), $this->getFriendId())) {
            return;
            //throw new Zend_Exception($this->getFriend()->getLogin(). " is alredy your frend.");
        }
        
        $row = array('user_id' => $this->getUserId() , 
                     'friend_id' => $this->getFriendId() , 
                     'created' => $this->getCreatedDate());
        $this->_db->insert('zanby_users__friends', $row);
    }
    /**
     * Delete users friends relations
     * replace delete method from Warecorp_Data_Entity
     *
     * @author Eugene Kirdzei
     * @todo Мне этот вариант очень не нравится и желательно заменить delete на стандартный, однако сейчас Data_Entity такую ситуацию не предусматривает
     * @return result
     */
    public function delete ()
    {
    	 $user_user = $this->_db->quoteInto('user_id = ?', $this->getUserId( ));
         $user_friend = $this->_db->quoteInto('user_id = ?', $this->getFriendId( ));
         $friend_user = $this->_db->quoteInto('friend_id = ?', $this->getUserId( ));
         $friend_friend = $this->_db->quoteInto('friend_id = ?', $this->getFriendId( ));
         $query = <<<_SQL
                    DELETE FROM zanby_users__friends
                   WHERE ($user_user AND $friend_friend) 
                         OR ($friend_user AND $user_friend)          
_SQL;
         return $this->_db->query($query);
    }
}
?>
