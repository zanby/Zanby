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
 * @created 12-Jun-2007 13:03:06
 */
class BaseWarecorp_DiscussionServer_Topic
{
	private $db;
	private $id;
	private $discussionId;
	private $authorId;
	private $subject;
	private $created;
	private $modified;
	private $discussion;
	private $author;
	private $closed;
	private $lastPostCreated;
	private $posts;
	private $sequenceNumber; //0 means that topic is unique within discussion

	const hotDaysCount = 10;
	const hotPostsCount = 5;
    
    protected static $dateHotStart;
    protected static $dateHotEnd;
    
    protected static $cacheDiscussions = array();

    protected $authorsCount = 0;
    protected $postsCount = 0;

	function __construct($topic = null)
	{
		$this->db = Zend_Registry::get("DB");
		if ( $topic !== null ) {
            if ( is_array($topic) )     $this->loadByData($topic);
            else                        $this->load($topic);
        }
	}

    /**
    * @desc 
    */
    public static function getDateHotStart()
    {
        if ( null === self::$dateHotEnd ) {
            $defaulttimezone = date_default_timezone_get();
            date_default_timezone_set('UTC');
            self::$dateHotEnd = new Zend_Date();
            date_default_timezone_set($defaulttimezone);        
            
            self::$dateHotStart = clone self::$dateHotEnd;
            self::$dateHotStart->add(-1*self::hotDaysCount, Zend_Date::DAY);
        }        
        return self::$dateHotStart;
    }
    
    /**
    * @desc 
    */
    public static function getDateHotEnd()
    {
        if ( null === self::$dateHotEnd ) {
            $defaulttimezone = date_default_timezone_get();
            date_default_timezone_set('UTC');
            self::$dateHotEnd = new Zend_Date();
            date_default_timezone_set($defaulttimezone);        
            
            self::$dateHotStart = clone self::$dateHotEnd;
            self::$dateHotStart->add(-1*self::hotDaysCount, Zend_Date::DAY);
        }
        return self::$dateHotEnd;
    }

    /**
    * @desc 
    */
	public function getId()
	{
		return $this->id;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setId($newVal)
	{
		$this->id = $newVal;
		return $this;
	}

    /**
    * @desc 
    */
	public function getDiscussionId()
	{
		return $this->discussionId;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setDiscussionId($newVal)
	{
		$this->discussionId = $newVal;
		return $this;
	}

    /**
    * @return Warecorp_DiscussionServer_Discussion
    */
	public function getDiscussion()
	{
		if ( $this->discussion === null ) $this->setDiscussion();
		return $this->discussion;
	}

	/**
	 * @param mixed $newVal
	 * @return Warecorp_DiscussionServer_Topic
	 */
	public function setDiscussion($newVal = null)
	{
		if ( $newVal !== null && $newVal instanceof Warecorp_DiscussionServer_Discussion ) {
            $this->discussion = $newVal;
		} else {
            if ( !array_key_exists($this->getDiscussionId(), self::$cacheDiscussions) ) {                
                self::$cacheDiscussions[$this->getDiscussionId()] = new Warecorp_DiscussionServer_Discussion($this->getDiscussionId());
            }           
            $this->discussion = self::$cacheDiscussions[$this->getDiscussionId()]; 
		}
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
	 *
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
	 *
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
	public function getSubject()
	{
		return $this->subject;
	}

    /**
    * @desc 
    */
	public function getShortSubject($size = 60)
	{
		if ( strlen($this->subject) > $size ) {
		   return substr($this->subject, 0, $size) . '...';
		} else return $this->subject;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setSubject($newVal)
	{
		$this->subject = $newVal;
		return $this;
	}

    /**
    * @desc 
    */
	public function getPosts()
	{
		if ( $this->posts === null ) $this->setPosts();
		return $this->posts;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setPosts(Warecorp_DiscussionServer_PostList $newVal = null)
	{
		if ( $newVal !== null ) {
		   $this->posts = $newVal;
		} else {
		   $this->posts = new Warecorp_DiscussionServer_PostList();
		}
		return $this;
	}

    /**
    * @desc 
    */
	public function isClosed()
	{
		return (boolean) $this->closed;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setClosed($newVal)
	{
		$this->closed = (boolean)$newVal;
		return $this;
	}
    
    /**
    * @desc 
    */
	public function getLastPostCreated()
	{
		return $this->lastPostCreated;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setLastPostCreated($newVal)
	{
		$this->lastPostCreated = $newVal;
		return $this;
	}
	/**
	 *
	 * @param topicId
	 */
	 static public function findById($topicId)
	{
		return new Warecorp_DiscussionServer_Topic($topicId);
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
	 * @param newVal
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
	 * @param newVal
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
	 * return post with topic description
	 * @return Warecorp_DiscussionServer_Post
	 * @author Artem Sukharev
	 */
	public function getTopicPost()
	{
		return Warecorp_DiscussionServer_Post::findTopicPartByTopicId($this->getId());
	}
    /**
     * Total posts counts
     * @return int
     */
    public function getPostsCount() {
        return $this->postsCount;
    }

    /**
     * Total authors counts
     * @return int
     */
    public function getAuthorsCount() {
        return $this->authorsCount;
    }

    /**
    * @desc 
    */
	private function load($topicId)
	{
        $memcache = Warecorp_Cache::getMemCache();

        $classname = get_class($this);
        $data = $memcache->load($classname.$topicId);

        //There is no cache. Load it from DB
        if (!$data) {
            $query = $this->db->select();
            $query->from('zanby_discussion__topics', '*')
                  ->where('topic_id = ?', $topicId);
            $data = $this->db->fetchRow($query);
            //Save it to memcache
            if ($data) $memcache->save($data, $classname.$data['topic_id'], array(), Warecorp_Cache::LIFETIME_30DAYS);
        }

		if ( $data ) {
            $this->loadByData($data);
		}
	}

    /**
    * @desc 
    */
    private function loadByData($hash)
    {
        $this->setId($hash['topic_id']);
        $this->setDiscussionId($hash['discussion_id']);
        $this->setAuthorId($hash['author_id']);
        $this->setSubject($hash['subject']);
        $this->setCreated($hash['created']);
        $this->setModified($hash['modified']);
        $this->setClosed($hash['closed']);
        $this->setLastPostCreated($hash['lastpostcreated']);
        $this->postsCount = $hash['posts_count'];
        $this->authorsCount = $hash['authors_count'];
    }

    /**
     * @desc Clears memcache instance for current object and all related objects.
     * @return void
     */
    public function clearMemcache() {
        $memcache = Warecorp_Cache::getMemcache();
        $classname = get_class($this);
        $memcache->remove($classname.$this->getId());
        $this->getDiscussion()->clearMemcache();
    }


    /**
    * @desc 
    */
	public function save()
	{
        $this->clearMemcache();

		$data = array();
		$data['discussion_id']     = $this->getDiscussionId();
		$data['author_id']         = $this->getAuthorId();
		$data['subject']           = $this->getSubject();
		$data['created']           = new Zend_Db_Expr('NOW()');
		$data['modified']          = new Zend_Db_Expr('NOW()');
		$data['lastpostcreated']   = new Zend_Db_Expr('NOW()');

		$rows_affected = $this->db->insert('zanby_discussion__topics', $data);
		$this->setId($this->db->lastInsertId());
		
        /**
         * Update Cache :
         * Cache Key : DISCUSSIONSRV_COUNT_TOPIC_BY_DISCUSSION__{$discussionId}
         * Cache Key : DISCUSSIONSRV_COUNT_TOPIC_BY_GROUP_{$groupId}_[WITHBLOG|WITHOUTBLOG]
         */
//        if ( Warecorp_DiscussionServer_Discussion::$useCache ) {
//            $cache = Warecorp_Cache::getCache('file');
//            $discussion = $this->getDiscussion();
//
//            $cacheKey = 'DISCUSSIONSRV_COUNT_TOPIC_BY_DISCUSSION_'.$this->getDiscussionId();
//            if ( false !== $count = $cache->load($cacheKey) ) {
//                $cache->save($count + 1, $cacheKey, array(), null);
//            }
//            $cacheKey = 'DISCUSSIONSRV_COUNT_TOPIC_BY_GROUP_'.$discussion->getGroupId().'_WITHBLOG';
//            if ( false !== $count = $cache->load($cacheKey) ) {
//                $cache->save($count + 1, $cacheKey, array(), null);
//            }
//            if ( !$discussion->isBlog() ) {
//                $cacheKey = 'DISCUSSIONSRV_COUNT_TOPIC_BY_GROUP_'.$discussion->getGroupId().'_WITHOUTBLOG';
//                if ( false !== $count = $cache->load($cacheKey) ) {
//                    $cache->save($count + 1, $cacheKey, array(), null);
//                }
//            }
//        }
	}

    /**
    * @desc 
    */
	public function update()
	{
		$data = array();
		$data['discussion_id']     = $this->getDiscussionId();
		$data['author_id']         = $this->getAuthorId();
		$data['subject']           = $this->getSubject();
		$data['modified']          = new Zend_Db_Expr('NOW()');
		$data['closed']            = (int) $this->isClosed();
		$data['lastpostcreated']   = $this->getLastPostCreated();

		$where = $this->db->quoteInto('topic_id = ?', $this->getId());
		$rows_affected = $this->db->update('zanby_discussion__topics', $data, $where);
	}

    /**
    * @desc 
    */
	public function delete()
	{
		// remove all topic subscriptions
		$subscription = new Warecorp_DiscussionServer_TopicSubscriptionList();
		$subscriptions = $subscription->findByTopic($this->getId());
		if ( sizeof($subscriptions) != 0 ) {
			foreach ( $subscriptions as $subs ) {
				$subs->delete();
			}
		}

		// remove all topic posts
		$posts = $this->getPosts()->findByTopicId($this->getId());
		if ( sizeof($posts) != 0 ) {
		   foreach ( $posts as $post ) {
			   $post->delete();
		   }
		}

		//  remove topic
		$where = $this->db->quoteInto('topic_id = ?', $this->getId());
		$rows_affected = $this->db->delete('zanby_discussion__topics', $where);

	    /**
         * Update Cache :
         * Cache Key : DISCUSSIONSRV_COUNT_TOPIC_BY_DISCUSSION__{$discussionId}
         * Cache Key : DISCUSSIONSRV_COUNT_TOPIC_BY_GROUP_{$groupId}_[WITHBLOG|WITHOUTBLOG]
         */
        if ( Warecorp_DiscussionServer_Discussion::$useCache ) {
            $cache = Warecorp_Cache::getCache('file');
            $discussion = $this->getDiscussion();
            
            $cacheKey = 'DISCUSSIONSRV_COUNT_TOPIC_BY_DISCUSSION_'.$this->getDiscussionId();
            if ( false !== $count = $cache->load($cacheKey) ) {
                $cache->save(($count > 0) ? $count - 1 : 0, $cacheKey, array(), null);
            }
            $cacheKey = 'DISCUSSIONSRV_COUNT_TOPIC_BY_GROUP_'.$discussion->getGroupId().'_WITHBLOG';
            if ( false !== $count = $cache->load($cacheKey) ) {
                $cache->save(($count > 0) ? $count - 1 : 0, $cacheKey, array(), null);
            }
            if ( !$discussion->isBlog() ) {
                $cacheKey = 'DISCUSSIONSRV_COUNT_TOPIC_BY_GROUP_'.$discussion->getGroupId().'_WITHOUTBLOG';
                if ( false !== $count = $cache->load($cacheKey) ) {
                    $cache->save(($count > 0) ? $count - 1 : 0, $cacheKey, array(), null);
                }
            } 
        }
		return true;
	}

    /**
    * @desc 
    */
	public function hasUnreadPosts($user_id, $showTopicPart = true)
	{
		return (boolean) $this->getPosts()->setShowTopicPart($showTopicPart)->countUnreadByTopicId($user_id, $this->getId());
	}
    
    /**
    * @desc 
    */
	public function isHot()
	{		
		$count = $this->getPosts()->countByTopicIdAndDate($this->getId(), self::getDateHotStart(), self::getDateHotEnd());
		if ( $count >= self::hotPostsCount ) return true;
		else return false;
	}
    
	/**
	 * set all posts of topic as readed
	 * @param int $user_id
	 * @return void
	 * @author Artem Sukharev
	 */
	public function setReadedForUser($user_id)
	{
		if ( $user_id !== null ) {
			$posts = $this->getPosts()->findByTopicId($this->getId());
			if ( sizeof($posts) != 0 ) {
				foreach ($posts as $post) {
					$post->setReadedForUser($user_id);
				}
			}
		}
		return $this;
	}
	
	/**
	 * Gets sequence nubre of topic within its discussion (csorted by creation date)
	 * or null if topic is unique.
	 *
	 * @return int or null if topic is unique
	 */
	public function getSequenceNumber()
	{
		if ( null === $this->sequenceNumber ) {
			if ( !$this->getId() || !$this->getDiscussionId() || !$this->getSubject() ) {
				$this->sequenceNumber = 0;
			} else {
				$query = $this->db->select();
				$query->from(array('zdt' => 'zanby_discussion__topics'), 'zdt.topic_id')
					  ->where('zdt.subject = ?', $this->getSubject())
					  ->where('zdt.discussion_id = ?', $this->getDiscussionId());
				$topicIds = $this->db->fetchCol($query);				
				if ( isset($topicIds) && count($topicIds) > 1 ) {
					$this->sequenceNumber = $this->getId();
				} else
					$this->sequenceNumber = 0;			
			}
		}
		
		if ($this->sequenceNumber == 0)
		   return null;
		else
		   return $this->sequenceNumber;
	}
	
	/**
	 * Gets subject of the topic with optional sequence number.
	 * This make sense in email messages
	 *
	 * @return string
	 */
	public function getSubjectForEmail()
	{
		return $this->getSubject() . ($this->getSequenceNumber() != null ? ' ('.$this->getSequenceNumber().')' : '');
	}
}
?>
