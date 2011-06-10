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
 * Warecorp FRAMEWORK
 * @package Warecorp_User_AccessManager
 * @copyright  Copyright (c) 2007
 */

/**
 * class for User to other Users data accessable
 *
 */

class BaseWarecorp_User_AccessManager {

    static private $instance= false;

    static private $isAnonymous;
    static private $isEqual;
    static private $isBlocked;

    static private $isFriend;
    static private $isMyGroupMember;
    static private $isFriendOfFriends;
    static private $isFriends_Groups;
    static private $isFriends_Network;
    static private $isFriends_Groups_Network;
    static private $privacy;

    static private $owner;
    static private $user;

    static public function getInstance() {
        if (!self::$instance) {
            self::$instance = new Warecorp_User_AccessManager();
        }
        return self::$instance;
    }

    static public function getIsBlocked($owner, $user) {
        self::getUsersRelations($owner, $user);
        return self::$isBlocked;
    }

    static public function getIsFriends_Groups($owner, $user) {
        self::getUsersRelations($owner, $user);
        return self::$isFriends_Groups;
    }

    static public function getIsFriends_Network($owner, $user) {
        self::getUsersRelations($owner, $user);
        return self::$isFriends_Network;
    }

    static public function getIsFriends_Groups_Network($owner, $user) {
        self::getUsersRelations($owner, $user);
        return self::$isFriends_Groups_Network;
    }

    /**
     * Get status user in relation of owner
     * @param int|Warecorp_User $owner, $user
     * @return void
     * @author Yury Zolotarsky
     */
    static private function getUsersRelations($owner, $user) {
        if (!($owner instanceof Warecorp_User)) $owner = new Warecorp_User('id', $owner);
        if (!($user instanceof Warecorp_User)) $user = new Warecorp_User('id', $user);

        if (self::$owner !== null && self::$user !== null && self::$owner->getId() == $owner->getId() && self::$user->getId() == $user->getId()) return;

        self::$owner = $owner;
        self::$user = $user;
        self::$privacy = $owner->getPrivacy();
        if (!(boolean)$user->getId()) {
            self::$isAnonymous = true;
            self::$isMyGroupMember = false;
            self::$isFriend = false;
            self::$isFriendOfFriends = false;
            self::$isFriends_Groups = false;
            self::$isFriends_Network = false;
            self::$isFriends_Groups_Network = false;
            return;
        } else self::$isAnonymous = false;

        if ($owner->getId() == $user->getId()) {
            self::$isEqual = true;
            self::$isMyGroupMember = true;
            self::$isFriend = true;
            self::$isFriendOfFriends = true;
            self::$isFriends_Groups = true;
            self::$isFriends_Network = true;
            self::$isFriends_Groups_Network = true;
            return;
        } else self::$isEqual = false;

        $privacy = self::$privacy;
        if ($privacy->getBlockList()->isExist($user)) {
            self::$isBlocked = true;
        } else self::$isBlocked = false;
        //$isGroupOrganizer = Warecorp_Group_Members_Abstract::isHostAnyGroup($user, true);
        //$isMyGroupOrganizer = Warecorp_Group_Members_Abstract::isUserGroupsOrganizer($owner, $user);
        //$isMyGroupMember = Warecorp_Group_Members_Abstract::isUserGroupsMember($owner, $user);

        self::$isFriend = Warecorp_User_Friend_Item::isUserFriend($owner->getId(), $user->getId());
        self::$isFriendOfFriends = Warecorp_User_Friend_ofFriend_Item::isUserFriendOfFriend($owner->getId(), $user->getId());
        self::$isMyGroupMember = Warecorp_Group_Members_Abstract::isUserGroupsMember($owner->getId(), $user->getId());

        self::$isFriends_Groups = self::$isFriend || self::$isMyGroupMember;
        self::$isFriends_Network = self::$isFriend || self::$isFriendOfFriends;
        self::$isFriends_Groups_Network = self::$isFriends_Groups || self::$isFriends_Network;
    }

    /**
     * Can user contact to owner
     * @param int|Warecorp_User $owner, $user
     * @return bool
     * @author Yury Zolotarsky
     */
    static public function canContact($owner, $user) {
        self::getUsersRelations($owner, $user);
        $privacy = self::$privacy;

        if ($owner->getId() == $user->getId()) return true;
        if ($privacy->getBlockList()->isExist($user)) return false;

        $isGroupOrganizer = Warecorp_Group_Members_Abstract::isHostAnyGroup($user, true);
        $isMyGroupOrganizer = Warecorp_Group_Members_Abstract::isUserGroupsOrganizer($owner, $user);
        $isMyGroupMember = Warecorp_Group_Members_Abstract::isUserGroupsMember($owner, $user);
        if (!self::$isBlocked) {
            $contactable1  = (boolean)$privacy->getCpAnyMembers();
            $contactable1  = $contactable1 || ($isGroupOrganizer && (boolean)$privacy->getCpGroupOrganizers());
            $contactable1  = $contactable1 || ($isMyGroupOrganizer && (boolean)$privacy->getCpMyGroupOrganizers());
            $contactable1  = $contactable1 || ($isMyGroupMember && (boolean)$privacy->getCpMyGroupMembers());
            $contactable1  = $contactable1 || (self::$isFriend && (boolean)$privacy->getCpMyFriends());
            $contactable1  = $contactable1 || (self::$isFriends_Network && (boolean)$privacy->getCpMyNetwork());

            $contactable   = (boolean)$user->getId() && $contactable1;
        }
        return $contactable;
    }

    /**
     * Can user view owner's profile
     * @param int|Warecorp_User $owner, $user
     * @return bool
     * @author Yury Zolotarsky
     */
    static public function canViewProfile($owner, $user) {
        self::getUsersRelations($owner, $user);
        $privacy = self::$privacy;
        if ($owner->getId() == $user->getId()) return true;
        if ($privacy->getBlockList()->isExist($user)) return false;

        $isGroupOrganizer = Warecorp_Group_Members_Abstract::isHostAnyGroup($user, true);
        $isMyGroupOrganizer = Warecorp_Group_Members_Abstract::isUserGroupsOrganizer($owner, $user);
        $isMyGroupMember = Warecorp_Group_Members_Abstract::isUserGroupsMember($owner, $user);

        if (!self::$isBlocked) {
            $viewable = (boolean)$privacy->getCvAnyOne();
            $viewable = $viewable || ((boolean)$user->getId() && (boolean)$privacy->getCvAnyMembers());
            $viewable = $viewable || ($isGroupOrganizer && (boolean)$privacy->getCvGroupOrganizers());
            $viewable = $viewable || ($isMyGroupOrganizer && (boolean)$privacy->getCvMyGroupOrganizers());
            $viewable = $viewable || ($isMyGroupMember && (boolean)$privacy->getCvMyGroupMembers());
            $viewable = $viewable || (self::$isFriend && (boolean)$privacy->getCvMyFriends());
            $viewable = $viewable || (self::$isFriends_Network && (boolean)$privacy->getCvMyNetwork());
        }
        return $viewable;
    }

    /**
     * Can user view owner's photos
     * @param int|Warecorp_User $owner, $user
     * @return bool
     * @author Yury Zolotarsky
     */
    static public function canViewPhotos($owner, $user) {
        self::getUsersRelations($owner, $user);
        //if (self::$isEqual == true) return true;
        $privacy = self::$privacy;
        if (!self::$isBlocked) {
            $viewable = (boolean)self::$user->getId();
            $viewable1 = ($privacy->getCvPublicPhotos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_EVERYONE);
            $viewable1 = $viewable1 || (self::$isFriends_Groups_Network && ($privacy->getCvPublicPhotos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Network && ($privacy->getCvPublicPhotos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Groups && ($privacy->getCvPublicPhotos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS));
            $viewable1 = $viewable1 || (self::$isFriend && ($privacy->getCvPublicPhotos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS));
            $viewable = $viewable && $viewable1;
            return $viewable;
        } else return false;
    }

    /**
     * Can user view owner`s videos
     * @author Roman Gabrusenok
     * @param int|Warecorp_User $owner, $user
     * @return bool
     */
    static public function canViewVideos($owner, $user) {
        self::getUsersRelations($owner, $user);
        //if (self::$isEqual == true) return true;
        $privacy = self::$privacy;
        if (!self::$isBlocked) {
            $viewable = (boolean)self::$user->getId();
            $viewable1 = ($privacy->getCvPublicVideos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_EVERYONE);
            $viewable1 = $viewable1 || (self::$isFriends_Groups_Network && ($privacy->getCvPublicVideos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Network && ($privacy->getCvPublicVideos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Groups && ($privacy->getCvPublicVideos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS));
            $viewable1 = $viewable1 || (self::$isFriend && ($privacy->getCvPublicVideos() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS));
            $viewable = $viewable && $viewable1;
            return $viewable;
        } else return false;
    }

    /**
     * Can user view owner's documents
     * @param int|Warecorp_User $owner, $user
     * @return bool
     * @author Yury Zolotarsky
     */
    static public function canViewDocuments($owner, $user) {
        self::getUsersRelations($owner, $user);
        $privacy = self::$privacy;
        if (!self::$isBlocked) {
            $viewable = (boolean)self::$user->getId();
            $viewable1 = ($privacy->getCvPublicDocuments() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_EVERYONE);
            $viewable1 = $viewable1 || (self::$isFriends_Groups_Network && ($privacy->getCvPublicDocuments() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Network && ($privacy->getCvPublicDocuments() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Groups && ($privacy->getCvPublicDocuments() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS));
            $viewable1 = $viewable1 || (self::$isFriend && ($privacy->getCvPublicDocuments() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS));
            $viewable = $viewable && $viewable1;
            return $viewable;
        } else return false;
    }

    /**
     * Can user view owner's lists
     * @param int|Warecorp_User $owner, $user
     * @return bool
     * @author Yury Zolotarsky
     */
    static public function canViewLists($owner, $user) {
        self::getUsersRelations($owner, $user);
        $privacy = self::$privacy;
        if (!self::$isBlocked) {
            $viewable = (boolean)self::$user->getId();
            $viewable1 = ($privacy->getCvPublicLists() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_EVERYONE);
            $viewable1 = $viewable1 || (self::$isFriends_Groups_Network && ($privacy->getCvPublicLists() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Network && ($privacy->getCvPublicLists() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Groups && ($privacy->getCvPublicLists() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS));
            $viewable1 = $viewable1 || (self::$isFriend && ($privacy->getCvPublicLists() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS));
            $viewable = $viewable && $viewable1;
            return $viewable;
        } else return false;
    }

    /**
     * Can user view owner's events
     * @param int|Warecorp_User $owner, $user
     * @return bool
     * @author Yury Zolotarsky
     */
    static public function canViewEvents($owner, $user) {
        self::getUsersRelations($owner, $user);
        $privacy = self::$privacy;
        if (!self::$isBlocked) {
            $viewable = (boolean)self::$user->getId();
            $viewable1 = ($privacy->getCvPublicEvents() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_EVERYONE);
            $viewable1 = $viewable1 || (self::$isFriends_Groups_Network && ($privacy->getCvPublicEvents() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Network && ($privacy->getCvPublicEvents() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Groups && ($privacy->getCvPublicEvents() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS));
            $viewable1 = $viewable1 || (self::$isFriend && ($privacy->getCvPublicEvents() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS));
            $viewable = $viewable && $viewable1;
            return $viewable;
        } else return false;
    }
    /**
     * Can user view owner's tags
     * @param int|Warecorp_User $owner, $user
     * @return bool
     * @author Yury Zolotarsky
     */

    static public function canViewTags($owner, $user) {
        self::getUsersRelations($owner, $user);
        $privacy = self::$privacy;
        if (!self::$isBlocked) {
            $viewable = (boolean)self::$user->getId();
            $viewable1 = ($privacy->getCvPublicTags() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_EVERYONE);
            $viewable1 = $viewable1 || (self::$isFriends_Groups_Network && ($privacy->getCvPublicTags() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Network && ($privacy->getCvPublicTags() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Groups && ($privacy->getCvPublicTags() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS));
            $viewable1 = $viewable1 || (self::$isFriend && ($privacy->getCvPublicTags() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS));
            $viewable = $viewable && $viewable1;
            return $viewable;
        } else return false;
    }

    /**
     * Can user view owner's friends
     * @param int|Warecorp_User $owner, $user
     * @return bool
     * @author Yury Zolotarsky
     */
    static public function canViewFriends($owner, $user) {
        self::getUsersRelations($owner, $user);
        $privacy = self::$privacy;
        if (!self::$isBlocked) {
            $viewable = (boolean)self::$user->getId();
            $viewable1 = ($privacy->getCvPublicFriends() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_EVERYONE);
            $viewable1 = $viewable1 || (self::$isFriends_Groups_Network && ($privacy->getCvPublicFriends() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Network && ($privacy->getCvPublicFriends() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_NETWORK));
            $viewable1 = $viewable1 || (self::$isFriends_Groups && ($privacy->getCvPublicFriends() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS_GROUPS));
            $viewable1 = $viewable1 || (self::$isFriend && ($privacy->getCvPublicFriends() == Warecorp_User_Privacy_Enum_PublicMeans::PUBLIC_IS_FRIENDS));
            $viewable = $viewable && $viewable1;
            return $viewable;
        } else return false;
    }

}
