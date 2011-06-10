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

class BaseWarecorp_User_Friend_Request_Item extends Warecorp_Data_Entity {
	
	private $id ;
	private $senderId ;
	private $recipientId ;
	private $requestDate ;
    private $email ;
	
	private $messageId ;
	private $message ;
	private $sender ;
	private $recipient ;
	
	/**
	 * Class constructor
	 *
	 * @param int $id
	 * @author Eugene Kirdzei
	 */
	function __construct ( $id = null ) {
		parent::__construct ( 'zanby_users__friends_requests' ) ;
		$this->addField ( 'id' ) ;
		$this->addField ( 'sender_id', 'senderId' ) ;
		$this->addField ( 'recipient_id', 'recipientId' ) ;
		$this->addField ( 'request_date', 'requestDate' ) ;
        $this->addField ( 'email', 'email' ) ;
		
		if ($id !== null) {
			$this->pkColName = 'id' ;
			$this->loadByPk ( $id ) ;
		}
	}
	
	/**
	 * Set request id
	 * 
	 * @param int newVal
	 * @author Eugene Kirdzei
	 * 
	 */
	public function setId ( $newVal ) {
		$this->id = $newVal ;
		return $this ;
	}
	
	/**
	 * return request id
	 *
	 * @return int
	 * @author Eugene Kirdzei
	 */
	public function getId () {
		return $this->id ;
	}
	
	/**
	 * Return sender id
	 *
	 * @return int
	 */
	public function getSenderId () {
		return $this->senderId ;
	}
	
	/**
	 * 
	 * @param newVal
	 */
	public function setSenderId ( $newVal ) {
		$this->senderId = $newVal ;
		return $this ;
	}
	
	/**
	 * Return recipient id
	 *
	 * @return int
	 * @author Eugene Kirdzei
	 */
	public function getRecipientId () {
		return $this->recipientId ;
	}
	
	/**
	 * Set recipient id
	 * 
	 * @param int
	 * @author Eugene Kirdzei
	 */
	public function setRecipientId ( $newVal ) {
		$this->recipientId = $newVal ;
		return $this ;
	}
	
    /**
     * Return email
     *
     * @return int
     * @author Eugene Kirdzei
     */
    public function getEmail () {
        return $this->email ;
    }
    
    /**
     * Set email
     * 
     * @param int
     * @author Eugene Kirdzei
     */
    public function setEmail ( $newVal ) {
        $this->email = $newVal ;
        return $this ;
    }
	/**
	 * Return message id
	 *
	 * @return int
	 * @author Eugene Kirdzei
	 */
	public function getMessageId () {
		if (null === $this->messageId) {
			$this->setMessageId () ;
		}
		return $this->messageId ;
	}
	
	/**
	 * Set message id
	 * 
	 * @param int
	 * @author Eugene Kirdzei
	 */
	public function setMessageId () {
		$query = $this->_db->select () ;
		$query->from ( 'zanby_requests__relations', 'message_id' ) ;
		$query->where ( 'friend_request_id = ?', $this->getId () ) ;
		$this->messageId = $this->_db->fetchOne ( $query ) ;
		return $this ;
	}
	
	/**
	 * Return message object
	 *
	 * @return obj
	 * @author Eugene Kirdzei
	 */
	public function getMessage () {
		if (null === $this->message && null !== $this->getMessageId ()) {
			$this->setMessage () ;
		}
		return $this->message ;
	}
	
	/**
	 * Set message object
	 * 
	 * @param obj
	 * @author Eugene Kirdzei
	 */
	public function setMessage () {
		$this->message = new Warecorp_Message_Standard ( $this->getMessageId () ) ;
		return $this ;
	}
	
	/**
	 * Return request date
	 *
	 * @return string
	 * @author Eugene Kirdzei
	 */
	public function getRequestDate () {
		return $this->requestDate ;
	}
	
	/**
	 * Set request date
	 * 
	 * @param int|string
	 * @author Eugene Kirdzei
	 */
	public function setRequestDate ( $newVal ) {
		if (is_int ( $newVal ))
			$newVal = date ( 'Y-m-d H:i:s', $newVal ) ;
		$this->requestDate = $newVal ;
		return $this ;
	}
	
	/**
	 * Return object of recipient
	 *
	 * @return obj Warecorp_User
	 * @author Eugene Kirdzei
	 */
	public function getRecipient () {
		if (null !== $this->getRecipientId () && null === $this->recipient) {
			$this->setRecipient () ;
		}
		
		return $this->recipient ;
	}
	
	/**
	 * Set object of sender
	 *
	 * @return obj Warecorp_User
	 * @author Eugene Kirdzei
	 */
	public function setRecipient () {
		$this->recipient = new Warecorp_User ( 'id', $this->getRecipientId () ) ;
		return $this ;
	}
	
	/**
	 * Return object of sender
	 *
	 * @return obj Warecorp_User
	 * @author Eugene Kirdzei
	 */
	public function getSender () {
		if (null !== $this->getSenderId () && null === $this->sender) {
			$this->setSender () ;
		}
		
		return $this->sender ;
	}
	
	/**
	 * Set object of sender
	 *
	 * @return obj Warecorp_User
	 * @author Eugene Kirdzei
	 */
	public function setSender () {
		$this->sender = new Warecorp_User ( 'id', $this->getSenderId () ) ;
		return $this ;
	}
	
    /**
     * Delete users friends requests
     * replace delete method from Warecorp_Data_Entity
     *
     * @author Eugene Kirdzei
     * @todo Мне этот вариант очень не нравится и желательно заменить delete на стандартный, однако сейчас Data_Entity такую ситуацию не предусматривает
     * @return result
     */
    public function deleteAll ()
    {
         $sender_sender = $this->_db->quoteInto('sender_id = ?', $this->getSenderId( ));
         $recipient_reciprent = $this->_db->quoteInto('recipient_id = ?', $this->getRecipientId( ));
         $recipient_sender = $this->_db->quoteInto('recipient_id = ?', $this->getSenderId( ));
         $sender_recipient = $this->_db->quoteInto('sender_id = ?', $this->getRecipientId( ));
         $query = <<<_SQL
                    DELETE FROM zanby_users__friends_requests
                    WHERE ($sender_sender AND $recipient_reciprent) 
                       OR ($recipient_sender AND $sender_recipient)          
_SQL;
         return $this->_db->query($query);
    }
    
    public function addRelation($messageId = null){
    	if ( null !== $messageId ) {
        return $query = $this->_db->insert('zanby_requests__relations', 
                                           array('friend_request_id' => $this->getId(),
                                                 'message_id' => $messageId));
    	} 
    	
    	return false;
    }
}
?>
