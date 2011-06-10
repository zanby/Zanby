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

class BaseWarecorp_Document_AccessManager_EIA extends Warecorp_Document_AccessManager
{
    /**
     * @var Warecorp_Group_Family
     */
    static protected $eiFamily;

    /**
     * Return instance of Access Manager
     * @return Warecorp_Document_AccessManager
     * @todo remove all this overloads when we will move to 5.3 with get_called_class()
     */
    static public function getInstance()
    {
        if ( !self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

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
            self::$eiFamily = Warecorp_Group_Factory::loadByGroupUID(IMPLEMENTATION_FAMILY_GROUP_UID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        if ( !self::$eiFamily || !(self::$eiFamily instanceof Warecorp_Group_Family) )
            return false;

        if ( !self::$eiFamily->getGroups()->isGroupInFamily($context) )
            return false;
        if ( self::$eiFamily->getMembers()->isHost($user) || self::$eiFamily->getMembers()->isCohost($user) )
            return true;
        return FALSE;
    }

    /**
     * CHECKED
     * @param Warecorp_User|Warecorp_Group_Base $objContext
     */
    public static function canAnonymousViewDocuments($objContext)
    {
        if ( $objContext instanceof Warecorp_User ) {
            return false;
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            if ( $objContext instanceof Warecorp_Group_Simple ) {
                if ( $objContext->isPrivate() ) return false;
                else                            return true;
            }
            if ( $objContext instanceof Warecorp_Group_Family ) {
                return true;
            }
        } else {
            throw new Warecorp_Document_Exception('Incorrect Content Object');
        }
        return false;
    }

    /**
     * проверяет, может ли пользователь видеть документы и папку (т.е. контент документов в общем)
     * данного владельца
     * @param Warecorp_Group_Base|Warecorp_User $context
     * @param obj $owner User || Warecorp_Group_...
     * @param int $userId
     * @return boolean
     */
    public static function canViewOwnerDocuments($context, $owner, $user_id) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewOwnerDocuments($context, $owner, $user_id) || self::isOwnerEIA($context, $user_id) && $owner->getId() == $context->getId();
        }
        return parent::canViewOwnerDocuments($context, $owner, $user_id);
    }

    /**
     * @return boolean
     */
    public static function canViewDocument($document, $context, $user) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewDocument($document, $context, $user) || self::isOwnerEIA($context, $user);
        }
        return parent::canViewDocument($document, $context, $user);
    }

    /**
     * @return boolean
     */
    public static function canViewFamilySharedDocuments($group, $family, $user) {
        if ( $group instanceof Warecorp_Group_Simple ) {
            return parent::canViewFamilySharedDocuments($group, $family, $user) || self::isOwnerEIA($group, $user);
        }
        return parent::canViewFamilySharedDocuments($group, $family, $user);
    }

    /**
     * @return boolean
     */
    public static function canViewPrivateDocuments($context, $owner, $user_id) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewPrivateDocuments($context, $owner, $user_id) || self::isOwnerEIA($context, $user_id);
        }
        return parent::canViewPrivateDocuments($context, $owner, $user_id);
    }

    /**
     * @return boolean
     */
    public static function canViewPublicDocuments($context, $owner, $user_id) {
        if ( $context instanceof Warecorp_Group_Simple ) {
            return parent::canViewPublicDocuments($context, $owner, $user_id) || self::isOwnerEIA($context, $user_id);
        }
        return parent::canViewPublicDocuments($context, $owner, $user_id);
    }
}
