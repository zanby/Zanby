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


class BaseWarecorp_List_AccessManager
{
    static protected $instance  = false;
    /**
     * Private constructor
     */
    //protected function __construct(){}
    
    /**
     * Return instance of Access Manager
     * @return Warecorp_List_AccessManager
     */
    static public function getInstance($className = null) 
    {      
        if ( !self::$instance ) {
            if ( null !== $className ) {
               self::$instance = new $className;
            } else {
               self::$instance = new Warecorp_List_AccessManager();
            }
        }
        return self::$instance;
    }
    
    /**
     * check context
     * @param mixed $context
     * @return void
     * @throws Zend_Exception
     */
    private function _checkContext($context) 
    {
        if ( !($context instanceof Warecorp_User) && !($context instanceof Warecorp_Group_Base) ) {
           throw new Zend_Exception('Incorrect context object');
        }
    }
    
    /**
     * check user object, convert it to Warecorp_User if need
     * @param int|Warecorp_User - current user
     */
    private function _checkUser(&$user)
    {
        if ( !($user instanceof Warecorp_User) ) {
           $user = new Warecorp_User('id', $user);
        }
    }
    
    /**
     * check list object, convert it to Warecorp_List_Item if need
     * @param int|Warecorp_List_Item
     */
    private function _checkList(&$list)
    {
    	if ( !($list instanceof Warecorp_List_Item ) ) {
    		$list = new Warecorp_List_Item($list);
    	}
    }
    /**
     * check record object, convert it to Warecorp_List_Record if need
     * @param int|Warecorp_List_Record
     */
    private function _checkRecord(&$record)
    {
        if ( !($record instanceof Warecorp_List_Record ) ) {
            $record = new Warecorp_List_Item($record);
        }
    }
    /**
     * check comment object, convert it to Warecorp_Data_Comment if need
     * @param int|Warecorp_Data_Comment
     */
    private function _checkComment(&$comment)
    {
        if ( !($comment instanceof Warecorp_Data_Comment ) ) {
            $comment = new Warecorp_Data_Comment($comment);
        }
    }
    
    /**
     * check comment object, convert it to Warecorp_Data_Comment if need
     * @param $list - Warecorp_List_Item
     * @return $event - Warecorp_ICal_Event | false
     */
    private function _isEventAttach($list)
    {
        if (isset($_SERVER['HTTP_REFERER']) && Zend_Uri::check($_SERVER['HTTP_REFERER'])) {
        	$uri = Zend_Uri::factory($_SERVER['HTTP_REFERER']);
            preg_match('/calendar\.event\.view\/id\/(\d+)\//i',$uri->getPath(), $match); 
            $eventId = isset($match[1]) ? $match[1] : 0;
            
            if (empty($eventId)) return false;
            
            $event = new Warecorp_ICal_Event($eventId);            
            return $event;
        }
    	return false;
    }
    
    
    //komarovski
    static public function canAddToMyLists()
    {
        return true;
    }
    static public function canPostComment()
    {
        return true;
    }
    static public function canAddListItem()
    {
        return true;
    }
    
    
    /**
     * check can user view owner's lists  
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canViewLists($context, $user)
    {
        self::_checkContext($context);
        self::_checkUser($user);

        //komarovski, fix only for group context and anonymous user
        if ( !$user->isAppMember() ) {
            if ('ESA' == IMPLEMENTATION_TYPE) {
                return false;
            //EIA
            } elseif ( $context instanceof Warecorp_Group_Family || ($context instanceof Warecorp_Group_Simple && !$context->getIsPrivate()) ) {
                // Bug #8628
                return true;
            }
        }
        //

    	if ( $context instanceof Warecorp_User ) {
            return Warecorp_User_AccessManager::getInstance()->canViewLists($context, $user);
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                    return ( !$context->isPrivate() || $context->getMembers()->isMemberExistsAndApproved($user->getId()) );
                    break;
                case 'family'       :
                	return true; // any member of zanby can view family lists
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;
    }
    /**
     * check can user create lists
     * 
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canCreateLists($context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);

        if ( $user->getId() === null ) return false; 
        
        if ( $context instanceof Warecorp_User ) {
            return ($context->getId() == $user->getId());
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
					return Warecorp_Group_AccessManager::canUseLists($context, $user);
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;
    }
    /**
     * check can user manage (edit, delete, unshare) owner's lists
     * 
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canManageLists($context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);

        if ( $user->getId() === null ) return false; 
        
        if ( $context instanceof Warecorp_User ) {
            return ($context->getId() == $user->getId());
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
					return Warecorp_Group_AccessManager::isHostPrivileges($context, $user);
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;
    }
    /**
     * check can user view public owner's lists
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canViewPublicLists($context, $user) 
    {
    	return self::canViewLists($context, $user);
    }
    /**
     * check can user view private owner's lists
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canViewPrivateLists($context, $user) 
    {
    	
        self::_checkContext($context);
        self::_checkUser($user);

        if ( $user->getId() === null ) return false; 
    	
        if ($context instanceof Warecorp_User) {
        	return ($context->getId() == $user->getId()); 
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                    return ( $context->getMembers()->isMemberExistsAndApproved($user->getId()) );
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;
    }

    /**
     * check can user view shared owner's lists
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canViewSharedLists($context, $user) 
    {
        return self::canViewPrivateLists($context, $user);
    }
    
    /**
     * check can user view list
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canViewList($list, $context, $user) 
    {
    	
    	self::_checkList($list);
    	    	
        self::_checkContext($context);
        self::_checkUser($user);
        $owner = $list->getOwner();
        self::_checkContext($owner);
                
        /**
        * @desc 
        * Дополнительный функционал для листов, приатаченых к событиям
        */
        if ($event = self::_isEventAttach($list)) {         
            return Warecorp_ICal_AccessManager::canViewEvent($event, $context, $user);
        }

        
        if ( $owner->getId() === null || $user->getId() === null || $list->getId() === null ) return false;
        
        if ($context instanceof Warecorp_User) {
        	if ($owner instanceof Warecorp_User) {
        		if ($context->getId() === $owner->getId()) {
        			if  ($list->getIsPrivate() && $owner->getId()!=$user->getId()) {
        			    return false;
        			} else {
        				return self::canViewLists($context, $user);
        			}
        		} else { // if list was shared to context:
        			return $list->isListShared($list->getId(), 'user', $context->getId()) && self::canViewPrivateLists($context, $user);
        		}
        	} else { // $owner - group  
        		return ( $list->isListShared($list->getId(), 'user', $context->getId()) && $context->getId() === $user->getId() );
        	}
        } else { // $context - group
            if ($owner instanceof Warecorp_User) { // list was shared to group ($context) from user ($owner)
            	return self::canViewPrivateLists($context, $user) && $list->isListShared($list->getId(), 'group', $context->getId());
            } else { // $owner - group
            	if ($owner->getId() === $context->getId()) {
                    if ($list->getIsPrivate()) {
                        return ( self::canViewPrivateLists($context, $user) || $list->isListShared($list->getId(), 'user', $user->getId()) ); 
                    } else {
                        return ( self::canViewPublicLists($context, $user) || $list->isListShared($list->getId(), 'user', $user->getId()) ); 
                    }
            	}else{ // list was shared to group ($context) from group ($owner)
                    if (self::canViewPrivateLists($context, $user) && $list->isListShared($list->getId(), 'group', $context->getId())) {
                    	return true;
                    }
                    return false;
            	}
            }
        }
        return false;
    }

    /**
     * check can user manage list (edit, delete)
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canManageList($list, $context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkList($list);

        if ( $user->getId() === null || $list->getId() === null ) return false;
        
        if ($context instanceof Warecorp_User) {
            return ( $list->getOwner() instanceof Warecorp_User && $list->getOwner()->getId() == $context->getId() && self::canManageLists($context, $user) );
        } else { // context Group
        	if ($list->getOwner() instanceof Warecorp_Group_Base) {
            	if ($list->getOwner()->getId() == $context->getId()) {
            		return self::canManageLists($context, $user) || $list->getCreatorId() == $user->getId();
            	}
        	}
        }
        return false;
    }
    
    /**
     * check can user share list
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canShareList($list, $context, $user) 
    {
    	if ($context instanceof Warecorp_User) {
	    	if ($list->getOwner()->getId() == $user->getId()) {
	    	   return self::canManageLists($context, $user);
	    	}
    	} else { // groups
    		if ($list->getOwner() instanceof Warecorp_Group_Base) {
				if ($list->getOwner()->getId() == $context->getId()) {
				    return self::canManageLists($context, $user) || $list->getCreatorId() == $user->getId();
	    		}
    		}
    	}
    	return false;
    }
    
    /**
     * check can user unshare list
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canUnshareList($list, $context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkList($list);
        $owner = $list->getOwner();
        if ($context instanceof Warecorp_Group_Base) {
            if ($owner instanceof Warecorp_Group_Base) {
// 				if ($context->getId() == $owner->getId()) return false;
                return self::canManageLists($context, $user) || $list->getCreatorId() == $user->getId();
            }
        }
        return self::canManageLists($context, $user) || self::canManageLists($owner, $user);
    }    

    /**
     * check can user manage(edit, delete) comment (when user view list)
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canManageComment($comment, $context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkComment($comment);
        $record = new Warecorp_List_Record($comment->entityId);
        $list = new Warecorp_List_Item($record->getListId());
        
        if ($user->getId() === null || $comment->id === null || $record->getId() === null || 
            $list->getId() === null || $record->EntityTypeId != $comment->entityTypeId || 
            $record->getId() != $comment->entityId){// || !(self::canViewList($list, $context, $user)) ) {
        	return false;
        }
        
        if ( $context instanceof Warecorp_User ) {
            return ( $user->getId() == $comment->userId || $context->getId() == $user->getId() );
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                    return ( $user->getId() === $comment->userId || Warecorp_Group_AccessManager::canUseLists($context, $user) );
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;        
    }


    /**
     * check can user delete, edit record (when user view list)
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canManageRecord($record, $context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkRecord($record);
        
        $list = new Warecorp_List_Item($record->getListId());
        
        if ($user->getId() === null || $list->getId() === null || !(self::canViewList($list, $context, $user)) ) {
            return false;
        }
        
        if ( $context instanceof Warecorp_User ) {
            return ( $user->getId() == $record->getCreatorId() || self::canManageList($list, $context, $user) );
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                    return ( $user->getId() === $record->getCreatorId() || self::canManageList($list, $context, $user) );
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;
    }

    /**
     * check can user append record (when user view list)
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canAppendRecord($list, $context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkList($list);
        
        if ($user->getId() === null || $list->getId() === null || !(self::canViewList($list, $context, $user)) ) {
            return false;
        }
        
        if ( $context instanceof Warecorp_User ) {
            return (bool)$list->getAdding();
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                    return (bool)$list->getAdding();
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;
    }

    /**
     * check can user rank record (when user view list)
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canRankRecord($record, $context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkRecord($record);
        
        $list = new Warecorp_List_Item($record->getListId());
        
        if ($user->getId() === null || $list->getId() === null || !(self::canViewList($list, $context, $user)) ) {
            return false;
        }
        
        if ( $context instanceof Warecorp_User ) {
            return (bool)$list->getRanking();
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                    return (bool)$list->getRanking();
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;
    }
    
    /**
     * check can user volunteer (when user view list)
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canVolunteer($record, $context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkRecord($record);
        
        $list = new Warecorp_List_Item($record->getListId());
        
        if ($user->getId() === null || $list->getId() === null || !(self::canViewList($list, $context, $user)) ) {
            return false;
        }
        
        if ( $context instanceof Warecorp_User ) {
            return ( !$record->isUserVolunteer() && ($record->getXmlFieldValue('limit')==0 || $record->getXmlFieldValue('limit')>$record->getVolunteersCount() ) );
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                    return ( !$record->isUserVolunteer() && ($record->getXmlFieldValue('limit')==0 || $record->getXmlFieldValue('limit')>$record->getVolunteersCount() ) );
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;
    }

    /**
     * check can user volunteer (when user view list)
     * @param obj $context User || Warecorp_Group_...
     * @param obj $user
     * @return boolean
     * @author Vitaly Targonsky
     */
    static public function canDeleteVolunteer($volunteer_id, $record, $context, $user) 
    {
        self::_checkContext($context);
        self::_checkUser($user);
        self::_checkRecord($record);
        
        $user_id = $record->getVolunteerUserId($volunteer_id); 
        $list = new Warecorp_List_Item($record->getListId());
        
        if ( $user->getId() === null || $list->getId() === null || !(self::canViewList($list, $context, $user)) ) {
            return false;
        }
        
        if ( $context instanceof Warecorp_User ) {
            return ( $context->getId() == $user->getId() || $user_id == $user->getId() );
        } else {
            switch ( $context->getGroupType() ) {
                case 'simple'       :
                case 'family'       :
                    return ( self::canManageLists($context, $user) || $user_id == $user->getId() );
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
        }
        return false;
    }

    static public function canShareListToAllFamilyGroups(Warecorp_List_Item $list, Warecorp_Group_Family $family, Warecorp_User $user)
    {
        if (
            Warecorp_Group_AccessManager::canShareToFamiliesGroups($family, $user)                          &&
           !Warecorp_Share_Entity::isShareExists( $family->getId(), $list->getId(), $list->EntityTypeId )   &&
           ($family->getMembers()->isHost($user) || $family->getMembers()->isCohost($user) || $list->getCreatorId() === $user->getId() || Warecorp_Group_AccessManager::isGroupHost()) 
        ) {
            return true;
        }
        return false;
    }

    static public function canUnshareListToAllFamilyGroups(Warecorp_List_Item $list, Warecorp_Group_Family $family, Warecorp_User $user)
    {
        if (
            Warecorp_Group_AccessManager::canUnshareToFamiliesGroups($family, $user)                        &&
            Warecorp_Share_Entity::isShareExists( $family->getId(), $list->getId(), $list->EntityTypeId )   &&
            ($family->getMembers()->isHost($user) || $family->getMembers()->isCohost($user) || $list->getCreatorId() === $user->getId() || Warecorp_Group_AccessManager::isGroupHost())
        ) {
            return true;
        }
        return false;
    }
}
