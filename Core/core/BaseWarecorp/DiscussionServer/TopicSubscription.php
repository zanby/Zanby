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
 * @created 23-Jun-2007 14:58:00
 */
class BaseWarecorp_DiscussionServer_TopicSubscription
{
    private $db;
    private $id;
    private $userId;
	private $topicId;
	private $subscriptionType;
	private $messagesCount;
	private $topic;

	function __construct($id = null)
	{
	    $this->db = Zend_Registry::get("DB");
	    if ( $id !== null ) $this->loadById($id);
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
	public function getTopicId()
	{
		return $this->topicId;
	}
	/**
	 * Enter description here...
	 * @return unknown
	 * @author Artem Sukharev
	 */
	public function setTopicId($newVal)
	{
		$this->topicId = $newVal;
	}
	/**
	 * Enter description here...
	 * @return unknown
	 * @author Artem Sukharev
	 */
	public function getTopic()
	{
	    if ( $this->topic === null ) $this->setTopic();
		return $this->topic;
	}
	/**
	 * Enter description here...
	 * @return unknown
	 * @author Artem Sukharev
	 */
	public function setTopic($newVal = null)
	{
	    if ( $newVal !== null && $newVal instanceof Warecorp_DiscussionServer_Topic ) {
            $this->topic = $newVal;
	    } else {
	        $this->topic = new Warecorp_DiscussionServer_Topic($this->getTopicId());
	    }
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
	 * @return unknown
	 * @author Artem Sukharev
	 */
	public function setMessagesCount($newVal)
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
		return $this->subscriptionType;
	}
	/**
	 * Enter description here...
	 * @return unknown
	 * @author Artem Sukharev
	 */
	public function setSubscriptionType($newVal)
	{
		$this->subscriptionType = $newVal;
	}

	public function loadById($subscription_id)
	{
	    $query = $this->db->select();
	    $query->from('zanby_discussion__subscription_topics', '*')
	          ->where('subscription_id = ?', $subscription_id);
	    $subscription = $this->db->fetchRow($query);
	    if ( $subscription ) {
            $this->setId($subscription['subscription_id']);
            $this->setTopicId($subscription['topic_id']);
            $this->setUserId($subscription['user_id']);
            $this->setMessagesCount($subscription['messages_count']);
	        $this->setSubscriptionType($subscription['subscription_type']);
	    }
	}
    public function save()
    {
        $data = array();
        $data['topic_id']           = $this->getTopicId();
        $data['user_id']            = $this->getUserId();
        $data['subscription_type']  = $this->getSubscriptionType();

        $this->setMessagesCountByType($this->getSubscriptionType());

        $data['messages_count']     = ( $this->getMessagesCount() !== null ) ? $this->getMessagesCount() : new Zend_Db_Expr('NULL');

        $rows_affected = $this->db->insert('zanby_discussion__subscription_topics', $data);
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
        $data['topic_id']           = $this->getTopicId();
        $data['user_id']            = $this->getUserId();
        $data['subscription_type']  = $this->getSubscriptionType();

        $this->setMessagesCountByType($this->getSubscriptionType());

        $data['messages_count']     = ( $this->getMessagesCount() !== null ) ? $this->getMessagesCount() : new Zend_Db_Expr('NULL');

        $where = $this->db->quoteInto('topic_id = ?', $this->getTopicId());
        $where .= " AND " . $this->db->quoteInto('user_id = ?', $this->getUserId());

        $rows_affected = $this->db->update('zanby_discussion__subscription_topics', $data, $where);
    }
    public function delete()
    {
        $where = $this->db->quoteInto('subscription_id = ?', $this->getId());
        $rows_affected = $this->db->delete('zanby_discussion__subscription_topics', $where);
        
	try {
	    //delete subscription proxy on mail database
	    $messageDb = Zend_Registry::get("messageDB");
        	$where = $messageDb->quoteInto('topic_subscription_id = ?', $this->getId());
        	$rows_affected = $messageDb->delete('zanby_subscription__topic_delivery', $where);
	} catch (Exception $exc) {
		//ignore error, reason: message database is unavailable
	}
    }
    static public function findByTopicAndUserId($topic_id, $user_id)
    {
        $db = Zend_Registry::get("DB");
	    $query = $db->select();
	    $query->from('zanby_discussion__subscription_topics', 'subscription_id')
	          ->where('topic_id = ?', $topic_id)
	          ->where('user_id = ?', $user_id);
	    $subscription = $db->fetchRow($query);
	    if ( $subscription ) {
            return new Warecorp_DiscussionServer_TopicSubscription($subscription);
	    } else {
	        return null;
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
     * Puts message to topic subscription identified by
     * specified user and topic.
     *
     * @param Warecorp_DiscussionServer_SubscriptionMessage $subscriptionMessage message to put in discussion queue
     * @param int $userId id of user who has subscription
     * @param int $topicId id of topic that has subscription
     */
    public static function enqueuePostForDelivery($subscriptionMessage, $userId, $topicId)
    {
        //get discussion subscription
        $topicSubscription = Warecorp_DiscussionServer_TopicSubscription::findByTopicAndUserId($topicId, $userId);
        if (isset($topicSubscription)) {
            
            $messageDb = Zend_Registry::get("messageDB");
            //insert subscription proxy if not exists
            $sql = 'INSERT IGNORE INTO zanby_subscription__topic_delivery (topic_subscription_id, last_delivery) VALUES ('.
                            $topicSubscription->getId().', NULL)';
            $query = $messageDb->query($sql);

            $messageDb = Zend_Registry::get("messageDB");
            //put message to queue
            $messageDb->insert('zanby_subscription__topic_messages', array('message_id' => $subscriptionMessage->getId(),
                                                                                'topic_subscription_id' => $topicSubscription->getId()));
        }

    }
    
    /**
     * Updates delivery date of this subscription
     */
    private function updateDeliveryDate()
    {
        $messageDb = Zend_Registry::get("messageDB");
        //insert subscription proxy or update existing
        $sql = 'INSERT INTO zanby_subscription__topic_delivery (topic_subscription_id, last_delivery) VALUES '.
                            '('.$this->getId().', NOW()) '.
                            'ON DUPLICATE KEY UPDATE last_delivery = VALUES(last_delivery)';
        $messageDb->query($sql);
    }
    
    /**
     * Delivers all messages that user has in his queues (connected to group)
     *
     * @param int $userId
     * @param int $groupId
     */
    public static function deliverEnqueuedMessagesOfUser($userId, $groupId)
    {
        //get topic subscriptions
        $list = new Warecorp_DiscussionServer_TopicSubscriptionList();
        $topicSubscriptions = $list->findByGroupAndUserId($groupId, $userId);
        if ($topicSubscriptions) 
            foreach ($topicSubscriptions as $topicSubscription)
            {
                //deliver messages for each subscription
                $queue = new Warecorp_DiscussionServer_SubscriptionQueue($topicSubscription->getId(), 'topic', $groupId, $userId);
                $queue->deliver($topicSubscription->getSubscriptionType(), $topicSubscription->getMessagesCount());
            }
    }
}
?>
