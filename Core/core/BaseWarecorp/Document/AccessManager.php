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


class BaseWarecorp_Document_AccessManager
{
    static protected $instance = false;

    /**
     * Private constructor
     */
    //protected function __construct(){}
    
    /**
     * Return instance of Access Manager
     * @return Warecorp_Document_AccessManager
     */
    static public function getInstance($className = null)
    {
        if ( !self::$instance ) {
        	if ( null !== $className ) {
        		self::$instance = new $className;
        	} else {
                self::$instance = new Warecorp_Document_AccessManager();
        	}
        }
        return self::$instance;
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $group
     * @return unknown
     */
    protected function _checkGroup($group)
    {
        if (!($group instanceof Warecorp_Group_Base)) $group = Warecorp_Group_Factory::loadById($group);
        return $group;
    }
    /**
     * check context
     * @param mixed $context
     * @return void
     * @throws Zend_Exception
     */
    protected function _checkContext($context)
    {
        if ( !($context instanceof Warecorp_User) && !($context instanceof Warecorp_Group_Base) ) {
           throw new Zend_Exception('Incorrect context object');
        }
    }
    
    /**
     * check user object, convert it to Warecorp_User if need
     * @param int|Warecorp_User - current user
     */
    protected function _checkUser(&$user)
    {
        if ( !($user instanceof Warecorp_User) ) {
           $user = new Warecorp_User('id', $user);
        }
    }
    
    protected function _checkDocument(&$document)
    {
        if ( !($document instanceof Warecorp_Document_Item  ) ) {
            $document = new Warecorp_Document_Item($document);
        }
    }
    
    
     /**
     * Default AssessManager for Documents for anaonmouse user for all implementations.
     * By default - zanby logic.  
     * @return boolean
     */
    
    public static function canAnonymousViewDocuments($objContext)
    {
        return false;
    }    
    
    
    /**
     * ДОСТУП НА УРОВНЕ ВЛАДЕЛЬЦА
     */

    /**
     * проверяет, может ли пользователь видеть документы и папку (т.е. контент документов в общем)
     * данного владельца
     * @param obj $owner User || Warecorp_Group_...
     * @param int $userId
     * @return boolean
     */
     
    static public function canViewOwnerDocuments($context, $owner, $user_id)
    {
        self::_checkContext($context);        
        if ( $context->getId() == null || $owner->getId() == null) return false;
        
        if ($user_id == null) {
        	$AccessManager = Warecorp_Document_AccessManager_Factory::create();
            return $AccessManager->canAnonymousViewDocuments($context);
        }    
        
        if ($context instanceof Warecorp_User ) {
            return $owner instanceof Warecorp_User && $context->getId() == $owner->getId() && Warecorp_User_AccessManager::getInstance()->canViewDocuments($owner, $user_id);
        } else { // $context is group
            if ($owner instanceof Warecorp_User) {
                return false;
            } else { // $owner is group
                if ($owner->getId() == $context->getId()) {
                    if ($context->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE) {
                        return !$owner->isPrivate() || $owner->getMembers()->isMemberExistsAndApproved($user_id);
                    } else { //FAMILY
                        return true;
                    }
                } else {
                    if ($context->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) {
                        return $context->getGroups()->isGroupInFamily($owner->getId());
                    } else {
                        return false;
                    }
                }
            }
        }
        return false;
    }
    /**
     * проверяет, может ли пользователь видеть публичные документы владельца
     * в конкретном контексе, контекст может быть либо группой, либо пользователем
     * @param obj $context User || Warecorp_Group_...
     * @param obj $owner User || Warecorp_Group_...
     * @param int $user_id
     * @return boolean
     */
    static public function canViewPublicDocuments($context, $owner, $user_id)
    {
        return self::canViewOwnerDocuments($context, $owner, $user_id);
    }
    /**
     * проверяет, может ли пользователь видеть приватные документы владельца
     * в конкретном контексе, контекст может быть либо группой, либо пользователем
     * @param obj $context User || Warecorp_Group_...
     * @param obj $owner User || Warecorp_Group_...
     * @param int $user_id
     * @return boolean
     */
    static public function canViewPrivateDocuments($context, $owner, $user_id)
    {

        self::_checkContext($context);
        if ( $context->getId() == null || $owner->getId() == null || $user_id == null ) return false;
        
        if ($context instanceof Warecorp_User ) {
            return $owner instanceof Warecorp_User && $context->getId() == $owner->getId() && $owner->getId() == $user_id;
        } else { // $context is group
            if ($owner instanceof Warecorp_User) {
                return false;
            } else { // $owner is group
                return $owner->getMembers()->isMemberExistsAndApproved($user_id);
            }
        }
        return false;
    }
    /**
     * проверяет, может ли пользователь управлять документами
     * (создавать папки, удалять папки, удалять документы и т.д.)
     * @param obj $owner User || Warecorp_Group_...
     * @param int $user_id
     * @return boolean
     */
    static public function canManageOwnerDocuments($context, $owner, $user_id)
    {
        self::_checkContext($context);
        if ( $context->getId() == null || $owner->getId() == null || $user_id == null ) return false;

        if ($context instanceof Warecorp_User ) {
            return (boolean) ($owner instanceof Warecorp_User && $context->getId() == $owner->getId() && self::canManageUserDocuments($owner, $user_id));
        } else { // $context is group
            if ($owner instanceof Warecorp_User) {
                return false;
            } else { // $owner is group
                return self::canManageGroupDocuments($owner, $user_id);
            }
        }
        return false;
    }

    /**
     * проверяет, может ли пользователь создавать документамы
     * @param obj $owner User || Warecorp_Group_...
     * @param int $user_id
     * @return boolean
     */
    static public function canCreateOwnerDocuments($context, $owner, $user_id)
    {
        self::_checkContext($context);
        if ( $context->getId() == null || $owner->getId() == null || $user_id == null ) return false;

        if ($context instanceof Warecorp_User ) {
            return (boolean) ($owner instanceof Warecorp_User && $context->getId() == $owner->getId() && self::canManageUserDocuments($owner, $user_id));
        } else { // $context is group
            if ($owner instanceof Warecorp_User) {
                return false;
            } else { // $owner is group
                return Warecorp_Group_AccessManager::canUseDocuments($owner, $user_id);
            }
        }
        return false;
    }
    static public function canCreateFolders($context, $owner, $user_id)
    {
        return self::canManageOwnerDocuments($context, $owner, $user_id);
    }
    /**
     * проверяет, может ли пользователь редактировать папки
     * @param obj $context User || Warecorp_Group_...
     * @param obj $owner User || Warecorp_Group_...
     * @param int $user_id
     * @return boolean
     */
    static public function canEditFolders($context, $owner, $user_id)
    {
        return self::canManageOwnerDocuments($context, $owner, $user_id);
    }
    /**
     * @param Warecorp_Document_Item $doc
     * @param Warecorp_Group_Base|Warecorp_User $context
     * @param Warecorp_User $user
     * @return boolean
     */
    static public function canEditDocument(Warecorp_Document_Item $doc, $context, Warecorp_User $user)
    {
        self::_checkContext($context);
        if ( $context instanceof Warecorp_User ) {
            return $doc->getCreatorId() === $user->getId();
        } else if ( $context instanceof Warecorp_Group_Base ) {
            if ( $doc->getCreatorId() === $user->getId() ) {
                return true;
            } else if ( $doc->getOwner() instanceof Warecorp_Group_Base ) {
                if ( $doc->getOwnerId() === $context->getId() ) {
                   if ( $context->getMembers()->isHost($user) || $context->getMembers()->isCohost($user) ) {
                       return true;
                   }
                }
            }
        }
        return false;
    }
    /**
     * проверяет, может ли пользователь удалять папки
     * @param obj $context User || Warecorp_Group_...
     * @param obj $owner User || Warecorp_Group_...
     * @param int $user_id
     * @return boolean
     */
    static public function canDeleteFolders($context, $owner, $user_id)
    {
        return self::canManageOwnerDocuments($context, $owner, $user_id);
    }
    /**
     * проверяет, может ли пользователь перемещать папки
     * @param obj $context User || Warecorp_Group_...
     * @param obj $owner User || Warecorp_Group_...
     * @param int $user_id
     * @return boolean
     */
    static public function canMoveFolders($context, $owner, $user_id)
    {
        return self::canManageOwnerDocuments($context, $owner, $user_id);
    }



    /**
     * ДОСТУП НА УРОВНЕ ГРУППЫ
     */

    /**
     * проверяет, может ли пользователь управлять документами конкретной группы
     * @param obj $group
     * @param int $user_id
     * @return boolean
     */
    static public function canManageGroupDocuments($group, $user_id)
    {
        switch ( $group->getGroupType() ) {
            case Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE :
            case Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY :
                return Warecorp_Group_AccessManager::isHostPrivileges($group, $user_id);
                break;
            default : throw new Zend_Exception("Incorrect Group Type");
        }
    }
    /**
     * проверяет, может ли пользователь шарить файлы в конкретной группе
     * @param obj $group
     * @param int $user_id
     * @return boolean
     */
    static public function canShareGroupFiles($group, $user_id)
    {
        //var_dump(self::canManageGroupDocuments($group, $user_id));
        return self::canManageGroupDocuments($group, $user_id);
    }


    /**
     * ДОСТУП НА УРОВНЕ ПОЛЬЗОВАТЕЛЯ
     */

    /**
     * проверяет, может ли пользователь управлять документами конкретного пользователя
     * @param obj $user - чьими документами надо управлять
     * @param int $user_id - текущий пользователь
     * @return boolean
     */
    static public function canManageUserDocuments($user, $user_id)
    {
    	return $user->getId() == $user_id; 
    }

    /**
     * canViewDocument
     *
     * @param Warecorp_Document_Item $document
     * @param Warecorp_User or Warecorp_Group $context
     * @param Warecorp_User | int $user
     * @author Vitaly Targonsky
     * @todo verify shared docs
     */
    static public function canViewDocument($document, $context, $user) 
    {
    	self::_checkDocument($document);
        self::_checkContext($context);
        self::_checkUser($user);
        $owner = $document->getOwner();
        self::_checkContext($owner);
        
        if ( $owner->getId() === null || $document->getId() === null ) return false;
        
        if ($context instanceof Warecorp_User ) {
            if ($owner instanceof Warecorp_User) {
                if ($document->getPrivate()) {
                    return self::canViewPrivateDocuments($context, $owner, $user->getId());
                } else {
                    return self::canViewPublicDocuments($context, $owner, $user->getId());
                }
            } else { // $owner is group
                return Warecorp_Document_Item::isDocumentShared($document->getId(), 'user', $context->getId()) && self::canViewPrivateDocuments($context, $owner, $user->getId());
            }
        } else { // context is group
            if ($owner instanceof Warecorp_User) {
                return Warecorp_Document_Item::isDocumentShared($document->getId(), 'group', $context->getId()) && self::canViewPrivateDocuments($context, $context, $user->getId());
            } else { // $owner is group
                if ($document->getPrivate()) {
                    return self::canViewPrivateDocuments($context, $owner, $user->getId());
                } else {
                    return self::canViewPublicDocuments($context, $owner, $user->getId());
                }
            }
        }
        return false;
    }

    /**
     * Can user unshare document into target group, or user
     * @param Warecorp_Document_Item $document
     * @param $target Warecorp_User | Warecorp_Group_...
     * @param int user_id
     * @return bool
     */
    static public function canShareDocument($document, $target, $user_id)
    {
        self::_checkDocument($document);
        self::_checkContext($target);
        $owner = $document->getOwner();
        if ( $owner->getId() == null || $user_id == null || $document->getId() == null ) return false;
        if ($target instanceof Warecorp_User) {
            if ($owner instanceof Warecorp_User) {
                return Warecorp_User_Friend_Item::isUserFriend($user_id, $target->getId()) && self::canManageUserDocuments($owner, $user_id);
            } else {
                return Warecorp_User_Friend_Item::isUserFriend($user_id, $target->getId()) && self::canManageGroupDocuments($owner, $user_id);
            }
            
//            print " !{$user_id}+{$target->getId()}+{$owner->getId()}! ";
//            var_dump(Warecorp_User_Friend_Item::isUserFriend($user_id, $target->getId()));
//            var_dump(self::canManageOwnerDocuments($owner, $owner, $user_id));
            
        } else { // $target is group
            if ($owner instanceof Warecorp_User) {
                return self::canManageGroupDocuments($target, $user_id) && self::canManageUserDocuments($owner, $user_id);
            } else {
                return self::canManageGroupDocuments($target, $user_id) && self::canManageGroupDocuments($owner, $user_id);
            }
        }
        return false;
    }
    /**
     * Can user unshare document
     *
     * @param unknown_type $document
     */
    static public function canUnshareDocument($document, $context, $user_id)
    {
        self::_checkDocument($document);
        self::_checkContext($context);
        $owner = $document->getOwner();
        if ( $owner->getId() == null || $user_id == null || $document->getId() == null ) return false;
        
        if ($owner instanceof Warecorp_User && $owner->getId() == $user_id) return true;
        if ( $document->getCreator()->getId() == $user_id ) return true;
        
        return self::canManageOwnerDocuments($context, $owner, $user_id) || self::canManageOwnerDocuments($context, $context, $user_id);
    }

    static public function canShareDocumentToAllFamilyGroups(Warecorp_Document_Item $document, Warecorp_Group_Family $family, Warecorp_User $user)
    {
        if (
            Warecorp_Group_AccessManager::canShareToFamiliesGroups($family, $user)                                  &&
           !Warecorp_Share_Entity::isShareExists( $family->getId(), $document->getId(), $document->EntityTypeId )     &&
            ($family->getMembers()->isHost($user) || $family->getMembers()->isCohost($user) || $document->getCreatorId() === $user->getId()  || Warecorp_Group_AccessManager::isGroupHost())
        ) {
            return true;
        }
        return false;
    }

    static public function canUnshareDocumentToAllFamilyGroups(Warecorp_Document_Item $document, Warecorp_Group_Family $family, Warecorp_User $user)
    {
        if (
            Warecorp_Group_AccessManager::canUnshareToFamiliesGroups($family, $user)                                &&
            Warecorp_Share_Entity::isShareExists( $family->getId(), $document->getId(), $document->EntityTypeId )     &&
            ($family->getMembers()->isHost($user) || $family->getMembers()->isCohost($user) || $document->getCreatorId() === $user->getId()  || Warecorp_Group_AccessManager::isGroupHost())
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check permissions view documents shared from Family to all children groups
     */
    static public function canViewFamilySharedDocuments(Warecorp_Group_Simple $group, Warecorp_Group_Family $family, Warecorp_User $user) {
        if ( $group->getMembers()->isMemberExistsAndApproved($user->getId()) ) {
            $families = $group->getFamilyGroups()->returnAsAssoc(true)->getList();
            if ( !empty($families) && in_array($family->getId(), array_keys($families)) ) {
                return true;
            }
        }
        return false;
    }

}
