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
 * Конкретная реализация прав доступа к Discussion Server для Zanby
 * Для своей реализации надо создать новый класс реализующий Warecorp_DiscussionServer_iAccessManager
 * и переопределить все методы интерфейса
 * @author Artem Sukharev
 * @version 1.0
 * @created 12-Jun-2007 13:00:17
 */

require_once(WARECORP_DIR.'DiscussionServer/iAccessManager.php');

class BaseWarecorp_DiscussionServer_AccessManager implements Warecorp_DiscussionServer_iAccessManager
{
    static protected $instance            = false;
    protected $discussionModeratorsList   = array();
    protected $groupModeratorsList        = array();
    protected $groupSettingsList          = array();
    protected $moderatorsList;

    /**
     * Private constructor
     */
    //protected function __construct(){}

    /**
     * Return instance of Access Manager
     * @return Warecorp_DiscussionServer_iAccessManager
     */
    static public function getInstance($className = null){
        if ( !self::$instance ) {
        	if ( null !== $className ) {
        	   self::$instance = new $className;
        	} else {
        	   self::$instance = new Warecorp_DiscussionServer_AccessManager();
        	}
            self::$instance->moderatorsList = new Warecorp_DiscussionServer_ModeratorList();
        }
        return self::$instance;
    }
       
    /**
     * build list of discussion moderators
     * @param int $discussion_id
     * @return array of user_id
     */
    protected function getDiscussionModerators($discussion_id)
    {
        if ( !isset(self::$instance->discussionModeratorsList[$discussion_id]) ) {
            $moderators = self::$instance->moderatorsList->findByDiscussionId($discussion_id);
            $moderatorsIds = array();
            if ( sizeof($moderators) != 0 ) {
                foreach ( $moderators as $moderator ) {
                    $moderatorsIds[] = $moderator;
                }
            }
            self::$instance->discussionModeratorsList[$discussion_id] = $moderatorsIds;
        }
        return self::$instance->discussionModeratorsList[$discussion_id];
    }
    /**
     * check is user moderator of discussion
     * @param int $discussion_id
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    protected function isDiscussionModerator($discussion_id, $user_id)
    {
        if ( in_array($user_id, self::$instance->getDiscussionModerators($discussion_id)) ) {            
        	return true;
        } else {
            return false;
        }
    }
    /**
     * build list of group moderators
     * @param int $group_id
     * @return array of user_id
     */
    protected function getGroupModerators($group_id)
    {
        if ( !isset(self::$instance->groupModeratorsList[$group_id]) ) {
            $group = self::_checkGroup($group_id);
            switch ( $group->getGroupType() ) {
                case 'simple'       :
                    $moderators = self::$instance->moderatorsList->findByGroupId($group_id);
                    $moderatorsIds = array();
                    if ( sizeof($moderators) != 0 ) {
                        foreach ( $moderators as $moderator ) {
                            $moderatorsIds[] = $moderator;
                        }
                    }
//                    $membersListObj = $group->getMembers();
//                    $membersListObj->setMembersRole(array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST));
//                    $hosts = $membersListObj->getList();
//                    if ( sizeof($hosts) != 0 ) {
//                        foreach ( $hosts as $host ) {
//                            $moderatorsIds[] = $host->getId();
//                        }
//                    }
                    self::$instance->groupModeratorsList[$group_id] = $moderatorsIds;
                    break;
                case 'family'       :
                    $moderators = self::$instance->moderatorsList->findByGroupId($group_id);
                    $moderatorsIds = array();
                    if ( sizeof($moderators) != 0 ) {
                        foreach ( $moderators as $moderator ) {
                            $moderatorsIds[] = $moderator;
                        }
                    }
                    $host = $group->getHost();
                    $moderatorsIds[] = $host->getId();
                    /*
                    $membersListObj = $group->getMembers();
                    $membersListObj->setMembersRole(array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST));
                    $hosts = $membersListObj->getList();
                    if ( sizeof($hosts) != 0 ) {
                        foreach ( $hosts as $host ) {
                            $moderatorsIds[] = $host->getId();
                        }
                    }
                    */
                    self::$instance->groupModeratorsList[$group_id] = $moderatorsIds;
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");

            }
        }
        return self::$instance->groupModeratorsList[$group_id];
    }
    /**
     * check is user moderator of group
     * @param int $group_id
     * @param int|Warecorp_User $user
     * @return boolean
     * @author Artem Sukharev
     */
    protected function isGroupModerator($group_id, $user)
    {
        if (!($user instanceof Warecorp_User)) $user = new Warecorp_User('id',$user);
        $group = self::$instance->_checkGroup($group_id);

        return in_array($user->getId(),self::$instance->getGroupModerators($group->getId())) || $user->getGroupRole($group) == 'host' || $user->getGroupRole($group) == 'cohost';

//        if ( in_array($user_id, self::$instance->getGroupModerators($group_id)) ) {
//        	return true;
//        } else {
//            return false;
//        }
    }
    /**
     * check is user moderator of group or discussion
     * @param obj $object (instance of Warecorp_DiscussionServer_[iDiscussionGroup|Discussion|Topic|Post])
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    protected function isModerator($object, $user_id)
    {    
        if ( $object instanceof Warecorp_DiscussionServer_iDiscussionGroup  ) {
            return $this->isGroupModerator($object->getDiscussionGroupId(), $user_id);
        } elseif ( $object instanceof Warecorp_DiscussionServer_Discussion  ) {
            return ( $this->isDiscussionModerator($object->getId(), $user_id) || $this->isGroupModerator($object->getGroupId(), $user_id) );
        } elseif ( $object instanceof Warecorp_DiscussionServer_Topic  ) {
            return ( $this->isDiscussionModerator($object->getDiscussionId(), $user_id) || $this->isGroupModerator($object->getDiscussion()->getGroupId(), $user_id) );
        } elseif ( $object instanceof Warecorp_DiscussionServer_Post  ) {
            if ($object->getId() === null) return false;
            return ( $this->isDiscussionModerator($object->getTopic()->getDiscussionId(), $user_id) || $this->isGroupModerator($object->getTopic()->getDiscussion()->getGroupId(), $user_id) );
        } else {
            throw new Zend_Exception("Incorrect object type");
        }
    }
    protected function getGroupSettings($group)
    {
        $group = self::_checkGroup($group);
        if ( !isset(self::$instance->groupSettingsList[$group->getId()]) ) {
            self::$instance->groupSettingsList[$group->getId()] = $group->getDiscussionGroupSettings();
        }
        return self::$instance->groupSettingsList[$group->getId()];
    }
    protected function _checkDiscussion($discussion)
    {
        if ( $discussion instanceof Warecorp_DiscussionServer_Discussion ) return $discussion;
        else return new Warecorp_DiscussionServer_Discussion($discussion);
    }
    protected function _checkTopic($topic)
    {
        if ( $topic instanceof Warecorp_DiscussionServer_Topic ) return $topic;
        else return new Warecorp_DiscussionServer_Topic($topic);
    }
    protected function _checkPost($post)
    {
        if ( $post instanceof Warecorp_DiscussionServer_Post ) return $post;
        else return new Warecorp_DiscussionServer_Post($post);
    }
    protected function _checkGroup($group)
    {
        if ( !($group instanceof Warecorp_Group_Base) ) {
            return Warecorp_Group_Factory::loadById($group);
        }
        return $group;
    }
    //*******************************************************************
    //
    //  Warecorp_DiscussionServer_iAccessManager Interface
    //
    //*******************************************************************

    //*********************************************************************
    /**
     * Group Access
     */
    //*********************************************************************
    /**
     * Проверяет, может ли группа публиковаться в фамилии
     * @param int $groupId
     * @param int $familyGroupId
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canPublishToFamily($groupId, $familyGroupId)
    {
        return Warecorp_DiscussionServer_Settings::getGroupPublish($groupId, $familyGroupId);
    }
    /**
     * проверяет, может ли анонимный пользователь просматривать (видеть)
     * дискуссии группы (доступ на уровне группы)
     * @param int|Group $group
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canAnonymousViewGroupDiscussions($group)
    {
        return false;
        $group = self::_checkGroup($group);
        switch ( $group->getGroupType() ) {
            case 'simple'       :
                if ($group->isPrivate() && !$group->getMembers()->isMemberExistsAndApproved($user_id)) return false;
                return true;
                break;
            case 'family'       :
                return true;
                break;
            default : throw new Zend_Exception("Incorrect Group Type");
        }        
    }
    /**
     * проверяет, может ли пользователь просматривать (видеть)
     * дискуссии группы (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewGroupDiscussions($group, $user_id)
    {
        $group = self::_checkGroup($group);
        switch ( $group->getGroupType() ) {
            case 'simple'       :
                if ($group->isPrivate() && !$group->getMembers()->isMemberExistsAndApproved($user_id)) return false;
                return true;
                break;
            case 'family'       :
                return true;
                break;
            default : throw new Zend_Exception("Incorrect Group Type");
        }
    }
    /**
     * проверяет, может ли пользователь осуществлять поиск
     * по группе, при проверке использует
     * @see Warecorp_DiscussionServer_AccessManager::canViewGroupDiscussions
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     */
    static public function canSearcGroupDiscussions($group, $user_id)
    {
        return Warecorp_DiscussionServer_AccessManager::canViewGroupDiscussions($group, $user_id);
    }
    /**
     * проверяет, может ли пользователь создавать сообщения (post)
     * для всех дискуссий/топиков группы (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canPostMessages($group, $user_id)
    {
        $group = self::_checkGroup($group);
        if ( $user_id === null ) return false;

        $moderatorsList = new Warecorp_DiscussionServer_ModeratorList();
        $isModerator    = FALSE !== array_search($user_id, $moderatorsList->findByGroupId($group->getId())) ? true : false;

        switch ( $group->getGroupType() ) {
            case 'simple'       :
                $settings = self::getGroupSettings($group);
                if ( $settings->getPostMode() == Warecorp_DiscussionServer_Enum_PostMode::EVERYONE ) return true;
                elseif ( $group->getMembers()->isMemberExistsAndApproved($user_id) || $isModerator) return true;
                return false;
                break;
            case 'family'       :
                $settings = self::getGroupSettings($group);
                if ( $settings->getPostMode() == Warecorp_DiscussionServer_Enum_PostMode::EVERYONE ) return true;
                elseif ( $group->getMembers()->isMemberExistsAndApproved($user_id) || $isModerator) return true;
                return false;
                break;
            default : throw new Zend_Exception("Incorrect Group Type");
        }
    }
    /**
     * проверяет, может ли пользователь отвечать на сообщения (post)
     * для всех дискуссий/топиков группы (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canReplyMessages($group, $user_id)
    {
        return self::canPostMessages($group, $user_id);
    }
    /**
     * проверяет, может ли пользователь редактировать свои сообщения
     * для всех дискуссий группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canEditOwnMessages($group, $user_id)
    {
        $group = self::_checkGroup($group);
        if ( $user_id === null ) return false;

        if ( self::$instance->isModerator($group, $user_id) ) return true;
        
        switch ( $group->getGroupType() ) {
            case 'simple'       :
            case 'family'       :
                $settings = self::getGroupSettings($group);
                if ( $settings->getAllowEditOwn() ) return true;
                break;
            default : throw new Zend_Exception("Incorrect Group Type");
        }
        return false;
    }
    /**
     * проверяет, может ли пользователь удалять свои сообщения
     * для всех дискуссий группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canDeleteOwnMessages($group, $user_id)
    {
        $group = self::_checkGroup($group);
        if ( $user_id === null ) return false;

        if ( self::$instance->isModerator($group, $user_id) ) return true;
        
        switch ( $group->getGroupType() ) {
            case 'simple'       :
            case 'family'       :
                $settings = self::getGroupSettings($group);
                if ( $settings->getAllowDeleteOwn() ) return true;
                break;
            default : throw new Zend_Exception("Incorrect Group Type");
        }

        return false;
    }
    /**
     * проверяет, может ли пользователь управлять настройками
     * пользователя для группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canConfigureSettings($group, $user_id)
    {
        $group = self::_checkGroup($group);
        if ( $user_id === null ) return false; 
        return true;
    }
    /**
     * проверяет, может ли пользователь управлять настройками хоста
     * для группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canConfigureHostSettings($group, $user_id)
    {
        $group = self::_checkGroup($group);
        if ( $user_id === null ) return false;
        if ( !$group->getMembers()->isHost($user_id) && !$group->getMembers()->isCoHost($user_id) ) return false;
        return true;
    }
    /**
     * проверяет, может ли пользователь помечать прочитанными все сообщения
     * для всех дискуссий/топиков группы
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canMarkGroupTopicsRead($group, $user_id)
    {
        $group = self::_checkGroup($group);
        if ( $user_id === null ) return false;
        return true;
    }
    /**
     * проверяет, может ли пользователь видеть страницу
     * Recent Posts (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewRecentMessages($group, $user_id)
    {
        $group = self::_checkGroup($group);
        if ( $user_id === null ) return false;
        return true;
    }
    /**
     * проверяет, может ли пользователь пользоваться RSS Feed
     * (доступ на уровне группы)
     * @param int|Group $group
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canGroupRSSFeed($group, $user_id)
    {
        $group = self::_checkGroup($group);
        if ( $user_id === null ) return false;
        return true;
    }


    //*********************************************************************
    /**
     * Discussion Access
     */
    //*********************************************************************

    /**
     * проверяет, может ли пользователь просматривать конкретную дискуссию
     * @param int|Discussion $discussion
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canViewDiscussion($discussion, $user_id)
    {
        return true;
    }
    /**
     * проверяет, может ли пользователь создавать новые топики для
     * определенной дискуссии, при проверке учитывается
     * @see Warecorp_DiscussionServer_AccessManager::canPostMessages
     * @param int $discussion
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canCreateDiscussionTopic($discussion, $user_id)
    {
        $discussion = self::_checkDiscussion($discussion);
        if ( $user_id === null ) return false;
        if ( self::$instance->isModerator($discussion, $user_id) ) return true;
        return Warecorp_DiscussionServer_AccessManager::canPostMessages($discussion->getGroupId(), $user_id);

    }
    /**
    * проверяет, кто может делать посты (главные новости) для блогов
    */
    static public function canCreateBlogPosts($discussion, $user_id)
    {
        $discussion = self::_checkDiscussion($discussion);
        if ( $user_id === null ) return false;
        if ( $discussion->getGroup()->getMembers()->isHost($user_id) || $discussion->getGroup()->getMembers()->isCohost($user_id) ) return true;
        return false;
        /*
        if ( self::$instance->isModerator($discussion, $user_id) ) return true;
        return Warecorp_DiscussionServer_AccessManager::canPostMessages($discussion->getGroupId(), $user_id);
        */

    }
    /**
     * проверяет, может ли пользователь пометить топики дискуссии как прочтенные
     * @param int $discussion
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canMarkDiscussionTopicsRead($discussion, $user_id)
    {
        $discussion = self::_checkDiscussion($discussion);
        if ( $user_id === null ) return false;
        return true;
    }
    /**
     * проверяет, может ли пользователь использовать rss дискуссии
     * @param int $discussion
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canDiscussionRSSFeed($discussion, $user_id)
    {
        $discussion = self::_checkDiscussion($discussion);
        if ( $user_id === null ) return false;
        return true;
    }


    //*********************************************************************
    /**
     * Topic Access
     */
    //*********************************************************************

    /**
     * проверяет, может ли пользователь подписываться на рассылку по топику
     * при проверке использует:
     * @see Warecorp_DiscussionServer_AccessManager::canViewGroupDiscussions
     * @param int $topic
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
    static public function canNotifyTopic($topic, $user_id)
    {
        $topic = self::_checkTopic($topic);
        if ($topic->isClosed()) return false;
	    if ( $user_id === null ) return false;
	    return Warecorp_DiscussionServer_AccessManager::canViewGroupDiscussions($topic->getDiscussion()->getGroupId(), $user_id);
    }
    /**
     * проверяет, может ли пользователь добавлять поста в
     * топик, при проверке использует
     * @see Warecorp_DiscussionServer_AccessManager::canPostMessages()
     * @param int $topic
     * @param int $user_id
     * @return boolean
     */
	static public function canReplyTopic($topic, $user_id)
	{
	    $topic = self::_checkTopic($topic);
	    if ($topic->isClosed()) return false;
	    if ( $user_id === null ) return false;
	    if ( self::$instance->isModerator($topic, $user_id) ) return true;
        return Warecorp_DiscussionServer_AccessManager::canPostMessages($topic->getDiscussion()->getGroupId(), $user_id);
	}
	/**
	 * проверяет, может ли пользователь управлять топиком (удалять его, закрывать, перемещать)
	 * @param int $topic
	 * @param int $user_id
	 * @return boolean
	 */
    static public function canManageTopic($topic, $user_id)
    {
        $topic = self::_checkTopic($topic);
        if ( $user_id === null ) return false;
        if ( self::$instance->isModerator($topic, $user_id) ) return true;
        else return false;
        
    }
    
    //*********************************************************************
    /**
     * Post Access
     */
    //*********************************************************************

    /**
     * проверяет, может ли пользователь видеть пост
     * @param int $post
     * @param int $user_id
     * @return boolean
     * @author Artem Sukharev
     */
	static public function canViewPost($post, $user_id)
	{
	    //$post = self::_checkPost($post);
        return true;
	}
	
	/**
	 * проверяет, может ли пользователь добавлять новый ответ на поста
	 * при проверке использует
	 * @see Warecorp_DiscussionServer_AccessManager::canPostMessages()
	 * @param int $post
	 * @param int $user_id
	 * @return boolean
	 */
	static public function canReplyPost($post, $user_id)
	{
	    $post = self::_checkPost($post);
	    if ($post->getTopic()->isClosed()) return false;
	    if ( $user_id === null ) return false;
	    if ( self::$instance->isModerator($post, $user_id) ) return true;
	    return Warecorp_DiscussionServer_AccessManager::canPostMessages($post->getTopic()->getDiscussion()->getGroupId(), $user_id);
	}
	
    /**
     * проверяет, может ли пользователь редактировать
     * конкретный пост, при проверке использует
     * @see Warecorp_DiscussionServer_AccessManager::canEditOwnMessages()
     * @param int $post
     * @param int $user_id
     * @return boolean
     */
	static public function canEditPost($post, $user_id)
	{
	    $post = self::_checkPost($post);
	    if ($post->getTopic()->isClosed()) return false;
	    if ( $user_id === null ) return false;
        if ( self::$instance->isModerator($post, $user_id) ) return true;
        elseif ( $post->getAuthorId() == $user_id ) {    //  post author
           return Warecorp_DiscussionServer_AccessManager::canEditOwnMessages($post->getTopic()->getDiscussion()->getGroupId(), $user_id);
        }
        return false;
	}
	
    /**
     * проверяет, может ли пользователь удалить
     * конкретный пост, при проверке использует
     * @see Warecorp_DiscussionServer_AccessManager::candeleteOwnMessages
     * @param int $post
     * @param int $user_id
     * @return boolean
     */
	static public function canDeletePost($post, $user_id)
	{
	    $post = self::_checkPost($post);
	    if ($post->getTopic()->isClosed()) return false;
	    if ( $post->isTopicPart() )    return false;
	    if ( $user_id === null )       return false;
        if ( self::$instance->isModerator($post, $user_id) ) return true;
        elseif ( $post->getAuthorId() == $user_id ) {    //  post author
           return Warecorp_DiscussionServer_AccessManager::candeleteOwnMessages($post->getTopic()->getDiscussion()->getGroupId(), $user_id);
        }

        return false;
	}
	
	/**
	 * проверяет, может ли пользователь отправлять
	 * отчет о посте
	 * @param int $post
	 * @param int $user_id
	 * @return boolean
	 */
	static public function canReportPost($post, $user_id)
	{
	    $post = self::_checkPost($post);
	    if ($post->getTopic()->isClosed()) return false;
	    if ( $user_id === null ) return false;
	    if ($post->getAuthorId() == $user_id) {        //  post author
            return false;
	    } else {                                       //  user
            return true;
	    }
	}
	
	/**
	 * проверяет, может ли пользователь отправлять письмо
	 * непосредственно автору конкретного поста
	 * @param int $post
	 * @param int $user_id
	 * @return boolean
	 */
	static public function canEmailAuthorPost($post, $user_id)
	{
	    $post = self::_checkPost($post);
	    if ($post->getTopic()->isClosed()) return false;
	    if ( $user_id === null ) return false;
	    if ($post->getAuthorId() == $user_id) {        //  post author
            return false;
	    } else {                                       //  user
            return true;
	    }
	}
}
?>
