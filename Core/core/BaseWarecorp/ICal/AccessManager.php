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

class BaseWarecorp_ICal_AccessManager
{
	static protected $instance = false;
	
    /**
     * Private constructor
     */
    //protected function __construct(){}

    /**
     * Return instance of Access Manager
     * @return Warecorp_ICal_AccessManager
     */
    static public function getInstance($className = null){
        if ( !self::$instance ) {
            if ( null !== $className ) {
               self::$instance = new $className;
            } else {
               self::$instance = new Warecorp_ICal_AccessManager();
            }
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
        elseif ( $objContext instanceof Warecorp_Group_Base ) return false;
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
            /* "Allow anyone to attend this event" has been checked */
            if ( $objEvent->getInvite()->getIsAnybodyJoin() ) return true;
                
            return false;
        } else throw new Warecorp_ICal_Exception('Incorrect Content Object');

        return false;
    }
        
    /**
    * CHECKED
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    */
    public static function canViewEvents($objContext, Warecorp_User $objChallenger)
    {
        if ( $objContext instanceof Warecorp_User ) {
            if ( $objContext->getId() == $objChallenger->getId() ) return true;
            return Warecorp_User_AccessManager::getInstance()->canViewEvents($objContext, $objChallenger);
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            switch ( $objContext->getGroupType() ) {
                case 'simple' :
                    /* if group is private and user isn't member of this group - deny */
                    if ( $objContext->isPrivate() && !$objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return false;
                    return true;
                    break;
                case 'family' :
                    return true;
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
            return false;
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }
        return false;
    }

    /**
    * CHECKED
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    */
    public static function canViewPublicEvents($objContext, Warecorp_User $objChallenger)
    {
        if ( $objContext instanceof Warecorp_User ) {
            return true;
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            switch ( $objContext->getGroupType() ) {
                case 'simple'       :
                    if ( !$objContext->isPrivate() ) return true;
                    if ( $objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return true;
                    return false;
                    break;
                case 'family'       :
                    return true;
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
            return false;
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }
        return false;
    }

    /**
    * CHECKED
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    */
    public static function canViewPrivateEvents($objContext, Warecorp_User $objChallenger)
    {
        if ( $objContext instanceof Warecorp_User ) {
            if ( $objContext->getId() == $objChallenger->getId() ) return true;
            return false;
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            switch ( $objContext->getGroupType() ) {
                case 'simple'       :
                    if ( $objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return true;
                    return false;
                    break;
                case 'family'       :
                    if ( $objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return true;
                    return false;
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
            return false;
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }

        return false;
    }

    /**
    * CHECKED
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    */
    public static function canViewSharedEvents($objContext, Warecorp_User $objChallenger)
    {
        if ( $objContext instanceof Warecorp_User ) {
            if ( $objContext->getId() == $objChallenger->getId() ) return true;
            return false;
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            switch ( $objContext->getGroupType() ) {
                case 'simple'       :
                    if ( $objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return true;
                    return false;
                    break;
                case 'family'       :
                    if ( $objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return true;
                    return false;
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
            return false;
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }

        return false;
    }

    /**
    * @param Warecorp_ICal_Event $objEvent
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    * @param string $eventViewAccessCode
    */
    public static function canViewEvent(Warecorp_ICal_Event $objEvent, $objContext, Warecorp_User $objChallenger, $eventViewAccessCode = null)
    {
        if ( $objContext instanceof Warecorp_User ) {
            /* if unregistered user enter to event page by link from mail. ID = null Email != 0  (access to event by code) */             
            if ( $objChallenger->getId() === null &&  ($eventViewAccessCode || $objChallenger->getEmail() !== null) ) return true;

            /* "Allow anyone to attend this event" has been checked */
            if ( $objEvent->getInvite()->getIsAnybodyJoin() ) return true;
            
            if ( $objContext->getId() == $objChallenger->getId() ) return true;
            if ( $objEvent->getCreatorId() == $objChallenger->getId() ) return true;
            if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER && $objEvent->getOwnerId() == $objChallenger->getId() ) return true;
            if ($objEvent->getSharing()->isShared($objChallenger)) return true;
            if ($objEvent->getAttendee()->findAttendee($objChallenger)) return true;
            if ( $objEvent->getPrivacy() ) return false;
            return Warecorp_User_AccessManager::getInstance()->canViewEvents($objContext, $objChallenger);
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {

            /* if unregistered user enter to event page by link from mail. ID = null Email != 0  (access to event by code) */
            if ( $objChallenger->getId() === null &&  ($eventViewAccessCode || $objChallenger->getEmail() !== null) ) return true;

            /* "Allow anyone to attend this event" has been checked */
            if ( $objEvent->getInvite()->getIsAnybodyJoin() ) return true;
            
            switch ( $objContext->getGroupType() ) {
                case 'simple'       :
                    if ( $objContext->isPrivate() && !$objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return false;
                    
                    return true;
                    break;
                case 'family'       :
                    if (null !== ($objAttendee = $objEvent->getAttendee()
                                 ->setDateFilter($objEvent->getDtstart()->toString('yyyy-MM-ddTHHmmss'))
                                 ->findAttendee($objChallenger)) &&
                        in_array($objAttendee->getAnswer(), array('YES', 'MAYBE'))) return true;

                    if ( $objEvent->getPrivacy() ) {
                        return self::canViewPrivateEvents($objContext, $objChallenger);
                    }
                    return true;
                    break;
                case 'committee'    :
                    if ( $objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return true; // If Committee member - can
                    switch ( $objContext->getCanCreate() ) {
                        case 1  :    //  Group Members can create, view and RSVP
                            $parentGroup = $objContext->getParentGroup();
                            if ( $parentGroup->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return true;
                            else return false;
                            break;
                        case 2  :    //  Group Members can view but not create and RSVP
                            $parentGroup = $objContext->getParentGroup();
                            if ( $parentGroup->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return true;
                            else return false;
                            break;
                        case 3  :    //  Only Committee members can create, view and RSVP
                            if ( $objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return true;
                            else return false;
                            break;
                        default : return false;
                    }
                default : throw new Zend_Exception("Incorrect Group Type");
            }
            return false;
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }
        return false;
    }
	
    /**
    * @param Warecorp_ICal_Event $objEvent
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
	* @author Artem Sukharev
	* @note DON'T REMOVE IT
    */
    public static function ___canViewEvent(Warecorp_ICal_Event $objEvent, $objContext, Warecorp_User $objChallenger)
    {
        if ( $objContext instanceof Warecorp_User ) {            
            /* Anonymous User try to view event */
            if ( $objChallenger->getId() === null ) {
                /* if unregistered user enter to event page by link from mail. ID = null Email != 0  (access to event by code) */
                if ( $objChallenger->getEmail() !== null) return true; 
                /* "Allow anyone to attend this event" has been checked */
                if ( $objEvent->getInvite()->getIsAnybodyJoin() ) return true;  

                return false;
            } 
            /* Logged in User try to view event */
            else {
                /* user own account */
                if ( $objContext->getId() == $objChallenger->getId() ) return true;
                /* user is creator of event */
                if ( $objEvent->getCreatorId() == $objChallenger->getId() ) return true;
                /* user is owner of event */
                if ( $objEvent->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER && $objEvent->getOwnerId() == $objChallenger->getId() ) return true;
                /* event is shared to user */
                if ($objEvent->getSharing()->isShared($objChallenger)) return true;
                /* user is invited to event */
                if ($objEvent->getAttendee()->findAttendee($objChallenger)) return true;
                
                /* event is private, invited users can view private event only  */
                if ( $objEvent->getPrivacy() ) return false;
                
                /* event is public, return global access rule for events */
                return Warecorp_User_AccessManager::getInstance()->canViewEvents($objContext, $objChallenger);
            }
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            /* Anonymous User try to view event */
            if ( $objChallenger->getId() === null ) {
                /* if unregistered user enter to event page by link from mail. ID = null Email != 0  (access to event by code) */
                if ( $objChallenger->getEmail() !== null) return true; 
                /* "Allow anyone to attend this event" has been checked */
                if ( $objEvent->getInvite()->getIsAnybodyJoin() ) return true;  

                return false;                
            }
            /* Logged in User try to view event */
            else {
                switch ( $objContext->getGroupType() ) {
                    case 'simple' :
                        /* group is private and user isn't member of this group - deny */
                        if ( $objContext->isPrivate() && !$objContext->getMembers()->isMemberExistsAndApproved($objChallenger->getId()) ) return false;
                        
                        return true;
                        break;
                    case 'family'       :
                        if (null !== ($objAttendee = $objEvent->getAttendee()
                                     ->setDateFilter($objEvent->getDtstart()->toString('yyyy-MM-ddTHHmmss'))
                                     ->findAttendee($objChallenger)) &&
                            in_array($objAttendee->getAnswer(), array('YES', 'MAYBE'))) return true;
                        /* event is private - check can user view private events */
                        if ( $objEvent->getPrivacy() ) return self::canViewPrivateEvents($objContext, $objChallenger);
                        
                        return true;
                        break;
                    default : throw new Zend_Exception("Incorrect Group Type");
                }
                
                return false;                
            }
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }
        return false;
    }

    /**
    * CHECKED
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    */
    public static function canCreateEvent($objContext, Warecorp_User $objChallenger)
    {
        if ( $objContext instanceof Warecorp_User ) {
            if ( null === $objChallenger->getId() ) return false;
            if ( $objContext->getId() != $objChallenger->getId() ) return false;
            return true;
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            if ( null === $objChallenger->getId() ) return false;
            switch ( $objContext->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                        return Warecorp_Group_AccessManager::canUseCalendar($objContext, $objChallenger);
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
            return false;
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }

        return false;
    }

    /**
    * CHECKED
    * @param Warecorp_ICal_Event $objEvent
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    */
    public static function canManageEvent(Warecorp_ICal_Event $objEvent, $objContext, Warecorp_User $objChallenger)
    {
    	if ( null === $objChallenger->getId() ) return false;
    	
        if ( $objContext instanceof Warecorp_User ) {
            if ( $objEvent->getOwnerType() != Warecorp_ICal_Enum_OwnerType::USER ) return false;
            if ( $objEvent->getOwnerId() != $objChallenger->getId() ) return false;
            
            return true;
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            if ( $objEvent->getOwnerType() != Warecorp_ICal_Enum_OwnerType::GROUP ) return false;
            if ( $objEvent->getOwnerId() != $objContext->getId() ) return false;
            if ( $objEvent->getCreatorId() == $objChallenger->getId() ) return true;

            switch ( $objContext->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                    if ( Warecorp_Group_AccessManager::isHostPrivileges($objContext, $objChallenger) ) return true;
                    return false;
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
            return false;
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }

        return false;
    }

    /**
    * @param Warecorp_ICal_Event $objEvent
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    */
    public static function canShareEvent(Warecorp_ICal_Event $objEvent, $objContext, Warecorp_User $objChallenger)
    {
        return self::canManageEvent($objEvent, $objContext, $objChallenger);
    }

    /**
    * @param Warecorp_ICal_Event $objEvent
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    */
    public static function isHostPrivileges($objContext, Warecorp_User $objChallenger)
    {
        if ( $objContext instanceof Warecorp_User ) {
            if ( $objContext->getId() == $objChallenger->getId() ) return true;
            return false;
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            if ( null === $objChallenger->getId() ) return false;

            switch ( $objContext->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                    return Warecorp_Group_AccessManager::isHostPrivileges($objContext, $objChallenger);
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
            return false;
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }

        return false;
    }

    static public function canShareEventToAllFamilyGroups(Warecorp_ICal_Event $event, Warecorp_Group_Family $family, Warecorp_User $user)
    {
        if (
            Warecorp_Group_AccessManager::canShareToFamiliesGroups($family, $user)  &&
            ($family->getMembers()->isHost($user) || $family->getMembers()->isCohost($user) || $event->getCreatorId() === $user->getId())
        ) {
            return true;
        }
        return false;
    }

    static public function canUnshareEventToAllFamilyGroups(Warecorp_ICal_Event $event, Warecorp_Group_Family $family, Warecorp_User $user)
    {
        if (
            Warecorp_Group_AccessManager::canUnshareToFamiliesGroups($family, $user)    &&
            ($family->getMembers()->isHost($user) || $family->getMembers()->isCohost($user) || $event->getCreatorId() === $user->getId())
        ) {
            return true;
        }
        return false;
    }

}
