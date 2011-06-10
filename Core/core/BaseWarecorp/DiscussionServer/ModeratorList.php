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
 * @created 12-Jun-2007 13:01:48
 */
class BaseWarecorp_DiscussionServer_ModeratorList
{
    private $db = null;
	private $listSize = null;
	private $currentPage = null;
	public $m_Warecorp_DiscussionServer_iModerator;

	function __construct()
	{
	    $this->db = Zend_Registry::get("DB");
	}

	public function getCurrentPage()
	{
		return $this->currentPage;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setCurrentPage($newVal)
	{
		$this->currentPage = $newVal;
	}

	public function getListSize()
	{
		return $this->listSize;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setListSize($newVal)
	{
		$this->listSize = $newVal;
	}

	public function getListLen()
	{
	}

	public function getList()
	{
	}

	public function findByDiscussionId($discussion_id)
	{
        if ($discussion_id === null) return array();

        $query = $this->db->select();
        $query->from('zanby_discussion__moderators', 'user_id')
              ->where('discussion_id = ?', $discussion_id);
        return $this->db->fetchCol($query);
	}
	public function findByGroupId($group_id)
	{
        $query = $this->db->select();
        $query->from('zanby_discussion__moderators', 'user_id')
              ->where('group_id = ?', $group_id);
        return $this->db->fetchCol($query);
	}
	public function findByGroupAndUserId($group_id, $user_id)
	{
        $query = $this->db->select();
        $query->from('zanby_discussion__moderators', 'user_id')
              ->where('group_id = ?', $group_id)
              ->where('user_id =?', $user_id);
        return $this->db->fetchCol($query);
	}

	public function addGroupModerator($group_id, $user_id)
	{
	    if ( $this->findByGroupAndUserId($group_id, $user_id) ) return false;

	    $data = array();
	    $data['group_id']          = $group_id;
	    $data['discussion_id']     = new Zend_Db_Expr("NULL");
	    $data['user_id']           = $user_id;

        $rows_affected = $this->db->insert('zanby_discussion__moderators', $data);
	}
	public function removeGroupModerator($group_id, $user_id)
	{
	    $where = $this->db->quoteInto('group_id = ?', $group_id);
	    $where .= " AND " . $this->db->quoteInto('user_id = ?', $user_id);
        $rows_affected = $this->db->delete('zanby_discussion__moderators', $where);
	}
}
?>
