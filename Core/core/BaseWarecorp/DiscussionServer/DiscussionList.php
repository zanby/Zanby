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
 * @created 12-Jun-2007 13:01:10
 */
class BaseWarecorp_DiscussionServer_DiscussionList
{
    private $db = null;
	private $currentPage = null;
	private $listSize = null;
	private $includeMain = true;    
	private $order = null;
    static private $includeBlog;

    /**
    * @desc 
    */
	function __construct()
	{
	    $this->db = Zend_Registry::get("DB");
	}
    /**
    * @desc 
    */
	public function getCurrentPage()
	{
		return $this->currentPage;
	}
	/**
    * @desc 
    */
	public function setCurrentPage($newVal)
	{
		$this->currentPage = $newVal;
		return $this;
	}
    /**
    * @desc 
    */
	public function getListSize()
	{
		return $this->listSize;
	}
	/**
    * @desc 
    */
	public function setListSize($newVal)
	{
		$this->listSize = $newVal;
		return $this;
	}
    /**
    * @desc 
    */
	public function isIncludeMain()
	{
		return (boolean) $this->includeMain;
	}
	/**
    * @desc 
    */
	public function setIncludeMain($newVal)
	{
		$this->includeMain = $newVal;
		return $this;
	}
    /**
    * @desc 
    */
    static public function isIncludeBlog()
    {
        if ( null === self::$includeBlog ) self::$includeBlog = false;
        return self::$includeBlog;
    }
    /**
    * @desc 
    */
    static public function setIncludeBlog($newValue)
    {
        self::$includeBlog = (boolean) $newValue;
    }
    /**
    * @desc 
    */
	public function getOrder()
	{
	    return $this->order;
	}
	/**
    * @desc 
    */
	public function setOrder($newVal)
	{
		$this->order = $newVal;
		return $this;
	}
    /**
    * @desc 
    */
	public function getList()
	{
	}
    /**
    * @desc 
    */
	public function getListLen()
	{
	}
	/**
	 * return most recent discussions
	 * @return array of Warecorp_DiscussionServer_Discussion
	 * @author Artem Sukharev
	 */
	public function findMostRecent()
	{
        $query = $this->db->select();
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdd.discussion_id', 'last_created' => 'MAX(zdp.created)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->order('last_created DESC')
              ->group('zdd.discussion_id');
        if ( !$this->isIncludeMain() ) $query->where('zdd.is_main = ?', 0);
        if ( !self::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
        $discussions = $this->db->fetchAll($query);
        if ( $discussions ) {
            foreach ( $discussions as &$discussion ) $discussion = new Warecorp_DiscussionServer_Discussion($discussion['discussion_id']);
        }
        return $discussions;
	}
	/**
	 * return most active discussions
	 * @param int $discussionId
	 * @return array of Warecorp_DiscussionServer_Discussion
	 * @author Artem Sukharev
	 */
	public function findMostActive()
	{
        $query = $this->db->select();

        /*
         * Old query. Has been changed according bug #2389
         *
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdd.discussion_id', 'posts_count' => 'COUNT(zdp.post_id)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->order('posts_count DESC')
              ->group('zdd.discussion_id');
        */
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdd.discussion_id', 'posts_count' => 'COUNT(zdp.post_id)', 'last_created' => 'MAX(zdp.created)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->order(array('posts_count DESC', 'last_created DESC'))
              ->group('zdd.discussion_id')
              ->having('NOW() - INTERVAL 1 WEEK <= last_created');  //  Display most active within the most recent week.
                    
        if ( !$this->isIncludeMain() ) $query->where('zdd.is_main = ?', 0);
        if ( !self::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
        $discussions = $this->db->fetchAll($query);
        if ( $discussions ) {
            foreach ( $discussions as &$discussion ) $discussion = new Warecorp_DiscussionServer_Discussion($discussion['discussion_id']);
        }
        return $discussions;
	}
	/**
	 * return list of discussions by id of group
	 * @param groupId
	 * @return array of Warecorp_DiscussionServer_Discussion
	 * @author Artem Sukharev
	 */
	public function findByGroupId($groupId)
	{
	    $query = $this->db->select();
	    $query->from(array('zdd' => 'zanby_discussion__discussions'), 'zdd.*')->where('zdd.group_id = ?', $groupId);
        if ( !$this->isIncludeMain() ) $query->where('zdd.is_main = ?', 0);
        if ( !self::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    if ( $this->getOrder() === null ) $query->order("zdd.position ASC");

	    $discussions = $this->db->fetchAll($query);
	    foreach ( $discussions as &$discussion ) {
	       $discussion = Warecorp_DiscussionServer_Discussion::findById($discussion);
	    }
	    return $discussions;
	}
	/**
	 * return unread topics
	 * @param int $user_id
	 * @param int $groupId
	 * @return array Warecorp_DiscussionServer_Discussion
	 * @author Artem Sukharev
	 */
    public function findUnreadByGroupId($user_id, $groupId)
    {
	    if ( $user_id === null ) return $this->findByGroupId($groupId);

	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('DISTINCT zdd.discussion_id'))
	          ->joinleft(array('zdpu' => 'zanby_discussion__user_post'), 'zdp.post_id = zdpu.post_id AND zdpu.user_id = '.$this->db->quote($user_id).'')
	          ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
	          ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
	          ->where('zdpu.post_id IS NULL')
	          ->where('zdd.group_id = ?', $groupId);
        if ( !$this->isIncludeMain() ) $query->where('zdd.is_main = ?', 0);
        if ( !self::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
	    if ( $this->getOrder() === null ) $query->order("zdd.position ASC");
	    $discussions = $this->db->fetchCol($query);
	    if ( sizeof($discussions) != 0 ) {
	       foreach ( $discussions as &$discussion ) $discussion = new Warecorp_DiscussionServer_Discussion($discussion);
	    }
	    return $discussions;
    }
	/**
	 * return main discussion for group
	 * @param int $groupId
	 * @return Warecorp_DiscussionServer_Discussion
	 * @author Artem Sukharev
	 */
    public function findMainByGroupId($groupId)
    {
	    $query = $this->db->select();
	    $query->from(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id')
	          ->where('zdd.group_id = ?', $groupId)
	          ->where('zdd.is_main = ?', 1);
	    $discussion = $this->db->fetchOne($query);
	    if ( !$discussion ) return null;
	    return new Warecorp_DiscussionServer_Discussion($discussion);
    }
    /**
     * return blog discussion for group
     * @param int $groupId
     * @return Warecorp_DiscussionServer_Discussion
     * @author Artem Sukharev
     */
    public function findBlogByGroupId($groupId)
    {
        $query = $this->db->select();
        $query->from(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id')
              ->where('zdd.group_id = ?', $groupId)
              ->where('zdd.is_blog = ?', 1);
        $discussion = $this->db->fetchOne($query);
        if ( !$discussion ) return null;
        return new Warecorp_DiscussionServer_Discussion($discussion);
    }
	/**
	 * return discussions count by id of group
	 * @return int
	 * @param groupId
	 * @author Artem Sukharev
	 */
//	public function countByGroupId($groupId)
//	{
//	    $query = $this->db->select();
//	    $query->from(array('zdd' => 'zanby_discussion__discussions'), new Zend_Db_Expr('COUNT(zdd.discussion_id)'))->where('zdd.group_id = ?', $groupId);
//        if ( !$this->isIncludeMain() ) $query->where('zdd.is_main = ?', 0);
//        if ( !self::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
//	    return $this->db->fetchOne($query);
//	}
	/**
	 * Searches for discussion using its full email.
	 * @param string $fullEmail
	 * @return Warecorp_DiscussionServer_Discussion or null if not found
	 */
	public function findByFullEmail($fullEmail)
	{
	    return Warecorp_DiscussionServer_Discussion::findByFullEmail($fullEmail);
	}
	/**
	 * Searches for discussion using its obsolete full email.
	 * Email is obsolete when it was unused no longer than a month ago.
	 * This method examines 3 situations:
	 * 1) main discussion changed email and child did't
	 * 2) main discussion changed email and child changed too
	 * 3) main discussion didn't change email but child changed
	 * Note: this method is based on that full email contains 2 parts: main-part
	 * and sub-part. They define main discussion and sub-discussion.
	 * @param string $fullEmail
	 * @return Warecorp_DiscussionServer_Discussion or null if not found
	 */
	public function findByObsoleteFullEmail($fullEmail)
	{
	    return Warecorp_DiscussionServer_Discussion::findByObsoleteFullEmail($fullEmail);
	}
	/**
	 * Searches for discussion by its email within group or main discussion if group id is null.
	 * Email is obsolete when it was unused no longer than a month ago.
	 * @param $discussionEmail string email of discussion
	 * @param $groupId int id of group
	 */
	public function findByEmail($discussionEmail, $groupId = null)
	{
	    $query = $this->db->select()->from(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id')->where('zdd.email = '.$this->db->quote($discussionEmail));
        if (isset($groupId)) $query->where('zdd.group_id = ?', $groupId);
        else $query->where('zdd.is_main = ?', 1);

	    $discussionId = $this->db->fetchOne($query);
        return $discussionId ? new Warecorp_DiscussionServer_Discussion(intval($discussionId)) : null;
	}
	/**
	 * Searches for discussion by its obsolete email within group or main discussion if group id is null.
	 * Email is obsolete when it was unused no longer than a month ago.
	 * @param $discussionEmail string email of discussion
	 * @param $groupId int id of group
	 * @return Warecorp_DiscussionServer_Discussion or null if not found
	 */
	public function findByObsoleteEmail($discussionEmail, $groupId = null)
	{
        $query = $this->db->select()
               ->from(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id')
               ->joininner(array('zdoe' => 'zanby_discussion__obsolete_emails'), 'zdd.discussion_id = zdoe.discussion_id')
               ->where('DATE_ADD(zdoe.changed, INTERVAL 1 MONTH) >= NOW()')
               ->where('zdoe.email = '.$this->db->quote($discussionEmail));

        if (isset($groupId))
            $query->where('zdd.group_id = ?', $groupId);
        else
            $query->where('zdd.is_main = ?', 1);

	    $discussionId = $this->db->fetchOne($query);
        return $discussionId ? new Warecorp_DiscussionServer_Discussion(intval($discussionId)) : null;
	}
}
