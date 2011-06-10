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
 * @created 25-Jun-2007 13:03:47
 */
class BaseWarecorp_DiscussionServer_TopicSubscriptionList
{
	private $db;

	function __construct()
	{
	    $this->db = Zend_Registry::get("DB");
	}
	public function findByTopic($topic_id)
	{
	    $query = $this->db->select();
	    $query->from('zanby_discussion__subscription_topics', 'subscription_id')
	          ->where('topic_id = ?', $topic_id);
        $subscriptions = $this->db->fetchCol($query);
        if ( sizeof($subscriptions) != 0 ) {
            foreach ( $subscriptions as &$subscription ) $subscription = new Warecorp_DiscussionServer_TopicSubscription($subscription);
        }
        return $subscriptions;
	}
	public function findByTopicAndUserId($topic_id, $user_id)
	{
	    return Warecorp_DiscussionServer_TopicSubscription::findByTopicAndUserId($topic_id, $user_id);
	}
	public function findByGroupAndUserId($group_id, $user_id)
	{
	    $groupIds[] = $group_id;
        $query = $this->db->select();
        $query->from(array('zdst' => 'zanby_discussion__subscription_topics'), 'zdst.subscription_id')
              ->join(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdst.topic_id')
              ->join(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->where('zdst.user_id = ?', $user_id)
              ->where('zdd.group_id IN (?)', $groupIds);
        $subscriptions = $this->db->fetchCol($query);
        if ( sizeof($subscriptions) != 0 ) {
            foreach ( $subscriptions as &$subscription ) $subscription = new Warecorp_DiscussionServer_TopicSubscription($subscription);
        }
        return $subscriptions;
	}
}
?>
