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
 * @package Warecorp_Group_Standard_FamilyGroup
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */
class BaseWarecorp_Group_Standard_FamilyGroup_List extends Warecorp_Abstract_List 
{
    /**
     * group id
     */
    private $_groupId;
    
    /**
     * group status in family, value from Warecorp_Group_Enum_GroupStatus
     */
    private $_status;
    
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
     * set group status in family
     * @param array|string|string_delimiter_by_; $newVal
     * @return Warecorp_Group_Standard_FamilyGroup_List
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
     * Constructor
     * @param int $groupId
     */
    public function __construct($groupId)
    {
        parent::__construct();
        $this->_groupId = $groupId;
    }
    
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
            $fields[] = ( $this->getAssocKey() === null ) ? 'zfr.family_id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zgi.name' : $this->getAssocValue();
            $query->from(array('zfr' => 'zanby_family__relations'), $fields);
            
        } else {
            $query->from(array('zfr' => 'zanby_family__relations'), 'zfr.family_id');
        }

        $query->join(array('zgi' => 'zanby_groups__items'), 'zgi.id = zfr.family_id',array('type'=>'zgi.type'));
        $query->where('zfr.child_id = ?', $this->getGroupId());
        $query->where('zfr.group_status IN (?)', $this->getStatus());
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('zfr.family_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zfr.family_id NOT IN (?)', $this->getExcludeIds());
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
        $query->from(array('zfr' => 'zanby_family__relations'), new Zend_Db_Expr('COUNT(zfr.family_id)'));
        $query->where('zfr.child_id = ?', $this->getGroupId());
        $query->where('zfr.group_status IN (?)', $this->getStatus());
        if ( $this->getWhere() ) $query->where($this->getWhere());
        if ( $this->getIncludeIds() ) $query->where('zfr.family_id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zfr.family_id NOT IN (?)', $this->getExcludeIds());
        return $this->_db->fetchOne($query);
    }
}
