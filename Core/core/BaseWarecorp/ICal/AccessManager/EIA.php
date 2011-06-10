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

class BaseWarecorp_ICal_AccessManager_EIA extends Warecorp_ICal_AccessManager
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
        if ( $context instanceof Warecorp_User ) return false;
        if ( !defined("IMPLEMENTATION_FAMILY_GROUP_UID") || IMPLEMENTATION_FAMILY_GROUP_UID === '' ) return false;
        
        if ( NULL === self::$eiFamily ) self::$eiFamily = Warecorp_Group_Factory::loadByGroupUID(IMPLEMENTATION_FAMILY_GROUP_UID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        if ( !self::$eiFamily || !(self::$eiFamily instanceof Warecorp_Group_Family) ) return false;

        if ( !self::$eiFamily->getGroups()->isGroupInFamily($context) ) return false;
        if ( self::$eiFamily->getMembers()->isHost($user) || self::$eiFamily->getMembers()->isCohost($user) ) return true;
        return FALSE;
    }
    /**
     * Return instance of Access Manager
     * @return Warecorp_ICal_AccessManager
     */
    static public function getInstance()
    {
        if ( !self::$instance ) {
            self::$instance = new Warecorp_ICal_AccessManager_EIA();
        }
        return self::$instance;
    }
    /**
    * CHECKED
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    */
    public static function canAnonymousViewEvents($objContext)
    {
        if ( $objContext instanceof Warecorp_User ) return false;
        elseif ( $objContext instanceof Warecorp_Group_Base ) return true;
        else throw new Warecorp_ICal_Exception('Incorrect Content Object');

        return false;
    }

    /**
    * CHECKED
    * @param Warecorp_ICal_Event $objEvent
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    */
    public static function canAnonymousViewEvent(Warecorp_ICal_Event $objEvent, $objContext)
    {
        if ( $objContext instanceof Warecorp_User ) {
            /* "Allow anyone to attend this event" has been checked */
            if ( $objEvent->getInvite()->getIsAnybodyJoin() ) return true;
            
            return false;
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            return true;
        } else throw new Warecorp_ICal_Exception('Incorrect Content Object');

        return false;
    }
    
    /**
    * @param Warecorp_ICal_Event $objEvent
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    * @param string $eventViewAccessCode
    */    
    public static function canViewEvent($objEvent, $objContext, $objChallenger, $eventViewAccessCode = null) {
        if ( $objContext instanceof Warecorp_Group_Simple ) {
            return self::isOwnerEIA($objContext, $objChallenger) || parent::canViewEvent($objEvent, $objContext, $objChallenger, $eventViewAccessCode);
        }
        return parent::canViewEvent($objEvent, $objContext, $objChallenger, $eventViewAccessCode);
    }

    public static function canViewEvents($objContext, $objChallenger) {
        if ( $objContext instanceof Warecorp_Group_Simple ) {
            return parent::canViewEvents($objContext, $objChallenger) || self::isOwnerEIA($objContext, $objChallenger);
        }
        return parent::canViewEvents($objContext, $objChallenger);
    }

    public static function canViewPrivateEvents($objContext, $objChallenger) {
        if ( $objContext instanceof Warecorp_Group_Simple ) {
            return parent::canViewPrivateEvents($objContext, $objChallenger) || self::isOwnerEIA($objContext, $objChallenger);
        }
        return parent::canViewPrivateEvents($objContext, $objChallenger);
    }

    public static function canViewPublicEvents($objContext, $objChallenger) {
        if ( $objContext instanceof Warecorp_Group_Simple ) {
            return parent::canViewPublicEvents($objContext, $objChallenger) || self::isOwnerEIA($objContext, $objChallenger);
        }
        return parent::canViewPublicEvents($objContext, $objChallenger);
    }

    public static function canViewSharedEvents($objContext, $objChallenger) {
        if ( $objContext instanceof Warecorp_Group_Simple ) {
            return parent::canViewSharedEvents($objContext, $objChallenger) || self::isOwnerEIA($objContext, $objChallenger);
        }
        return parent::canViewSharedEvents($objContext, $objChallenger);
    }
}
