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
 * @created 12-Jun-2007 13:02:17
 */
class BaseWarecorp_DiscussionServer_Post implements Warecorp_Global_iSearchFields
{
    private $db;
	private $id;
	private $parentId;
	private $topicId;
	private $authorId;
	private $content;
	private $created;
	private $modified;
	private $topicPart;
	private $parent;
	private $topic;
	private $author;
	private $readed;
	private $views;
	private $position;
    private $format = 'bbcode';

	function __construct($postId = null)
	{
	    $this->db = Zend_Registry::get("DB");
	    if ( $postId !== null ) $this->load($postId);
	}

	function __destruct()
	{
	}



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

	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setParentId($newVal)
	{
		$this->parentId = $newVal;
		return $this;
	}

	public function getParent()
	{
		return $this->parent;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setParent($newVal)
	{
		$this->parent = $newVal;
		return $this;
	}

	public function getTopicId()
	{
		return $this->topicId;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setTopicId($newVal)
	{
		$this->topicId = $newVal;
		return $this;
	}
    /**
     * return topic object for post
     * @return Warecorp_DiscussionServer_Topic
     * @author Artem Sukharev
     */
	public function getTopic()
	{
	    if ( $this->topic === null ) $this->setTopic();
		return $this->topic;
	}

	/**
	 * set topic object for post
	 * @param newVal - Warecorp_DiscussionServer_Topic
	 * @return Warecorp_DiscussionServer_Post
	 * @author Artem Sukharev
	 */
	public function setTopic($newVal = null)
	{
	    if ( $newVal !== null && $newVal instanceof Warecorp_DiscussionServer_Topic ) {
	       $this->topic = $newVal;
	    } else {
		  $this->topic = new Warecorp_DiscussionServer_Topic($this->getTopicId());
	    }
	    return $this;
	}

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

	public function getUserCreated($timeZone)
	{
        date_default_timezone_set("UTC");
	    $created = new Zend_Date($this->created, Zend_Date::ISO_8601);
	    if ( $timeZone ) $created->setTimezone($timeZone);
		return $created->get(Zend_Date::DATE_MEDIUM) . ' ' . $created->get(Zend_Date::TIME_MEDIUM);
	}

    
    /**
    * Set Content of Post              
    * @param string newVal
    * @return Warecorp_DiscussionServer_Post obj
    * @author Artem Sukharev
    */
    public function setContent($newVal)
    {
        $this->content = $newVal;
        return $this;
    }
    /**
    * Return original content of post
    * @return string
    * @author Artem Sukharev
    */
	public function getContent()
	{
		return $this->content;
	}
    /**
     * returns content without BBCode tags or without HTML tags (as plain text)
     * @return string
     * @author Komarovski 
     * @author Artem Sukharev
     */
    public function getTextContent()
    {
        require_once ENGINE_DIR.'/html2text/class.html2text.inc';
        require_once ENGINE_DIR.'/Xbb/bbcode.lib.php';
        $bb = new bbcode($this->getContent());        
        $h2t = new html2text($bb->get_html());
        $text = $h2t->get_text();
        return $text;
    }
    /**
    * Convert BB codes to HTML tags and return it
    * @return string
    * @author Artem Sukharev
    */
	public function getBBContent()
	{		
	    require_once ENGINE_DIR.'/Xbb/bbcode.lib.php';
	    $bb = new bbcode($this->getContent());
	    return $bb->get_html();
	}
	/**
    * Return post content as HTML
    * @return string
    * @author Artem Sukharev
    */
    public function getHTMLContent()
    {
        return $this->getContent();
    }
    /**
    * Detect is content format html or bbcode and return final content
    * @return string
    * @author Artem Sukharev
    */
    public function getPostContent()
    {
        if ( $this->getFormat() == 'bbcode' ) {
            return $this->getBBContent();
        } elseif ( $this->getFormat() == 'html' ) {
            return $this->getHTMLContent();
        } elseif ( $this->getFormat() == 'text' ) {
            $content = $this->getContent();
            $content = htmlspecialchars($content);
            $content = str_replace("\n", "<br/>", $content);
            return $content;
        } else {
            return $this->getContent();
        }
    }
    
    
	public function getMailBBContentHTML()
	{
        $quotes = array();      
        require_once ENGINE_DIR.'/Xbb/bbcode.lib.mail.php';
        $bb = new mail_html_bbcode($this->getContent());
        $out = $bb->get_html(false, $quotes);
        $out = '<div style="font-family:\'Courier New\', Courier, monospace; font-size:14px;">'.$out.'</div>';
        return $out;
	}
	
    public function getMailBBContentPlain()
    {
        $quotes = array();      
        require_once ENGINE_DIR.'/Xbb/bbcode.lib.mail.plain.php';
        $bb = new mail_plain_bbcode($this->getContent());
        $out = $bb->get_html(false, $quotes);
        if ( sizeof($quotes) != 0 ) {                                                                                    
            foreach ( $quotes as $index => $quote ) {
                $out = preg_replace("/_____QUOTE_START_____".($index+1)."_____(.*?)_____QUOTE_END_____".($index+1)."_____/mis", "*****", $out);
            }
        }
        return $out;
    }

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

	public function getAuthor()
	{
	    if ( $this->author === null ) throw new Zend_Exception("Author is not defined");
		return $this->author;
	}

    public function createAuthor()
    {
        if ( $this->author === null ) $this->author = new Warecorp_User('id', $this->getAuthorId());
        return $this;
    }
    
	/**
	 *
	 * @param newVal
	 */
	public function setAuthor(Warecorp_DiscussionServer_iAuthor $newVal = null)
	{
		$this->author = $newVal;
		return $this;
	}

	public function isTopicPart()
	{
		return (boolean)$this->topicPart;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setTopicPart($newVal)
	{
		$this->topicPart = (boolean) $newVal;
		return $this;
	}
	public function getViews()
	{
		return $this->views;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setViews($newVal)
	{
		$this->views = $newVal;
		return $this;
	}
	public function getPosition()
	{
		return $this->position;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setPosition($newVal)
	{
		$this->position = $newVal;
		return $this;
	}
	
    public function getFormat()
    {
        if ( null === $this->format ) throw new Warecorp_Exception('Post format is not set');
        return $this->format;
    }
    
    public function setFormat($value)
    {
        $allowedFormats = array('html', 'bbcode', 'text');
        $value = strtolower($value);
        if ( !in_array($value, $allowedFormats) ) throw new Warecorp_Exception('Incorrect post content format "'.$value.'"');
        $this->format = $value;
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
	 *
	 * @param postId
	 */
	public static function findById($postId)
	{
	    return new Warecorp_DiscussionServer_Post($postId);
	}
    /**
     * return post with topic description
     * @return Warecorp_DiscussionServer_Post
     * @author Artem Sukharev
     */
	static public function findTopicPartByTopicId($topicId)
	{
	    $db = Zend_Registry::get("DB");
	    $query = $db->select();
	    $query->from('zanby_discussion__posts', 'post_id')
	          ->where('topic_id = ?', $topicId)
	          ->where('istopic = ?', 1);
	    $postId = $db->fetchOne($query);
	    return new Warecorp_DiscussionServer_Post($postId);
	}

	private function load($postId)
	{

        $memcache = Warecorp_Cache::getMemCache();

        $classname = get_class($this);
        $data = $memcache->load($classname.$postId);

        //There is no cache. Load it from DB
        if (!$data) {
            $query = $this->db->select();
            $query->from('zanby_discussion__posts', '*')
                  ->where('post_id = ?', $postId);
            $data = $this->db->fetchRow($query);
            //Save it to memcache
            if ($data) $memcache->save($data, $classname.$data['post_id'], array(), Warecorp_Cache::LIFETIME_30DAYS);
        }

	    if ( $data ) {
	        $this->setId($postId);
	        $this->setTopicId($data['topic_id']);
	        $this->setParentId($data['parent_id']);
	        $this->setAuthorId($data['author_id']);
	        $this->setCreated($data['created']);
	        $this->setModified($data['modified']);
	        $this->setTopicPart($data['istopic']);
	        $this->setContent($data['content']);
	        $this->setViews($data['views']);
	        $this->setPosition($data['position']);
            $this->setFormat($data['format']);
	    }
	}

    /**
     * @desc Clears memcache instance for current object and all related objects.
     * @return void
     */
    public function clearMemcache() {
        $memcache = Warecorp_Cache::getMemcache();
        $classname = get_class($this);
        $memcache->remove($classname.$this->getId());
        $this->getTopic()->clearMemcache();
    }

	/**
	 * save new instance of post
	 */
	public function save()
	{
        $this->clearMemcache();
        
	    $data['topic_id']     = $this->getTopicId();
	    $data['parent_id']    = $this->getParentId();
	    $data['author_id']    = $this->getAuthorId();
	    $data['content']      = $this->getContent();
	    $data['created']      = new Zend_Db_Expr('NOW()');
	    $data['modified']     = new Zend_Db_Expr('NOW()');
	    $data['position']     = $this->_getMaxPosition() + 1;
        $data['format']       = $this->getFormat();
	    if ( $this->isTopicPart() ) $data['istopic']  = 1;

        $rows_affected = $this->db->insert('zanby_discussion__posts', $data);
        $this->setId($this->db->lastInsertId());

        $topic = $this->getTopic();
        $topic->setLastPostCreated(new Zend_Db_Expr('NOW()'));
        $topic->update();
        
        /**
         * Update Cache :
         * Cache Key : DISCUSSIONSRV_COUNT_POST_BY_DISCUSSION_{$discussionId}
         * Cache Key : DISCUSSIONSRV_COUNT_POST_BY_GROUP_{$groupId}_[WITHBLOG|WITHOUTBLOG]
         */
//        if ( Warecorp_DiscussionServer_Discussion::$useCache ) {
//            $cache = Warecorp_Cache::getCache('file');
//            $discussion = $topic->getDiscussion();
//
//            $cacheKey = 'DISCUSSIONSRV_COUNT_POST_BY_DISCUSSION_'.$topic->getDiscussionId();
//            if ( false !== $count = $cache->load($cacheKey) ) {
//                $cache->save($count + 1, $cacheKey, array(), null);
//            }
//            $cacheKey = 'DISCUSSIONSRV_COUNT_POST_BY_GROUP_'.$discussion->getGroupId().'_WITHBLOG';
//            if ( false !== $count = $cache->load($cacheKey) ) {
//                $cache->save($count + 1, $cacheKey, array(), null);
//            }
//            if ( !$discussion->isBlog() ) {
//                $cacheKey = 'DISCUSSIONSRV_COUNT_POST_BY_GROUP_'.$discussion->getGroupId().'_WITHOUTBLOG';
//                if ( false !== $count = $cache->load($cacheKey) ) {
//                    $cache->save($count + 1, $cacheKey, array(), null);
//                }
//            }
//        }
        
        if ( Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() || !$topic->getDiscussion()->isBlog() ) {
            try {
                //queue message for email delivery
                $this->load($this->getId());//fill all fields by correct values
                Warecorp_DiscussionServer_GroupSubscription::EnqueuePostForDelivery($this);
            } catch (Exception $exc) {
                //ignore error and continue work
                //TODO: log error message
            }
        }
	}

	/**
	 * update existent instance of post
	 */
	public function update()
	{
	}
	/**
	 * update existent instance of post
	 */
	public function updateContent()
	{
	    $data['content']      = $this->getContent();
        $data['format']       = $this->getFormat();
	    $data['modified']     = new Zend_Db_Expr('NOW()');
        $where = $this->db->quoteInto('post_id = ?', $this->getId());
        $rows_affected = $this->db->update('zanby_discussion__posts', $data, $where);
        
        if ( Warecorp_DiscussionServer_DiscussionList::isIncludeBlog() || !$this->getTopic()->getDiscussion()->isBlog() ) {
            try {
                //queue message for email delivery
                $this->load($this->getId());//fill all fields by correct values
                Warecorp_DiscussionServer_GroupSubscription::EnqueuePostForDelivery($this);
            } catch (Exception $exc) {
                //ignore error and continue work
                //TODO: log error message
            }
        }
	}
	/**
	 * delete existent instance of post
	 * @author Artem Sukharev
	 */
	public function delete()
	{
	    $topic = $this->getTopic();
	    
	    // remove user view history
        $where = $this->db->quoteInto('post_id = ?', $this->getId());
        $rows_affected = $this->db->delete('zanby_discussion__user_post', $where);

        //  update child posts
	    $data['parent_id']     = new Zend_Db_Expr('NULL');
        $where = $this->db->quoteInto('parent_id = ?', $this->getId());
        $rows_affected = $this->db->update('zanby_discussion__posts', $data, $where);

        //  remove post
        $where = $this->db->quoteInto('post_id = ?', $this->getId());
        $rows_affected = $this->db->delete('zanby_discussion__posts', $where);

        $this->_updatePositions();

	    /**
         * Update Cache :
         * Cache Key : DISCUSSIONSRV_COUNT_POST_BY_DISCUSSION_{$discussionId}
         * Cache Key : DISCUSSIONSRV_COUNT_POST_BY_GROUP_{$groupId}_[WITHBLOG|WITHOUTBLOG]
         */
        if ( Warecorp_DiscussionServer_Discussion::$useCache ) {
            $cache = Warecorp_Cache::getCache('file');
            $discussion = $topic->getDiscussion();
            
            $cacheKey = 'DISCUSSIONSRV_COUNT_POST_BY_DISCUSSION_'.$topic->getDiscussionId();
            if ( false !== $count = $cache->load($cacheKey) ) {
                $cache->save( ($count > 0) ? $count - 1 : 0, $cacheKey, array(), null);
            }
            $cacheKey = 'DISCUSSIONSRV_COUNT_POST_BY_GROUP_'.$discussion->getGroupId().'_WITHBLOG';
            if ( false !== $count = $cache->load($cacheKey) ) {
                $cache->save(($count > 0) ? $count - 1 : 0, $cacheKey, array(), null);
            }
            if ( !$discussion->isBlog() ) {
                $cacheKey = 'DISCUSSIONSRV_COUNT_POST_BY_GROUP_'.$discussion->getGroupId().'_WITHOUTBLOG';
                if ( false !== $count = $cache->load($cacheKey) ) {
                    $cache->save(($count > 0) ? $count - 1 : 0, $cacheKey, array(), null);
                }
            } 
        }
        
        return true;
	}
	/**
	 * check for user is post readed
	 * @param int $user_id
	 * @return boolean
	 * @author Artem Sukharev
	 */
	public function isReaded($user_id)
	{
	    if ( $user_id === null ) return false;
	    if ( $this->readed === null ) {
            $query = $this->db->select();
            $query->from('zanby_discussion__user_post', 'isReaded')
                  ->where('post_id = ?', $this->getId())
                  ->where('user_id = ?', $user_id);
            $res = $this->db->fetchOne($query);
            $this->readed = (boolean) $res;
	    }
		return $this->readed;
	}
	/**
	 * mark post as readed for user
	 * @param int $user_id
	 * @return void
	 * @author Artem Sukharev
	 */
	public function setReadedForUser($user_id)
	{
	    if ( $user_id !== null ) {
    	    if ( !$this->isReaded($user_id) ) {
        	    $data['post_id']       = $this->getId();
        	    $data['user_id']       = $user_id;
        	    $data['isReaded']      = 1;
                $rows_affected = $this->db->insert('zanby_discussion__user_post', $data);

                $data = array();
                $data['views'] = new Zend_Db_Expr('views + 1');
                $where = $this->db->quoteInto('post_id = ?', $this->getId());
                $rows_affected = $this->db->update('zanby_discussion__posts', $data, $where);
                $this->setViews($this->getViews() + 1);
    	    }
	    }
        return $this;
	}
    private function _getMaxPosition()
    {
        $query = $this->db->select();
        $query->from(array('zdp' => 'zanby_discussion__posts'), new Zend_Db_Expr('MAX(zdp.position)'))
              ->where('zdp.topic_id = ?', $this->getTopicId());
        $position = $this->db->fetchOne($query);
        if ( !$position ) return 0;
        return $position;
    }
    private function _updatePositions()
    {
        $data['position'] = new Zend_Db_Expr("position - 1");
        $where = $this->db->quoteInto('post_id > ?', $this->getId());
        $where .= ' AND ' . $this->db->quoteInto('topic_id = ?', $this->getTopicId());
        $rows_affected = $this->db->update('zanby_discussion__posts', $data, $where);
    }
    
    
    public function entityObject()
    {
        return $this;
    
    }
    
    /**
    * return object id
    * @return int
    */
    public function entityObjectId()
    {
        return $this->getId();
    }

    /**
    * return object type. possible values: simple, family, blank string or null
    * @return string
    */
    public function entityObjectType()
    {
        return null;
    }

    /**
    * return owner type
    * possible values: group, user
    * @return string
    */
    public function entityOwnerType()
    {
        return "group";
    
    }

    /**
    * return title for entity (like group name, username, photo or gallery title)
    * @return string
    */
    public function entityTitle()
    {
        return $this->getTopic()->getSubject();
    }

    /**
    * return headline for entity (like group headline, members first and last name, photo or gallery title,etc). 
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityHeadline()
    {
        return $this->entityTitle();
    }
    
    /**
    * return description for entity (group description, user intro, gallery or photo description, etc.). 
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityDescription()
    {
        return $this->getContent();
    }

    /**
    * return username of owner 
    * @return string
    */
    public function entityAuthor()
    {
        return $this->createAuthor()->getAuthor()->getLogin();
    }

    /**
    * return user_id of entity owner 
    * @return string
    */
    public function entityAuthorId()
    {
        return $this->getAuthorId();
    
    }

    /**
    * return picture URL (avatar, group picture, trumbnails, etc.) 
    * @return int
    */
    public function entityPicture()
    {
        return null;
    }
    
    /**
    * return creation date for all elements
    * @return string
    */
    public function entityCreationDate()
    {
        return $this->getCreated();
    }

    /**
    * return update date for all elements
    * @return string
    */
    public function entityUpdateDate()
    {
        return $this->getCreated();
    }

    /**
    * items count (members, posts, child groups, etc.)
    * @return int
    */
    public function entityItemsCount()
    {
        return 1;
    }
    
    /**
    * get category for entity (event type, list type, group category, etc)
    * possible values: string 
    * @return int
    */
    public function entityCategory()
    {
        return "";
    }

    /**
    * get category_id for entity (event type, list type, group category, etc)
    * possible values: int , null 
    * @return int
    */
    public function entityCategoryId()
    {
        return null;
    }

    /**
    * get country for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCountry()
    {
        return "";
    }

    /**
    * get country_int for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCountryId()
    {
        return null;
    }

    
    /**
    * get city for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCity()
    {
        return "";
    }

    /**
    * get city_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCityId()
    {
        return null;
    }
    /**
    * get zip for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityZIP()
    {
        return "";
    }
    
    /**
    * get state for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityState()
    {
        return "";
    }
    /**
    * get state_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityStateId()
    {
        return null;
    }
    /**
    * path to video(video galleries)
    * possible values: string
    * @return int
    */
    public function entityVideo()
    {
        return null;
    
    }
    
    /**
    * comments count for entity
    * possible values: int
    * @return int
    */
    public function entityCommentsCount()
    {
        return null;
    }
    
    /**
    * comments count for entity
    * possible values: int
    * @return int
    */
    public function entityURL()
    {
        return $this->getTopic()->getDiscussion()->getGroup()->getGroupPath('topic')."topicid/".$this->getTopicId()."/";
    }
    
    /**
     * 
     * @param $objSender
     * @return unknown_type
     */
    public function sendMessageToAuthor( $objSender, $message )
    {       
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered('DISCUSSION_EMAIL_AUTHOR') ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {      
                $objRecipient = new Warecorp_User('id', $this->getAuthorId());          
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                $msrvRecipients->addRecipient($recipient);
                
                $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    //$request = $client->setSender($campaignUID, $objSender->getEmail, $objSender->getFirstName().' '.$objSender->getLastname());
                    $request = $client->setSender($campaignUID, $this->getTopic()->getDiscussion()->getFullEmail(), SITE_NAME_AS_STRING.' Discussions');
                    $request = $client->setTemplate($campaignUID, 'DISCUSSION_EMAIL_AUTHOR', HTTP_CONTEXT); /* DISCUSSION_EMAIL_AUTHOR */
                    
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'sender_login', $objSender->getLogin() );
                    
                    if ( DISCUSSION_MODE == 'html' ) {
                        require_once ENGINE_DIR.'/html2text/class.html2text.inc';
                        $h2t = new html2text($message);
                        $message_plain = $h2t->get_text();
                        $params->addParam( 'content_plain', $message_plain );
                        $params->addParam( 'content_html', $message );
                    } else {
                        $params->addParam( 'content_plain', $message );
                        $params->addParam( 'content_html', nl2br(htmlspecialchars($message)) );
                    }
                    
                    $params->addParam( 'url_topic', $this->getTopic()->getDiscussion()->getGroup()->getGroupPath('topic/topicid/'.$this->getTopic()->getId()) );
                    $request = $client->addParams($campaignUID, $params);

                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    unset( $pmbRecipients );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            //  Send message
            $mail = new Warecorp_Mail_Template('template_key', 'DISCUSSION_EMAIL_AUTHOR');
            $mail->setSender($objSender);
            $mail->addRecipient(new Warecorp_User('id', $this->getAuthorId()));
            
            $mail->addParam('group', $this->getTopic()->getDiscussion()->getGroup());
            $mail->addParam('discussion', $this->getTopic()->getDiscussion());
            $mail->addParam('topic', $this->getTopic());
            $mail->addParam('post', $this);
            $mail->addParam('content', $message);
            $mail->sendToPMB(true);
            $mail->send();            
        }
    }
    
    /**
     * 
     * @param $objSender
     * @param $lstRecipients
     * @return void
     */
    public function sendPostReport( $objSender, $lstRecipients )
    {       
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered('DISCUSSION_REPORT_POST') ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {      
                
                foreach ( $lstRecipients as $objRecipient ) {          
                    $recipient = new Warecorp_SOAP_Type_Recipient();
                    $recipient->setEmail( $objRecipient->getEmail() );
                    $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                    $recipient->setLocale( null );
                    $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                    $recipient->addParam( 'recipient_full_name', $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                    $msrvRecipients->addRecipient($recipient);
                    
                    $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                }
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    //$request = $client->setSender($campaignUID, $objSender->getEmail, $objSender->getFirstName().' '.$objSender->getLastname());
                    $request = $client->setSender($campaignUID, $this->getTopic()->getDiscussion()->getFullEmail(), SITE_NAME_AS_STRING.' Discussions');
                    $request = $client->setTemplate($campaignUID, 'DISCUSSION_REPORT_POST', HTTP_CONTEXT); /* DISCUSSION_REPORT_POST */
                    
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'discussion_group_name', $this->getTopic()->getDiscussion()->getGroup()->getName() );
                    $params->addParam( 'discussion_title', $this->getTopic()->getDiscussion()->getTitle() );
                    $params->addParam( 'topic_subject', $this->getTopic()->getSubject() );
                    if ( $this->getFormat() == 'html' ) {
                        $params->addParam( 'post_content_plain', $this->getTextContent() );
                        $params->addParam( 'post_content_html', $this->getContent() );
                    } else {
                        //$params->addParam( 'post_content_plain', $this->getContent() );
                        //$params->addParam( 'post_content_html', nl2br(htmlspecialchars($this->getContent())) );
                        $params->addParam( 'post_content_plain', $this->getTextContent() );
                        $params->addParam( 'post_content_html', $this->getBBContent() );
                    }
                    $params->addParam( 'url_topic', $this->getTopic()->getDiscussion()->getGroup()->getGroupPath('topic/topicid/'.$this->getTopic()->getId()) );
                    $request = $client->addParams($campaignUID, $params);

                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    unset( $pmbRecipients );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            //  Send message
            $mail = new Warecorp_Mail_Template('template_key', 'DISCUSSION_REPORT_POST');
            $mail->setSender($objSender);
            foreach ( $lstRecipients as $recipient ) $mail->addRecipient($recipient);
            
            $mail->addParam('group', $this->getTopic()->getDiscussion()->getGroup());
            $mail->addParam('discussion', $this->getTopic()->getDiscussion());
            $mail->addParam('topic', $this->getTopic());
            $mail->addParam('post', $this);
            $mail->sendToPMB(true);
            $mail->send();            
        }
    }
    
    static public function formatQuoteStart()
    {
        $return =
            '<div style="border: 1px solid #CBD9D9; padding: 5px; margin: 5px;">'.
            '<div style="padding-bottom:5px;"></div>'.
            '<div style="color:#3F3F3F;">';
        return $return;
    }
    
    static public function formatQuoteEnd()
    {
        $return =
            '</div>'.
            '</div>';
        return $return;
    }
    
}
?>
