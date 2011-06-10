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
 * @created 12-Jun-2007 13:02:26
 */
class BaseWarecorp_DiscussionServer_PostList
{
    const ADMIN_ID = 1; //  Use for filtering Admin topics and posts such as "Group Creation Confermation" and all other.

    private $db;
	private $listSize;
	private $currentPage;
    private $showTopicPart = true;
    private $order;
    private $countByAuthorIds = array();

    protected static $cacheCountUnreadByTopicId;
    protected static $cacheCountByTopicId;
    protected static $cacheCountAuthorsByTopicId;
    protected static $cacheCountByTopicIdAndDate;

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
	function __destruct()
	{
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
	public function getShowTopicPart()
	{
		return $this->showTopicPart;
	}
    /**
    * @desc
    */
	public function setShowTopicPart($newVal)
	{
		$this->showTopicPart = $newVal;
		return $this;
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
	 * return posts for topic
	 * @param topicId
	 * @return array of Warecorp_DiscussionServer_Post
	 * @author Artem Sukharev
	 */
	public function findByTopicId($topicId)
	{
	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), 'zdp.post_id')
	          ->where('zdp.topic_id = ?', $topicId);
	    if ( !$this->getShowTopicPart() ) {
            $query->where('zdp.isTopic != ?', 1);
	    }
	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
	    $posts = $this->db->fetchCol($query);
	    foreach ( $posts as &$post ) {
	       $post = new Warecorp_DiscussionServer_Post($post);
	    }
	    return $posts;
	}
	/**
	 * return posts for discussion
	 * @param topicId
	 * @return array of Warecorp_DiscussionServer_Post
	 * @author Artem Sukharev
	 */
	public function findByDiscussionId($discussionId, $skipConfirmations=false)
	{
	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), 'zdp.post_id')
	          ->joininner(array('zdt' => 'zanby_discussion_topics'), 'zdt.topic_id = zdp.topic_id')
	          ->where('zdt.discussion_id = ?', $discussionId);
        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
	    if ( !$this->getShowTopicPart() ) {
            $query->where('zdp.isTopic != ?', 1);
	    }
	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
	    $posts = $this->db->fetchCol($query);
	    foreach ( $posts as &$post ) {
	       $post = new Warecorp_DiscussionServer_Post($post);
	    }
	    return $posts;
	}
	/**
	 * return unread posts
	 * @param int $user_id
	 * @param int $topicId
	 * @return array of Warecorp_DiscussionServer_Post
	 * @author Artem Sukharev
	 */
	public function findUnreadByTopicId($user_id, $topicId)
	{
	    if ( $user_id === null ) return $this->findByTopicId($topicId);

	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), 'zdp.post_id')
	          ->joinleft(array('zdpu' => 'zanby_discussion__user_post'), 'zdp.post_id = zdpu.post_id AND zdpu.user_id = '.$this->db->quote($user_id).'')
	          ->where('zdpu.post_id IS NULL')
	          ->where('zdp.topic_id = ?', $topicId);
	    if ( !$this->getShowTopicPart() ) {
            $query->where('zdp.isTopic != ?', 1);
	    }
	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
	    $posts = $this->db->fetchCol($query);
	    foreach ( $posts as &$post ) {
	       $post = new Warecorp_DiscussionServer_Post($post);
	    }
	    return $posts;
	}
	/**
	 * return unread posts
	 * @param int $user_id
	 * @param int $topicId
	 * @return array of Warecorp_DiscussionServer_Post
	 * @author Artem Sukharev
	 */
	public function findUnreadByGroupId($user_id, $groupsId, $skipConfirmations=false)
	{
	    if ( $user_id === null ) return array();
        if (is_array($groupsId) && count($groupsId)<1) return array();   

	    if ( !is_array($groupsId) ) $groupsId = array($groupsId);
	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), 'zdp.post_id')
	          ->joinleft(array('zdpu' => 'zanby_discussion__user_post'), 'zdp.post_id = zdpu.post_id AND zdpu.user_id = '.$this->db->quote($user_id).'')
	          ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
	          ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
	          ->where('zdpu.post_id IS NULL')
	          ->where('zdd.group_id IN (?)', $groupsId);
        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
	    if ( !$this->getShowTopicPart() ) $query->where('zdp.isTopic != ?', 1);
	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('zdd.position ASC');

	    $posts = $this->db->fetchCol($query);
	    foreach ( $posts as &$post ) $post = new Warecorp_DiscussionServer_Post($post);
	    return $posts;
	}
	/**
	 * return recent posts
	 * @param int $user_id
	 * @param int $groupsId
	 * @return array of Warecorp_DiscussionServer_Post
	 * @author Artem Sukharev
	 */
	public function findRecentByGroupId($user_id, $groupsId, $skipConfirmations=false)
	{
	    return $this->findUnreadByGroupId($user_id, $groupsId, $skipConfirmations);
	}
	/**
	 * return number of authors
	 * @param topicId
	 * @return int
	 * @author Artem Sukharev
	 */
	public function countAuthorsByTopicId($topicId)
	{
        if (
            null !== self::$cacheCountAuthorsByTopicId &&
            isset(self::$cacheCountAuthorsByTopicId[(int)$this->getShowTopicPart()]) &&
            isset(self::$cacheCountAuthorsByTopicId[(int)$this->getShowTopicPart()][$topicId]) ) {
            return self::$cacheCountAuthorsByTopicId[(int)$this->getShowTopicPart()][$topicId];
        }

	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(DISTINCT zdp.author_id)'))->where('zdp.topic_id = ?', $topicId);
	    if ( !$this->getShowTopicPart() ) $query->where('zdp.isTopic != ?', 1);

        return $this->db->fetchOne($query);
	}
	/**
	 * return number of posts for topic
	 * @param topicId
	 * @return int
	 * @author Artem Sukharev
	 */
	public function countByTopicId($topicId)
	{
        if (
            null !== self::$cacheCountByTopicId &&
            isset(self::$cacheCountByTopicId[(int)$this->getShowTopicPart()]) &&
            isset(self::$cacheCountByTopicId[(int)$this->getShowTopicPart()][$topicId]) ) {
            return self::$cacheCountByTopicId[(int)$this->getShowTopicPart()][$topicId];
        }

	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(zdp.post_id)'))->where('zdp.topic_id = ?', $topicId);
	    if ( !$this->getShowTopicPart() ) $query->where('zdp.isTopic != ?', 1);

	    return $this->db->fetchOne($query);
	}
	/**
	 * return number of posts postet between start and end dates
	 * @param int $topicId
	 * @param Zend_Date $startDate
	 * @param Zend_Date $endDate
	 * @return boolean
	 */
	public function countByTopicIdAndDate($topicId, $startDate, $endDate)
	{
        if (
            null !== self::$cacheCountByTopicIdAndDate &&
            isset(self::$cacheCountByTopicIdAndDate[(int)$this->getShowTopicPart()]) ) {
                if ( isset(self::$cacheCountByTopicIdAndDate[(int)$this->getShowTopicPart()][$topicId]) )
                    return self::$cacheCountByTopicIdAndDate[(int)$this->getShowTopicPart()][$topicId];
                else return 0;
        }

	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(zdp.post_id)'))->where('zdp.topic_id = ?', $topicId);
	    if ( !$this->getShowTopicPart() ) $query->where('zdp.isTopic != ?', 1);

	    $query->where('created >= ?', $startDate->get(Zend_Date::ISO_8601));
	    $query->where('created <= ?', $endDate->get(Zend_Date::ISO_8601));
	    return $this->db->fetchOne($query);
	}
    /**
     * return number of posts for discussion
     * @param int $discussionId
     * @return int
     * @author Artem Sukharev
     */
    public function countByDiscussionId($discussionId, $skipConfirmations=false)
    {
        if ( Warecorp_DiscussionServer_Discussion::$useCache ) {
            /**
             * Cache Key : DISCUSSIONSRV_COUNT_POST_BY_DISCUSSION_{$discussionId}
             * Save count of posts that belong to current discussion
             */
            $cacheKey = 'DISCUSSIONSRV_COUNT_POST_BY_DISCUSSION_'.$discussionId.(($skipConfirmations)?'_true':'_false');
            $cache = Warecorp_Cache::getCache('file');
            /**
             * Load data
             */
            if ( false === $count = $cache->load($cacheKey) ) {
                $query = $this->db->select();
                $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(zdp.post_id)'))
                      ->join(array('zdt' => 'zanby_discussion__topics'), 'zdp.topic_id = zdt.topic_id')
                      ->where('zdt.discussion_id = ?', $discussionId);
                if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
                $count = $this->db->fetchOne($query);
                $cache->save($count, $cacheKey, array(), null);
            }
            if ( !$this->getShowTopicPart() && $count > 0 ) return $count - 1;
            else return $count;
        } else {
            $query = $this->db->select();
    	    $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(zdp.post_id)'))
    	          ->join(array('zdt' => 'zanby_discussion__topics'), 'zdp.topic_id = zdt.topic_id')
    	          ->where('zdt.discussion_id = ?', $discussionId);
            if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
    	    if ( !$this->getShowTopicPart() ) $query->where('zdp.isTopic != ?', 1);
            return $this->db->fetchOne($query);
        }
    }
    /**
     * return number of posts for group
     * @param int $discussionId
     * @param bool $skipConfirmations
     * @return int
     * @author Artem Sukharev
     */
//    public function countByGroupId($groupId, $skipConfirmations = false)
//    {
//        if ( Warecorp_DiscussionServer_Discussion::$useCache ) {
//            /**
//             * Cache Key : DISCUSSIONSRV_COUNT_POST_BY_GROUP_{$groupId}_[WITHBLOG|WITHOUTBLOG]
//             * Save count of topics that belong to current group
//             */
//            $cacheKey = 'DISCUSSIONSRV_COUNT_POST_BY_GROUP_'.$groupId.'_'.(($skipConfirmations)?'_true':'_false');
//            $cacheKey .= ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) ? 'WITHOUTBLOG' : 'WITHBLOG';
//            $cache = Warecorp_Cache::getCache('file');
//            /**
//             * Load data
//             */
//            if ( false === $count = $cache->load($cacheKey) ) {
//                $query = $this->db->select();
//                $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(zdp.post_id)'))
//                      ->join(array('zdt' => 'zanby_discussion__topics'), 'zdp.topic_id = zdt.topic_id')
//                      ->join(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
//                      ->where('zdd.group_id = ?', $groupId);
//                if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
//                if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
//                $count = $this->db->fetchOne($query);
//                $cache->save($count, $cacheKey, array(), null);
//            }
//            if ( !$this->getShowTopicPart() && $count > 0 ) return $count - 1;
//            else return $count;
//        } else {
//            $query = $this->db->select();
//            $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(zdp.post_id)'))
//                  ->join(array('zdt' => 'zanby_discussion__topics'), 'zdp.topic_id = zdt.topic_id')
//                  ->join(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
//                  ->where('zdd.group_id = ?', $groupId);
//            if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
//            if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
//            if ( !$this->getShowTopicPart() ) $query->where('zdp.isTopic != ?', 1);
//            return $this->db->fetchOne($query);
//        }
//    }
    /**
     * return number of post by author
     * @param int $authorId
     * @return int
     * @author Artem Sukharev
     */
    public function countByAuthorId($authorId, $cache = false)
    {
        if ( $authorId === null ) return 0;
        if ( $cache && isset($this->countByAuthorIds[$authorId]) ) {
            return $this->countByAuthorIds[$authorId];
        } else {
            $query = $this->db->select();
            $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('count(post_id)'))->where('zdp.author_id = ?', $authorId);
            $this->countByAuthorIds[$authorId] = $this->db->fetchOne($query);
            return $this->countByAuthorIds[$authorId];
        }
    }
	/**
	 * return number of unread posts
	 * @param int $user_id
	 * @param int $topicId
	 * @return int
	 * @author Artem Sukharev
	 */
	public function countUnreadByTopicId($user_id, $topicId)
	{
	    if ( $user_id === null ) return 0;//$this->countByTopicId($topicId);

        if (
            null !== self::$cacheCountUnreadByTopicId &&
            isset(self::$cacheCountUnreadByTopicId[$user_id]) &&
            isset(self::$cacheCountUnreadByTopicId[$user_id][(int)$this->getShowTopicPart()])) {
                if ( isset(self::$cacheCountUnreadByTopicId[$user_id][(int)$this->getShowTopicPart()][$topicId]) )
                    return self::$cacheCountUnreadByTopicId[$user_id][(int)$this->getShowTopicPart()][$topicId];
                else return 0;
        }

	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(zdp.post_id)'))
	          ->joinleft(array('zdpu' => 'zanby_discussion__user_post'), 'zdp.post_id = zdpu.post_id AND zdpu.user_id = '.$this->db->quote($user_id).'')
	          ->where('zdpu.post_id IS NULL')
	          ->where('zdp.topic_id = ?', $topicId);
	    if ( !$this->getShowTopicPart() ) $query->where('zdp.isTopic != ?', 1);

	    $count = $this->db->fetchOne($query);
	    return $count;
	}
	/**
	 * return number of unread posts
	 * @param int $user_id
	 * @param int $discussionId
     * @param bool $skipConfirmations
	 * @return int
	 * @author Artem Sukharev
	 */
    public function countUnreadByDiscussionId($user_id, $discussionId, $skipConfirmations=false)
    {
	    if ( $user_id === null ) return $this->countByDiscussionId($discussionId);

	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(zdp.post_id)'))
	          ->joinleft(array('zdpu' => 'zanby_discussion__user_post'), 'zdp.post_id = zdpu.post_id AND zdpu.user_id = '.$this->db->quote($user_id).'')
	          ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
	          ->where('zdpu.post_id IS NULL')
	          ->where('zdt.discussion_id = ?', $discussionId);
        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
	    if ( !$this->getShowTopicPart() ) {
            $query->where('zdp.isTopic != ?', 1);
	    }
	    $count = $this->db->fetchOne($query);
	    return $count;
    }
	/**
	 * return number of unread posts
	 * @param int $user_id
	 * @param int $groupsId
	 * @return int
	 * @author Artem Sukharev
	 */
    public function countUnreadByGroupId($user_id, $groupsId, $skipConfirmations=false)
    {
	    if ( $user_id === null ) return 0;
        if (is_array($groupsId) && count($groupsId)<1) return 0;   
        if ( !is_array($groupsId) ) $groupsId = array($groupsId);
	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('COUNT(zdp.post_id)'))
	          ->joinleft(array('zdpu' => 'zanby_discussion__user_post'), 'zdp.post_id = zdpu.post_id AND zdpu.user_id = '.$this->db->quote($user_id).'')
	          ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
	          ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
	          ->where('zdpu.post_id IS NULL')
	          ->where('zdd.group_id IN (?)', $groupsId);
        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
	    if ( !$this->getShowTopicPart() ) $query->where('zdp.isTopic != ?', 1);
	    $count = $this->db->fetchOne($query);
	    return $count;
    }
    /**
     * return number of recent posts by group(s)
     * @param int $user_id
     * @param int $groupsId
     * @return int
     * @author Artem Sukharev
     */
    public function countRecentByGroupId($user_id, $groupsId, $skipConfirmations=false)
    {
        return $this->countUnreadByGroupId($user_id, $groupsId, $skipConfirmations);
    }
    /**
     * return number of recent posts by topic
     * @param int $user_id
     * @param int $groupsId
     * @return int
     * @author Artem Sukharev
     */
    public function countRecentByTopicId($user_id, $topicId)
    {
        return $this->countUnreadByTopicId($user_id, $topicId);
    }


    /**
    * @desc
    */
    public function buildCacheCountAuthorsByTopicId($showTopicPart = true, $skipConfirmations=false)
    {
        if ( null === self::$cacheCountAuthorsByTopicId || !isset(self::$cacheCountAuthorsByTopicId[(int)$showTopicPart]) ) {
            $query = $this->db->select();
            $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', new Zend_Db_Expr('COUNT(DISTINCT zdp.author_id)')))->group('zdp.topic_id');
            if ( !$showTopicPart ) $query->where('zdp.isTopic != ?', 1);

            if ( $skipConfirmations ) {
                $query->joininner(array('zdt' => 'zanby_discussion_topics'), 'zdt.topic_id=zdp.topic_id');
                $query->where('zdt.author_id != ?', self::ADMIN_ID);
            }

            $result = $this->db->fetchPairs($query);
            if ( null === self::$cacheCountAuthorsByTopicId ) self::$cacheCountAuthorsByTopicId = array();
            self::$cacheCountAuthorsByTopicId[(int)$showTopicPart] = $result;
        }
    }
    /**
    * @desc
    */
    public function buildCacheCountByTopicId($showTopicPart = true, $skipConfirmations=false)
    {
        if ( null === self::$cacheCountByTopicId || !isset(self::$cacheCountByTopicId[(int)$showTopicPart]) ) {
            $query = $this->db->select();
            $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'cnt' => 'COUNT(zdp.post_id)'))->group('zdp.topic_id');
            if ( !$showTopicPart ) $query->where('zdp.isTopic != ?', 1);

            if ( $skipConfirmations ) {
                $query->joininner(array('zdt' => 'zanby_discussion_topics'), 'zdt.topic_id=zdp.topic_id');
                $query->where('zdt.author_id != ?', self::ADMIN_ID);
            }

            $result = $this->db->fetchPairs($query);
            if ( null === self::$cacheCountByTopicId ) self::$cacheCountByTopicId = array();
            self::$cacheCountByTopicId[(int)$showTopicPart] = $result;
        }
    }
    /**
    * @desc
    */
    public function buildCacheCountByTopicIdAndDate($startDate, $endDate, $showTopicPart = true, $skipConfirmations=false)
    {
        if ( null === self::$cacheCountByTopicIdAndDate || !isset(self::$cacheCountByTopicIdAndDate[(int)$showTopicPart]) ) {
            $query = $this->db->select();
            $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'cnt' => 'COUNT(zdp.post_id)'))->group('zdp.topic_id');
            if ( !$showTopicPart ) $query->where('zdp.isTopic != ?', 1);

            if ( $skipConfirmations ) {
                $query->joininner(array('zdt' => 'zanby_discussion_topics'), 'zdt.topic_id=zdp.topic_id');
                $query->where('zdt.author_id != ?', self::ADMIN_ID);
            }

            $query->where('created >= ?', $startDate->get(Zend_Date::ISO_8601));
            $query->where('created <= ?', $endDate->get(Zend_Date::ISO_8601));

            $result = $this->db->fetchPairs($query);
            if ( null === self::$cacheCountByTopicIdAndDate ) self::$cacheCountByTopicIdAndDate = array();
            self::$cacheCountByTopicIdAndDate[(int)$showTopicPart] = $result;
        }
    }
    /**
    * @desc
    */
    public function buildCacheCountUnreadByTopicId($userId, $showTopicPart = true)
    {
        if ( null === self::$cacheCountUnreadByTopicId || !isset(self::$cacheCountUnreadByTopicId[$userId]) || !isset(self::$cacheCountUnreadByTopicId[$userId][(int)$showTopicPart]) ) {
            $query = $this->db->select();
            $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'count' => 'COUNT(zdp.post_id)'))
                  ->joinleft(array('zdpu' => 'zanby_discussion__user_post'), 'zdp.post_id = zdpu.post_id AND zdpu.user_id = '.$this->db->quote($userId).'')
                  ->where('zdpu.post_id IS NULL')
                  ->group('zdp.topic_id');
            if ( !$showTopicPart ) $query->where('zdp.isTopic != ?', 1);

            $result = $this->db->fetchPairs($query);
            if ( null === self::$cacheCountUnreadByTopicId ) self::$cacheCountUnreadByTopicId = array();
            self::$cacheCountUnreadByTopicId[$userId][(int)$showTopicPart] = $result;
        }
    }
}
?>
