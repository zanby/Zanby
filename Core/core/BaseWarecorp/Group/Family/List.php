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
 * List class for family groups
 * @author Dmitry Kamenka
 *
 */
class BaseWarecorp_Group_Family_List extends Warecorp_Abstract_List {
    private $childTypes;
    private $childStatus;
    
    /**
     * set group types, that will count as number of child groups in family
     * @param array|string|string_delimiter_by_; $newVal from Warecorp_Group_Enum_GroupType
     * @return Warecorp_Group_Family_List
     */
    public function setChildTypes($newVal)
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
        $this->childTypes = $newVal;
        return $this;
    }

    /**
     * get child group types, that will count as number of child groups in family
     * @return array
     */
    public function getChildTypes()
    {
        if ( $this->childTypes === null ) $this->childTypes = array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY );
        return $this->childTypes;
    }    
    

    /**
     * set child group status, that will count as number of child groups in family
     * @param array|string|string_delimiter_by_; $newVal from Warecorp_Group_Enum_GroupStatus
     * @return Warecorp_Group_Family_List
     */
    public function setChildStatus($newVal)
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

        $this->childStatus = $newVal;
        return $this;
    }

    /**
     * return child group status, that will count as number of child groups in family
     * @return array
     */
    public function getChildStatus()
    {
        if ( $this->childStatus === null ) $this->setStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED);
        return $this->childStatus;
    }
    
    
    public function getList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zgi.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zgi.name' : $this->getAssocValue();
            $query->from(array('zgi' => 'zanby_groups__items'), $fields);
        } else {
            $query->from(array('zgi' => 'zanby_groups__items'));
            $query->joinLeft(array('zgr' => 'zanby_groups__relations'), $this->_db->quoteInto('zgi.id = zgr.parent_group_id AND zgr.status IN (?)', $this->getChildStatus()));
            $query->joinLeft(array('zgi1' => 'zanby_groups__items'), $this->_db->quoteInto('zgr.child_group_id = zgi1.id AND zgi1.type IN (?)', $this->getChildTypes()), array('child_groups_cnt' => new Zend_Db_Expr('COUNT(zgi1.id)')));
            $query->group('zgi.id');
        }
        
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgi.type = ?', Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        
        if ( $this->getIncludeIds() ) $query->where('zgi.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgi.id NOT IN (?)', $this->getExcludeIds());
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        
        $return = array();
        if ( $this->isAsAssoc() ) {
            $return = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchAll($query);
            foreach ( $items as $item ) {
                $group = Warecorp_Group_Factory::loadById($item['id'], Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
                $group->setGroupsInFamilyCount($item['child_groups_cnt']);
                $return[] = $group;
            }
        }
        return $return;
    }

    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('zgi' => 'zanby_groups__items'), new Zend_Db_Expr('COUNT(zgi.id)'));
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where("zgi.type = ?", Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        if ( $this->getIncludeIds() ) $query->where('zgi.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgi.id NOT IN (?)', $this->getExcludeIds());
        return $this->_db->fetchOne($query);
    }
    
}
