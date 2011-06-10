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
class BaseWarecorp_User_Friend_Request_List extends Warecorp_Abstract_List
{

	private $userId;
	private $recipientId;
	private $isSender;
    private $email;

	
    static public function createRequestsForNewUser($objUser)
    {
        $listObj = new Warecorp_User_Friend_Request_List();
        $listObj = $listObj->setEmail($objUser->getEmail());   
        $list = array();
        $list = $listObj->getList();
        foreach($list as $key=>&$request) {
            $request->setRecipientId($objUser->getId());
            $request->setEmail(new Zend_Db_Expr('null'));
            $request->save();
            $objUser->sendFriendInvite( $request->getSender(), $objUser, $request, Warecorp::t("No message") );
        }
    }
    
    /**
     * Set sender id
     *
     * @param int newVal
     * @return self
     * @author Eugene Kirdzei
     */
    public function setSenderId($newVal)
    {
        $this->userId = $newVal;
        return $this;
    }
    /**
     * Return sender id
     *
     * @return int
     * @author Eugene Kirdzei
     */
    public function getSenderId()
    {
        return $this->userId;
    }
    
    /**
     * Set recipient id
     *
     * @param int $newVal
     * @return self
     * @author Eugene Kirdzei
     */
    public function setRecipientId($newVal)
    {
        $this->recipientId = $newVal;
        return $this;
    }
    
    /**
     * Return recipient id
     *
     * @return int
     * @author Eugene Kirdzei
     */
    public function getRecipientId()
    {
        return $this->recipientId;
    }

    /**
     * Return recipient emaIL
     *
     * @return int
     * @author Yury Zolotarsky
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * set email
     *
     * @param string $newVal
     * @return self
     * @author Yury Zolotarsky
     */
    public function setEmail($newVal)
    {
        $this->email = $newVal;
        return $this;
    }
    
    /**
     * Sets isSender TRUE if user is sender
     * else FALSE
     *
     * @param boolean newVal
     * @return self
     * @author Eugene Kirdzei
     */
    public function setIsSender($newVal)
    {
        $this->isSender = $newVal;
        return $this;
    }

    /**
     * Return "Is user sender"
     *
     * @return boolean
     * @author Eugene Kirdzei
     */
    public function getIsSender()
    {
        return $this->isSender;
    }


	/**
	 * Return list of friends requests
	 *
	 * @return array
	 * @author Eugene Kirdzei
	 */
	public function getList()
	{
		$query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            if ($this->getIsSender()) {
                $fields[] = ( $this->getAssocKey() === null ) ? 'id' : $this->getAssocKey();
                $fields[] = ( $this->getAssocValue() === null ) ? 'recipient_id' : $this->getAssocValue();
            } else {
            	$fields[] = ( $this->getAssocKey() === null ) ? 'id' : $this->getAssocKey();
                $fields[] = ( $this->getAssocValue() === null ) ? 'sender_id' : $this->getAssocValue();
            }
            $query->from('zanby_users__friends_requests', $fields);
        } else {
            $query->from('zanby_users__friends_requests', 'id');
        }

        if ($this->getSenderId()) {
            $query->where('sender_id = ?', $this->getSenderId());
        } 
        
        if ($this->getRecipientId()) {
        	$query->where('recipient_id = ?', $this->getRecipientId());
        }
        if ($this->getEmail()) {
            $query->where('email = ?', $this->getEmail());
        } else {
            $query->where('recipient_id is not null');
        }
        if ( $this->getWhere() ) $query->where( $this->getWhere() );

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        
        $items = array();
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = new Warecorp_User_Friend_Request_Item($item);
        }
        return $items;
	}


	/**
	 * Return count of friends requests for user
	 *
	 * @return int
	 * @author Eugene Kirdzei
	 */
	public function getCount()
	{
		$query = $this->_db->select();
        $query->from('zanby_users__friends_requests', new Zend_Db_Expr('COUNT(id)'));

	    if ($this->getSenderId()) {
            $query->where('sender_id = ?', $this->getSenderId());
        } 
        
        if ($this->getRecipientId()) {
            $query->where('recipient_id = ?', $this->getRecipientId());
        }
        if ($this->getEmail()) {
            $query->where('email = ?', $this->getEmail());
        }        
        
        if ( $this->getWhere() ) $query->where( $this->getWhere() );
		return $this->_db->fetchOne($query);
	}

}
?>
