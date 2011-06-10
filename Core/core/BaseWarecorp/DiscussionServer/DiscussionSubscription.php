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
 * @author Artem Sukharev
 * @version 1.0
 * @created 23-Jun-2007 14:57:50
 */
class BaseWarecorp_DiscussionServer_DiscussionSubscription
{
    private $db;
    private $id = null;
    private $userId;
	private $discussionId;
	private $subscriptionType;
	private $messagesCount;

	function __construct($subscription_id = null)
	{
	    $this->db = Zend_Registry::get("DB");
	    if ( $subscription_id !== null ) $this->loadById($subscription_id);
	}
    /**
     * get id of subscription
     * @return int
     * @author Artem Sukharev
     */
	public function getId()
	{
		return $this->id;
	}
	/**
	 * set id of subscription
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setId($newVal)
	{
		$this->id = $newVal;
	}
	/**
	 * Enter description here...
	 * @return unknown
	 * @author Artem Sukharev
	 */
	public function getDiscussionId()
	{
	    if ( $this->discussionId === null ) throw new Zend_Exception("Subscription Discussion ID isn't defined");
		return $this->discussionId;
	}
	/**
	 * Enter description here...
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setDiscussionId($newVal)
	{
		$this->discussionId = $newVal;
	}
	/**
	 * Enter description here...
	 * @return unknown
	 * @author Artem Sukharev
	 */
	public function getUserId()
	{
	    if ( $this->userId === null ) throw new Zend_Exception("Subscription User ID isn't defined");
		return $this->userId;
	}
	/**
	 * Enter description here...
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setUserId($newVal)
	{
		$this->userId = $newVal;
	}
	/**
	 * Enter description here...
	 * @return unknown
	 * @author Artem Sukharev
	 */
	public function getMessagesCount()
	{
		return $this->messagesCount;
	}
	/**
	 * Enter description here...
	 * @param newVal
	 * @author Artem Sukharev
	 */
	private function setMessagesCount($newVal)
	{
		$this->messagesCount = $newVal;
	}
	/**
	 * Enter description here...
	 * @return unknown
	 * @author Artem Sukharev
	 */
	public function getSubscriptionType()
	{
	    if ( $this->subscriptionType === null ) throw new Zend_Exception("Subscription Type isn't defined");
		return $this->subscriptionType;
	}
	/**
	 * Enter description here...
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setSubscriptionType($newVal)
	{
		$this->subscriptionType = $newVal;
	}

	public function loadById($subscription_id)
	{
	    $query = $this->db->select();
	    $query->from('zanby_discussion__subscription_discussions', '*')
	          ->where('subscription_id = ?', $subscription_id);
	    $subscription = $this->db->fetchRow($query);
	    if ( $subscription ) {
            $this->setId($subscription['subscription_id']);
            $this->setDiscussionId($subscription['discussion_id']);
            $this->setUserId($subscription['user_id']);
            $this->setMessagesCount($subscription['messages_count']);
	        $this->setSubscriptionType($subscription['subscription_type']);
	    }
	}
    public function save()
    {
        $data = array();
        $data['discussion_id']      = $this->getDiscussionId();
        $data['user_id']            = $this->getUserId();
        $data['subscription_type']  = $this->getSubscriptionType();

        $this->setMessagesCountByType($this->getSubscriptionType());

        $data['messages_count']     = ( $this->getMessagesCount() !== null ) ? $this->getMessagesCount() : new Zend_Db_Expr('NULL');

        $rows_affected = $this->db->insert('zanby_discussion__subscription_discussions', $data);
        $this->setId($this->db->lastInsertId());

	try {
	        $this->updateDeliveryDate();
	} catch (Exception $exc) {
		//ignore this error, reason: message database is unavailable
	}
    }
    
    public function update()
    {
        $data = array();
        $data['discussion_id']      = $this->getDiscussionId();
        $data['user_id']            = $this->getUserId();
        $data['subscription_type']  = $this->getSubscriptionType();

        $this->setMessagesCountByType($this->getSubscriptionType());

        $data['messages_count']     = ( $this->getMessagesCount() !== null ) ? $this->getMessagesCount() : new Zend_Db_Expr('NULL');
        
        $where = $this->db->quoteInto('discussion_id = ?', $this->getDiscussionId());
        $where .= " AND " . $this->db->quoteInto('user_id = ?', $this->getUserId());

        $rows_affected = $this->db->update('zanby_discussion__subscription_discussions', $data, $where);
    }
    public function delete()
    {
        $where = $this->db->quoteInto('subscription_id = ?', $this->getId());
        $rows_affected = $this->db->delete('zanby_discussion__subscription_discussions', $where);
    }
	static public function findByDiscussionAndUserId($discussion_id, $user_id)
	{
	    $db = Zend_Registry::get("DB");
        $query = $db->select();
	    $query->from('zanby_discussion__subscription_discussions', 'subscription_id')
	          ->where('discussion_id = ?', $discussion_id)
	          ->where('user_id = ?', $user_id);
        $subscription = $db->fetchOne($query);
        if ( $subscription ) return new Warecorp_DiscussionServer_DiscussionSubscription($subscription);
        else {
            $subsctiption = new Warecorp_DiscussionServer_DiscussionSubscription();
            $subsctiption->setDiscussionId($discussion_id);
            $subsctiption->setUserId($user_id);
            $subsctiption->setSubscriptionType(Warecorp_DiscussionServer_Enum_SubscriptionType::PAUSE);
            $subsctiption->save();
            $subsctiption = new Warecorp_DiscussionServer_DiscussionSubscription($subsctiption->getId());
            return $subsctiption;
        }
	}

	/**
	 * Private Functions
	 */
    private function setMessagesCountByType($type)
    {
        if ( $type === null ) {
            $this->setMessagesCount(null);
        } else {
            switch ( $type ) {
                case 0 : $this->setMessagesCount(null);     break;
                case 1 : $this->setMessagesCount(null);     break;
                case 2 : $this->setMessagesCount(null);     break;
                case 3 : $this->setMessagesCount(25);       break;
                case 4 : $this->setMessagesCount(50);       break;
                case 5 : $this->setMessagesCount(null);     break;
                default : $this->setMessagesCount(null);
            }
        }
    }
    
    /**
     * Updates delivery date of this subscription
     */
    private function updateDeliveryDate()
    {
        $messageDb = Zend_Registry::get("messageDB");
        //insert subscription proxy or update existing
        $sql = 'INSERT INTO zanby_subscription__discussion_delivery (discussion_subscription_id, last_delivery) VALUES '.
                            '('.$this->getId().', NOW()) '.
                            'ON DUPLICATE KEY UPDATE last_delivery = VALUES(last_delivery)';
        $messageDb->query($sql);
    }
    
    /**
     * Puts message to discussion subscription identified by
     * specified user and discussion.
     *
     * @param Warecorp_DiscussionServer_SubscriptionMessage $subscriptionMessage message to put in discussion queue
     * @param int $userId id of user who has subscription
     * @param int $discussionId id of discussion that has subscription
     */
    public static function enqueuePostForDelivery($subscriptionMessage, $userId, $discussionId)
    {
        //get discussion subscription
        $discussionSubscription = Warecorp_DiscussionServer_DiscussionSubscription::findByDiscussionAndUserId($discussionId, $userId);
        if (isset($discussionSubscription)) {
            
            $messageDb = Zend_Registry::get("messageDB");
            //insert subscription proxy if not exists
            $sql = 'INSERT IGNORE INTO zanby_subscription__discussion_delivery (discussion_subscription_id, last_delivery) VALUES ('.
                                $discussionSubscription->getId().', NULL)';
            $query = $messageDb->query($sql);

            //put message to queue
            $messageDb->insert('zanby_subscription__discussion_messages', array('message_id' => $subscriptionMessage->getId(),
                                                                                'discussion_subscription_id' => $discussionSubscription->getId()));
        }
    }
    
    /**
     * Delivers all messages that user has in his queues (connected to group)
     *
     * @param int $userId
     * @param int $groupId
     */
    public static function deliverEnqueuedMessagesOfUser($userId, $groupId)
    {
        //get discussion subscriptions
        $discussionSubscriptions = Warecorp_DiscussionServer_DiscussionSubscriptionList::findByGroupAndUserId($groupId, $userId);
        
        /**
         * if subscription exists
         */
        if ( $discussionSubscriptions )
            foreach ($discussionSubscriptions as $discussionSubscription) {
                /**
                 * deliver messages for each subscription
                 */
                $queue = new Warecorp_DiscussionServer_SubscriptionQueue($discussionSubscription->getId(), 'discussion', $groupId, $userId);               
                $queue->deliver($discussionSubscription->getSubscriptionType(), $discussionSubscription->getMessagesCount());
            }
    }
}
?>
