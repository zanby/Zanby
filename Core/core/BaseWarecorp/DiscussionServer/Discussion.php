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
 * @created 12-Jun-2007 12:59:23
 */
class BaseWarecorp_DiscussionServer_Discussion
{
	private $db;
	private $id;
	private $groupId;
	private $authorId;
	private $title;
	private $email;
	private $description;
	private $created;
	private $modified;
	private $group;
	private $author;
	private $main;
    private $isBlog;
	private $position;
	private $moderators;
	private $topics;
	private $posts;
	private $mainDiscussion;
    private $httpContext;

    private $isHot;

    protected $topicsCount = 0;
    protected $postsCount = 0;

    public static $useCache = false;

   /**
    * @return string
    */
    public function getHttpContext()
    {
        if (null === $this->httpContext) $this->setHttpContext();
        return $this->httpContext;
    }

   /**
    * If we create a new discussion and do not set manualy httpContext for it,
    * class should set httpContext automatically. But every new discussion
    * should have a httpContext as main one.
    * If we create a main discussion class will set httpContext as have got its group,
    * but httpContext will set automatically only if we called $this->setHttpContext()
    * without one.
    *
    * @param string $httpContext
    * @return self
    */
    public function setHttpContext($httpContext = null)
    {
        if (null === $httpContext) {
            if ( $this->getGroupId() && !$this->isMain() ) {
                $httpContext = $this->getMainDiscussion()->getHttpContext();
            }
            elseif ( defined('HTTP_CONTEXT') ) {
                $httpContext = HTTP_CONTEXT;
            }
            else {
                $httpContext = 'zanby';
            }
        }
        $this->httpContext = $httpContext;
        return $this;
    }

	/**
	 * get disussion id
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
	/**
	 * set discussion id
	 * @param int $newVal
	 * @return Warecorp_DiscussionServer_Discussion
	 */
	public function setId($newVal)
	{
		$this->id = $newVal;
		return $this;
	}
	/**
	 * get discussion title
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
	/**
	 * set discussion title
	 * @param string $newVal
	 * @return Warecorp_DiscussionServer_Discussion
	 */
	public function setTitle($newVal)
	{
		$this->title = $newVal;
		return $this;
	}
	/**
	 * Constructor
	 * @param int $discussionId - default null
	 * @author Artem Sukharev
	 */
	public function __construct($discussion = null)
	{
		$this->db = Zend_Registry::get("DB");
		if ( $discussion !== null ) {
            if ( is_array($discussion) )    $this->loadByData($discussion);
            else                            $this->load($discussion);
        }
	}
	/**
	 * return discussion moderators
	 * @return array of Warecorp_User
	 * @author Artem Sukharev
	 */
	public function getModerators()
	{
		return $this->moderators;
	}
	/**
	 * set discussion moderators
	 * @param array of Warecorp_User $newVal
	 * @author Artem Sukharev
	 */
	public function setModerators($newVal)
	{
		$this->moderators = $newVal;
		return $this;
	}
	/**
	 * return settings object for discussion
	 * @author Artem Sukharev
	 */
	public function getSettings()
	{
		return $this->settings;
	}
	/**
	 * @param newVal    newVal
	 */
	public function setSettings($newVal)
	{
		$this->settings = $newVal;
		return $this;
	}
	/**
	 * return Warecorp_DiscussionServer_TopicList object for discussion
	 * @return object Warecorp_DiscussionServer_TopicList
	 * @author Artem Sukharev
	 */
	public function getTopics()
	{
		if ( $this->topics === null ) $this->setTopics();
		return $this->topics;
	}
	/**
	 * set Warecorp_DiscussionServer_TopicList object for discussion
	 * @param newVal
	 * @return void
	 * @author Artem Sukharev
	 */
	public function setTopics($newVal = null)
	{
		if ( $newVal !== null && $newVal instanceof Warecorp_DiscussionServer_TopicList ) {
			$this->topics = $newVal;
		} else {
			$this->topics = new Warecorp_DiscussionServer_TopicList();
		}
		return $this;
	}
	/**
	 * return Warecorp_DiscussionServer_PostList object for discussion
	 * @return object Warecorp_DiscussionServer_PostList
	 * @author Artem Sukharev
	 */
	public function getPosts()
	{
		if ( $this->posts === null ) $this->setPosts();
		return $this->posts;
	}
	/**
	 * set Warecorp_DiscussionServer_PostList object for discussion
	 * @param newVal
	 * @return void
	 * @author Artem Sukharev
	 */
	public function setPosts($newVal = null)
	{
		if ( $newVal !== null && $newVal instanceof Warecorp_DiscussionServer_PostList ) {
			$this->posts = $newVal;
		} else {
			$this->posts = new Warecorp_DiscussionServer_PostList();
		}
		return $this;
	}
	/**
    * @desc
    */
    public function getGroupId()
	{
		return $this->groupId;
	}
	/**
	 * @param newVal    newVal
	 */
	public function setGroupId($newVal)
	{
		$this->groupId = $newVal;
		return $this;
	}
    /**
    * @desc
    */
	public function getGroup()
	{
		if ( $this->group === null ) {
            $this->group = Warecorp_Group_Factory::loadById($this->getGroupId());
        }
		return $this->group;
	}
	/**
	 * @param newVal    newVal
	 */
	public function setGroup(Warecorp_DiscussionServer_iDiscussionGroup $newVal = null)
	{
		$this->group = $newVal;
		return $this;
	}
    /**
    * @desc
    */
	public function getShortTitle($size = 50)
	{
		if ( strlen($this->title) > $size ) {
		   return substr($this->title, 0, $size) . '...';
		} else return $this->title;
	}
    /**
    * @desc
    */
	public function getEmail()
	{
		return $this->email;
	}
	/**
	 * @param newVal    newVal
	 */
	public function setEmail($newVal)
	{
		$this->email = $newVal;
		return $this;
	}
    /**
    * @desc
    */
	public function getFullEmail()
	{
		if ( $this->isMain() ) {
			return $this->email.'@'.DOMAIN_FOR_GROUP_EMAIL;
		} else {
			return $this->email.'.'.$this->getMainDiscussion()->getEmail().'@'.DOMAIN_FOR_GROUP_EMAIL;
		}
	}
    /**
    * @desc
    */
	public function getDescription()
	{
		return $this->description;
	}
	/**
	 * @param newVal    newVal
	 */
	public function setDescription($newVal)
	{
		$this->description = $newVal;
		return $this;
	}
	/**
    * @desc
    */
    public function getCreated()
	{
		return $this->created;
	}
	/**
	 * @param newVal
	 */
	public function setCreated($newVal)
	{
		$this->created = $newVal;
		return $this;
	}
    /**
    * @desc
    */
	public function getModified()
	{
		return $this->modified;
	}
	/**
	 * @param newVal
	 */
	public function setModified($newVal)
	{
		$this->modified = $newVal;
		return $this;
	}
	/**
    * @desc
    */
    public function isMain()
	{
		return (boolean) $this->main;
	}
	/**
	 * @param newVal
	 */
	public function setMain($newVal)
	{
		$this->main = (boolean) $newVal;
		return $this;
	}
    /**
    * @desc
    */
    public function isBlog()
    {
        return (boolean) $this->isBlog;
    }
    /**
     * @param newVal
     */
    public function setBlog($newVal)
    {
        $this->isBlog = (boolean) $newVal;
        return $this;
    }
	/**
    * @desc
    */
    public function getPosition()
	{
		return $this->position;
	}
	/**
	 * @param newVal
	 */
	public function setPosition($newVal)
	{
		$this->position = $newVal;
		return $this;
	}
	/**
    * @desc
    */
	public function isHot()
	{
        if ( null === $this->isHot ) {
		    $defaulttimezone = date_default_timezone_get();
		    date_default_timezone_set('UTC');
		    $endDate = new Zend_Date();
		    date_default_timezone_set($defaulttimezone);

		    $startDate = clone $endDate;
		    $startDate->add(-1*Warecorp_DiscussionServer_Topic::hotDaysCount, Zend_Date::DAY);

		    $query = $this->db->select();
		    $query->from(array('zdp' => 'zanby_discussion__posts'), array('count' => new Zend_Db_Expr('COUNT(zdp.post_id)')));
		    $query->join(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id = zdp.topic_id');
		    $query->where('zdt.discussion_id = ?', $this->getId());
		    $query->where('zdp.created >= ?', $startDate->get(Zend_Date::ISO_8601));
		    $query->where('zdp.created <= ?', $endDate->get(Zend_Date::ISO_8601));
		    $query->group('zdp.topic_id');
		    $rows = $this->db->fetchAll($query);
		    $count = 0;
		    if ( sizeof($rows) != 0 ) {
			    foreach ( $rows as &$row ) {
				    if ( $row['count'] >= Warecorp_DiscussionServer_Topic::hotPostsCount ) $count++;
			    }
		    }
		    $this->isHot = ( $count > 2 ) ? true : false;
        }
        return $this->isHot;
	}
	/**
	 * @param discussionId
	 */
	public static function findById($discussionId)
	{
		return new Warecorp_DiscussionServer_Discussion($discussionId);
	}
	/**
	 * @param discussionEmail
	 */
	public static function findByFullEmail($discussionEmail)
	{
		$discussionEmail = preg_replace('/@(.*?)$/i', '', $discussionEmail);

		$db = Zend_Registry::get("DB");
		$query = $db->select();
		$query->from(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id')
			  ->joininner(array('zddMain' => 'zanby_discussion__discussions'), 'zddMain.is_main = 1 AND zddMain.group_id = zdd.group_id')
			  ->where("(zdd.is_main = 1 AND CONCAT(zdd.email, '') = ?)", $discussionEmail)
			  ->orwhere("(zdd.is_main = 0 AND CONCAT(zdd.email, '.', zddMain.email , '') = ?)", $discussionEmail);
		$res = $db->fetchOne($query);
		if ( $res ) return new Warecorp_DiscussionServer_Discussion($res);
		else return null;
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
	 *
	 * @param string $fullEmail
	 * @return Warecorp_DiscussionServer_Discussion or null if not found
	 */
	public static function findByObsoleteFullEmail($fullEmail)
	{
		$fullEmail = preg_replace('/@(.*?)$/i', '', $fullEmail);

		$db = Zend_Registry::get("DB");
		$query = $db->select();
		$query->from(array('zdd' => 'zanby_discussion__discussions'), 'zdd.discussion_id')
			  ->joininner(array('zddMain' => 'zanby_discussion__discussions'), 'zddMain.is_main = 1 AND zddMain.group_id = zdd.group_id')
			  ->joinleft(array('zdoe' => 'zanby_discussion__obsolete_emails'), 'zdoe.discussion_id = zdd.discussion_id')
			  ->joinleft(array('zdoeMain' => 'zanby_discussion__obsolete_emails'), 'zdoeMain.discussion_id = zddMain.discussion_id');
		$where = "
			(zdoe.email IS NOT NULL OR zdoeMain.email IS NOT NULL) AND (
				  ( zdd.is_main = 1 && CONCAT(zdoeMain.email, '') = ".$db->quote($fullEmail)." ) OR
				  ( zdd.is_main = 0 &&
					( zdoeMain.email IS NOT NULL && zdoe.email IS NULL && CONCAT(zdd.email, '.', zdoeMain.email , '') = ".$db->quote($fullEmail).") OR
					( zdoeMain.email IS NULL && zdoe.email IS NOT NULL && CONCAT(zdoe.email, '.', zddMain.email , '') = ".$db->quote($fullEmail).") OR
					( zdoeMain.email IS NOT NULL && zdoe.email IS NOT NULL && CONCAT(zdoe.email, '.', zdoeMain.email , '') = ".$db->quote($fullEmail)." )
				  )
			)
		";
		$query->where($where);
		$res = $db->fetchOne($query);
		if ( $res ) return new Warecorp_DiscussionServer_Discussion($res);
		else return null;
	}

    /**
    * @desc
    */
	public function getAuthorId()
	{
		return $this->authorId;
	}

	/**
	 *
	 * @param newVal    newVal
	 */
	public function setAuthorId($newVal)
	{
		$this->authorId = $newVal;
		return $this;
	}

    /**
    * @desc
    */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 *
	 * @param newVal    newVal
	 */
	public function setAuthor($newVal)
	{
		$this->author = $newVal;
		return $this;
	}

	/**
	 * return Warecorp_DiscussionServer_AccessManager object () [singleton]
	 * @return obj Warecorp_DiscussionServer_AccessManager
	 * @author Artem Sukharev
	 */
	public function getDiscussionAccessManager()
	{
		return Warecorp_DiscussionServer_AccessManager_Factory::create();
	}

	/**
	 * return main discussion
	 * @return obj Warecorp_DiscussionServer_Discussion
	 * @author Artem Sukharev
	 */
	public function getMainDiscussion()
	{
		if ( $this->mainDiscussion === null ) {
			$list = new Warecorp_DiscussionServer_DiscussionList();
			$this->mainDiscussion = $list->findMainByGroupId($this->getGroupId());
		}
		return $this->mainDiscussion;
	}

    /**
    * @desc
    */
	public function hasTopics($useCache=false, $removeConfirmation=false)
	{
        return ($this->topicsCount >0);
	}

    /**
     * Returns total topics count
     * @return int
     */
    public function getTopicsCount() {
        return $this->topicsCount;
    }
    /**
     * Returns total posts count
     * @return int
     */
    public function getPostsCount() {
        return $this->postsCount;
    }

    /**
    * @desc
    */
	private function load($discussionId)
	{
        $memcache = Warecorp_Cache::getMemCache();

        $classname = get_class($this);
        $data = $memcache->load($classname.$discussionId);

        //There is no cache. Load it from DB
        if (!$data) {
            $query = $this->db->select();
            $query->from('zanby_discussion__discussions', '*')->where('discussion_id = ?', $discussionId);
            $data = $this->db->fetchRow($query);
            //Save it to memcache
            if ($data) $memcache->save($data, $classname.$data['discussion_id'], array(), Warecorp_Cache::LIFETIME_30DAYS);
        }
        
		if ( $data ) {
           $this->loadByData($data);
		}
	}
    /**
    * @desc
    */
    private function loadByData($discussion)
    {
       $this->setId($discussion['discussion_id']);
       $this->setGroupId($discussion['group_id']);
       $this->setAuthorId($discussion['author_id']);
       $this->setTitle($discussion['title']);
       $this->setEmail($discussion['email']);
       $this->setDescription($discussion['description']);
       $this->setCreated($discussion['created']);
       $this->setModified($discussion['modified']);
       $this->setMain($discussion['is_main']);
       $this->setBlog($discussion['is_blog']);
       $this->setPosition($discussion['position']);
       $this->setHttpContext($discussion['http_context']);
       $this->topicsCount = $discussion['topics_count'];
       $this->postsCount = $discussion['posts_count'];
    }

    /**
     * @desc Clears memcache instance for current object and all related objects.
     * @return void
     */
    public function clearMemcache() {
        $memcache = Warecorp_Cache::getMemcache();
        $classname = get_class($this);
        $memcache->remove($classname.$this->getId());
        $this->getGroup()->clearMemcache();
    }
	/**
	 * save new instance of post discussion
	 * @author Artem Sukharev
	 */
	public function save()
	{
        $this->clearMemcache();

		$data = array();
		$data['group_id']      = $this->getGroupId();
		$data['author_id']     = $this->getAuthorId();
		$data['title']         = $this->getTitle();
		$data['email']         = $this->getEmail();
		$data['description']   = $this->getDescription();
		$data['created']       = new Zend_Db_Expr('NOW()');
		$data['modified']      = new Zend_Db_Expr('NOW()');
		$data['is_main']       = (int) $this->isMain();
        $data['is_blog']       = (int) $this->isBlog();
		$data['position']      = $this->_getMaxPosition() + 1;

       /**
        * Method Warecorp_DiscussionServer_Discussion::getHttpContext() should called after
        * when Warecorp_DiscussionServer_Discussion::groupId already setted, because
        * if httpContext isn`t set, class try to set it themself by read httpContext property
        * from Main Discussion in current Group.
        * @see $this->setHttpContext()
        */
        $data['http_context']  = $this->getHttpContext();

		$rows_affected = $this->db->insert('zanby_discussion__discussions', $data);
		$this->setId($this->db->lastInsertId());
	}

	/**
	 * update existent instance of discussion
	 * @author Artem Sukharev
	 */
	public function update()
	{
		$data = array();
		$data['group_id']      = $this->getGroupId();
		$data['author_id']     = $this->getAuthorId();
		$data['title']         = $this->getTitle();
		$data['email']         = $this->getEmail();
		$data['description']   = $this->getDescription();
		$data['modified']      = new Zend_Db_Expr('NOW()');
		$data['is_main']       = (int) $this->isMain();
        $data['is_blog']       = (int) $this->isBlog();
		$data['position']      = $this->getPosition();
        $data['http_context']  = $this->getHttpContext();

		$where = $this->db->quoteInto('discussion_id = ?', $this->getId());
		$rows_affected = $this->db->update('zanby_discussion__discussions', $data, $where);
	}

	public function updateEmail()
	{
		$this->db->beginTransaction();

		try {
			//first log old email to obsolete emails table
			$this->db->query('INSERT INTO zanby_discussion__obsolete_emails (`discussion_id`, `changed`, `email`)
												(SELECT zdd.`discussion_id`, NOW(), zdd.`email` FROM zanby_discussion__discussions as zdd
												WHERE '.$this->db->quoteInto('zdd.`discussion_id` = ?', $this->getId()).' LIMIT 1)
												ON DUPLICATE KEY UPDATE `changed` = NOW(), `discussion_id` = VALUES(`discussion_id`)');

			//update email
			$data = array();
			$data['email']         = $this->getEmail();
			$data['modified']      = new Zend_Db_Expr('NOW()');
			$where = $this->db->quoteInto('discussion_id = ?', $this->getId());
			$rows_affected = $this->db->update('zanby_discussion__discussions', $data, $where);

			//check if new email already is in the obsolete emails table and delete it (since now it should not be obsolete)
			$this->db->delete('zanby_discussion__obsolete_emails', $this->db->quoteInto('`email` = ?', $this->getEmail()));

			$this->db->commit();

		} catch (Exception $exc) {
			$this->db->rollBack();
			throw $exc;
		}

	}

	public function updatePosition()
	{
		$data = array();
		$data['position']      = $this->getPosition();
		$where = $this->db->quoteInto('discussion_id = ?', $this->getId());
		$rows_affected = $this->db->update('zanby_discussion__discussions', $data, $where);
	}

	/**
	 * delete existent instance of discussion
	 * @author Artem Sukharev
	 */
	public function delete($allowRemoveMain = false, $allowRemoveBlog = false)
	{
		if ( $this->isMain() && !$allowRemoveMain ) return false;
        if ( $this->isBlog() && !$allowRemoveBlog ) return false;

		// remove all discussion subscriptions
		$subscription = new Warecorp_DiscussionServer_DiscussionSubscriptionList();
		$subscriptions = $subscription->findByDiscussionId($this->getId());

		if ( sizeof($subscriptions) != 0 ) {
			foreach ( $subscriptions as $subs ) {
				$subs->delete();
			}
		}

		// remove all discussion topics
		$topics = $this->getTopics()->findByDiscussionId($this->getId());
		if ( sizeof($topics) != 0 ) {
		   foreach ( $topics as &$topic ) {
				$topic->delete();
		   }
		}
		//  remove discussion
		$where = $this->db->quoteInto('discussion_id = ?', $this->getId());
		$rows_affected = $this->db->delete('zanby_discussion__discussions', $where);

		$this->_updatePositions();

		return true;

	}
	/**
	 * set all posts of discussion as readed
	 * @param int $user_id
	 * @return void
	 * @author Artem Sukharev
	 */
	public function setReadedForUser($user_id)
	{
		if ( $user_id !== null ) {
			$topics = $this->getTopics()->findByDiscussionId($this->getId());
			if ( sizeof($topics) != 0 ) {
				foreach ($topics as $topic) {
					$topic->setReadedForUser($user_id);
				}
			}
		}
		return $this;
	}
	/**
	 * check uniq email for discussion
	 * @param int $group_id
	 * @param string $email
	 * @param int $exclude
	 * @return boolean
	 * @author Artem Sukharev
	 */
	static public function checkUniqEmail($group_id, $email, $exclude = null)
	{
		/**
		 * check if this email exists in subdiscussions of group
		 */
		$db = Zend_Registry::get("DB");
		$query = $db->select();
		$query->from('zanby_discussion__discussions', 'discussion_id')
			  ->where('group_id = ?', $group_id)
			  ->where('email = ?', $email)
			  ->where('is_main != ?', 1);
		if ( $exclude !== null ) {
			$query->where('discussion_id != ?', $exclude);
		}
		$res = $db->fetchCol($query);
		if ( sizeof($res) != 0 ) return false;

		/**
		 * check if this email exists in main discussions
		 */
		$list = new Warecorp_DiscussionServer_DiscussionList();
		$mainDiscussion = $list->findMainByGroupId($group_id);

		$fullEmail = $email.".".$mainDiscussion->getEmail();
		return self::checkUniqMainEmail($fullEmail);
	}
	/**
	 * check uniq email for main discussion
	 * @param string $email
	 * @param int $exclude
	 * @return boolean
	 * @author Artem Sukharev
	 */
	static public function checkUniqMainEmail($email, $exclude = null)
	{
		$db = Zend_Registry::get("DB");
		$query = $db->select();
		$query->from('zanby_discussion__discussions', 'discussion_id')
			  ->where('email = ?', $email)
			  ->where('is_main = ?', 1);
		if ( $exclude !== null ) {
			$query->where('discussion_id != ?', $exclude);
		}
		$res = $db->fetchCol($query);
		if ( sizeof($res) != 0 ) return false;
		else return true;
	}

	private function _getMaxPosition()
	{
		$query = $this->db->select();
		$query->from('zanby_discussion__discussions', new Zend_Db_Expr('MAX(position)'))
			  ->where('group_id = ?', $this->getGroupId());
		$position = $this->db->fetchOne($query);
		if ( !$position ) return 0;
		return $position;
	}
	private function _updatePositions()
	{
		$data['position'] = new Zend_Db_Expr("position - 1");
		$where = $this->db->quoteInto('discussion_id > ?', $this->getId());
		$where .= ' AND ' . $this->db->quoteInto('group_id = ?', $this->getGroupId());
		$rows_affected = $this->db->update('zanby_discussion__discussions', $data, $where);
	}
}
?>
