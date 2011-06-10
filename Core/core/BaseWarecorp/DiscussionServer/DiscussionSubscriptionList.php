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
 * @created 25-Jun-2007 13:07:52
 */
class BaseWarecorp_DiscussionServer_DiscussionSubscriptionList
{

	private $db;

	function __construct()
	{
	    $this->db = Zend_Registry::get("DB");
	}

	public function findByDiscussionId($discussion_id)
	{
	    $db = Zend_Registry::get("DB");
        $query = $db->select();
	    $query->from('zanby_discussion__subscription_discussions', 'subscription_id')
	          ->where('discussion_id = ?', $discussion_id);
        $subscriptions = $db->fetchCol($query);
        if ( sizeof($subscriptions) != 0 ) {
            foreach ( $subscriptions as &$subscription ) $subscription = new Warecorp_DiscussionServer_DiscussionSubscription($subscription);
        }
        return $subscriptions;
	}
	public function findByDiscussionAndUserId($discussion_id, $user_id)
	{
	    return Warecorp_DiscussionServer_DiscussionSubscription::findByDiscussionAndUserId($discussion_id, $user_id);
	}
    
	/**
	 * Finds all subscriptions of given user in group
	 *
	 * @param int $groupId
	 * @param int $userId
	 * @return array of Warecorp_DiscussionServer_DiscussionSubscription
	 */
	static public function findByGroupAndUserId($groupId, $userId)
	{ 
        $db = Zend_Registry::get("DB");
        $query = $db->select();
        $query->from(array('zdsd' => 'zanby_discussion__subscription_discussions'), 'zdsd.subscription_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdsd.discussion_id')
              ->where('zdd.group_id = ?', $groupId)
              ->where('zdsd.user_id = ?', $userId);
        $subscriptions = $db->fetchCol($query);
        if ( $subscriptions ) 
            foreach ($subscriptions as &$subscription)
                $subscription = new Warecorp_DiscussionServer_DiscussionSubscription($subscription);
        return $subscriptions;
	}
}
?>
