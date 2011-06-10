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
 * Class implement methods for LOCATIONS (City List, Country List, State List) of GROUPS any family group
 * @package Warecorp_Group_Family
 * @copyright Copyright (c) 2007
 * @author Artem Sukharev
 */
class BaseWarecorp_Group_Family_Group_LocationList extends Warecorp_Abstract_List
{
    /**
     * object of group
     */
    protected $_groupId;
    
    /**
     * group types for select
     */
    private $_types;
    
    /**
     * ids of group for include
     */
    protected $_groupIdsIn;
    
    /**
     * ids of group for exclude
     */
    protected $_groupIdsOut;
    
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
     * set parent group id
     * @param int $newVal
     * @return Warecorp_Group_Family_Group_List
     * @author Artem Sukharev
     */
    public function setGroupId($newVal)
    {
        $this->_groupId = $newVal;
        return $this;
    }
    
    /**
     * get parent group id
     * @return int
     * @author Artem Sukharev
     */
    public function getGroupId()
    {
        if ( $this->_groupId === null ) throw new Zend_Exception('Group Id not set');
        return $this->_groupId;
    }
    
    /**
     * set group types
     * @param array|string|string_delimiter_by_; $newVal
     * @return Warecorp_Group_List
     * @author Artem Sukharev
     */
    public function setTypes($newVal)
    {
        if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_GroupType::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect group type');
                }
            }
        } elseif ( strpos($newVal, ';') ) {
            $newVal = explode(';', $newVal);
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_GroupType::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect group type');
                }
            }
        } else {
            if ( !Warecorp_Group_Enum_GroupType::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect group type');
            }
            $newVal = array($newVal);
        }
        $this->_types = $newVal;
        return $this;
    }
    
    /**
     * get group types
     * @return array
     * @author Artem Sukharev
     */
    public function getTypes()
    {
        if ( $this->_types === null ) $this->_types = array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY );
        return $this->_types;
    }
 
    /**
     * set ids of groups from include
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setGroupIdsIn($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_groupIdsIn = $newVal;
        return $this;
    }
    
    /**
     * get ids of groups from include
     * @return array
     * @author Artem Sukharev
     */
    public function getGroupIdsIn()
    {
        return $this->_groupIdsIn;
    }
    
    /**
     * set ids of groups from include
     * @param array $newVal
     * @return Warecorp_Group_Locations_Abstract
     * @author Artem Sukharev
     */
    public function setGroupIdsOut($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_groupIdsOut = $newVal;
        return $this;
    }
    
    /**
     * get ids of groups from include
     * @return array
     * @author Artem Sukharev
     */
    public function getGroupIdsOut()
    {
        return $this->_groupIdsOut;
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
    public function getCountriesList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zlcr.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zlcr.name' : $this->getAssocValue();
            $query->from(array('zgr' => 'zanby_groups__relations'), $fields);  
        } else {
            $query->from(array('zgr' => 'zanby_groups__relations'), new Zend_Db_Expr('DISTINCT zlcr.id'));
        }
        $query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        $query->joinleft(array('zlcr' => 'zanby_location__countries'), 'zls.country_id = zlcr.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgr.parent_group_id = ?', $this->getGroupId());
        $query->where('zgi.type IN (?)', $this->getTypes());
        if ( $this->getIncludeIds() )       $query->where('zlcr.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zlcr.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getGroupIdsIn() )       $query->where('zgi.id IN (?)', $this->getGroupIdsIn());
        if ( $this->getGroupIdsOut() )      $query->where('zgi.id NOT IN (?)', $this->getGroupIdsOut());
        if ( $this->getCountryIdsIn() )     $query->where('zls.country_id IN (?)', $this->getCountryIdsIn());
        if ( $this->getCountryIdsOut() )    $query->where('zls.country_id NOT IN (?)', $this->getCountryIdsOut());
        if ( $this->getStateIdsIn() )       $query->where('zlc.state_id IN (?)', $this->getStateIdsIn());
        if ( $this->getStateIdsOut() )      $query->where('zlc.state_id NOT IN (?)', $this->getStateIdsOut());
        if ( $this->getCityIdsIn() )        $query->where('zlc.id IN (?)', $this->getCityIdsIn());
        if ( $this->getCityIdsOut() )       $query->where('zlc.id IN (?)', $this->getCityIdsOut());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order(array('zlcr.sort_order ASC', 'zlcr.name ASC'));
        
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = Warecorp_Location_Country::create($item);
        }
        return $items;
    }
    
    /**
     * Returns number of countries of members for group
     * @return int
     * @author Artem Sukharev
     */
    public function getCountriesCount()
    {
    	$query = $this->_db->select();
        $query->from(array('zgr' => 'zanby_groups__relations'), new Zend_Db_Expr('COUNT(DISTINCT zlcr.id)'));
        $query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        $query->joinleft(array('zlcr' => 'zanby_location__countries'), 'zls.country_id = zlcr.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgr.parent_group_id = ?', $this->getGroupId());
        $query->where('zgi.type IN (?)', $this->getTypes());
        if ( $this->getIncludeIds() )       $query->where('zlcr.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zlcr.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getGroupIdsIn() )       $query->where('zgi.id IN (?)', $this->getGroupIdsIn());
        if ( $this->getGroupIdsOut() )      $query->where('zgi.id NOT IN (?)', $this->getGroupIdsOut());        
        if ( $this->getCountryIdsIn() )     $query->where('zls.country_id IN (?)', $this->getCountryIdsIn());
        if ( $this->getCountryIdsOut() )    $query->where('zls.country_id NOT IN (?)', $this->getCountryIdsOut());
        if ( $this->getStateIdsIn() )       $query->where('zlc.state_id IN (?)', $this->getStateIdsIn());
        if ( $this->getStateIdsOut() )      $query->where('zlc.state_id NOT IN (?)', $this->getStateIdsOut());
        if ( $this->getCityIdsIn() )        $query->where('zlc.id IN (?)', $this->getCityIdsIn());
        if ( $this->getCityIdsOut() )       $query->where('zlc.id IN (?)', $this->getCityIdsOut());        
        
        return $this->_db->fetchOne($query);
    }
    
    /**
     * Returns array of states of members for group
     * @return array of Warecorp_Location_State
     * @author Artem Sukharev
     */
    public function getStatesList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zls.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zls.name' : $this->getAssocValue();
            $query->from(array('zgr' => 'zanby_groups__relations'), $fields);  
        } else {
            $query->from(array('zgr' => 'zanby_groups__relations'), new Zend_Db_Expr('DISTINCT zls.id'));
        }
        $query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgr.parent_group_id = ?', $this->getGroupId());
        $query->where('zgi.type IN (?)', $this->getTypes());
        if ( $this->getIncludeIds() )       $query->where('zls.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zls.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getGroupIdsIn() )       $query->where('zgi.id IN (?)', $this->getGroupIdsIn());
        if ( $this->getGroupIdsOut() )      $query->where('zgi.id NOT IN (?)', $this->getGroupIdsOut());
        if ( $this->getCountryIdsIn() )     $query->where('zls.country_id IN (?)', $this->getCountryIdsIn());
        if ( $this->getCountryIdsOut() )    $query->where('zls.country_id NOT IN (?)', $this->getCountryIdsOut());
        if ( $this->getStateIdsIn() )       $query->where('zlc.state_id IN (?)', $this->getStateIdsIn());
        if ( $this->getStateIdsOut() )      $query->where('zlc.state_id NOT IN (?)', $this->getStateIdsOut());
        if ( $this->getCityIdsIn() )        $query->where('zlc.id IN (?)', $this->getCityIdsIn());
        if ( $this->getCityIdsOut() )       $query->where('zlc.id IN (?)', $this->getCityIdsOut());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('zls.name ASC');
        
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = Warecorp_Location_State::create($item);
        }
        return $items;
    }
    
    /**
     * Returns number of states of members for group
     * @return int
     * @author Artem Sukharev
     */
    public function getStatesCount()
    {
        $query = $this->_db->select();
        $query->from(array('zgr' => 'zanby_groups__relations'), new Zend_Db_Expr('COUNT(DISTINCT zls.id)'));
        $query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgr.parent_group_id = ?', $this->getGroupId());
        $query->where('zgi.type IN (?)', $this->getTypes());
        if ( $this->getIncludeIds() )       $query->where('zls.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zls.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getGroupIdsIn() )       $query->where('zgi.id IN (?)', $this->getGroupIdsIn());
        if ( $this->getGroupIdsOut() )      $query->where('zgi.id NOT IN (?)', $this->getGroupIdsOut());
        if ( $this->getCountryIdsIn() )     $query->where('zls.country_id IN (?)', $this->getCountryIdsIn());
        if ( $this->getCountryIdsOut() )    $query->where('zls.country_id NOT IN (?)', $this->getCountryIdsOut());
        if ( $this->getStateIdsIn() )       $query->where('zlc.state_id IN (?)', $this->getStateIdsIn());
        if ( $this->getStateIdsOut() )      $query->where('zlc.state_id NOT IN (?)', $this->getStateIdsOut());
        if ( $this->getCityIdsIn() )        $query->where('zlc.id IN (?)', $this->getCityIdsIn());
        if ( $this->getCityIdsOut() )       $query->where('zlc.id IN (?)', $this->getCityIdsOut());
        
        return $this->_db->fetchOne($query);
    }
    
    /**
     * Returns array of cities of members for group
     * @return array of Warecorp_Location_City
     * @author Artem Sukharev
     */
    public function getCitiesList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zlc.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zlc.name' : $this->getAssocValue();
            $query->from(array('zgr' => 'zanby_groups__relations'), $fields);  
        } else {
            $query->from(array('zgr' => 'zanby_groups__relations'), new Zend_Db_Expr('DISTINCT zlc.id'));
        }
        $query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id');
        if ( $this->getCountryIdsIn() || $this->getCountryIdsOut() ) {
            $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        }
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgr.parent_group_id = ?', $this->getGroupId());
        $query->where('zgi.type IN (?)', $this->getTypes());
        if ( $this->getIncludeIds() )       $query->where('zlc.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zlc.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getGroupIdsIn() )       $query->where('zgi.id IN (?)', $this->getGroupIdsIn());
        if ( $this->getGroupIdsOut() )      $query->where('zgi.id NOT IN (?)', $this->getGroupIdsOut());
        if ( $this->getCountryIdsIn() )     $query->where('zls.country_id IN (?)', $this->getCountryIdsIn());
        if ( $this->getCountryIdsOut() )    $query->where('zls.country_id NOT IN (?)', $this->getCountryIdsOut());
        if ( $this->getStateIdsIn() )       $query->where('zlc.state_id IN (?)', $this->getStateIdsIn());
        if ( $this->getStateIdsOut() )      $query->where('zlc.state_id NOT IN (?)', $this->getStateIdsOut());
        if ( $this->getCityIdsIn() )        $query->where('zlc.id IN (?)', $this->getCityIdsIn());
        if ( $this->getCityIdsOut() )       $query->where('zlc.id IN (?)', $this->getCityIdsOut());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('zlc.name ASC');
        
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = Warecorp_Location_State::create($item);
        }
        return $items;
    }
    
    /**
     * Returns number of cities of members for group
     * @return int
     * @author Artem Sukharev
     */
    public function getCitiesCount()
    {
        $query = $this->_db->select();
        $query->from(array('zgr' => 'zanby_groups__relations'), new Zend_Db_Expr('COUNT(DISTINCT zlc.id)'));
        $query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id');
        if ( $this->getCountryIdsIn() || $this->getCountryIdsOut() ) {
            $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        }
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgr.parent_group_id = ?', $this->getGroupId());
        $query->where('zgi.type IN (?)', $this->getTypes());
        if ( $this->getIncludeIds() )       $query->where('zlc.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zlc.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getGroupIdsIn() )       $query->where('zgi.id IN (?)', $this->getGroupIdsIn());
        if ( $this->getGroupIdsOut() )      $query->where('zgi.id NOT IN (?)', $this->getGroupIdsOut());
        if ( $this->getCountryIdsIn() )     $query->where('zls.country_id IN (?)', $this->getCountryIdsIn());
        if ( $this->getCountryIdsOut() )    $query->where('zls.country_id NOT IN (?)', $this->getCountryIdsOut());
        if ( $this->getStateIdsIn() )       $query->where('zlc.state_id IN (?)', $this->getStateIdsIn());
        if ( $this->getStateIdsOut() )      $query->where('zlc.state_id NOT IN (?)', $this->getStateIdsOut());
        if ( $this->getCityIdsIn() )        $query->where('zlc.id IN (?)', $this->getCityIdsIn());
        if ( $this->getCityIdsOut() )       $query->where('zlc.id IN (?)', $this->getCityIdsOut());
        
        return $this->_db->fetchOne($query);
    }
}
