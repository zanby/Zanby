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
 * Zanby group class.
 * @package Warecorp_Group_Location_Member
 * @copyright Copyright (c) 2007
 * @author Artem Sukharev
 */
abstract class BaseWarecorp_Group_Location_Member_AbstractList extends Warecorp_Abstract_List 
{
    /**
     * object of group
     */
    protected $_groupId;
    
    /**
     * ids of users for include
     */
    protected $_userIdsIn;
    
    /**
     * ids of users for exclude
     */
    protected $_userIdsOut;
    
    /**
     * members status for select
     */
    protected $_membersStatus;

    /**
     * members role for select
     */
    protected $_membersRole;
       
    /**
     * ids of countries for include
     */
    protected $_countryIdsIn;

    /**
     * ids of countries for exclude
     */
    protected $_countryIdsOut;
    
    /**
     * ids of states for include
     */
    protected $_stateIdsIn;

    /**
     * ids of states for exclude
     */
    protected $_stateIdsOut;
    
    /**
     * ids of states for include
     */
    protected $_cityIdsIn;

    /**
     * ids of states for exclude
     */
    protected $_cityIdsOut;
    
    /**
     * set group id for locations object
     * @param int $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setGroupId($newVal)
    {
        $this->_groupId = $newVal;
        return $this;
    }
    
    /**
     * return group id for locations object
     * @return int
     * @throws Zend_Exception
     * @author Artem Sukharev
     */
    public function getGroupId()
    {
        if ( $this->_groupId === null ) throw new Zend_Exception('Group Id not set');
        return $this->_groupId;
    }
    
    /**
     * set member status for locations object
     * @param array|string|string_delimiter_by_; $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setMembersStatus($newVal)
    {
        if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_MemberStatus::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect member status');
                }
            }
        } elseif ( strpos($newVal, ';') ) {
            $newVal = explode(';', $newVal);
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_MemberStatus::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect member status');
                }
            }
        } elseif ( $newVal == Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_BOTH ) {
            $newVal = array(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED, Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_PENDING);
        } else {
            if ( !Warecorp_Group_Enum_MemberStatus::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect member status');
            }
            $newVal = array($newVal);
        }
        foreach ($newVal as &$_value) {
            $_value = Warecorp_Group_Enum_MemberStatus::translate($_value);
        }
        $this->_membersStatus = $newVal;
        return $this;
    }
    
    /**
     * return member status for locations object
     * @return array
     * @author Artem Sukharev
     */
    public function getMembersStatus()
    {
        if ( $this->_membersStatus === null ) $this->setMembersStatus(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED);
        return $this->_membersStatus;
    }
    
    /**
     * set member role for locations object
     * @param array|string|string_delimiter_by_; $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setMembersRole($newVal)
    {
        if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_MemberRole::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect member role');
                }
            }
        } elseif ( strpos($newVal, ';') ) {
            $newVal = explode(';', $newVal);
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_MemberRole::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect member role');
                }
            }
        } else {
            if ( !Warecorp_Group_Enum_MemberRole::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect member role');
            }
            $newVal = array($newVal);
        }
        $this->_membersRole = $newVal;
        return $this;
    }
    
    /**
     * return member role for locations object
     * @return array
     * @author Artem Sukharev
     */
    public function getMembersRole()
    {
        if ( $this->_membersRole === null ) $this->_membersRole = array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST);
        return $this->_membersRole;
    }
    
    /**
     * set ids of users from include
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setUserIdsIn($newVal)
    {
    	if ( !is_array($newVal) ) $newVal = array($newVal);
    	$this->_userIdsIn = $newVal;
    	return $this;
    }
    
    /**
     * get ids of users from include
     * @return array
     * @author Artem Sukharev
     */
    public function getUserIdsIn()
    {
    	return $this->_userIdsIn;
    }
    
    /**
     * set ids of users from exclude
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setUserIdsOut($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_userIdsOut = $newVal;
        return $this;
    }
    
    /**
     * get ids of users from exclude
     * @return array
     * @author Artem Sukharev
     */
    public function getUserIdsOut()
    {
        return $this->_userIdsOut;
    }

    /**
     * set ids of countries from include
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setCountryIdsIn($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_countryIdsIn = $newVal;
        return $this;
    }
    
    /**
     * get ids of countries from include
     * used in methods 
     * @return array
     * @author Artem Sukharev
     */
    public function getCountryIdsIn()
    {
        return $this->_countryIdsIn;
    }
    
    /**
     * set ids of countries from exclude
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setCountryIdsOut($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_countryIdsOut = $newVal;
        return $this;
    }
    
    /**
     * get ids of countries from exclude
     * @return array
     * @author Artem Sukharev
     */
    public function getCountryIdsOut()
    {
        return $this->_countryIdsOut;
    }
    
    /**
     * set ids of states from include
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setStateIdsIn($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_stateIdsIn = $newVal;
        return $this;
    }
    
    /**
     * get ids of states from include
     * @return array
     * @author Artem Sukharev
     */
    public function getStateIdsIn()
    {
        return $this->_stateIdsIn;
    }
    
    /**
     * set ids of users from exclude
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setStateIdsOut($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_stateIdsOut = $newVal;
        return $this;
    }
    
    /**
     * get ids of users from exclude
     * @return array
     * @author Artem Sukharev
     */
    public function getStateIdsOut()
    {
        return $this->_stateIdsOut;
    }
    
    /**
     * set ids of cities from include
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setCityIdsIn($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_cityIdsIn = $newVal;
        return $this;
    }
    
    /**
     * get ids of cities from include
     * @return array
     * @author Artem Sukharev
     */
    public function getCityIdsIn()
    {
        return $this->_cityIdsIn;
    }
    
    /**
     * set ids of cities from exclude
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setCityIdsOut($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_cityIdsOut = $newVal;
        return $this;
    }
    
    /**
     * get ids of cities from exclude
     * @return array
     * @author Artem Sukharev
     */
    public function getCityIdsOut()
    {
        return $this->_cityIdsOut;
    }
    
    /**
     * Constructor
     * @param int groupId
     * @return void
     * @author Artem Sukharev
     */
    public function  __construct($groupId)
    {
        parent::__construct();
        $this->setGroupId($groupId);
    }
    
    /**
     *  return list of all items
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getList()
    {
    	throw new Zend_Exception('You can not use this method directly.');
    }
    
    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev
     */
    public function getCount()
    {
    	throw new Zend_Exception('You can not use this method directly.');
    }
    
    /**
     * Returns array of countries of members for group
     * @return array of Warecorp_Location_Country
     * @author Artem Sukharev
     */
    abstract public function getCountriesList();
    
    /**
     * Returns number of countries of members for group
     * @return int
     * @author Artem Sukharev
     */
    abstract public function getCountriesCount();
    
    /**
     * Returns array of states of members for group
     * @return array of Warecorp_Location_State
     * @author Artem Sukharev
     */
    abstract public function getStatesList();
    
    /**
     * Returns number of states of members for group
     * @return int
     * @author Artem Sukharev
     */
    abstract public function getStatesCount();
    
    /**
     * Returns array of cities of members for group
     * @return array of Warecorp_Location_City
     * @author Artem Sukharev
     */
    abstract public function getCitiesList();
    
    /**
     * Returns number of cities of members for group
     * @return int
     * @author Artem Sukharev
     */
    abstract public function getCitiesCount();
    
}
