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
 * Class implement methods for LOCATIONS (City List, Country List, State List) of MEMBER any simple group
 * @package Warecorp_Group_Simple
 * @copyright Copyright (c) 2007
 * @author Artem Sukharev
 */
class BaseWarecorp_Group_Simple_Member_LocationList extends Warecorp_Group_Location_Member_AbstractList 
{
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
            $query->from(array('zgm' => 'zanby_groups__members'), $fields);  
        } else {
            $query->from(array('zgm' => 'zanby_groups__members'), 'zlcr.id');
        }
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        $query->joinleft(array('zlcr' => 'zanby_location__countries'), 'zls.country_id = zlcr.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());       
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() )       $query->where('zlcr.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zlcr.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getUserIdsIn() )        $query->where('zua.id IN (?)', $this->getUserIdsIn());
        if ( $this->getUserIdsOut() )       $query->where('zua.id NOT IN (?)', $this->getUserIdsOut());
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
        $query->from(array('zgm' => 'zanby_groups__members'), new Zend_Db_Expr('COUNT(zlcr.id)'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        $query->joinleft(array('zlcr' => 'zanby_location__countries'), 'zls.country_id = zlcr.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());       
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() )       $query->where('zlcr.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zlcr.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getUserIdsIn() )        $query->where('zua.id IN (?)', $this->getUserIdsIn());
        if ( $this->getUserIdsOut() )       $query->where('zua.id NOT IN (?)', $this->getUserIdsOut());
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
            $query->from(array('zgm' => 'zanby_groups__members'), $fields);  
        } else {
            $query->from(array('zgm' => 'zanby_groups__members'), 'zls.id');
        }
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() )       $query->where('zls.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zls.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getUserIdsIn() )        $query->where('zua.id IN (?)', $this->getUserIdsIn());
        if ( $this->getUserIdsOut() )       $query->where('zua.id NOT IN (?)', $this->getUserIdsOut());
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
        $query->from(array('zgm' => 'zanby_groups__members'), new Zend_Db_Expr('COUNT(zls.id)'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() )       $query->where('zls.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zls.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getUserIdsIn() )        $query->where('zua.id IN (?)', $this->getUserIdsIn());
        if ( $this->getUserIdsOut() )       $query->where('zua.id NOT IN (?)', $this->getUserIdsOut());
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
            $query->from(array('zgm' => 'zanby_groups__members'), $fields);  
        } else {
            $query->from(array('zgm' => 'zanby_groups__members'), 'zlc.id');
        }
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        if ( $this->getCountryIdsIn() || $this->getCountryIdsOut() ) {
            $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        }
                
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() )       $query->where('zlc.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zlc.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getUserIdsIn() )        $query->where('zua.id IN (?)', $this->getUserIdsIn());
        if ( $this->getUserIdsOut() )       $query->where('zua.id NOT IN (?)', $this->getUserIdsOut());
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
        $query->from(array('zgm' => 'zanby_groups__members'), new Zend_Db_Expr('COUNT(zlc.id)'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        if ( $this->getCountryIdsIn() || $this->getCountryIdsOut() ) {
            $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        }
                
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() )       $query->where('zlc.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() )       $query->where('zlc.id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getUserIdsIn() )        $query->where('zua.id IN (?)', $this->getUserIdsIn());
        if ( $this->getUserIdsOut() )       $query->where('zua.id NOT IN (?)', $this->getUserIdsOut());
        if ( $this->getCountryIdsIn() )     $query->where('zls.country_id IN (?)', $this->getCountryIdsIn());
        if ( $this->getCountryIdsOut() )    $query->where('zls.country_id NOT IN (?)', $this->getCountryIdsOut());
        if ( $this->getStateIdsIn() )       $query->where('zlc.state_id IN (?)', $this->getStateIdsIn());
        if ( $this->getStateIdsOut() )      $query->where('zlc.state_id NOT IN (?)', $this->getStateIdsOut());
        if ( $this->getCityIdsIn() )        $query->where('zlc.id IN (?)', $this->getCityIdsIn());
        if ( $this->getCityIdsOut() )       $query->where('zlc.id IN (?)', $this->getCityIdsOut());
        
        return $this->_db->fetchOne($query);
    }
}
