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
 * Represents entity SubscriptionMessage.
 * SubscriptionMessage is a copy of Post state at some moment.
 * It is used for subscription mechanism for delivering digests and
 * single messages to subscribers.
 * @author Yury Nelipovich
 */
class BaseWarecorp_DiscussionServer_SubscriptionMessage
{
    private $db = null;
	private $id = null;
	private $postId = null;
	private $content = null;
	private $posted = null;
	private $isReply = null;
	
	private $post = null;
	private $topic = null;
	private $discussion = null;
	private $author = null;
	
	/**
	 * Object chache for better performance.
	 * key is string: object_id:object_type
	 * value is object
	 *
	 * @var array
	 */
	private $objectCache = array();

	/**
	 * Constructs message by its id in message database.
	 *
	 * @param int $id
	 */
	function __construct($id = null)
	{
	    $this->db = Zend_Registry::get("messageDB");
	    if ( $id !== null ) $this->load($id);
	}

	function __destruct()
	{
	}

	public function getId()
	{
		return $this->id;
	}
	
	public function getPostId()
	{
	    return $this->postId;
	}
	
	/**
	 * Gets post that message is based on.
	 *
	 * @return Warecorp_DiscussionServer_Post
	 */
	public function getPost()
	{
	    if (!isset($this->post)) {
    	    if (!isset($this->objectCache[$this->postId.':post']))
    	        $this->objectCache[$this->postId.':post'] = new Warecorp_DiscussionServer_Post($this->postId);
   	        $this->post = $this->objectCache[$this->postId.':post'];
	    }
	    
	    return $this->post;
	}
	
	/**
	 * Gets Topic of the post that message is based on.
	 *
	 * @return Warecorp_DiscussionServer_Topic
	 */
	public function getTopic()
	{
	    if (!isset($this->topic)) {
    	    if (!isset($this->objectCache[$this->getPost()->getTopicId().':topic']))
    	        $this->objectCache[$this->getPost()->getTopicId().':topic'] = $this->getPost()->getTopic();
	        $this->topic = $this->objectCache[$this->getPost()->getTopicId().':topic'];
	    }
	    return $this->topic;
	}
	
	/**
	 * Gets Discussion of the post that message is based on.
	 *
	 * @return Warecorp_DiscussionServer_Discussion
	 */
	public function getDiscussion()
	{
	    if (!isset($this->discussion)) {
    	    if (!isset($this->objectCache[$this->getTopic()->getDiscussionId().':discussion'])) {
    	        $this->objectCache[$this->getTopic()->getDiscussionId().':discussion'] = $this->getTopic()->getDiscussion();
    	        $group = Warecorp_Group_Factory::loadById($this->objectCache[$this->getTopic()->getDiscussionId().':discussion']->getGroupId());
    	        $this->objectCache[$this->getTopic()->getDiscussionId().':discussion']->setGroup($group);
    	    }
 	        $this->discussion = $this->objectCache[$this->getTopic()->getDiscussionId().':discussion'];
	    }
	    return $this->discussion;
	}
	
	/**
	 * Sets post as source of message
	 *
	 * @param Warecorp_DiscussionServer_Post $post
	 */
	public function setPost($post)
	{
        $this->postId = $post->getId();
        $this->content = $post->getContent();
        $this->posted = $post->getModified();
        $this->isReply = !$post->isTopicPart();
	}
	
	/**
	 * Gets author of the post
	 *
	 * @return unknown
	 */
	public function getAuthor()
	{
	    if (!isset($this->author)) {
    	    if (!isset($this->objectCache[$this->getPost()->getAuthorId().':author']))
    	        $this->objectCache[$this->getPost()->getAuthorId().':author'] = $this->getPost()->setAuthor(Warecorp_User::createAuthorById($this->getPost()->getAuthorId()))->getAuthor();
            $this->author = $this->objectCache[$this->getPost()->getAuthorId().':author'];
	    }
        return $this->author;
	}
	
	/**
	 * Gets content of message
	 * Note: content of message but not post. They can differ.
	 *
	 * @return string
	 */
	public function getContent()
	{
	    return $this->content;
	}
	
	/**
	 * Gets datetime when message was created
	 *
	 * @param unknown_type $timeZone
	 * @return string
	 */
	public function getPosted($timeZone)
	{
	    $posted = new Zend_Date($this->posted, Zend_Date::ISO_8601, 'en');
	    if ( $timeZone ) $posted->setTimezone($timeZone);
		return $posted->get(Zend_Date::DATE_MEDIUM) . ' ' . $posted->get(Zend_Date::TIME_MEDIUM);
	}
	
	/**
	 * True if post was replied.
	 *
	 * @return unknown
	 */
	public function getIsReply()
	{
	    return $this->isReply;
	}
	
	/**
	 * Loads object by id
	 *
	 * @param int $id
	 */
    private function load($id)
	{
	    $query = $this->db->select();
	    $query->from('zanby_subscription__messages', '*')->where('id = ?', $id);
	    $data = $this->db->fetchRow($query);
	    if ( $data ) {
	        $this->id      = $id;
            $this->postId  = $data['post_id'];
            $this->content = $data['content'];
            $this->posted  = $data['posted'];
            $this->isReply = $data['is_reply'];
	    }
	}

	public function save()
	{
	    $data = array();
	    $data['post_id'] = $this->postId;
	    $data['content'] = $this->content;
	    $data['posted'] = $this->posted;
	    $data['is_reply'] = $this->isReply;

        $rows_affected = $this->db->insert('zanby_subscription__messages', $data);
        $this->id = $this->db->lastInsertId();
	}

	public function update()
	{
	}

	/**
	 * Deletes message. It must not be in any queue.
	 *
	 * @return unknown
	 */
	public function delete()
	{
        // if this message is in any queue then it cannot be deleted adn error will occur
	    $where = $this->db->quoteInto('id = ?', $this->id);
        $rows_affected = $this->db->delete('zanby_subscription__messages', $where);

        return true;
	}
	
}

?>
