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
 * @package Warecorp_User_Group
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */
class BaseWarecorp_User_Group_List extends Warecorp_Abstract_List 
{
	/**
	 * user Id
	 */
    private $_userId;
    
    /**
     * group types for select
     */
    private $_types;
    
    /**
     * members status for select
     */
    protected $_membersStatus;
    
    /**
     * members role for select
     */
    protected $_membersRole;
    
    /**
     * return pairs id=>type instead id=>name
     */
    private $_returnTypes = false;
    
    /**
     * 
     */
    public function setReturnTypes()
    {
        $this->_returnTypes = true;
        return $this;
    }
    
    /**
     * 
     */
    public function getReturnTypes()
    {
        return $this->_returnTypes;
    }
    
    /**
     * set user id
     * @param int $newVal
     * @return Warecorp_User_Group_List
     * @author Artem Sukharev
     */
    public function setUserId($newVal)
    {
    	$this->_userId = $newVal;
    	return $this;
    }
    
    /**
     * return user id
     * @return int
     * @author Artem Sukharev
     */
    public function getUserId()
    {
    	return $this->_userId;
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
     * set member status
     * @param array|string|string_delimiter_by_; $newVal
     * @return Warecorp_User_Group_List
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
     * return member status
     * @return array
     * @author Artem Sukharev
     */
    public function getMembersStatus()
    {
        if ( $this->_membersStatus === null ) $this->setMembersStatus(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_APPROVED);
        return $this->_membersStatus;
    }
    
    /**
     * set member role
     * @param array|string|string_delimiter_by_; $newVal
     * @return Warecorp_User_Group_List
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
     * return member role
     * @return array
     * @author Artem Sukharev
     */
    public function getMembersRole()
    {
        if ( $this->_membersRole === null ) $this->_membersRole = array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST);
        return $this->_membersRole;
    }
    
    /**
     * set name of db field for assoc array key
     * @param string $fieldName
     * @return Zend_Exception
     * @author Artem Sukharev
     */
    public function setAssocKey($fieldName)
    {
        throw new Zend_Exception('This method is disabled in class Warecorp_User_Group_List');
    }
    
    /**
     * set name of db field for assoc array value
     * @param string $fieldName
     * @return Zend_Exception
     * @author Artem Sukharev
     */
    public function setAssocValue($fieldName)
    {
        throw new Zend_Exception('This method is disabled in class Warecorp_User_Group_List');
    }
    
    /**
	 * Constructor
	 * @param int $userId
	 * @author Artem Sukharev
	 */
    public function __construct($userId)
	{
		parent::__construct();
		$this->_userId = $userId;
	}
	
    /**
     *  return list of all items
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getList()
    {       
        $query = array();
        if (in_array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, $this->getTypes())) {
            $sub_query = "
                    SELECT zgi.id as id, zgi.name as name, '".Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE ."' as type
                    FROM zanby_groups__members as zgm
                    INNER JOIN zanby_groups__items as zgi ON zgi.id = zgm.group_id
                    WHERE
                        " . $this->_db->quoteInto('zgm.user_id = ?', $this->getUserId()) . " AND 
                        " . $this->_db->quoteInto('zgm.status IN (?)', $this->getMembersRole()) . " AND 
                        " . $this->_db->quoteInto('zgm.is_approved IN (?)', $this->getMembersStatus()) . " AND
                        zgi.type = '" . Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE . "'
                ";
            if ( $this->getIncludeIds() ) $sub_query .= " AND " . $this->_db->quoteInto('zgi.id IN (?)', $this->getIncludeIds());
            if ( $this->getExcludeIds() ) $sub_query .= " AND " . $this->_db->quoteInto('zgi.id NOT IN (?)', $this->getExcludeIds());
            if ( $this->getWhere() ) $sub_query .= " AND " . $this->getWhere();
            $query[] = $sub_query;
        }
        if (in_array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY, $this->getTypes())) {
            
            $userRoles = array();
            if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST, $this->getMembersRole())  ) $userRoles[] = 1;
            if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST, $this->getMembersRole())) $userRoles[] = 2;
            if ( in_array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_MEMBER, $this->getMembersRole())) $userRoles[] = 0;
            $sub_query = "
                    SELECT DISTINCT zgi.id as id, zgi.name as name, '".Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY ."' as type
                    FROM view_family__users as vfu 
                    INNER JOIN zanby_groups__items as zgi ON zgi.id = vfu.family_id
                    WHERE
                        " . $this->_db->quoteInto('vfu.user_id = ?', $this->getUserId()) . " AND
                        " . $this->_db->quoteInto('vfu.family_owner IN (?)',  $userRoles) . " AND 
                        " . $this->_db->quoteInto('vfu.is_approved IN (?)', $this->getMembersStatus()) . " AND
                        zgi.type = '" . Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY . "'
                ";             
            if ( $this->getIncludeIds() ) $sub_query .= " AND " . $this->_db->quoteInto('zgi.id IN (?)', $this->getIncludeIds());
            if ( $this->getExcludeIds() ) $sub_query .= " AND " . $this->_db->quoteInto('zgi.id NOT IN (?)', $this->getExcludeIds());
            if ( $this->getWhere() ) $sub_query .= " AND " . $this->getWhere();
            $query[] = $sub_query;
        }
        
        if (sizeof($query) == 0) return array();
        $query = join(" UNION ", $query);
                
        if ($this->getOrder() !== null) $query .= "ORDER BY " . $this->getOrder(); 
        else $query .= " ORDER BY name";
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            //$query .= " LIMIT ".(($this->getCurrentPage()-1)*$this->getListSize()).", ".(($this->getCurrentPage()-1)*$this->getListSize()+$this->getListSize())."";
            $query .= " LIMIT ".(($this->getCurrentPage()-1)*$this->getListSize()).", ".$this->getListSize()."";
        }

        $result = $this->_db->query($query);
        $groups = $result->fetchAll();
        $return = array(); 
        
    	if ( $this->isAsAssoc() ) {
	        if ( sizeof($groups) != 0 ) foreach ( $groups as $group ) $return[$group['id']] = $this->getReturnTypes() ? $group['type'] : $group['name'];
	        return $return; 
    	} else {
            //Array of id for simple groups and family
            foreach ($groups as $group) {
                if ($group['type'] == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE ||
                    $group['type'] == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) {
                    $return[] = Warecorp_Group_Factory::loadById($group['id'],$group['type']);
                }
            }
            return $return; 
    	}
    }

    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev
     */
    public function getCount()
    {
        $count = 0;
        if (in_array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, $this->getTypes())) {
            $sub_query = "
                    SELECT COUNT(zgi.id) as count
                    FROM zanby_groups__members as zgm
                    INNER JOIN zanby_groups__items as zgi ON zgi.id = zgm.group_id
                    WHERE
                        " . $this->_db->quoteInto('zgm.user_id = ?', $this->getUserId()) . " AND 
                        " . $this->_db->quoteInto('zgm.status IN (?)', $this->getMembersRole()) . " AND 
                        " . $this->_db->quoteInto('zgm.is_approved IN (?)', $this->getMembersStatus()) . " AND
                        zgi.type = '" . Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE . "'
                ";
            if ( $this->getIncludeIds() ) $sub_query .= " AND " . $this->_db->quoteInto('zgi.id IN (?)', $this->getIncludeIds());
            if ( $this->getExcludeIds() ) $sub_query .= " AND " . $this->_db->quoteInto('zgi.id NOT IN (?)', $this->getExcludeIds());
            if ( $this->getWhere() ) $sub_query .= " AND " . $this->getWhere();
	        $result = $this->_db->query($sub_query);
	        $_count = $result->fetchColumn(0);
	        $count = $count + $_count;
        }
        if (in_array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY, $this->getTypes())) {
            $sub_query = "
                    SELECT COUNT(DISTINCT zgi.id) as count
                    FROM view_family__users as vfu 
                    INNER JOIN zanby_groups__members as zgm ON zgm.user_id = vfu.user_id AND zgm.group_id = vfu.family_id
                    INNER JOIN zanby_groups__items as zgi ON zgi.id = vfu.family_id
                    WHERE
                        " . $this->_db->quoteInto('vfu.user_id = ?', $this->getUserId()) . " AND
                        " . $this->_db->quoteInto('zgm.status IN (?)', $this->getMembersRole()) . " AND 
                        " . $this->_db->quoteInto('vfu.is_approved IN (?)', $this->getMembersStatus()) . " AND
                        zgi.type = '" . Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY . "'
                ";
            if ( $this->getIncludeIds() ) $sub_query .= " AND " . $this->_db->quoteInto('zgi.id IN (?)', $this->getIncludeIds());
            if ( $this->getExcludeIds() ) $sub_query .= " AND " . $this->_db->quoteInto('zgi.id NOT IN (?)', $this->getExcludeIds());
            if ( $this->getWhere() ) $sub_query .= " AND " . $this->getWhere();
            $result = $this->_db->query($sub_query);
            $_count = $result->fetchColumn(0);
            $count = $count + $_count;
        }
        return $count;
    }	
}
