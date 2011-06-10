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
 * @package Warecorp_Share_List_Family
 * @author Michael pianko
 * @version 1.0
 */
class BaseWarecorp_Share_List_Family extends Warecorp_Abstract_List
{

//    private $_sharedList    = true;
    private $_user          = null;
    private $_entityId      = null;
    private $_entityType    = null;
    private $_context;
    
    public function setUser($objUser)
    {
        if ($objUser instanceof Warecorp_User){
            $this->_user = $objUser;
            return $this;
        }
        else{
            throw new Zend_Exception('User not set');
        }
    }
    
    public function getUser()
    {
        return $this->_user;
    }

    public function setContext( $context )
    {
        if ( !($context instanceof Warecorp_User || $context instanceof Warecorp_Group_Base) )
            throw new Zend_Exception('Unexpected Entity');
        $this->_context = $context;
        return $this;
    }
    
    public function setEntity($entityId, $entityType)
    {
        $this->_entityId    = $entityId;
        $this->_entityType  = $entityType;
        return $this;
    }
    
//    public function setListMode($shared = true)
//    {
//        $this->_sharedList = $shared;
//        return $this;
//    }
    
//    public function getListMode()
//    {
//        return $this->_sharedList;
//    }
    
    public function getList()
    {
//        if ($this->_user === null)
//            return array();
//
//        $groupFamiliesList = $this->_user->getGroups()
//                                         ->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY)
//                                         ->setMembersStatus(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED)
//                                         ->getList();
//        if ( empty($groupFamiliesList) )
//            return array();
//
//        $result = array();
//        foreach ($groupFamiliesList as $familyId => $family) {
//            if ( $this->getListMode() &&
//                (!Warecorp_Group_AccessManager::canShareToFamily( $family, $this->_user ) ||
//                  Warecorp_Share_Entity::isShareExists($family->getId(), $this->_entityId, $this->_entityType))
//            ) {
//                unset($groupFamiliesList[$familyId]);
//            }
//            elseif ( !$this->getListMode() &&
//                (!Warecorp_Group_AccessManager::canShareToFamily( $family, $this->_user ) ||
//                 !Warecorp_Share_Entity::isShareExists($family->getId(), $this->_entityId, $this->_entityType))
//            ) {
//                unset($groupFamiliesList[$familyId]);
//            }
//            if ( $this->isAsAssoc() ) {
//                $result[$family->getId()] = $family->getName();
//                unset($groupFamiliesList[$familyId]);
//            }
//            else{
//                $result[$family->getId()] = $family;
//                unset($groupFamiliesList[$familyId]);
//            }
//        }
//        return $result;
          throw new Zend_Exception('Method '.__METHOD__.' is depricated. Use getListSharedFamilies() or getListNotSharedFamilies() instead');
    }

    public function getListSharedFamilies()
    {
//        if ($this->_user === null)
//            return array();
        $groupFamiliesList = array();
        if ($this->_context === null)
            throw new Zend_Exception('You must set the context');

        if ( $this->_context instanceof Warecorp_User ) {
            $groupFamiliesList = $this->_context->getGroups()
                                      ->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY)
                                      ->setMembersStatus(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED)
                                      ->getList();
        }
        elseif ( $this->_context instanceof Warecorp_Group_Simple ) {
            $groupFamiliesList = $this->_context->getFamilyGroups()
                                      ->setStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED)
                                      ->getList();
        }
        elseif ( $this->_context instanceof Warecorp_Group_Family ) {
            $groupFamiliesList[$this->_context->getId()] = $this->_context;
        }
        else {
            return array();
        }

        if ( empty($groupFamiliesList) )
            return array();

        $result = array();
        foreach ( $groupFamiliesList as $familyId => $family ) {
            if ( Warecorp_Share_Entity::isShareExists($family->getId(), $this->_entityId, $this->_entityType) &&
                 Warecorp_Group_AccessManager::canUnshareToFamiliesGroups($family, $this->_user)
            ) {
                if ( $this->isAsAssoc() ) {
                    $result[$family->getId()] = $family->getName();
                }
                else {
                    $result[$family->getId()] = $family;
                }
            }
        }
        return $result;
    }

    public function getListNotSharedFamilies()
    {
//        if ($this->_user === null)
//            return array();
        $groupFamiliesList = array();
        if ($this->_context === null)
            throw new Zend_Exception('You must set the context');

        if ( $this->_context instanceof Warecorp_User ) {
            $groupFamiliesList = $this->_context->getGroups()
                                      ->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY)
                                      ->setMembersStatus(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED)
                                      ->getList();
        }
        elseif ( $this->_context instanceof Warecorp_Group_Simple ) {
            $groupFamiliesList = $this->_context->getFamilyGroups()
                                      ->setStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED)
                                      ->getList(); 
        }
        elseif ( $this->_context instanceof Warecorp_Group_Family ) {
            $groupFamiliesList[$this->_context->getId()] = $this->_context;
        }
        else {
            return array();
        }

        if ( empty($groupFamiliesList) )
            return array();

        $result = array();
        foreach ( $groupFamiliesList as $familyId => $family ) {
            if ( !Warecorp_Share_Entity::isShareExists($family->getId(), $this->_entityId, $this->_entityType) &&
                  Warecorp_Group_AccessManager::canShareToFamiliesGroups($family, $this->_user) ) {
                if ( $this->isAsAssoc() ) {
                    $result[$family->getId()] = $family->getName();
                }
                else{
                    $result[$family->getId()] = $family;
                }
            }
        }
        return $result;
    }
    
    public static function prepeareArrayKeys($familyList)
    {
        foreach ($familyList as $key => $value) {
            $familyList['family_'.$key] = "All groups in ".$value; 
            unset($familyList[$key]);
        }
        return $familyList;
    }

    public static function prepeareArrayKeysOnly($familyList)
    {
        foreach ($familyList as $key => $value) {
            $familyList['family_'.$key] = $value;
            unset($familyList[$key]);
        }
        return $familyList;
    }
    
    public function getCount() {
        return 0;
    }
   
}
