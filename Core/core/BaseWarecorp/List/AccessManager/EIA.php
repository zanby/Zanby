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

class BaseWarecorp_List_AccessManager_EIA extends Warecorp_List_AccessManager
{
    /**
     * @var Warecorp_Group_Family
     */
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
     * @return Warecorp_List_AccessManager
     */
    static public function getInstance()
    {
        if ( !self::$instance ) {
            self::$instance = new Warecorp_List_AccessManager_EIA();
        }
        return self::$instance;
    }

    //komarovski
    static public function canAddToMyLists($user)
    {
        if ( !($user instanceof Warecorp_User) || !$user->getId()) {
           return false;
        }
        return true;
    }
    static public function canPostComment($user)
    {
        if ( !($user instanceof Warecorp_User) || !$user->getId()) {
           return false;
        }
        return true;
    }
    static public function canAddListItem($user)
    {
        if ( !($user instanceof Warecorp_User) || !$user->getId()) {
           return false;
        }
        return true;
    }

    public static function canViewList($list, $context, $user) {
        if ( $context instanceof Warecorp_Group_Standard && !$context->getIsPrivate() && !$list->getIsPrivate() ) {
            return true;
        }
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewList($list, $context, $user) || self::isOwnerEIA($context, $user);
        }
        return parent::canViewList($list, $context, $user);
    }

    public static function canViewPrivateLists($context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewPrivateLists($context, $user) || self::isOwnerEIA($context, $user);
        }
        return parent::canViewPrivateLists($context, $user);
    }

    public static function canViewLists($context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewLists($context, $user) || self::isOwnerEIA($context, $user);
        }
        return parent::canViewLists($context, $user);
    }

    public static function canViewPublicLists($context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewPublicLists($context, $user) || self::isOwnerEIA($context, $user);
        }
        return parent::canViewPublicLists($context, $user);
    }

    public static function canViewSharedLists($context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewSharedLists($context, $user) || self::isOwnerEIA($context, $user);
        }
        return parent::canViewSharedLists($context, $user);
    }
}
