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
 * @package    Warecorp_Group_Family
 * @copyright  Copyright (c) 2007
 */
class BaseWarecorp_Group_Family_Members extends Warecorp_Group_Members_Abstract
{
    /**
     *  return list of all items
     *  @return array of objects
     *  @author Artem Sukharev
     */

    private $distinct;
    private $_groupStatus;

    /**
     * set group status
     * @param array|string|string_delimiter_by_; $newVal from Warecorp_Group_Enum_GroupStatus
     * @return Warecorp_Group_Family_Group_List
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function setGroupStatus($newVal)
    {
        if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_GroupStatus::isIn($_value) ) {
                    throw new Zend_Exception('Incorrect group status');
                }
            }
        } elseif ( strpos($newVal, ';') ) {
            $newVal = explode(';', $newVal);
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_GroupStatus::isIn($_value) ) {
                    throw new Zend_Exception('Incorrect group status');
                }
            }
        } elseif ( $newVal == Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_BOTH ) {
            $newVal = array(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED, Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_PENDING);
        } else {
            if ( !Warecorp_Group_Enum_GroupStatus::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect group status');
            }
            $newVal = array($newVal);
        }

        $this->_groupStatus = $newVal;
        return $this;
    }

    /**
     * return group status
     * @return array
     * @author Artem Sukharev, Vitaly Targonsky
     */
    public function getGroupStatus()
    {
        if ( $this->_groupStatus === null ) $this->setGroupStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED);
        return $this->_groupStatus;
    }

    /**
     * @author Vitaly Targonsky
     */
    public function setDistinct($boolean)
    {
        $this->distinct = $boolean;
        return $this;
    }
    /**
     * @author Vitaly Targonsky
     */
    public function getDistinct()
    {
        if ( $this->distinct === null) return true;
        return $this->distinct;
    }
    /**
     * Get group
     * @author Vitaly Targonsky
     */
    public function getGroup()
    {
        if ($this->_group === null) {
            $this->_group = Warecorp_Group_Factory::loadById($this->getId(), Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        }
        return $this->_group;
    }

    public function getList()
    {
        $query = $this->_db->select()->distinct();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'vfu.user_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zua.login' : $this->getAssocValue();
            $query->from(array('vfu' => 'view_family__users'), $fields);
        } else {
            $query->from(array('vfu' => 'view_family__users'), new Zend_Db_Expr((($this->getDistinct()) ? 'DISTINCT ' : '') .'vfu.user_id'));
        }
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.is_approved IN (?)', $this->getMembersStatus());
        $query->where('vfu.group_status IN (?)', $this->getGroupStatus());

        $userRoles = array();
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $this->getMembersRole()) ) $userRoles[] = 1;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $this->getMembersRole()) ) $userRoles[] = 2;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $this->getMembersRole()) ) $userRoles[] = 0;
        $query->where('vfu.family_owner IN (?)', $userRoles);

        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('vfu.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vfu.user_id NOT IN (?)', $this->getExcludeIds());

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('zua.login');

        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            //var_dump(count($items)); exit();
            foreach ( $items as &$item ) $item = new Warecorp_User('id', $item);
        }
        return $items;
    }

    public function getEmailsList()
    {
        $query = $this->_db->select();
		$query->from(array('vfu' => 'view_family__users'), array('zua.id', 'zua.email'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.is_approved IN (?)', $this->getMembersStatus());
        $query->where('vfu.group_status IN (?)', $this->getGroupStatus());

        $userRoles = array();
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $this->getMembersRole()) ) $userRoles[] = 1;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $this->getMembersRole()) ) $userRoles[] = 2;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $this->getMembersRole()) ) $userRoles[] = 0;
        $query->where('vfu.family_owner IN (?)', $userRoles);

        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('vfu.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vfu.user_id NOT IN (?)', $this->getExcludeIds());

        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) $query->order($this->getOrder());
        else $query->order('zua.id');

		$items = $this->_db->fetchPairs($query);
        return $items;
    }
	
    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev
     */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('vfu' => 'view_family__users'), new Zend_Db_Expr('COUNT('. ( $this->getDistinct() ? 'DISTINCT ' : '').'vfu.user_id)'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.is_approved IN (?)', $this->getMembersStatus());
        $query->where('vfu.group_status IN (?)', $this->getGroupStatus());

        $userRoles = array();
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $this->getMembersRole()) ) $userRoles[] = 1;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $this->getMembersRole()) ) $userRoles[] = 2;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $this->getMembersRole()) ) $userRoles[] = 0;
        $query->where('vfu.family_owner IN (?)', $userRoles);

        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('vfu.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vfu.user_id NOT IN (?)', $this->getExcludeIds());

        return $this->_db->fetchOne($query);
    }

    /**
    * Returns date of joining user to the family group
    * @param int|Warecorp_User $user
    * @retrun date
    * @author Yury Zolotarsky
    */
    public function getJoinDate($user)
    {
        if (!($user instanceof Warecorp_User)) $user = new Warecorp_User('id', $user);
        $query = $this->_db->select()
                    ->from(array('zgr' => 'zanby_groups__relations'), array('join_date' => new Zend_Db_Expr('CASE WHEN zgr.join_date>zgm.creation_date THEN zgr.join_date ELSE zgm.creation_date END')))
                    ->join(array('zgm' => 'zanby_groups__members'), 'zgr.child_group_id = zgm.group_id')
                    ->where('zgr.parent_group_id = ?', $this->getGroupId())
                    ->where('zgm.user_id = ?', $user->getId())
                    ->order('join_date asc')
                    ->limit(1);

        $join_date = $this->_db->fetchOne($query);
        if ($this->isHost($user)) {
             $member = $this->getMemberData($this->getId($user));
             if ($member) {
                $host_date = $member['creation_date'];
             }
        }
        if ($join_date && !empty($host_date)) {
            return (strtotime($join_date) < strtotime($host_date))?$join_date:$host_date;
        } elseif (!empty($host_date)) {
            return $host_date;
        } elseif ($join_date) {
            return $join_date;
        } else {
            return false;
        }
    }

    /**
     * Проверяет, является ли пользователь членом группы
     * @param int $user_id
     * @return bool
     * @author Artem Sukharev
     */
    public function isMemberExists($user_id)
    {
        $query = $this->_db->select();
        $query->from(array('vfu' => 'view_family__users'), 'vfu.user_id');
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.user_id = ?', $user_id);
        $query->where('zua.status IN (?)', 'active');
        $res = $this->_db->fetchCol($query);
        return (bool) $res;
    }
    /**
     * Проверяет, является ли пользователь членом группы и подтвержден т.е. активен
     * @param int $user_id
     * @return bool
     * @author Artem Sukharev
     */
    public function isMemberExistsAndApproved($user_id)
    {
        $query = $this->_db->select();
        $query->from(array('vfu' => 'view_family__users'), 'vfu.user_id');
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.user_id = ?', (NULL === $user_id) ? new Zend_Db_Expr('NULL') : $user_id, 'INTEGER');
        $query->where('vfu.is_approved = ?', 1);
        $query->where('zua.status IN (?)', 'active');
        $res = $this->_db->fetchCol($query);
        return (bool) $res;
    }
    /**
     * Проверяет, является ли пользователь членом группы и неподтвержденным т.е. неактивен
     * @param int $user_id
     * @return bool
     * @author Artem Sukharev
     */
    public function isMemberExistsAndPending($user_id)
    {
        $query = $this->_db->select();
        $query->from(array('vfu' => 'view_family__users'), 'vfu.user_id');
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.user_id = ?', (NULL === $user_id) ? new Zend_Db_Expr('NULL') : $user_id, 'INTEGER');
        $query->where('vfu.is_approved = ?', 0);
        $query->where('zua.status IN (?)', 'active');
        $res = $this->_db->fetchCol($query);
        return (bool) $res;
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
            $fields[] = ( $this->getAssocKey() === null ) ? 'vfu.user_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zua.login' : $this->getAssocValue();
            $query->from(array('vfu' => 'view_family__users'), $fields);
        } else {
            $query->from(array('vfu' => 'view_family__users'), new Zend_Db_Expr('DISTINCT vfu.user_id'));
        }
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zls.country_id = ?', $countryId);
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.is_approved IN (?)', $this->getMembersStatus());

        /**
         * для фемели группы есть только овнер, а не хост или сохост
         * поэтому, если указана для выборки роль хоста, то извлекаются овнеры как хосты
         * если указаны сохост или мембер - извлекаются просто мемберы
         * все это смотриться по полю family_owner во вьюшке
         */
        $userRoles = array();
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $this->getMembersRole()) ) $userRoles[] = 1;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $this->getMembersRole()) || in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $this->getMembersRole()) ) {$userRoles[] = 0; $userRoles[] = 2;}
        $query->where('vfu.family_owner IN (?)', $userRoles);

        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('vfu.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vfu.user_id NOT IN (?)', $this->getExcludeIds());

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
        $query->from(array('vfu' => 'view_family__users'), new Zend_Db_Expr('COUNT(DISTINCT vfu.user_id)'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');
        $query->joinleft(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zls.country_id = ?', $countryId);
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.is_approved IN (?)', $this->getMembersStatus());

        /**
         * для фемели группы есть только овнер, а не хост или сохост
         * поэтому, если указана для выборки роль хоста, то извлекаются овнеры как хосты
         * если указаны сохост или мембер - извлекаются просто мемберы
         * все это смотриться по полю family_owner во вьюшке
         */
        $userRoles = array();
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $this->getMembersRole()) ) $userRoles[] = 1;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $this->getMembersRole()) || in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $this->getMembersRole()) ) {$userRoles[] = 0; $userRoles[] = 2;}
        $query->where('vfu.family_owner IN (?)', $userRoles);

        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('vfu.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vfu.user_id NOT IN (?)', $this->getExcludeIds());

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
            $fields[] = ( $this->getAssocKey() === null ) ? 'vfu.user_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zua.login' : $this->getAssocValue();
            $query->from(array('vfu' => 'view_family__users'), $fields);
        } else {
            $query->from(array('vfu' => 'view_family__users'), new Zend_Db_Expr('DISTINCT vfu.user_id'));
        }
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zlc.state_id = ?', $stateId);
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.is_approved IN (?)', $this->getMembersStatus());

        /**
         * для фемели группы есть только овнер, а не хост или сохост
         * поэтому, если указана для выборки роль хоста, то извлекаются овнеры как хосты
         * если указаны сохост или мембер - извлекаются просто мемберы
         * все это смотриться по полю family_owner во вьюшке
         */
        $userRoles = array();
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $this->getMembersRole()) ) $userRoles[] = 1;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $this->getMembersRole()) || in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $this->getMembersRole()) ) {$userRoles[] = 0; $userRoles[] = 2;}
        $query->where('vfu.family_owner IN (?)', $userRoles);

        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('vfu.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vfu.user_id NOT IN (?)', $this->getExcludeIds());

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
        $query->from(array('vfu' => 'view_family__users'), new Zend_Db_Expr('COUNT(DISTINCT vfu.user_id)'));
        $query->joininner(array('zua' => 'zanby_users__accounts'), 'zua.id = vfu.user_id');
        $query->joinleft(array('zlc' => 'zanby_location__cities'), 'zua.city_id = zlc.id');

        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zlc.state_id = ?', $stateId);
        $query->where('vfu.family_id = ?', $this->getGroupId());
        $query->where('vfu.is_approved IN (?)', $this->getMembersStatus());

        /**
         * для фемели группы есть только овнер, а не хост или сохост
         * поэтому, если указана для выборки роль хоста, то извлекаются овнеры как хосты
         * если указаны сохост или мембер - извлекаются просто мемберы
         * все это смотриться по полю family_owner во вьюшке
         */
        $userRoles = array();
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $this->getMembersRole()) ) $userRoles[] = 1;
        if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $this->getMembersRole()) || in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $this->getMembersRole()) ) {$userRoles[] = 0; $userRoles[] = 2;}
        $query->where('vfu.family_owner IN (?)', $userRoles);

        $query->where('zua.status IN (?)', 'active');
        if ( $this->getIncludeIds() ) $query->where('vfu.user_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('vfu.user_id NOT IN (?)', $this->getExcludeIds());

        return $this->_db->fetchOne($query);
    }

    /**
     * set Host of Group
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     */
    public function setAsHost($user)
    {
    	$this->changeHost($user);
    }

    /**
     * Change Host of Group
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     */
    public function changeHost($user)
    {			
    	parent::setAsHost($user);		
    	if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;

        $where = $this->_db->quoteInto('user_id = ?', $userId).
        $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId());
        $this->_db->delete('zanby_groups__members', $where);

        $where = $this->_db->quoteInto('group_id = ?', $this->getGroupId()).
        $this->_db->quoteInto(' AND status = ?', Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST);
        $this->_db->delete('zanby_groups__members', $where);

        $data = array('user_id' => $userId, 'group_id' => $this->getGroupId(), 'creation_date' => date('Y-m-d H:i:s'), 'status' => Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST);
        $this->_db->insert('zanby_groups__members', $data);

//        $table = 'zanby_groups__privileges_users';
//        $group = $this->getGroup();

/*        $member = $group->getMembers()->getId($user);
        $where = $this->_db->quoteInto('member_id = ?', $member);
		$rows_affected = $this->_db->delete($table, $where);
		$gid = $this->_db->quote($this->getGroupId());
		$query = 'insert into zanby_groups__privileges_users
					select distinct zfr.family_id, zfr.child_parent_id, zgpu.tool_type, zgm2.id
					from zanby_family__relations zfr join zanby_groups__privileges_users zgpu on
					(zfr.family_id = zgpu.group_id) join zanby_groups__members zgm1 on
					(zgpu.member_id = zgm1.id) join zanby_groups__members zgm2 on
					(zgm1.user_id = zgm2.user_id)
					where (zfr.child_id = '.$gid.') and (zgm2.group_id = '.$gid.')
					and (zgm2.user_id = '.$userId.')';
		$this->_db->query($query);*/
    }

     /**
     * Set member status to approve
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     */
    public function approveMember($user)
    {
		throw new Zend_Exception('can not approveMember for family group');
    }


    public function setAsCohost($user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;

        if ($this->isMemberExists($userId)) {
            $query = $this->_db->select();
            $query->from('zanby_groups__members', 'status')
                ->where('user_id =? ', $userId)
                ->where('group_id =? ', $this->getGroupId());

            $status = $this->_db->fetchOne($query);
            if ($status === false) {
                $data = array('user_id' => $userId, 'group_id' => $this->getGroupId(), 'creation_date' => date('Y-m-d H:i:s'), 'status' => Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST);
                $this->_db->insert('zanby_groups__members', $data);
            } else {
                if ($status != Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST && $status != Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST) {
                    /**
                     * Changed according to Bug #3543
                     * @author Artem Sukharev
                     * original query : $data = array('status' => Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST);
                     */
                    $data = array('status' => Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, 'is_approved' => 1);
                    $where = $this->_db->quoteInto('user_id = ?', $userId).
                    $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId());
                    $this->_db->update('zanby_groups__members', $data, $where);
                }
            }
            $privileges = new Warecorp_Group_Privileges($this->getGroupId());
            $privileges->deleteUserFromAllTools($user);
        } else {
            throw new Zend_Exception('Co-Owner must be member of group joined in the family');
        }
    }

     /**
     * Set Member as member
     * @param int|Warecorp_User $user
     * @return void
     * @author Dmitry Kamenka
     */
    public function setAsMember($user)
    {
        if ( $user instanceof Warecorp_User ) $userId = $user->getId();
        else $userId = $user;

        $where = $this->_db->quoteInto('user_id = ?', $userId).
        $this->_db->quoteInto(' AND group_id = ?', $this->getGroupId());

        $this->_db->delete('zanby_groups__members', $where);
    }

    /**
     * get id's from members table
     * @param int|Warecorp_User $user
     * @author Yury Zolotarsky
     * @return int|array
     */


    public function getMemberId($user)
	{
		if (!($user instanceof Warecorp_User)) $user = new Warecorp_User('id', $user);
		$query = $this->_db->select()
					->from(array('zgm' => 'zanby_groups__members'), array('parent' => 'DISTINCT zfr.child_parent_id','member' => 'zgm.id'))
					->join(array('zfr' => 'zanby_family__relations'), 'zfr.child_id = zgm.group_id or zfr.family_id = zgm.group_id', array())
					->where('zfr.family_id = ?', $this->getGroupId())
					->where('zgm.user_id = ?', $user->getId());					
		return $this->_db->fetchAll($query);
		
/*		
		if ($group->getMembers()->isHost($user)) $membersIds[] = $group->getMembers()->getId($user);
		$groupsList = $group->getGroups()->setAll();
		$userGroups = $user->getGroups()->getList();
		foreach($userGroups as $userGroup) {
			if ($groupsList->isGroupInFamily($userGroup)) {
				 $ids = $userGroup->getMembers()->getMemberId($user);
				 if (is_array($ids)) $membersIds = $membersIds + $ids; else $membersIds[] = $ids;
			}
		}
		return $membersIds;*/
	}

	public function getAllMembersId()
	{
		//$membersIds = array();
		$query = $this->_db->select()
					->from(array('zgm' => 'zanby_groups__members'), array('member' => 'zgm.id'))
					->join(array('zfr' => 'zanby_family__relations'), 'zfr.child_id = zgm.group_id or zfr.family_id = zgm.group_id', array('parent' => 'zfr.child_parent_id'))
					->where('zfr.family_id = ?', $this->getGroupId());
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

        $select->from(array('vfu' => 'view_family__users'),
        array('UPPER(SUBSTRING(zua.firstname, 1, 1)) AS "letter"',
        'COUNT(*) AS "count"'))
        ->join(array('zua' => 'zanby_users__accounts'), 'vfu.user_id = zua.id')
        ->where('ORD(UPPER(SUBSTRING(zua.firstname, 1, 1))) BETWEEN 65 AND 90')
        ->group('letter')
        ->order('letter');
        $select->where('vfu.family_id = ?', $this->getGroupId());
        $select->where('vfu.is_approved IN (?)', $this->getMembersStatus());
        $select->where('zua.status IN (?)', 'active');
        $result = $this->_db->fetchPairs($select);
        return $result;
    }

    public function getHostsOfAllGroupsInFamily($asAssoc = false)
    {
        $query = $this->_db->select();
        if ($asAssoc) {
            $query = $query->from(array('zgm' => 'zanby_groups__members'), array('id' => 'zgm.user_id'))
                     ->join(array('zua' => 'zanby_users__accounts'), 'zgm.user_id = zua.id', array('login' => 'zua.login'));
        } else {
            $query = $query->from(array('zgm' => 'zanby_groups__members'), 'zgm.user_id');
        }
        $query = $query->join(array('zfr' => 'zanby_family__relations'), 'zfr.child_id = zgm.group_id or zfr.family_id = zgm.group_id')
                    ->where('zfr.family_id = ?', $this->getGroupId())
                    ->where('zgm.status = ?', Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST)
                    ->where('zfr.group_status = ?', Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED);
        if ($asAssoc) {
            return $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ($items as &$item) {
                $item = new Warecorp_User('id', $item);
            }
            return $items;
        }
    }
}
