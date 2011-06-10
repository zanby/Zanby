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
  * @version 1.0
 * @created 12-Jun-2007 13:03:51
 */
interface BaseWarecorp_DiscussionServer_iDiscussionGroup
{
    /**
     * return ID of current group
     * @return int
     * @author Artem Sukharev
     */
	public function getDiscussionGroupId();
	/**
	 * return name of group
	 * @return string
	 * @author Artem Sukharev
	 */
	public function getDiscussionGroupName();
	/**
	 * return link to group home page
	 * @return string
	 * @author Artem Sukharev
	 */
	public function getDiscussionGroupHomePageLink();
	/**
	 * return type of current group
	 * @return string
	 * @author Artem Sukharev
	 */
	public function getDiscussionGroupType();
	/**
	 * return list on groups
	 * @return array of Warecorp_DiscussionServer_iDiscussionGroup
	 * @author Artem Sukharev
	 */
	public function getDiscussionGroupList();
	/**
	 * return Warecorp_DiscussionServer_DiscussionList object for current group
	 * @return object Warecorp_DiscussionServer_DiscussionList
	 * @author Artem Sukharev
	 */
	public function getDiscussionGroupDiscussions();
	/**
	 * return Warecorp_DiscussionServer_iModerator object
	 * @return object Warecorp_DiscussionServer_iModerator
	 * @author Artem Sukharev
	 */
	public function getDiscussionGroupHost();
	/**
	 * return Warecorp_DiscussionServer_AccessManager object () [singleton]
	 * @return obj Warecorp_DiscussionServer_AccessManager
	 * @author Artem Sukharev
	 */
	public function getDiscussionAccessManager();
	/**
	 * return Warecorp_DiscussionServer_Settings object
	 * @return obj Warecorp_DiscussionServer_Settings
	 * @author Artem Sukharev
	 */
	public function getDiscussionGroupSettings();
    /**
     * delete discussion server artifacts for group
     * @author Artem Sukharev
     */
	public function deleteDiscussionGroup();
	/**
	 * create default discussion for group
	 * @author Artem Sukharev
	 */
	public function createMainDiscussion();
	/**
	 * update default discussion for group
	 * @author Artem Sukharev
	 */
	public function updateMainDiscussion();
}
?>
