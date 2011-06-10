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
 * @package    Warecorp_Group_Simple
 * @copyright  Copyright (c) 2007
 */
class BaseWarecorp_Group_Simple_Members extends Warecorp_Group_Members_Abstract 
{
    /**
     *  return list of all items
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zgm.user_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zua.login' : $this->getAssocValue();
            $query->from(array('zgm' => 'zanby_groups__members'), $fields);  
        } else {
            $query->from(array('zgm' => 'zanby_groups__members'), 'zgm.user_id');
        }
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('zgm.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgm.user_id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('zua.login');
        
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = new Warecorp_User('id', $item);
        }
        return $items;
    }

    /**
     *  return list of all member emails
     *  @return array of string
     *  @author Artem Sukharev
     */
    public function getEmailsList()
    {
        $query = $this->_db->select();
		$query->from(array('zgm' => 'zanby_groups__members'), array('zua.id', 'zua.email'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('zgm.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgm.user_id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('zua.id');
        
		$items = $this->_db->fetchPairs($query);
        return $items;
    }

    /**
     * Returns date of joining user to the group
     * @param int|Warecorp_User $user
     * @retrun date
     * @author Yury Zolotarsky
     */
    public function getJoinDate($user)
    {
        if ( $user instanceof Warecorp_User) $userId = $user->getId();
        else $userId = $user;

        $query = $this->_db->select();
        $query->from(array('zgm' => 'zanby_groups__members'), 'creation_date');        
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.user_id = ?', $userId);
        return $this->_db->fetchOne($query);        
    }
    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev
     */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('zgm' => 'zanby_groups__members'), new Zend_Db_Expr('COUNT(zgm.user_id)'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id', NULL);
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('zgm.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgm.user_id NOT IN (?)', $this->getExcludeIds());
              
        return $this->_db->fetchOne($query);
    }
    
    /**
     * Проверяет, является ли пользователь членом группы
     * @param int $user_id .. or object of Warecorp User
     * @return bool
     * @author Artem Sukharev, Alexander Komarovski
     * @author Pavel Shutin
     */
    public function isMemberExists($user_id)
    {
        if ( !($user_id instanceof Warecorp_User)) {
            $user_id = new Warecorp_User('id',$user_id);
        }

        return ($user_id->getGroupRole($this->getGroup()));
    }
    
    /**
     * Проверяет, является ли пользователь членом группы и подтвержден т.е. активен
     * @param int|Warecorp_User $user_id
     * @return bool
     * @author Artem Sukharev, Alexander Komarovski
     * @author Pavel Shutin
     */
    public function isMemberExistsAndApproved($user_id)
    {
        // fix by komarovski
        if ( !($user_id instanceof Warecorp_User)) {
            $user_id = new Warecorp_User('id',$user_id);
        }

        return ($user_id->getGroupRole($this->getGroup()) && $user_id->getGroupRole($this->getGroup()) !== 'pending');
    }
    
    /**
     * Проверяет, является ли пользователь членом группы и неподтвержденным т.е. неактивен
     * @param int|Warecorp_User $user_id
     * @return bool
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
    public function isMemberExistsAndPending($user_id)
    {
        if ( !($user_id instanceof Warecorp_User)) {
            $user_id = new Warecorp_User('id',$user_id);
        }

        return ($user_id->getGroupRole($this->getGroup()) === 'pending');
    }
    
    /**
     * Returns list of group's members for requested country
     * @param int|Warecorp_Location_Country $country
     * @retrun array of pairs|Warecorp_User
     * @author Alexander Komarovski
     * @author Artem Sukharev
     */
    public function getListByCountry($country)
    {
        if ( $country instanceof Warecorp_Location_Country ) $countryId = $country->id;
        else $countryId = $country;
        

        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zgm.user_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zua.login' : $this->getAssocValue();
            $query->from(array('zgm' => 'zanby_groups__members'), $fields);  
        } else {
            $query->from(array('zgm' => 'zanby_groups__members'), 'zgm.user_id');
        }
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zls.country_id = ?', $countryId);
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('zgm.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgm.user_id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('zua.login');
        
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = new Warecorp_User('id', $item);
        }
        return $items;
    }
    
    /**
     * Returns number of group's members for requested country
     * @param int|Warecorp_Location_Country $country
     * @retrun int
     * @author Artem Sukharev
     */
    public function getCountByCountry($country)
    {
        if ( $country instanceof Warecorp_Location_Country ) $countryId = $country->id;
        else $countryId = $country;
        
        $query = $this->_db->select();
        $query->from(array('zgm' => 'zanby_groups__members'), new Zend_Db_Expr('COUNT(zgm.user_id)'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zls.country_id = ?', $countryId);
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('zgm.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgm.user_id NOT IN (?)', $this->getExcludeIds());       
        return $this->_db->fetchOne($query);
    }
    
    /**
     * Returns list of group's members for requested state
     * @param int|Warecorp_Location_State $state
     * @retrun array of pairs|Warecorp_User
     * @author Alexander Komarovski
     * @author Artem Sukharev
     */
    public function getListByState($state)
    {
        if ( $state instanceof Warecorp_Location_State ) $stateId = $state->id;
        else $stateId = $state;
        
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zgm.user_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zua.login' : $this->getAssocValue();
            $query->from(array('zgm' => 'zanby_groups__members'), $fields);  
        } else {
            $query->from(array('zgm' => 'zanby_groups__members'), 'zgm.user_id');
        }
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zlc.state_id = ?', $stateId);
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('zgm.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgm.user_id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('zua.login');
        
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = new Warecorp_User('id', $item);
        }
        return $items;
    }
    
    /**
     * Returns list of group's members for requested state
     * @param int|Warecorp_Location_State $state
     * @retrun int
     * @author Artem Sukharev
     */
    public function getCountByState($state)
    {
        if ( $state instanceof Warecorp_Location_State ) $stateId = $state->id;
        else $stateId = $state;
        
        $query = $this->_db->select();
        $query->from(array('zgm' => 'zanby_groups__members'), new Zend_Db_Expr('COUNT(zgm.user_id)'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = zgm.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zlc.state_id = ?', $stateId);
        $query->where('zgm.group_id = ?', $this->getGroupId());
        $query->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $query->where('zgm.status IN (?)', $this->getMembersRole());
        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) {
            $query->where('zgm.user_id IN (?)', $this->getIncludeIds());
        }
        if ( $this->getExcludeIds() ) {
            $query->where('zgm.user_id NOT IN (?)', $this->getExcludeIds());
        }        
        return $this->_db->fetchOne($query);
    }
    
    /**
     * set Host of Group
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     */  
    public function setAsHost($user)
    {
        parent::setAsHost($user);
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;
        $data = array('status' => Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST);
        $where = $this->_db->quoteInto('user_id = ?', $userId).
                 $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId());
        $this->_db->update('zanby_groups__members', $data, $where);        
    }

    /**
     * Change Host of Group
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     */    
    public function changeHost($userId)
    {
        if ($this->getGroup()->getHost() !== null) $this->setAsMember($this->getGroup()->getHost());
        $this->setAsHost($userId);    
    }   
    
    /**
     * get id's from members table
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     * @return array
     */

    public function getMemberId($user)
    {
        if ( $user instanceof Warecorp_User) $userId = $user->getId();
        else $userId = $user;
        $query = $this->_db->select();
        $query->from(array('zgm' => 'zanby_groups__members'), array('parent' => new Zend_Db_Expr($this->getGroupId()), 'member' => 'zgm.id'))
                ->where('zgm.group_id = ?', $this->getGroupId())
                ->where('zgm.user_id = ?', $userId);
        return $this->_db->fetchAll($query);
    }  
    
    /**
     * get id's from members table for all members
     * @author Yury Zolotarsky
     * @return array
     */
    public function getAllMembersId()
    {
        $query = $this->_db->select();
        $query->from(array('zgm' => 'zanby_groups__members'), array('parent' => new Zend_Db_Expr($this->getGroupId()), 'member' => 'zgm.id'))
                ->where('zgm.group_id = ?', $this->getGroupId());
        return $this->_db->fetchAll($query);        
    }
    
    /**
     * get counts of member for filter 
     * @author Andrew Peresalyak
     * @return array
     */
    public function getLettersCount()
    {
        $select = $this->_db->select();

        $select->from(array('zgm' => 'zanby_groups__members'),
        array('UPPER(SUBSTRING(zua.firstname, 1, 1)) AS "letter"',
        'COUNT(*) AS "count"'))
        ->join(array('zua' => 'zanby_users__accounts'), 'zgm.user_id = zua.id')
        ->where('ORD(UPPER(SUBSTRING(zua.firstname, 1, 1))) BETWEEN 65 AND 90')
        ->group('letter')
        ->order('letter');
        $select->where('zgm.group_id = ?', $this->getGroupId());
        $select->where('zgm.is_approved IN (?)', $this->getMembersStatus());
        $select->where('zgm.status IN (?)', $this->getMembersRole());
        $select->where('zua.status IN (?)', 'active');
        $result = $this->_db->fetchPairs($select);
        return $result;
    }
    /**
     * Get group
     * @author Pavel Shutin
     */
    public function getGroup()
    {
        if ($this->_group === null) {
            $this->_group = Warecorp_Group_Factory::loadById($this->getId(), Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
        }
        return $this->_group;
    }
}
