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
 * Represents queue of messages in subscription.
 * @author Yury Nelipovich
 */
class BaseWarecorp_DiscussionServer_SubscriptionQueue
{
    /**
     * Enter description here...
     *
     * @var Zend_Db_Adapter_Abstract
     */
    private $db = null;
    private $subscriptionId = null;
    private $subscriptionName = null;
    private $groupId = null;
    
    /**
     * Datetime of last delivery operation
     * @var Zend_Date
     */
    private $lastDelivery = null;
    
    private $messages = null;
    
    /**
     * User who receive messages from queue
     * @var Warecorp_User
     */
    private $recipient = null;
    
    /**
     * Constructs new instance of queue
     * @param int $subscriptionId
     * @param string $subscriptionName one of 'group', 'discussion', 'topic'
     * @param int $groupId id of group that queue belongs to
     * @param int $recipientId id of recipient
     */
	function __construct($subscriptionId, $subscriptionName, $groupId, $recipientId)
	{
	    $this->db = Zend_Registry::get("messageDB");
	    $this->subscriptionId = $subscriptionId;
	    $this->subscriptionName = $subscriptionName;
	    $this->groupId = $groupId;
	    $this->recipient = new Warecorp_User('id', $recipientId);
	    $this->load($subscriptionId);
	}
	
    private function load($subscriptionId)
	{
	    $this->db = Zend_Registry::get("messageDB");
	    
	    //load last delivery
	    $query = $this->db->select();
	    $query->from('zanby_subscription__'.$this->subscriptionName.'_delivery', new Zend_Db_Expr('UNIX_TIMESTAMP(last_delivery)'))
	          ->where($this->subscriptionName.'_subscription_id = ?', $this->subscriptionId);
	    $queryResult = $this->db->fetchOne($query);
	    $this->lastDelivery = new Zend_Date(intval($queryResult));

	    //select messages from queue
	    $query = $this->db->select();
	    $query->from(array('zsm' => 'zanby_subscription__messages'), 'zsm.id')
	          ->joininner(array('zsmm' => 'zanby_subscription__'.$this->subscriptionName.'_messages'), 'zsm.id = zsmm.message_id')
	          ->where('zsmm.'.$this->subscriptionName.'_subscription_id = ?', $this->subscriptionId)
	          ->order('zsm.posted ASC');
	    $this->messages = $this->db->fetchCol($query);
	    foreach ($this->messages as $_ind => &$message) {
	       $message = new Warecorp_DiscussionServer_SubscriptionMessage($message);
	       /**
	        * check if post of this message exists
	        */
	       if ( !$message->getPost()->getId() ) {
	           /**
	            * remove records for this message
	            */
               $where = $this->db->quoteInto('id = ?', $message->getId());
               $this->db->delete('zanby_subscription__messages', $where);
	           unset($this->messages[$_ind]);
	       }
	    }
	}
	
	/**
	 * Delivers all messages from queue depending on type and messages count
	 * of subscription
	 * @param enum $subscriptionType of Warecorp_DiscussionServer_Enum_SubscriptionType
	 * @param int $messagesCount used when $subscriptionType == DIGEST25 or DIGEST50
	 */
	public function deliver($subscriptionType, $messagesCount)
	{
	    if (count($this->messages) == 0) {
            $this->updateLastDelivery();
	        return;
	    }	    
        $skipped = false;
        
        /**
	     * deliver messages depending on $subscriptionType
	     */
        
        /**
         * DIGEST25 or DIGEST50 subscription
         */
	    if ($subscriptionType == Warecorp_DiscussionServer_Enum_SubscriptionType::DIGEST25 ||
	        $subscriptionType == Warecorp_DiscussionServer_Enum_SubscriptionType::DIGEST50) {
            while ( count($this->messages) >= $messagesCount ) {
               $this->composeAmountDigest($messagesCount);
            }
	    }
	    /**
	     * SINGLE subscription
	     */ 
	    elseif ( $subscriptionType == Warecorp_DiscussionServer_Enum_SubscriptionType::SINGLE ) {
            $this->composeSingleMessages();
	    } 
	    /**
	     * DAILY subscription
	     */
	    elseif ($subscriptionType == Warecorp_DiscussionServer_Enum_SubscriptionType::DAILY && $this->dayElapsed()) {
            $this->composePeriodDigest('DAILY');
	    } 
	    /**
	     * WEEKLY
	     */
	    elseif ($subscriptionType == Warecorp_DiscussionServer_Enum_SubscriptionType::WEEKLY && $this->weekElapsed()) {	    	
            $this->composePeriodDigest('WEEKLY');
	    } 
	    /**
	     * Subscripotion is PAUSED
	     */
	    elseif ($subscriptionType == Warecorp_DiscussionServer_Enum_SubscriptionType::PAUSE) {
	        /**
	         * clear all messages from queue
	         */
	        $this->removeMessages();
	    } 
        /**
         * else skip delivering this queue at this moment
         */
	    else {
            $skipped = true;
	    }
	    
	    if (!$skipped)
            $this->updateLastDelivery();
	}
	
	/**
	 * Checks if day elapsed from last delivery
	 *
	 * @return bool
	 */
	private function dayElapsed()
	{
		$date = clone $this->lastDelivery;
        $date->setHour(0)->setMinute(0)->setSecond(0);
	    return time() >= $date->addDay(1)->toValue(Zend_Date::TIMESTAMP);
	}
	
	/**
	 * Checks if week elapsed from last delivery
	 *
	 * @return bool
	 */
	private function weekElapsed()
	{
		$date = clone $this->lastDelivery;
        $date->setHour(0)->setMinute(0)->setSecond(0);
	    return time() >= $date->addWeek(1)->toValue(Zend_Date::TIMESTAMP);
	}
	
	/**
	 * Updates datetime of last delivery in database
	 */
	private function updateLastDelivery()
	{
	    $rowsCount = $this->db->update('zanby_subscription__'.$this->subscriptionName.'_delivery',
	                               array('last_delivery' => new Zend_Db_Expr('NOW()')),
	                               $this->subscriptionName.'_subscription_id = '.$this->subscriptionId);
	}

	/**
	 * Composes amount digest and sends it
	 *
	 * @param int $messagesCount
	 */
	private function composeAmountDigest($messagesCount)
	{
	    $digest = new Warecorp_DiscussionServer_Digest('DIGEST_'.strtoupper($this->subscriptionName).'_FIXED_AMOUNT',
	                                                   $this->lastDelivery,
	                                                   $this->subscriptionName,
	                                                   $this->subscriptionId,
	                                                   $this->groupId);
	    $messageIndexes = array();
	    $addedCount = 0;
	    foreach ($this->messages as $i => $message) {
	        $digest->addMessage($message);
	        $messageIndexes[] = $i;
	        
	        $addedCount++;
	        if ($addedCount >= $messagesCount)
	           break;
	    }
	    
	    $this->removeMessages($messageIndexes);
	    $digest->setType('DIGEST_'.$messagesCount);
	    $digest->send($this->recipient);
	}
	
	/**
	 * Composes period digest and sends it
	 *
	 * @param string $type one of 'DAILY' or 'WEEKLY'
	 */
	private function composePeriodDigest($type)
	{
	    $digest = new Warecorp_DiscussionServer_Digest('DIGEST_'.strtoupper($this->subscriptionName).'_'.$type,
	                                                   $this->lastDelivery,
	                                                   $this->subscriptionName,
	                                                   $this->subscriptionId,
	                                                   $this->groupId);
	    foreach ($this->messages as $i => $message)
	        $digest->addMessage($message);
	    
	    $this->removeMessages();
	    $digest->setType('DIGEST_'.strtoupper($type));
	    $digest->send($this->recipient);
	}
	
	/**
	 * Composes single messages and sends them.
	 *
	 * @param string $type one of 'DAILY' or 'WEEKLY'
	 */
	private function composeSingleMessages()
	{

	    foreach ($this->messages as $i => $message) {
    	    $singleEmail = new Warecorp_DiscussionServer_SingleEmailMessage('DIGEST_SINGLE_MESSAGE',
                                                                            $this->lastDelivery,
                                                                            $this->subscriptionName,
                                                                            $this->subscriptionId,
                                                                            $this->groupId);
	        $singleEmail->setMessage($message);
    	    $singleEmail->setType('SINGLE');
	        $singleEmail->send($this->recipient);
	    }
	    
	    $this->removeMessages();
	}
	
	/**
	 * Removes messages from queue.
	 *
	 * @param array $messageIndexes of indexes to remove or null if all messages
	 */
	private function removeMessages($messageIndexes = null)
	{
	    if (isset($messageIndexes) && count($messageIndexes) == 0)
	       return;
	       
        $ids = array();
	    if (isset($messageIndexes)) { //if defined indexes of messages to remove
    	    foreach ($messageIndexes as $messageIndex) {
    	        $message = $this->messages[$messageIndex];
    	        $ids[] = $message->getId();
    	        unset($this->messages[$messageIndex]);
    	    }
	    } else { //remove all messages
    	    foreach ($this->messages as $message)
    	        $ids[] = $message->getId();
	        $this->messages = array();
	    }
	    
	    $idsStr = 'message_id IN ('.implode(', ', $ids).') AND ';
	    
	    $where = $idsStr.$this->subscriptionName.'_subscription_id = '.$this->subscriptionId;
        $rows_affected = $this->db->delete('zanby_subscription__'.$this->subscriptionName.'_messages', $where);
	}
}

?>
