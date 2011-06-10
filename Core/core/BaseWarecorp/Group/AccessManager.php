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
 * @package Warecorp_Group_AccessManager
 * @copyright  Copyright (c) 2009
 * @author Yury Zolotarsky, Alexander Komarovski
 */

/**
 * User to Group data accessable
 *
 */

//@TODO @author komarovski -- develop interface which will include all necessary VIEW and MANAGE methods and implement it to Group Access Manager

class BaseWarecorp_Group_AccessManager {

    static private $group;
    static private $user;
    static private $privileges;
    static private $privilegesValues;
    static private $isHostPrivileges;
    static private $isFamily;
    static private $isGroupHost;

    static private $cacheCreatedUsers = array();
    static private $cacheCreatedGroups = array();

    protected static function canCheck($group, $user) {
        if ( !($group instanceof Warecorp_Group_Base) ) {
            if ( array_key_exists($group, self::$cacheCreatedGroups) ) $group = self::$cacheCreatedGroups[$group];
            else {
                $group = Warecorp_Group_Factory::loadById($group);
                self::$cacheCreatedGroups[$group->getId()] = $group;
            }
        }
        if ( !($user  instanceof Warecorp_User) ) {
            if ( array_key_exists($user, self::$cacheCreatedUsers) ) $user = self::$cacheCreatedUsers[$user];
            else {
                $user = new Warecorp_User('id', $user);
                self::$cacheCreatedUsers[$user->getId()] = $user;
            }
        }
        self::$group = $group;
        self::$user  = $user;

        if ($group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) {
            self::$isFamily = true;
            self::$privilegesValues['OwnersOnly'] = 0;
            self::$privilegesValues['OwnersAndGroupHosts'] = 1;
            self::$privilegesValues['AllMembers'] = 2;
            self::$privilegesValues['OwnersAndCertainMembers'] = 3;
            $hosts = $group->getMembers()->getHostsOfAllGroupsInFamily(true);
            $hosts = array_keys($hosts);
            self::$isGroupHost = (in_array($user->getId(), $hosts));
        } else {
            self::$isFamily = false;
            self::$privilegesValues['OwnersOnly'] = 1;
            self::$privilegesValues['AllMembers'] = 0;
            self::$privilegesValues['OwnersAndCertainMembers'] = 2;
            self::$privilegesValues['OwnersAndGroupHosts'] = -1;
        }
        self::$privileges = $group->getPrivileges();
        self::$isHostPrivileges = self::checkHostPrivileges($group, $user);
        return self::$isHostPrivileges || $group->getMembers()->isMemberExistsAndApproved($user->getId());
    }
    
    public static function isGroupHost(){
        return self::$isGroupHost;
    }
    
    /**
     * @author Alexander Komarovski
     * Host privileges method changed in coordination with Bug #6231
     */
    protected static function checkHostPrivileges($group, $user) {
        //OLD CODE
        //if ($group->getMembers()->isHost($user) || $group->getMembers()->isCohost($user)) {
        //    return true;
        ///}

        //NEW
        if ($group instanceof Warecorp_Group_Simple) {
            if ( 'ESA' == IMPLEMENTATION_TYPE ) {
                //TOOLS
                //private group - HOST, CO-HOST, FAMILY HOST (if member of group), FAMILY CO-HOST (if member of group)
                //public group - HOST, CO-HOST, FAMILY HOST, FAMILY CO-HOST
                if ($group->getMembers()->isHost($user) || $group->getMembers()->isCohost($user)) {
                    return true;
                }

                $globalGroup = null;
                if ( Zend_Registry::isRegistered('globalGroup') ) {
                    $globalGroup = Zend_Registry::get('globalGroup');
                    if ($globalGroup !== null && $globalGroup->getId()) {
                        if ($globalGroup->getMembers()->isHost($user) || $globalGroup->getMembers()->isCohost($user)) {
                            if (!$group->getIsPrivate() || $group->getMembers()->isMemberExistsAndApproved($user->getId())) {
                                return true;
                            }
                        }
                    }
                }
                /**
                 * @author Roman Gabrusenok
                 * Source has been commented according change request #9644
                 */
                /*else {
					/**
					 * if current member is owner or coowner of any family that contain current group - 
					 * this user has host permissions for curren group
					 * @author of notes Artem Sukharev
					 *
                    $families = $group->getFamilyGroups()->getList();
                    foreach($families as &$globalGroup) {
                        if ($globalGroup->getMembers()->isHost($user) || $globalGroup->getMembers()->isCohost($user)) {
                            if (!$group->getIsPrivate() || $group->getMembers()->isMemberExistsAndApproved($user->getId())) {
                                return true;
                            }
                        }
                    }
                }
                */



            } else { //EIA
                //TOOLS
                //public/private group - HOST, CO-HOST, FAMILY HOST, FAMILY CO-HOST
                if ($group->getMembers()->isHost($user) || $group->getMembers()->isCohost($user)) {
                    return true;
                }

                /*
                //  Roman Gabrusenok
                //  Family Owner can view documents in child group, but hi can't do administrative functions
                $globalGroup = null;
                if ( Zend_Registry::isRegistered('globalGroup') ) {
                    $globalGroup = Zend_Registry::get('globalGroup');
                }

                if ($globalGroup !== null && $globalGroup->getId() && ($globalGroup->getMembers()->isHost($user) || $globalGroup->getMembers()->isCohost($user)) ) {
                    return true;
                }
                */
            }
        } elseif ($group instanceof Warecorp_Group_Family) {
            //TOOLS
            //public/private group - FAMILY HOST, FAMILY CO-HOST
            if ($group->getMembers()->isHost($user) || $group->getMembers()->isCohost($user)) {
                return true;
            }
        }
        //

        return false;
    }

    public static function canUseCalendar($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getCalendar() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getCalendar() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getCalendar() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getCalendar() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpCalendar')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function canUseEmail($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getEmail() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getEmail() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getEmail() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getEmail() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpEmail')->isExist($user)) || self::$isHostPrivileges);
        return $usable;

    }

    public static function canUsePhotos($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getPhotos() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getPhotos() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getPhotos() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getPhotos() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpPhotos')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function canUseVideos($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getVideos() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getVideos() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getVideos() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));

        $usable = $usable || ((self::$privileges->getVideos() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpVideos')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function canUseDocuments($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getDocuments() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getDocuments() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getDocuments() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getDocuments() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpDocuments')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function canUseLists($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getLists() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getLists() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getLists() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getLists() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpLists')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function canUseForumsPosts($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getForumsPosts() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getForumsPosts() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getForumsPosts() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getForumsPosts() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpForumsPosts')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function canUseForumsModerate($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getForumsModerate() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getForumsModerate() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getForumsModerate() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getForumsModerate() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpForumsModerate')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function canUsePolls($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getPolls() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getPolls() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getPolls() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getPolls() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpPolls')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    /**
     *  @param Warecorp_Group_Base $group
     *  @param int $user User ID
     */
	public static function canUseManageMembers($group, $user)
	{
		$usable = self::canCheck($group, $user) && (self::$privileges->getManageMembers() == self::$privilegesValues['AllMembers']);
		$usable = $usable || ((self::$privileges->getManageMembers() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && self::$group->getMembers()->isMemberExistsAndApproved(self::$user->getId()) && (self::$privileges->getManageMembers() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
		$usable = $usable || ((self::$privileges->getManageMembers() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpManageMembers')->isExist($user)) || self::$isHostPrivileges);
		return $usable;
	}

    public static function canUseManageGroupFamilies($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getManageGroupFamilies() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getManageGroupFamilies() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getManageGroupFamilies() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getManageGroupFamilies() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpManageGroupFamilies')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function canUseModifyLayout($group, $user) {
        $usable = self::canCheck($group, $user) && (self::$privileges->getModifyLayout() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getModifyLayout() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getModifyLayout() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getModifyLayout() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpModifyLayout')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function isHostPrivileges($group, $user) {
        return self::canCheck($group, $user) && self::$isHostPrivileges;
    }

    public static function canShareToFamiliesGroups($group, $user)
    {
        $usable = self::canCheck($group, $user) && (self::$privileges->getShareToFamily() == self::$privilegesValues['AllMembers']);
        $usable = $usable || ((self::$privileges->getShareToFamily() == self::$privilegesValues['OwnersOnly']) && self::$isHostPrivileges);
        $usable = $usable || (self::$isFamily && (self::$privileges->getShareToFamily() == self::$privilegesValues['OwnersAndGroupHosts']) && (self::$isHostPrivileges || self::$isGroupHost));
        $usable = $usable || ((self::$privileges->getShareToFamily() == self::$privilegesValues['OwnersAndCertainMembers']) && (self::$privileges->getUsersListByTool('gpShareToFamily')->isExist($user)) || self::$isHostPrivileges);
        return $usable;
    }

    public static function canUnshareToFamiliesGroups($group, $user)
    {
        return self::canCheck($group, $user);
    }

    /* Can VIEW Functions
    *  @author Alexander Komarovski
    *  functions you can find above checks ability to manage stuff
    *  functions you can find below provide functionality which cheks ability to view content in coordination with "Content visibility permissions matrix"
    */
    public static function canViewSummary($group, $user) {
        // any user can view summary of any group for ESA and EIA
        // EXCEPTION: Anonymous (not application member) user can't view summary of private group
//        if (!$group->getIsPrivate() || $user->isAppMember()) {
//            return true;
//        }
//        return false;

        /**
         * @see https://secure.warecorp.com/redmine/issues/12510
         * @author Artem Sukharev
         */
        return self::canViewMembers($group, $user);
    }
    public static function canViewMembers($group, $user) {
        if ($group instanceof Warecorp_Group_Simple) {
//            if ( 'ESA' == IMPLEMENTATION_TYPE ) {
//                if ($group->getIsPrivate()) {
//                    if ($group->getMembers()->isMemberExistsAndApproved($user) || $group->getMembers()->isHost($user) || $group->getMembers()->isCohost($user)) {
//                        return true;
//                    }
//                    $globalGroup = null;
//                    if ( Zend_Registry::isRegistered('globalGroup') ) {
//                        $globalGroup = Zend_Registry::get('globalGroup');
//                        if ($globalGroup !== null && $globalGroup->getId()) {
//                            if ($globalGroup->getMembers()->isHost($user) || $globalGroup->getMembers()->isCohost($user)) {
//                                if ($group->getMembers()->isMemberExistsAndApproved($user->getId())) {
//                                    return true;
//                                }
//                            }
//                        }
//                    } else {
//                        $families = $group->getFamilyGroups()->getList();
//                        foreach($families as &$globalGroup) {
//                            if ($globalGroup->getMembers()->isHost($user) || $globalGroup->getMembers()->isCohost($user)) {
//                                if ($group->getMembers()->isMemberExistsAndApproved($user->getId())) {
//                                    return true;
//                                }
//                            }
//                        }
//                    }
//                } else {
//                    if ($user->isAppMember()) {
//                        return true;
//                    }
//                }
//            } else { //EIA
                if ($group->getIsPrivate()) {
                    if ($group->getMembers()->isMemberExistsAndApproved($user) || $group->getMembers()->isHost($user) || $group->getMembers()->isCohost($user)) {
                        return true;
                    }
                    $globalGroup = null;
                    if ( Zend_Registry::isRegistered('globalGroup') ) {
                        $globalGroup = Zend_Registry::get('globalGroup');
                        if ($globalGroup !== null && $globalGroup->getId() && ($globalGroup->getMembers()->isHost($user) || $globalGroup->getMembers()->isCohost($user)) ) {
                            return true;
                        }
                    }

                } else {
                    return true;
                }
        //    }
        } elseif ($group instanceof Warecorp_Group_Family) {
//            if ( 'ESA' == IMPLEMENTATION_TYPE ) {
//                if ($user->isAppMember()) {
//                    return true;
//                }
//            } else { //EIA
                return true;
        //    }
        }
        return false;
    }
    public static function canViewDiscussions($group, $user) {
        //access rules is the same now
        return self::canViewMembers($group, $user);
    }
    public static function canViewPhotos($group, $user) {
        //access rules is the same now
        return self::canViewMembers($group, $user);
    }
    public static function canViewVideos($group, $user) {
        //access rules is the same now
        return self::canViewMembers($group, $user);
    }
    public static function canViewLists($group, $user) {
        //access rules is the same now
        return self::canViewMembers($group, $user);
    }
    public static function canViewEvents($group, $user) {
        //access rules is the same now
        return self::canViewMembers($group, $user);
    }
    public static function canViewDocuments($group, $user) {
        //access rules is the same now
        return self::canViewMembers($group, $user);
    }
    public static function canViewTools($group, $user) {
        //isHostPriveleges must do the same
        return self::isHostPrivileges($group, $user);
    }
    /*Tools not included*/

    /* /VIEW functions */

}
