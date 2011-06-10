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
 * @created 23-Jun-2007 14:57:38
 */
class BaseWarecorp_DiscussionServer_GroupSubscription
{
    private $db;
    private $id = null;
    private $userId;
	private $groupAsOne;
	private $subscriptionMode;
	private $messagesCount;
	private $subscriptionType;
	private $groupId;

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
	public function getGroupId()
	{
	    if ( $this->groupId === null ) throw new Zend_Exception("Subscription Group ID isn't defined");
		return $this->groupId;
	}

	/**
	 * Enter description here...
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setGroupId($newVal)
	{
		$this->groupId = $newVal;
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
	public function getSubscriptionMode()
	{
	    if ( $this->groupId === null ) throw new Zend_Exception("Subscription Mode isn't defined");
		return $this->subscriptionMode;
	}
	/**
	 * Enter description here...
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setSubscriptionMode($newVal)
	{
		$this->subscriptionMode = $newVal;
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
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setSubscriptionType($newVal)
	{
		$this->subscriptionType = $newVal;
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
	public function getGroupAsOne()
	{
		return $this->groupAsOne;
	}
	/**
	 * Enter description here...
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setGroupAsOne($newVal)
	{
		$this->groupAsOne = $newVal;
	}


	public function loadById($subscription_id)
	{
	    $query = $this->db->select();
	    $query->from('zanby_discussion__subscription_groups', '*')
	          ->where('subscription_id = ?', $subscription_id);
	    $subscription = $this->db->fetchRow($query);
	    if ( $subscription ) {
            $this->setId($subscription['subscription_id']);
            $this->setGroupId($subscription['group_id']);
            $this->setUserId($subscription['user_id']);
            $this->setMessagesCount($subscription['messages_count']);
	        $this->setSubscriptionMode($subscription['subscription_mode']);
	        $this->setSubscriptionType($subscription['subscription_type']);
	        $this->setGroupAsOne($subscription['group_as_one']);
	    }
	}
	
    public function save()
    {
        $data = array();
        $data['group_id']           = $this->getGroupId();
        $data['user_id']            = $this->getUserId();
        $data['subscription_mode']  = $this->getSubscriptionMode();
        if ( $this->getSubscriptionType() !== null )    $data['subscription_type']  = $this->getSubscriptionType();

        $this->setMessagesCountByType($this->getSubscriptionType());

        $data['messages_count']     = ( $this->getMessagesCount() !== null ) ? $this->getMessagesCount() : new Zend_Db_Expr('NULL');
        
        if ( $this->getGroupAsOne() !== null )          $data['group_as_one']       = (int) $this->getGroupAsOne();

        $rows_affected = $this->db->insert('zanby_discussion__subscription_groups', $data);
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
        $data['group_id']           = $this->getGroupId();
        $data['user_id']            = $this->getUserId();
        $data['subscription_mode']  = $this->getSubscriptionMode();
        if ( $this->getSubscriptionType() !== null )    $data['subscription_type']  = $this->getSubscriptionType();

        $this->setMessagesCountByType($this->getSubscriptionType());

        $data['messages_count']     = ( $this->getMessagesCount() !== null ) ? $this->getMessagesCount() : new Zend_Db_Expr('NULL');
        
        //TODO: check if SubscriptionMode was changed and move subscription messages to:
        // - group subscription when SubscriptionMode == ALL;
        // - discussion and topic subscriptions when SubscriptionMode == SPECIFIC;
        // - simply remove subscription messages when SubscriptionMode == OFF;
        // and update delivery dates of corresponding subscriptions

        if ( $this->getGroupAsOne() !== null )          $data['group_as_one']       = (int) $this->getGroupAsOne();

        $where = $this->db->quoteInto('group_id = ?', $this->getGroupId());
        $where .= " AND " . $this->db->quoteInto('user_id = ?', $this->getUserId());

        $rows_affected = $this->db->update('zanby_discussion__subscription_groups', $data, $where);
    }

    public function delete()
    {
        $table = 'zanby_discussion__subscription_groups';
        $where = $this->db->quoteInto('subscription_id = ?', $this->getId());
        $rows_affected = $this->db->delete($table, $where);
    }
    
    /**
     * Updates delivery date of this subscription
     */
    private function updateDeliveryDate()
    {
        $messageDb = Zend_Registry::get("messageDB");
        //insert subscription proxy or update existing
        $sql = 'INSERT INTO zanby_subscription__group_delivery (group_subscription_id, last_delivery) VALUES '.
                            '('.$this->getId().', NOW()) '.
                            'ON DUPLICATE KEY UPDATE last_delivery = VALUES(last_delivery)';
        $messageDb->query($sql);
    }
    
    public function updateSubscriptionMode()
    {
        $data = array();
        $data['subscription_mode'] = (Warecorp_DiscussionServer_Enum_SubscriptionMode::isIn($this->getSubscriptionType())) ? $this->getSubscriptionType() : Warecorp_DiscussionServer_Enum_SubscriptionMode::OFF;
        $where = $this->db->quoteInto('group_id = ?', $this->getGroupId());
        $where .= " AND " . $this->db->quoteInto('user_id = ?', $this->getUserId());

        $rows_affected = $this->db->update('zanby_discussion__subscription_groups', $data, $where);
    }
	static public function findByGroupAndUserId($group_id, $user_id)
	{
	    $db = Zend_Registry::get("DB");
        $query = $db->select();
	    $query->from('zanby_discussion__subscription_groups', 'subscription_id')
	          ->where('group_id = ?', $group_id)
	          ->where('user_id = ?', $user_id);
        $subscription = $db->fetchOne($query);
        if ( $subscription ) return new Warecorp_DiscussionServer_GroupSubscription($subscription);
        else {
            $subsctiption = new Warecorp_DiscussionServer_GroupSubscription();
            $subsctiption->setGroupId($group_id);
            $subsctiption->setUserId($user_id);
            $subsctiption->setSubscriptionMode(Warecorp_DiscussionServer_Enum_SubscriptionMode::OFF);
            $subsctiption->save();
            $subsctiption = new Warecorp_DiscussionServer_GroupSubscription($subsctiption->getId());
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
     * Enqueues given post to external message queue (also called subscription)
     * for delivey.
     *
     * @param Warecorp_DiscussionServer_Post $post
     */
    public static function enqueuePostForDelivery($post)
    {
        /**
         * get attributes of given $post
         */
        $topicId        = $post->getTopicId();
	    $db             = Zend_Registry::get("DB");
        $query          = $db->select();
	    
        $query->from(array('zdt' => 'zanby_discussion__topics'), array('zdt.discussion_id'))
	          ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdt.discussion_id = zdd.discussion_id', array('zdd.group_id'))
	          ->where('zdt.topic_id = ?', $topicId);
	    
        $topicAttrs     = $db->fetchRow($query);
        $discussionId   = $topicAttrs['discussion_id'];
        $groupId        = $topicAttrs['group_id'];
        
        /**
         * use transaction for putting new message for delivery
         */
	    $messageDb = Zend_Registry::get("messageDB");
	    $messageDb->beginTransaction();
	    try {
            /**
             * create new subscription message
             */
            $subscriptionMessage = new Warecorp_DiscussionServer_SubscriptionMessage();
            $subscriptionMessage->setPost($post);
            $subscriptionMessage->save();

            /**
             * for each subscription of current group do: 
             * 1) check mode of subscription (Turn off, all content of group, specific discussions and topics).
             * 2) put subscription message to corresponding queue
             * 
             * load all group subscriptions of current group
             */
            $subscriptions = Warecorp_DiscussionServer_GroupSubscriptionList::getByGroup($groupId);
            foreach ($subscriptions as $groupSubscription) {
                /**
                 * check if subscription belongs to author of the post
                 * and do not enqueue message to him
                 */
                /*
                 * Removed according Bug #4024
                 * Don't remove it
                 * 
                if ($post->getAuthorId() == $groupSubscription->getUserId())
                    continue;
                */
                
                /**
                 * check if group allows delivering messages via email
                 */
                if (Warecorp_DiscussionServer_GroupSubscription::chekDeliveryAllowed($groupSubscription->getGroupId())) {
                    if ($groupSubscription->getSubscriptionMode() == Warecorp_DiscussionServer_Enum_SubscriptionMode::ALL) {
                        /**
                         * put message to group subscription
                         */
                        Warecorp_DiscussionServer_GroupSubscription::enqueuePostForDeliveryInGroup($subscriptionMessage, $groupSubscription->getId());
                    } elseif ($groupSubscription->getSubscriptionMode() == Warecorp_DiscussionServer_Enum_SubscriptionMode::SPECIFIC) {
                        /**
                         * put message to discussion and topic subscriptions
                         */
                        Warecorp_DiscussionServer_DiscussionSubscription::enqueuePostForDelivery($subscriptionMessage, $groupSubscription->getUserId(), $discussionId);
                        Warecorp_DiscussionServer_TopicSubscription::enqueuePostForDelivery($subscriptionMessage, $groupSubscription->getUserId(), $topicId);
                    }
                } //else skip delivery
            }
    	    $messageDb->commit();
    	    
	    } catch (Exception $exc) {
    	    $messageDb->rollBack();
	        throw $exc;
	    }
    }
    
    /**
     * Puts message to discussion subscription
     *
     * @param Warecorp_DiscussionServer_SubscriptionMessage $subscriptionMessage
     * @param int $groupSubscriptionId
     */
    protected static function enqueuePostForDeliveryInGroup($subscriptionMessage, $groupSubscriptionId)
    {
        $messageDb = Zend_Registry::get("messageDB");
        //insert subscription proxy if not exists (for fail-safe)
        $sql = 'INSERT IGNORE INTO zanby_subscription__group_delivery (group_subscription_id, last_delivery) VALUES ('.
                            $groupSubscriptionId.', NULL)';
        $messageDb->query($sql);
        
        //put message to queue
        $messageDb->insert('zanby_subscription__group_messages', array('message_id' => $subscriptionMessage->getId(),
                                                                            'group_subscription_id' => $groupSubscriptionId));
    }
    
    /**
     * Delivers all subscriptions of all members of all groups.
     * This is entry point for automated notification delivery mechanism.
     */
    public static function deliverAllSubscriptions()
    {
	    $db = Zend_Registry::get("DB");
        
        //iterate over all group subscriptions. for each subscription do:
        //1) check mode of subscription (Turn off, all content of group, specific discussions and topics).
        //2) get messages from corresponding queue and deliver them

        /**
         * load all group subscriptions
         */
        $subscriptionRows = Warecorp_DiscussionServer_GroupSubscriptionList::getAll();
               
        if ( sizeof($subscriptionRows) != 0 ) {
	        foreach ($subscriptionRows as $key => &$subscriptionData) {
	            $groupId = intval($subscriptionData['group_id']);
	            /**
	             * check if group allows delivering messages via email
	             */
	            if ( Warecorp_DiscussionServer_GroupSubscription::chekDeliveryAllowed($groupId) ) {
	                if ( $subscriptionData['subscription_mode'] == Warecorp_DiscussionServer_Enum_SubscriptionMode::ALL ) {
	                    /**
	                     * use messages from current group subscription
	                     */
	                    Warecorp_DiscussionServer_GroupSubscription::deliverEnqueuedMessages($subscriptionData);
	                } elseif ( $subscriptionData['subscription_mode'] == Warecorp_DiscussionServer_Enum_SubscriptionMode::SPECIFIC ) {
	                    /**
	                     * deliver messages from discussion and topic subscriptions of this user
	                     */
	                    Warecorp_DiscussionServer_DiscussionSubscription::deliverEnqueuedMessagesOfUser(intval($subscriptionData['user_id']), intval($subscriptionData['group_id']));
	                    Warecorp_DiscussionServer_TopicSubscription::deliverEnqueuedMessagesOfUser(intval($subscriptionData['user_id']), intval($subscriptionData['group_id']));
	                }
	            } //else skip delivering
                unset($subscriptionRows[$key]);
	        }
        }
        Warecorp_DiscussionServer_GroupSubscription::cleanupFreeMessages();
    }
    
    /**
     * Delivers all messages from queue of given subscription
     * @param array $subscriptionData selected data of subscription
     */
    protected static function deliverEnqueuedMessages($subscriptionData)
    {
        $queue = new Warecorp_DiscussionServer_SubscriptionQueue(intval($subscriptionData['subscription_id']), 'group', intval($subscriptionData['group_id']), intval($subscriptionData['user_id']));
        $queue->deliver($subscriptionData['subscription_type'], intval($subscriptionData['messages_count']));
    }
    
    /**
     * Checks if group allows delivering or posting messages via email
     *
     * @param int $groupId
     * @return bool
     */
    protected static function chekDeliveryAllowed($groupId)
    {
        //check if group allows delivering messages via email
        $groupSettings = Warecorp_Group_Factory::loadById($groupId)->getDiscussionGroupSettings();
        return $groupSettings->getDiscussionStyle() == Warecorp_DiscussionServer_Enum_DiscussionStyle::WEB_AND_EMAIL;
    }
    
    /**
     * Removes free messages from database.
     */
    protected static function cleanupFreeMessages()
    {
        $messageDb = Zend_Registry::get("messageDB");
        
        //select messages that don't locate in any subscription queue
        $query = $messageDb->select();
	    $query->from(array('zsm' => 'zanby_subscription__messages'), 'zsm.id')
	          ->joinleft(array('zstm' => 'zanby_subscription__topic_messages'), 'zsm.id = zstm.message_id', array())
	          ->joinleft(array('zsdm' => 'zanby_subscription__discussion_messages'), 'zsm.id = zsdm.message_id', array())
	          ->joinleft(array('zsgm' => 'zanby_subscription__group_messages'), 'zsm.id = zsgm.message_id', array())
              ->where('zstm.message_id IS NULL')
              ->where('zsdm.message_id IS NULL')
              ->where('zsgm.message_id IS NULL');

	    $messageIds = $messageDb->fetchCol($query);
	          
	    if ($messageIds)
	          $messageDb->delete('zanby_subscription__messages',
	                             'id IN ('.implode(', ', $messageIds).')');
    }
}
?>
