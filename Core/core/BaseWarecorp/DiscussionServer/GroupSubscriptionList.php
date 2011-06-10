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
 * @created 25-Jun-2007 13:08:07
 */
class BaseWarecorp_DiscussionServer_GroupSubscriptionList
{

	private $db;

	/**
	 * Finds list of group subscriptions
	 * by group specified.
	 *
	 * @param int $groupId
	 * @return array of Warecorp_DiscussionServer_GroupSubscription
	 */
    public static function getByGroup($groupId)
    {
	    $db = Zend_Registry::get("DB");
        
        $query = $db->select();
	    $query->from('zanby_discussion__subscription_groups', 'subscription_id')
	          ->where('group_id = ?', $groupId);
        $subscriptions = $db->fetchCol($query);
	    foreach ( $subscriptions as &$subscription ) {
	       $subscription = new Warecorp_DiscussionServer_GroupSubscription($subscription);
	    }
	    return $subscriptions;
    }

    /**
     * Selects all group subscriptions and returns them
     * as array of rows (creating objects is omitted because of
     * larde data returved)
     *
     * @return array
     */
    public static function getAll()
    {
	    $db = Zend_Registry::get("DB");
        return $db->fetchAll($db->select()->from('zanby_discussion__subscription_groups'));
    }
}

