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
 * @created 12-Jun-2007 13:03:15
 */
class BaseWarecorp_DiscussionServer_TopicList
{
    private $db;
	private $listSize = 25;
	private $currentPage;
	private $order;
	private $_countsByDiscussionId = array();
    /** FIXME: Romove this hardcode **/
	private $_confirmationSubjects = array ('Group Creation Confirmation',
                                          'You have successfully created an  Professional Group Family',
                                          'You have successfully created an  Organic Group Family');

    const ADMIN_ID = 1;  //  Use for filtering admin posts and topics such as "Group Creation Confirmation" and etc.
    /**
     * @author Artem Sukharev
     */
	function __construct()
	{
	    $this->db = Zend_Registry::get("DB");
	}
    /**
     * @author Artem Sukharev
     */
	function __destruct()
	{
	}
    /**
     * @author Artem Sukharev
     */
	public function getCurrentPage()
	{
		return $this->currentPage;
	}
	/**
	 * @param newVal
	 */
	public function setCurrentPage($newVal)
	{
		$this->currentPage = $newVal;
		return $this;
	}
    /**
     * @author Artem Sukharev
     */
	public function getListSize()
	{
		return $this->listSize;
	}
	/**
	 * @param newVal
	 * @author Artem Sukharev
	 */
	public function setListSize($newVal)
	{
		$this->listSize = $newVal;
		return $this;
	}
    /**
    * @desc
    */
	public function getOrder()
	{
	    if ( $this->order === null ) $this->order = 'zdt.lastpostcreated DESC';
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
     * return all discussions
     * @return array of Warecorp_DiscussionServer_Discussion
     * @author Artem Sukharev
     */
	public function getList()
	{
	}
    /**
     * return count of all discussions
     * @return int
     * @author Artem Sukharev
     */
	public function getListLen()
	{
	}
	/**
	 * @param int discussionId
	 * @return array Warecorp_DiscussionServer_Topic
	 * @author Artem Sukharev
	 */
	public function findByDiscussionId($discussionId, $skipConfirmations=false)
	{
	    $query = $this->db->select();
	    $query->from(array('zdt' => 'zanby_discussion__topics'), 'zdt.*')->where('zdt.discussion_id = ?', $discussionId);
        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);

	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    $query->order($this->getOrder());

	    $topics = $this->db->fetchAll($query);
	    if ( sizeof($topics) != 0 ) {
	       foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic);
	    }
	    return $topics;
	}
	/**
	 * return most recent topics for discussion
	 * @param int $discussionId
	 * @return array of Warecorp_DiscussionServer_Topic
	 * @author Artem Sukharev
	 */
	public function findMostRecentByDiscussionId($discussionId, $skipConfirmations=false)
	{
        $query = $this->db->select();
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'last_created' => 'MAX(zdp.created)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->where('zdt.discussion_id = ?', $discussionId)
              ->order('last_created DESC')
              ->group('zdp.topic_id');
        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
        $topics = $this->db->fetchAll($query);
        if ( $topics ) {
            foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic['topic_id']);
        }
        return $topics;
	}
    /**
     * return most recent topics for group(s)
     * @param array[int] $groupId
     * @return array of Warecorp_DiscussionServer_Topic
     * @author Artem Sukharev
     */
    public function findMostRecentByGroupId($groupId, $removeConfirmationTopics = false)
    {
        if (is_array($groupId) && empty($groupId)) return array(); // crutch by Komarovski

        if ( !is_array($groupId) ) $groupId = array($groupId);
        $query = $this->db->select();
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'last_created' => 'MAX(zdp.created)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->where('zdd.group_id IN (?)', $groupId)
              ->order('last_created DESC')
              ->group('zdp.topic_id');

        if ( $removeConfirmationTopics ) $query->where('zdt.author_id != ?', self::ADMIN_ID);

        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
           $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        $topics = $this->db->fetchAll($query);
        if ( $topics ) {
            foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic['topic_id']);
        }
        return $topics;
    }
	/**
	 * return most active topics for discussion
	 * @param int $discussionId
	 * @return array of Warecorp_DiscussionServer_Topic
	 * @author Artem Sukharev
	 */
	public function findMostActiveByDiscussionId($discussionId, $skipConfirmations=false)
	{
        $query = $this->db->select();
        /*
         * Old query. Has been changed according bug #2389
         *
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'posts_count' => 'COUNT(zdp.post_id)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->where('zdt.discussion_id = ?', $discussionId)
              ->order('posts_count DESC')
              ->group('zdp.topic_id');
        */
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'posts_count' => 'COUNT(zdp.post_id)', 'last_created' => 'MAX(zdp.created)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->where('zdt.discussion_id = ?', $discussionId)
              ->order(array('posts_count DESC', 'last_created DESC'))
              ->group('zdp.topic_id')
              ->having('NOW() - INTERVAL 1 WEEK <= last_created');  //  Display most active within the most recent week.

        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
        $topics = $this->db->fetchAll($query);
        if ( $topics ) {
            foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic['topic_id']);
        }
        return $topics;
	}
    /**
     * return most active topics for group(s)
     * @param array[int] $groupId
     * @return array of Warecorp_DiscussionServer_Topic
     * @author Artem Sukharev
     */
    public function findMostActiveByGroupId($groupId, $removeConfirmationTopics = false)
    {
        if (is_array($groupId) && empty($groupId)) return array(); // crutch by Komarovski

        if ( !is_array($groupId) ) $groupId = array($groupId);
        $query = $this->db->select();
        /*
         * Old query. Has been changed according bug #2389
         *
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'posts_count' => 'COUNT(zdp.post_id)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->where('zdd.group_id IN (?)', $groupId)
              ->order('posts_count DESC')
              ->group('zdp.topic_id');
        */
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'posts_count' => 'COUNT(zdp.post_id)', 'last_created' => 'MAX(zdp.created)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->where('zdd.group_id IN (?)', $groupId)
              ->order(array('posts_count DESC', 'last_created DESC'))
              ->group('zdp.topic_id')
              ->having('NOW() - INTERVAL 1 WEEK <= last_created');  //  Display most active within the most recent week.

        if ( $removeConfirmationTopics ) $query->where('zdt.author_id != ?', self::ADMIN_ID);

        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
           $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        $topics = $this->db->fetchAll($query);
        if ( $topics ) {
            foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic['topic_id']);
        }
        return $topics;
    }
    /**
     * return most active topics for all public groups
     * @return array of Warecorp_DiscussionServer_Topic
     * @author Alexander Komarovski
     */
    public function findMostActive($removeConfirmationTopics = false)
    {
        /**
         *
         */
        $query = $this->db->select();
        /*
         * Old query. Has been changed according bug #2389
         *
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'posts_count' => 'COUNT(zdp.post_id)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->joininner(array('zgi' => 'zanby_groups__items'), 'zgi.id = zdd.group_id')
              ->where('zgi.private = 0 AND zgi.type IN (?)', array('simple', 'family'))
              ->where('zdp.author_id != 1')
              ->order('posts_count DESC')
              ->group('zdp.topic_id');
        */
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'posts_count' => 'COUNT(zdp.post_id)', 'last_created' => 'MAX(zdp.created)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->joininner(array('zgi' => 'zanby_groups__items'), 'zgi.id = zdd.group_id')
              ->where('zgi.private = 0 AND zgi.type IN (?)', array('simple', 'family'))
              ->where('zdp.author_id != '.self::ADMIN_ID)
              ->order(array('posts_count DESC', 'last_created DESC'))
              ->group('zdp.topic_id')
              ->having('NOW() - INTERVAL 1 WEEK <= last_created');  //  Display most active within the most recent week.


        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);

        if ($removeConfirmationTopics) $query->where('zdt.author_id != ?', self::ADMIN_ID);

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
           $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        $topics = $this->db->fetchAll($query);
        if ( $topics ) {
            foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic['topic_id']);
        }
        return $topics;
    }
    /**
     * return most recent topics for all public groups
     * @return array of Warecorp_DiscussionServer_Topic
     * @author Alexander Komarovski
     */
    public function findMostRecent($removeConfirmationTopics = false)
    {
        $query = $this->db->select();
        $query->from(array('zdp' => 'zanby_discussion__posts'), array('zdp.topic_id', 'last_created' => 'MAX(zdp.created)'))
              ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id')
              ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
              ->joininner(array('zgi' => 'zanby_groups__items'), 'zgi.id = zdd.group_id')
              ->where('zgi.private = 0 AND zgi.type IN (?)', array('simple', 'family'))
              ->where('zdp.author_id != '.self::ADMIN_ID);

        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);

        if ($removeConfirmationTopics) $query->where('zdt.author_id != ?', self::ADMIN_ID);

        $query->order('last_created DESC')
              ->group('zdp.topic_id');
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
           $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        $topics = $this->db->fetchAll($query);
        if ( $topics ) {
            foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic['topic_id']);
        }
        return $topics;
    }
	/**
	 *
	 * @param int $groupId
	 * @return array Warecorp_DiscussionServer_Topic
	 * @author Artem Sukharev
	 */
	public function findByGroupId($groupId, $skipConfirmations=false)
	{
	    $query = $this->db->select();
	    $query->from(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id')
	          ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
	          ->where('zdd.group_id = ?', $groupId);
        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);

	    if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
	       $query->limitPage($this->getCurrentPage(), $this->getListSize());
	    }
	    $topics = $this->db->fetchCol($query);
	    if ( sizeof($topics) != 0 ) {
	       foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic);
	    }
	    return $topics;
	}
	/**
	 * return unread topics
	 * @param int $user_id
	 * @param int $groupId
	 * @return array Warecorp_DiscussionServer_Topic
	 * @author Artem Sukharev
	 */
    public function findUnreadByGroupId($user_id, $groupId, $skipConfirmations=false)
    {
	    if ( $user_id === null ) return $this->findByGroupId($groupId);

	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), array())
	          ->joinleft(array('zdpu' => 'zanby_discussion__user_post'), 'zdp.post_id = zdpu.post_id AND zdpu.user_id = '.$this->db->quote($user_id), array())
	          ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id', array(new Zend_Db_Expr('DISTINCT zdt.topic_id')))
	          ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id', array())
	          ->where('zdpu.post_id IS NULL')
	          ->where('zdd.group_id = ?', $groupId);
        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);

	    $topics = $this->db->fetchCol($query);
	    if ( sizeof($topics) != 0 ) {
	       foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic);
	    }
	    return $topics;
    }
	/**
	 * return unread topics
	 * @param int $user_id
	 * @param int $groupId
	 * @return array Warecorp_DiscussionServer_Topic
	 * @author Artem Sukharev
	 */
    public function findUnreadByDiscussionId($user_id, $duscussionId, $skipConfirmations=false)
    {
	    if ( $user_id === null ) return $this->findByDiscussionId($duscussionId);

	    $query = $this->db->select();
	    $query->from(array('zdp' => 'zanby_discussion__posts'), array())
	          ->joinleft(array('zdpu' => 'zanby_discussion__user_post'), 'zdp.post_id = zdpu.post_id AND zdpu.user_id = '.$this->db->quote($user_id), array())
	          ->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id', array(new Zend_Db_Expr('DISTINCT zdt.topic_id')))
	          ->where('zdpu.post_id IS NULL')
	          ->where('zdt.discussion_id = ?', $duscussionId);
        if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
	    $topics = $this->db->fetchCol($query);
	    if ( sizeof($topics) != 0 ) {
	       foreach ( $topics as &$topic ) $topic = new Warecorp_DiscussionServer_Topic($topic);
	    }
	    return $topics;
    }
    /**
     *
     * @param int $groupId
     * @param bool $skipConfirmations
     * @return array Warecorp_DiscussionServer_Topic
     * @author Artem Sukharev
     */
//    public function countByGroupId($groupId, $skipConfirmations = false)
//    {
//        if ( Warecorp_DiscussionServer_Discussion::$useCache ) {
//            /**
//             * Cache Key : DISCUSSIONSRV_COUNT_TOPIC_BY_GROUP_{$groupId}_[WITHBLOG|WITHOUTBLOG]
//             * Save count of topics that belong to current group
//             */
//            $cacheKey = 'DISCUSSIONSRV_COUNT_TOPIC_BY_GROUP_'.$groupId.'_'.(($skipConfirmations)?'_true':'_false');
//            $cacheKey .= ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) ? 'WITHOUTBLOG' : 'WITHBLOG';
//            $cache = Warecorp_Cache::getCache('file');
//            /**
//             * Load data
//             */
//            if ( false === $count = $cache->load($cacheKey) ) {
//                $query = $this->db->select();
//                $query->from(array('zdt' => 'zanby_discussion__topics'), new Zend_Db_Expr('COUNT(zdt.topic_id)'))
//                      ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
//                      ->where('zdd.group_id = ?', $groupId);
//                if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
//                if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
//                $count = $this->db->fetchOne($query);
//                $cache->save($count, $cacheKey, array(), null);
//            }
//            return $count;
//        } else {
//            $query = $this->db->select();
//            $query->from(array('zdt' => 'zanby_discussion__topics'), new Zend_Db_Expr('COUNT(zdt.topic_id)'))
//                  ->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id')
//                  ->where('zdd.group_id = ?', $groupId);
//            if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) $query->where('zdd.is_blog = ?', 0);
//            if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
//
//            return $this->db->fetchOne($query);
//        }
//    }
    /**
	 * @param discussionId
	 * @author Artem Sukharev
	 */
//	public function countByDiscussionId($discussionId, $useCache = false, $skipConfirmations=false)
//	{
//	    if ( Warecorp_DiscussionServer_Discussion::$useCache ) {
//            /**
//             * Cache Key : DISCUSSIONSRV_COUNT_TOPIC_BY_DISCUSSION__{$discussionId}
//             * Save count of topics that belong to current discussion
//             */
//            $cacheKey = 'DISCUSSIONSRV_COUNT_TOPIC_BY_DISCUSSION_'.$discussionId.(($skipConfirmations)?'_true':'_false');
//            $cache = Warecorp_Cache::getCache('file');
//            /**
//             * Load data
//             */
//            if ( false === $count = $cache->load($cacheKey) ) {
//                $query = $this->db->select();
//                $query->from(array('zdt' => 'zanby_discussion__topics'), new Zend_Db_Expr('COUNT(zdt.topic_id)'))
//                      ->where('zdt.discussion_id = ?', $discussionId);
//                if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
//                $this->_countsByDiscussionId[$discussionId] = $this->db->fetchOne($query);
//                $count = $this->db->fetchOne($query);
//                $cache->save($count, $cacheKey, array(), null);
//            }
//            return $count;
//	    } else {
//    	    if ( $useCache == true && isset( $this->_countsByDiscussionId[$discussionId] ) ) {
//    	       return $this->_countsByDiscussionId[$discussionId];
//    	    } else {
//    	        $query = $this->db->select();
//        	    $query->from(array('zdt' => 'zanby_discussion__topics'), new Zend_Db_Expr('COUNT(zdt.topic_id)'))
//        	          ->where('zdt.discussion_id = ?', $discussionId);
//                if ( $skipConfirmations ) $query->where('zdt.author_id != ?', self::ADMIN_ID);
//        	    $this->_countsByDiscussionId[$discussionId] = $this->db->fetchOne($query);
//        	    return $this->_countsByDiscussionId[$discussionId];
//    	    }
//	    }
//	}
	/**
	 * Searches for topic
	 *
	 * @param string $topicName
	 * @param int $discussionId
	 * @param int $index index of topic in list of topics having same names. if it is null the latest topic will be returned.
	 * @return Warecorp_DiscussionServer_Topic
	 */
	public function findByTopicNameInDiscussion($topicName, $discussionId, $index = null)
	{
	    $query = $this->db->select();
	    $query->from(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id')
	          ->where('zdt.subject = ?', $topicName)
	          ->where('zdt.discussion_id = ?', $discussionId);
	    if (isset($index)) {
	          //$query->order('zdt.created ASC')->limit(1, $index);
	          $query->where('zdt.topic_id = ?', $index);
	    } else {
	          $query->order('zdt.topic_id ASC')->limit(1);
	    }

	    $topicId = $this->db->fetchOne($query);
	    $topic = $topicId ? new Warecorp_DiscussionServer_Topic($topicId) : null;

	    if (!isset($topic) && isset($index)) {
	        //it seems that there is no topic at given $index. return the latest topic.
	        return Warecorp_DiscussionServer_TopicList::findByTopicNameInDiscussion($topicName, $discussionId);
	    }

	    return $topic;
	}
    /**
     * find topics commented user
     * @param int $userId
     * @param array $excludeIds - ids of topic for exclude
     * @return array Warecorp_DiscussionServer_Topic
     * @author Artem Sukharev
     */
    public function findByUserCommented($userId, $excludeIds = null)
    {
        $query = $this->db->select();
        $query->from(array('zdp' => 'zanby_discussion__posts'), array());
        $query->joininner(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id', array(new Zend_Db_Expr('DISTINCT zdt.topic_id'), 'zdt.*'));
        $query->where('zdp.author_id = ?', $userId);

        if ( !Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() ) {
            $query->joininner(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id = zdt.discussion_id');
            $query->where('zdd.is_blog = ?', 0);
        }
        if ( $excludeIds !== null && is_array($excludeIds) && sizeof($excludeIds) != 0 )    $query->where('zdt.topic_id NOT IN (?)', $excludeIds);
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null )            $query->limitPage($this->getCurrentPage(), $this->getListSize());

        $query->order($this->getOrder());

        $topics = $this->db->fetchAll($query);
        if ( sizeof($topics) != 0 ) {
           foreach ( $topics as &$topic ) {
               $topic = new Warecorp_DiscussionServer_Topic($topic);
           }
        }
        return $topics;
    }
    /**
     * return array fo ids of topics excluded from my discussions for user
     * @param int $userId
     * @return array of int
     * @author Artem Sukharev
     */
    static public function getExcludedTopicIdsByUser($userId)
    {
    	$db = Zend_Registry::get('DB');
    	$query = $db->select();
    	$query->from('zanby_discussion__mydiscussions', 'excluded_topic_id');
    	$query->where('user_id = ?', $userId);
    	$res = $db->fetchAll($query);

    	if ( !$res ) return array();
    	else {
    		$return = array();
    		foreach ( $res as $item ) $return[] = $item['excluded_topic_id'];
            return $return;
    	}
    }
    /**
    * @desc
    */
    static public function addExcludedTopicIdByUser($userId, $id)
    {
    	$db = Zend_Registry::get('DB');
    	$query = $db->select();
    	$query->from('zanby_discussion__mydiscussions', new Zend_Db_Expr('count(*)'));
    	$query->where('user_id = ?', $userId);
    	$query->where('excluded_topic_id = ?', $id);
    	$res = $db->fetchOne($query);
    	if ( !$res ) {
            $data = array();
            $data['user_id']            = $userId;
            $data['excluded_topic_id']   = $id;
            $db->insert('zanby_discussion__mydiscussions', $data);
    	}
    }
}
?>
