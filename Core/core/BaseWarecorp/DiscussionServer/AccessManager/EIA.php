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

class BaseWarecorp_DiscussionServer_AccessManager_EIA extends Warecorp_DiscussionServer_AccessManager
{
    static protected $eiFamily;
    /**
     *  Only for EIA Implementations
     *
     * @param Warecorp_User|Warecorp_Group_Base $context
     * @param Warecorp_User|int $user
     * @return boolean
     */
    static protected function isOwnerEIA($context, $user)
    {
        if ( $context instanceof Warecorp_User )
            return false;

        if ( !defined("IMPLEMENTATION_FAMILY_GROUP_UID") || IMPLEMENTATION_FAMILY_GROUP_UID === '' )
            return false;
        if ( NULL === self::$eiFamily )
            self::$eiFamily = Zend_Registry::get("globalGroup");
        if ( !self::$eiFamily || !(self::$eiFamily instanceof Warecorp_Group_Family) )
            return false;

        if ( !self::$eiFamily->getGroups()->isGroupInFamily($context) )
            return false;

        

        if ( self::$eiFamily->getMembers()->isHost($user) || self::$eiFamily->getMembers()->isCohost($user) )
            return true;
        
        return FALSE;
    }
    /**
     * Return instance of Access Manager
     * @return Warecorp_DiscussionServer_AccessManager
     */
	static public function getInstance() {
        if ( !self::$instance ){
            self::$instance = new Warecorp_DiscussionServer_AccessManager_EIA();
            self::$instance->moderatorsList = new Warecorp_DiscussionServer_ModeratorList();
        }
        return self::$instance;
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
        $group = self::_checkGroup($group);
        if  ( $group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE && $group->isPrivate()  ) {
            return false;
        }
        return true;
    }

    /**
     * @return boolean
     */
    public static function canViewDiscussion($discussion, $user_id) {
        return parent::canViewDiscussion($discussion, $user_id);
    }

    /**
     * @return boolean
     */
    public static function canViewGroupDiscussions($group, $user_id) {
        if ( !$group instanceof Warecorp_Group_Base )
            $group = Warecorp_Group_Factory::loadById($group);
        if ( $group instanceof Warecorp_Group_Simple ) {
            return parent::canViewGroupDiscussions($group, $user_id) || self::isOwnerEIA($group, $user_id);
        }
        return parent::canViewGroupDiscussions($group, $user_id);
    }

    /**
     * @return boolean
     */
    public static function canViewRecentMessages($group, $user_id) {
        if ( !$group instanceof Warecorp_Group_Base )
            $group = Warecorp_Group_Factory::loadById($group);
        if ( $group instanceof Warecorp_Group_Simple )
            return parent::canViewRecentMessages($group, $user_id) || self::isOwnerEIA($group, $user_id);
        return parent::canViewRecentMessages($group, $user_id);
    }

    /**
     * @return boolean
     */
    public static function canMarkDiscussionTopicsRead($discussion, $user_id) {
        
        $discussion = parent::_checkDiscussion($discussion);
        
        $group = $discussion->getGroup();
        return true;
        if ( $group instanceof Warecorp_Group_Simple ) {
            return parent::canMarkDiscussionTopicsRead($discussion, $user_id) || self::isOwnerEIA($group, $user_id);
        }
        return parent::canMarkDiscussionTopicsRead($discussion, $user_id);
    }

    /**
     * @return boolean
     */
    public static function canMarkGroupTopicsRead($group, $user_id) {
        if ( !$group instanceof Warecorp_Group_Base ) {
            $group = Warecorp_Group_Factory::loadById($group);
        }
        if ( $group instanceof Warecorp_Group_Simple ) {
            return parent::canMarkGroupTopicsRead($group, $user_id) || self::isOwnerEIA($group, $user_id);
        }
        return parent::canMarkGroupTopicsRead($group, $user_id);
    }

    /**
     * @return boolean
     */
    public static function canViewPost($post, $user_id) {
        if ( !$post instanceof Warecorp_DiscussionServer_Post )
            $post = new Warecorp_DiscussionServer_Post($post);
        $group = $post->getTopic()->getDiscussion()->getGroup();
        if ( $group instanceof Warecorp_Group_Simple ) {
            return parent::canViewPost($post, $user_id) || self::isOwnerEIA($group, $user_id);
        }
        return parent::canViewPost($post, $user_id);
    }
}
