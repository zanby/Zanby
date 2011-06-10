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
 * @package Warecorp_Group_Family_Group
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */
class BaseWarecorp_Group_Family_Group_List extends Warecorp_Abstract_List
{
	/**
	 * group id
	 */
	private $_groupId;

	/**
     * group types for select
     */
	private $_types;

	/**
	private $_types;

	/**
     * group level for select
     */
	private $all;
	/**
     * group status, value from Warecorp_Group_Enum_GroupStatus
     */
	private $_status;

    private $_excludeGroupIds = null;
    private $_includeGroupIds = null;

	/**
     * membership type
     */
	private $_membershipType;

	protected static $usersGroups = array(); // ассоциативные массивы групп, в которых пользователь хост

    private $family;

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

	public function getFamily() {
	    if (!$this->family) {
            $this->family = Warecorp_Group_Factory::loadById($this->getGroupId(), 'family');
	    }
	    return $this->family;
	}

	/**
     * set flag to get all groups
     * @param boolean
     * @author Yuri Zolotarski
     * @todo modify getList if all = true for extract all levels
     */



	public function setAll()
	{
		$this->all = true;
		return $this;
	}

	/**
     * set flag to get children groups
     * @param boolean
     * @author Yuri Zolotarski
     * @todo modify getList if all = false for extract top level
     */

	public function setChildren()
	{
		$this->all = false;
		return $this;
	}

	/**
     * get all groups
     * @param boolean
     * @author Yuri Zolotarski
     */

	public function getAll()
	{
		return $this->all;
	}

	/**
     * get only children groups
     * @param boolean
     * @author Yuri Zolotarski
     */
	public function getChilder()
	{
		return !$this->all;
	}


    public function setExcludeGroupIds($ids)
    {
        if (is_array($ids) and (!empty($ids))) {
            $this->_excludeGroupIds = $ids;
        } else {
            $this->_excludeGroupIds = null;
        }
        return $this;
    }

    public function setIncludeGroupIds($ids)
    {
        if (is_array($ids) and (!empty($ids))) {
            $this->_includeGroupIds = $ids;
        } else {
            $this->_includeGroupIds = null;
        }
        return $this;
    }

    public function getExcludeGroupIds()
    {
        return $this->_excludeGroupIds;
    }

    public function getIncludeGroupIds()
    {
        return $this->_includeGroupIds;
    }
	/**
     * set group types
     * @param array|string|string_delimiter_by_; $newVal from Warecorp_Group_Enum_GroupType
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
     * set group status
     * @param array|string|string_delimiter_by_; $newVal from Warecorp_Group_Enum_GroupStatus
     * @return Warecorp_Group_Family_Group_List
     * @author Artem Sukharev
     */
	public function setStatus($newVal)
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

		$this->_status = $newVal;
		return $this;
	}

	/**
     * return group status
     * @return array
     * @author Artem Sukharev
     */
	public function getStatus()
	{
		if ( $this->_status === null ) $this->setStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED);
		return $this->_status;
	}

	/**
     * set membership type status
     * DO NOTHING
     * @param array|string|string_delimiter_by_; $newVal from Warecorp_Group_Enum_GroupMembershipType
     * @return Warecorp_Group_Family_Group_List
     * @author Artem Sukharev
     * TODO Remove this function as it do nothing now
     */
	public function setMembershipType($newVal)
	{
		if ( is_array($newVal) ) {
			foreach ($newVal as &$_value) {
				$_value = trim($_value);
				if ( !Warecorp_Group_Enum_GroupMembershipType::isIn($_value) ) {
					throw new Zend_Exception('Incorrect membership type');
				}
			}
		} elseif ( strpos($newVal, ';') ) {
			$newVal = explode(';', $newVal);
			foreach ($newVal as &$_value) {
				$_value = trim($_value);
				if ( !Warecorp_Group_Enum_GroupMembershipType::isIn($_value) ) {
					throw new Zend_Exception('Incorrect membership type');
				}
			}
		} elseif ( $newVal == Warecorp_Group_Enum_GroupMembershipType::GROUP_MEMBERSHIP_BOTH ) {
			$newVal = array(Warecorp_Group_Enum_GroupMembershipType::GROUP_MEMBERSHIP_MEMBER, Warecorp_Group_Enum_GroupMembershipType::GROUP_MEMBERSHIP_COOWNER);
		} else {
			if ( !Warecorp_Group_Enum_GroupMembershipType::isIn($newVal) ) {
				throw new Zend_Exception('Incorrect membership type');
			}
			$newVal = array($newVal);
		}

		$this->_membershipType = $newVal;
		return $this;
	}

	/**
     * return group status
     * @return array
     * @author Artem Sukharev
     */
	public function getMembershipType()
	{
		if ( $this->_membershipType === null ) $this->setMembershipType(array(Warecorp_Group_Enum_GroupMembershipType::GROUP_MEMBERSHIP_MEMBER, Warecorp_Group_Enum_GroupMembershipType::GROUP_MEMBERSHIP_COOWNER));
		return $this->_membershipType;
	}

	/**
     * Constructor
     * @param int $groupId
     */
	public function __construct($groupId)
	{
		parent::__construct();
		$this->_groupId = $groupId;
		$this->setChildren();
	}

	public function getAllFamiliesInTree()
	{
		$query = $this->_db->select()->distinct()
					->from(array('zfr' => 'zanby_family__relations'),'zfr.child_parent_id')
					->where('zfr.family_id = ?', $this->getGroupId());
		return $this->_db->fetchCol($query);
	}
	/**
     *  return list of all items
     *  @return array of objects
     *  @author Artem Sukharev
     */
	public function getList()
	{
		$query = $this->_db->select();
		if ($this->all) {
			$query->distinct();
			$families = $this->getAllFamiliesInTree();
			if (empty($families)) $families = $this->getGroupId();
		} else $families = $this->getGroupId();
		if ( $this->isAsAssoc() ) {
			$fields = array();
			$fields[] = ( $this->getAssocKey() === null ) ? 'zgi.id' : $this->getAssocKey();
			$fields[] = ( $this->getAssocValue() === null ) ? 'zgi.name' : $this->getAssocValue();
			$query->from(array('zgr' => 'zanby_groups__relations'), $fields);
		} else {
			if ($this->getOrder() !== null && $this->getOrder() == 'creation_date') {
			    $query->from(array('zgr' => 'zanby_groups__relations'), array('zgr.child_group_id', 'creation_date' => 'IF(ISNULL(zgi.creation_date), 0, zgi.creation_date)'));
			} else {
				$query->from(array('zgr' => 'zanby_groups__relations'), 'zgr.child_group_id');
			}
		}
		$query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id',array('type'=>'zgi.type'));

		$query->where('zgr.parent_group_id in (?)', $families);
		$query->where('zgi.type IN (?)', $this->getTypes());
		$query->where('zgr.status IN (?)', $this->getStatus());
		if ( $this->getWhere() ) $query->where($this->getWhere());
		if ( $this->getIncludeIds() ) $query->where('zgr.child_group_id IN (?)', $this->getIncludeIds());
		if ( $this->getExcludeIds() ) $query->where('zgr.child_group_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getIncludeGroupIds() ) $query->where('zgi.groupUID IN (?)', $this->getIncludeGroupIds());
        if ( $this->getExcludeGroupIds() ) $query->where('(zgi.groupUID is null or zgi.groupUID NOT IN (?))', $this->getExcludeGroupIds());
		if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
			$query->limitPage($this->getCurrentPage(), $this->getListSize());
		}
		if ( $this->getOrder() !== null ) {
			$query->order($this->getOrder());
		}
		if ( $this->isAsAssoc() ) {
			$items = $this->_db->fetchPairs($query);
		} else {
			$result = $this->_db->fetchPairs($query);
            $items = array();
			foreach ( $result as $id=>$type ) $items[] = Warecorp_Group_Factory::loadById($id,$type);
		}
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
		if ($this->all) {
			$query->distinct();
			$families = $this->getAllFamiliesInTree();
			if (empty($families)) $families = $this->getGroupId();
		} else $families = $this->getGroupId();
		$query->from(array('zgr' => 'zanby_groups__relations'), new Zend_Db_Expr('COUNT(zgr.child_group_id)'));
		$query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id');
		$query->where('zgr.parent_group_id in (?)', $families);
		$query->where('zgi.type IN (?)', $this->getTypes());
		$query->where('zgr.status IN (?)', $this->getStatus());
		if ( $this->getWhere() ) $query->where($this->getWhere());
		if ( $this->getIncludeIds() ) $query->where('zgr.child_group_id IN (?)', $this->getIncludeIds());
		if ( $this->getExcludeIds() ) $query->where('zgr.child_group_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getIncludeGroupIds() ) $query->where('zgi.groupUID IN (?)', $this->getIncludeGroupIds());
        if ( $this->getExcludeGroupIds() ) $query->where('(zgi.groupUID is null or zgi.groupUID NOT IN (?))', $this->getExcludeGroupIds());
		return $this->_db->fetchOne($query);
	}

    /**
     *  return list of all items by country
     *  @param int|Warecorp_Location_Country $city
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getListByCountry($country)
    {
        if ( $country instanceof Warecorp_Location_Country ) $countryId = $country->id;
        else $countryId = $country;

        $query = $this->_db->select();
        if ($this->all) {
            $query->distinct();
            $families = $this->getAllFamiliesInTree();
            if (empty($families)) $families = $this->getGroupId();
        } else $families = $this->getGroupId();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zgr.child_group_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zgi.name' : $this->getAssocValue();
            $query->from(array('zgr' => 'zanby_groups__relations'), $fields);
        } else {
            $query->from(array('zgr' => 'zanby_groups__relations'), 'zgr.child_group_id');
        }
        $query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id',array('type'=>'zgi.type'));
        $query->join(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id');
        $query->join(array('zls' => 'zanby_location__states'), 'zlc.state_id = zls.id');

        $query->where('zgr.parent_group_id in (?)', $families)
        ->where('zgi.type IN (?)', $this->getTypes())
        ->where('zls.country_id = ?', $countryId);
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('zgr.child_group_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgr.child_group_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getIncludeGroupIds() ) $query->where('zgi.groupUID IN (?)', $this->getIncludeGroupIds());
        if ( $this->getExcludeGroupIds() ) $query->where('(zgi.groupUID is null or zgi.groupUID NOT IN (?))', $this->getExcludeGroupIds());
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
			$result = $this->_db->fetchPairs($query);
            $items = array();
			foreach ( $result as $id=>$type ) $items[] = Warecorp_Group_Factory::loadById($id,$type);
        }
        return $items;
    }

	/**
     *  return list of all items by state
     *  @param int|Warecorp_Location_State $city
     *  @return array of objects
     *  @author Artem Sukharev
     */
	public function getListByState($state)
	{
		if ( $state instanceof Warecorp_Location_State ) $stateId = $state->id;
		else $stateId = $state;

		$query = $this->_db->select();
		if ($this->all) {
			$query->distinct();
			$families = $this->getAllFamiliesInTree();
			if (empty($families)) $families = $this->getGroupId();
		} else $families = $this->getGroupId();
		if ( $this->isAsAssoc() ) {
			$fields = array();
			$fields[] = ( $this->getAssocKey() === null ) ? 'zgr.child_group_id' : $this->getAssocKey();
			$fields[] = ( $this->getAssocValue() === null ) ? 'zgi.name' : $this->getAssocValue();
			$query->from(array('zgr' => 'zanby_groups__relations'), $fields);
		} else {
			$query->from(array('zgr' => 'zanby_groups__relations'), 'zgr.child_group_id');
		}
		$query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id',array('type'=>'zgi.type'));
		$query->join(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id');
		$query->where('zgr.parent_group_id in (?)', $families)
		->where('zgi.type IN (?)', $this->getTypes())
		->where('zlc.state_id = ?', $stateId);
		if ( $this->getWhere() ) $query->where($this->getWhere());
		if ( $this->getIncludeIds() ) $query->where('zgr.child_group_id IN (?)', $this->getIncludeIds());
		if ( $this->getExcludeIds() ) $query->where('zgr.child_group_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getIncludeGroupIds() ) $query->where('zgi.groupUID IN (?)', $this->getIncludeGroupIds());
        if ( $this->getExcludeGroupIds() ) $query->where('(zgi.groupUID is null or zgi.groupUID NOT IN (?))', $this->getExcludeGroupIds());
		if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
			$query->limitPage($this->getCurrentPage(), $this->getListSize());
		}
		if ( $this->getOrder() !== null ) {
			$query->order($this->getOrder());
		}
		if ( $this->isAsAssoc() ) {
			$items = $this->_db->fetchPairs($query);
		} else {
			$result = $this->_db->fetchPairs($query);
            $items = array();
			foreach ( $result as $id=>$type ) $items[] = Warecorp_Group_Factory::loadById($id,$type);
		}
		return $items;
	}

	/**
     * return number of all items by state
     * @param int|Warecorp_Location_State $city
     * @return int count
     * @author Artem Sukharev
     */
	public function getCountByState($state)
	{
		if ( $state instanceof Warecorp_Location_State ) $stateId = $state->id;
		else $stateId = $state;

		$query = $this->_db->select();
		if ($this->all) {
			$query->distinct();
			$families = $this->getAllFamiliesInTree();
			if (empty($families)) $families = $this->getGroupId();
		} else $families = $this->getGroupId();
		$query->from(array('zgr' => 'zanby_groups__relations'), new Zend_Db_Expr('COUNT(zgr.child_group_id)'));
		$query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id');
		$query->join(array('zlc' => 'zanby_location__cities'), 'zgi.city_id = zlc.id');
		$query->where('zgr.parent_group_id in (?)', $families)
		->where('zgi.type IN (?)', $this->getTypes())
		->where('zlc.state_id = ?', $stateId);
		if ( $this->getWhere() ) $query->where($this->getWhere());
		if ( $this->getIncludeIds() ) $query->where('zgr.child_group_id IN (?)', $this->getIncludeIds());
		if ( $this->getExcludeIds() ) $query->where('zgr.child_group_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getIncludeGroupIds() ) $query->where('zgi.groupUID IN (?)', $this->getIncludeGroupIds());
        if ( $this->getExcludeGroupIds() ) $query->where('(zgi.groupUID is null or zgi.groupUID NOT IN (?))', $this->getExcludeGroupIds());
		return $this->_db->fetchOne($query);
	}

	/**
     *  return list of all items by city
     *  @param int|Warecorp_Location_City $city
     *  @return array of objects
     *  @author Artem Sukharev
     */
	public function getListByCity($city)
	{
		if ( $city instanceof Warecorp_Location_City ) $cityId = $city->id;
		else $cityId = $city;

		$query = $this->_db->select();
		if ($this->all) {
			$query->distinct();
			$families = $this->getAllFamiliesInTree();
			if (empty($families)) $families = $this->getGroupId();
		} else $families = $this->getGroupId();
		if ( $this->isAsAssoc() ) {
			$fields = array();
			$fields[] = ( $this->getAssocKey() === null ) ? 'zgr.child_group_id' : $this->getAssocKey();
			$fields[] = ( $this->getAssocValue() === null ) ? 'zgi.name' : $this->getAssocValue();
			$query->from(array('zgr' => 'zanby_groups__relations'), $fields);
		} else {
			$query->from(array('zgr' => 'zanby_groups__relations'), 'zgr.child_group_id');
		}
		$query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id',array('type'=>'zgi.type'));
		$query->where('zgr.parent_group_id in (?)', $families)
		->where('zgi.type IN (?)', $this->getTypes())
		->where('zgi.city_id = ?', $cityId);
		if ( $this->getWhere() ) $query->where($this->getWhere());
		if ( $this->getIncludeIds() ) $query->where('zgr.child_group_id IN (?)', $this->getIncludeIds());
		if ( $this->getExcludeIds() ) $query->where('zgr.child_group_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getIncludeGroupIds() ) $query->where('zgi.groupUID IN (?)', $this->getIncludeGroupIds());
        if ( $this->getExcludeGroupIds() ) $query->where('(zgi.groupUID is null or zgi.groupUID NOT IN (?))', $this->getExcludeGroupIds());
		if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
			$query->limitPage($this->getCurrentPage(), $this->getListSize());
		}
		if ( $this->getOrder() !== null ) {
			$query->order($this->getOrder());
		}
		if ( $this->isAsAssoc() ) {
			$items = $this->_db->fetchPairs($query);
		} else {
			$result = $this->_db->fetchPairs($query);
            $items = array();
			foreach ( $result as $id=>$type ) $items[] = Warecorp_Group_Factory::loadById($id,$type);
		}
		return $items;
	}

	/**
     * return number of all items by city
     * @param int|Warecorp_Location_City $city
     * @return int count
     * @author Artem Sukharev
     */
	public function getCountByCity($city)
	{
		if ( $city instanceof Warecorp_Location_City ) $cityId = $city->id;
		else $cityId = $city;

		$query = $this->_db->select();
		if ($this->all) {
			$query->distinct();
			$families = $this->getAllFamiliesInTree();
			if (empty($families)) $families = $this->getGroupId();
		} else $families = $this->getGroupId();
		$query->from(array('zgr' => 'zanby_groups__relations'), new Zend_Db_Expr('COUNT(zgr.child_group_id)'));
		$query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgr.child_group_id');
		$query->where('zgr.parent_group_id in (?)', $families)
		->where('zgi.type IN (?)', $this->getTypes())
		->where('zgi.city_id = ?', $cityId);
		if ( $this->getWhere() ) $query->where($this->getWhere());
		if ( $this->getIncludeIds() ) $query->where('zgr.child_group_id IN (?)', $this->getIncludeIds());
		if ( $this->getExcludeIds() ) $query->where('zgr.child_group_id NOT IN (?)', $this->getExcludeIds());
        if ( $this->getIncludeGroupIds() ) $query->where('zgi.groupUID IN (?)', $this->getIncludeGroupIds());
        if ( $this->getExcludeGroupIds() ) $query->where('(zgi.groupUID is null or zgi.groupUID NOT IN (?))', $this->getExcludeGroupIds());
		return $this->_db->fetchOne($query);
	}

	/**
     * Add new child group to family
     * @param int|Warecorp_Group_Base $group
     * @param string $status = pending,active,blocked,deleted
     * @return boolean
     * @author Artem Sukharev
     */
	public function addGroup($group, $status = "pending")
	{
        if (!($group instanceof Warecorp_Group_Base)) $group = Warecorp_Group_Factory::loadById($group);
		if ( !$this->isGroupInFamily($group) ) {
			$data = array();
			$data['parent_group_id']  = $this->getGroupId();
			$data['child_group_id']   = $group->getId();
			$data['status']           = $status;
			$data['join_date']        = new Zend_Db_Expr('NOW()');
			$result = $this->_db->insert('zanby_groups__relations', $data);
			//privileges
			$groupId = $this->_db->quote($group->getId());
			$gid = $this->_db->quote($this->getGroupId());
			$parent_group = ($group->getGroupType() == 'simple')?$gid:$groupId;
			$query_part = ($group->getGroupType() == 'simple')?'zfr.child_id = '.$group->getId():'zfr.child_parent_id = '.$gid;
			$query = 'insert into zanby_groups__privileges_users
							select distinct zfr.family_id, '.$parent_group.', zgpu.tool_type, zgm2.id
							from zanby_family__relations zfr join zanby_groups__privileges_users zgpu on
							(zfr.family_id = zgpu.group_id) join zanby_groups__members zgm1 on
							(zgpu.member_id = zgm1.id) join zanby_groups__members zgm2 on
							(zgm1.user_id = zgm2.user_id)
							where ('.$query_part.') and ((zgm2.group_id = '.$group->getId().')
							or (zgm2.group_id in (select child_id from zanby_family__relations where family_id = '.$groupId.')))';
			$this->_db->query($query);
            /**
             * run stored procedure
             */

            /**
            * @todo Add support of tree-structure when will be added support GF -> GF ability
            */

            /**
             * add group to live regional tree
             */
            $query = $this->_db->select()->from('zanby_groups__hierarchy_relation','hierarchy_id')
                    ->where('hierarchy_type = ?',Warecorp_Group_Hierarchy_Enum::TYPE_LIVE)
                    ->where('group_id = ?',$parent_group);

            $result = $this->_db->fetchCol($query);
            foreach ($result as $id) {
                $hierarchy = Warecorp_Group_Hierarchy_Factory::create($id);
                switch ($hierarchy->getCategoryFocus()) {
                    case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_CANADA:
                        if ($group->getCountry()->id == 38) {
                            $hierarchy->addCustomItem($group,null);
                        }
                        break;
                    case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_USA:
                        if ($group->getCountry()->id == 1) {
                            $hierarchy->addCustomItem($group,null);
                        }
                        break;
                    case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_USA_CANADA:
                        if ($group->getCountry()->id == 1 || $group->getCountry()->id == 38) {
                            $hierarchy->addCustomItem($group,null);
                        }
                        break;
                    default:
                        $hierarchy->addCustomItem($group,null);
                }
            }


            $exec = $this->_db->prepare("CALL refresh_family_relations()");
            $exec->execute();
            if ($this->getFamily()->getGroupUID() !== NULL && $group->getMainGroupUID() === NULL )
            {
                $group->setMainGroupUID($this->getFamily()->getGroupUID());
                $group->save();
            }

            /**
             * clear cache for this family
             */
            $cache = Warecorp_Cache::getFileCache();
            $cache->remove('groups_members_cache_'.$this->getGroupId());
            $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('group_hierarchy_tree_'.$this->getGroupId()));
			return true;
		}
		return false;
	}

	/**
     * remove group from family
     * @param int $groupId
     * @return boolean
     * @author Artem Sukharev
     * @todo implement artifact removing and artifact sharing removing
     * @todo remove moderators from family if they isn't members of family
     * FIXME this method work incorrect! implemnt todos
     */
	public function removeGroup($groupId)
	{
		//privileges
		$table = 'zanby_groups__privileges_users';
		$group = Warecorp_Group_Factory::loadById($groupId);
		$parent_group = ($group->getGroupType() == 'simple')?$this->getGroupId():$groupId;
    	$membersIds = $group->getMembers()->getAllMembersId();

        /* Select for delete subscriptions - members who joined to FG only by this group */
        $fields = $this->_db->quoteInto(' vfu.user_id, SUM(CASE WHEN vfu.group_id=? THEN 0 ELSE 1 END) as cnt', $groupId);
        $sel = $this->_db->select()->from(array('vfu' => 'view_family__users'), $fields)
                                   ->where('family_id = ?',$parent_group)
                                   ->group('vfu.user_id')
                                   ->having('cnt = 0');
        $res = $this->_db->fetchAll($sel);
        foreach($res as $item) {
            $subscription = Warecorp_DiscussionServer_GroupSubscription::findByGroupAndUserId($parent_group, $item['user_id']);
            if(null!==$subscription->getId()) {
                $subscription->delete();
            }
        }

        /**
         * Remove shares from group to Family
         */
        $exec = $this->_db->prepare("CALL delete_shares_to_family({$this->getFamily()->getId()}, {$groupId}, 'group')");
        $exec->execute(); unset($exec);
        /**
         * Remove shares from users to Famaly from current group
         */
        $exec = $this->_db->prepare("CALL update_shares_to_family_every_group_member({$groupId})");
        $exec->execute(); unset($exec);

    	$members = array();
    	foreach($membersIds as $member) {
    		array_push($members, $member['member']);
    	}

		$where = $this->_db->quoteInto('(group_id IN (select distinct family_id from zanby_family__relations where child_id = ?))', $groupId).
                 $this->_db->quoteInto(' AND (parent_group = ?)', $parent_group).
                 $this->_db->quoteInto(' AND (member_id in (?))', (!empty($members)) ? $members : false);
        
		$rows_affected = $this->_db->delete($table, $where);

		/**
         * group relations
         */
		$table = 'zanby_groups__relations';
		$where = array();
		$where[] = $this->_db->quoteInto('parent_group_id = ?', $this->getGroupId());
		$where[] = $this->_db->quoteInto('child_group_id = ?', $groupId);
		$where = join(' AND ', $where);
		$rows_affected = $this->_db->delete($table, $where);

		/**
		 * downgrade account
		 */
		$_groups = $group->getHost()->getGroups()->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))->setMembersRole("host")->getList();
/*        NEVER DOWNGRADE
		$_downgrade = true;
		if (!empty($_groups) && is_array($_groups)) {
		    foreach ($_groups as &$g) {
		        if ($g->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) {
		            $_downgrade = false;
		            break;
		        } elseif ($g->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE && $g->getFamilyGroups()->getCount()) {
		            $_downgrade = false;
		            break;
                }
		    }
		}
		if ($_downgrade) {
            if ( null !== $group->getHost()->getId() ) {
		        $group->getHost()->setMembershipDowngrade( new Zend_Db_Expr('UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL 1 MONTH))') );
		        $group->getHost()->save();
            }
		}
*/

		/**
		 * privileges
		 */
/*
		$query1 = $this->_db->select()->from(array('zfr' => 'zanby_family__relation'), 'zfr.child_id')->where('zfr.family_id = ?',$this->getGroupId());
		$query2 = $this->_db->select()->from(array('zgpu' => 'zanby_groups__privileges_users'), 'zgm.id')
						->join(array('zgm' => 'zanby_group__members'), 'zgpu.member_id = zgm.id')
						->where('zgpu.group_id = ?',$this->getGroupId())
						->where('zgm.group_id = ?', $groupId);
		//$where = array();
		$where = $this->_db->quoteInto('(group_id = ?)', $this->getGroupId()).
							' and ((parent_group not in ('.$query1->__toString().')) or (member_id in ('.$query2->__toString().')))';
		print_r($query1->__toString());
		print_r($query2->__toString());
		print_r($where);
		exit;*/

		/**
		 * remove group from hierarchies of this group
		 */
        $query = $this->_db->select();
        $query->from('zanby_groups__hierarchy_tree', 'id');
        $query->where('type = ?', 'item');
        $query->where('group_id = ?', $groupId);
        $res = $this->_db->fetchCol($query);
        if ( $res ) {
	        $query = $this->_db->select();
	        $query->from('zanby_groups__hierarchy_relation', 'hierarchy_id');
	        $query->where('group_id = ?', $this->getGroupId());
	        $res1 = $this->_db->fetchCol($query);
	        $hIds = array();
	        if ( $res1 ) foreach ( $res1 as $hId ) $hIds[] = $hId;

            $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
            foreach ( $res as $_id ) {
                $parentId = $tree->getParentRoot($_id);
                if ( in_array($parentId, $hIds) ) $tree->remove($_id);
            }
        }

        /**
         * run stored procedure
         */
        $exec = $this->_db->prepare("CALL refresh_family_relations()");
        $exec->execute();

        // unset mainGroupUID for
        if ($this->getFamily()->getGroupUID() !== NULL && $this->getFamily()->getGroupUID() === $group->getMainGroupUID() )
        {
            $group->setMainGroupUID(NULL);
            $group->save();
        }

        /* delete discussions */


        /**
         * clear cache for this family
         */
        $cache = Warecorp_Cache::getFileCache();
        $cache->remove('groups_members_cache_'.$this->getGroupId());
        $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('group_hierarchy_tree_'.$this->getGroupId()));
		return true;
	}

	/**
     * check is group with groupId already in family
     * @param int|Warecorp_Group_Base $groupId
     * @return boolean
     * @author Artem Sukharev
     */
	public function isGroupInFamily($groupId){
		if ($groupId instanceof Warecorp_Group_Base) $groupId = $groupId->getId();
		if ($this->all) {
			$query = $this->_db->select();
			$query->from('zanby_family__relations', new Zend_Db_Expr('COUNT(child_id)'))
			->where('family_id = ?', $this->getGroupId())
			->where('child_id = ?', $groupId);
		} else {
			$query = $this->_db->select();
			$query->from('zanby_groups__relations', new Zend_Db_Expr('COUNT(child_group_id)'))
			->where('parent_group_id = ?', $this->getGroupId())
			->where('child_group_id = ?', $groupId);
		}
		$result = $this->_db->fetchCol($query);
		return (boolean)$result[0];
	}

	/**
     * check if group (or user) is coowner of family group
     * @param $object - object of group_standart or user
     * @return boolean
     * @author Artem Sukharev
     * @author Eugene Halauniou
     * @author Dmitry Kamenka
     */
	public function isCoowner($object)
	{
		if ($object instanceof Warecorp_Group_Standard ){
		    $object = $object->getHost();
		}
		if($object instanceof Warecorp_User){
		    $members = $this->getFamily()->getMembers();
		    return $members->isCohost($object);
		} else throw new Zend_Exception('bad argument for isCoowner. only groupStandart & userStandart allowed ');
	}

	/**
     * set group as coowner for family group
     * @param $object - object of group_standart or user
     * @return boolean
     * @author Artem Sukharev
     * @author Dmitry Kamenka
     */
	public function setAsCoowner($object)
	{
		if ($object instanceof Warecorp_Group_Standard ){
		    $object = $object->getHost();
		}
		if($object instanceof Warecorp_User){
    		$members = $this->getFamily()->getMembers();
    		$members->setAsCohost($object);
		    return true;
		} else throw new Zend_Exception('bad argument for setAsCoowner. only groupStandart & userStandart allowed ');
	}

	/**
     * change group status to 'active'
     * @param int $group_id
     * @return boolean
     * @author Yury Zolotarsky
     */

	public function approveGroup($group)
	{
        if ($group instanceof Warecorp_Group_Standard) $groupId = $group->getId();
        else $groupId = $group;

        $data = array('status' => Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED);
        $where = $this->_db->quoteInto('parent_group_id = ?', $this->getGroupId()).
                 $this->_db->quoteInto(' AND child_group_id = ?', $groupId);
        $this->_db->update('zanby_groups__relations', $data, $where);

        /**
         *  Run trigger for update zanby_family__relations table
         */
        $exec = $this->_db->prepare("CALL refresh_family_relations()");
        $exec->execute();
	}

	/**
     * change group status to 'active'
     * @param int $group_id
     * @return boolean
     * @author Yury Zolotarsky
     */

	public function isGroupExistAndPending($group)
	{
        if ($group instanceof Warecorp_Group_Family) $groupId = $group->getId();
        else $groupId = $group;

		$query = $this->_db->select();
        $query->from(array('zgr' => 'zanby_groups__relations'), 'zgr.child_group_id');
        $query->where('zgr.parent_group_id = ?', $this->getGroupId());
        $query->where('zgr.child_group_id = ?', $groupId);
        $query->where('zgr.status IN (?)', 'pending');
        return (boolean) $this->_db->fetchOne($query);
	}

	/**
     * set group as member for family group
     * @param $object - object of group_standart or user
     * @return boolean
     * @author Dmitry Kamenka
     */
	public function setAsMember($object)
	{
		if ($object instanceof Warecorp_Group_Standard ){
		    $object = $object->getHost();
		}
		if($object instanceof Warecorp_User){
    		$members = $this->getFamily()->getMembers();
    		$members->setAsMember($object);
		    return true;
		} else throw new Zend_Exception('bad argument for setAsMember. only groupStandart & userStandart allowed ');
	}

	/**
	 * return join date for certain member
	 * @param int $memberGroupId - id of group member
	 * @return
	 */
	public function getMemberJoinDate($memberGroupId)
	{
		$query = $this->_db->select();
		$query->from('zanby_groups__relations', 'join_date');
		$query->where('parent_group_id = ?', $this->getGroupId());
		$query->where('child_group_id = ?', $memberGroupId);
		$res = $this->_db->fetchOne($query);
		if ( !$res ) return '0000-00-00';
		else return $res;
	}
}
