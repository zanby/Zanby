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
 * @package Warecorp_Group
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */
class BaseWarecorp_Group_List extends Warecorp_Abstract_List
{
	/**
	 * group types for select
	 */
	private $_types;
	private $_privates; // array
	
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
     *  return list of all items
     *  @return array of objects
     *  @author Artem Sukharev
     */
    public function getList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'zgi.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zgi.name' : $this->getAssocValue();
            $query->from(array('zgi' => 'zanby_groups__items'), $fields);  
        } else {
            $query->from(array('zgi' => 'zanby_groups__items'), array('id'=>'zgi.id','type'=>'zgi.type'));
        }
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgi.type IN (?)', $this->getTypes());
        
        if ( $this->getPrivate() !== NULL ) $query->where('zgi.private IN (?)', $this->getPrivate());
        
        if ( $this->getIncludeIds() ) $query->where('zgi.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgi.id NOT IN (?)', $this->getExcludeIds());
        
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
        $query->from(array('zgi' => 'zanby_groups__items'), new Zend_Db_Expr('COUNT(zgi.id)'));
        if ( $this->getWhere() ) $query->where($this->getWhere());
        $query->where('zgi.type IN (?)', $this->getTypes());
        if ( $this->getPrivate() !== NULL ) $query->where('zgi.private IN (?)', $this->getPrivate());
        if ( $this->getIncludeIds() ) $query->where('zgi.id IN (?)', $this->getIncludeIds());
        if ( $this->getExcludeIds() ) $query->where('zgi.id NOT IN (?)', $this->getExcludeIds());
        return $this->_db->fetchOne($query);
    }
    
    /**
     * Return count of group in city and in category
     * @param int $city_id
     * @param int $category_id
     * @return int
     * @author Artem Sukharev
     */
    public function countByCityAndCaterory( $city_id, $category_id )
    {
        $query = $this->_db->select();
        $query->from(array('zgi' => 'zanby_groups__items'), new Zend_Db_Expr('count(zgi.id)'));
        $query->where('zgi.city_id =? ', $city_id);
        $query->where('zgi.type IN (?)', $this->getTypes());
        if ( $this->getPrivate() !== NULL ) $query->where('zgi.private IN (?)', $this->getPrivate());
        $query->where('zgi.category_id =?', $category_id);
        $res = $this->_db->fetchOne($query);
        return $res;
    }
    
    public function findByEmail($email)
    {
        $email = preg_replace('/@'.DOMAIN_FOR_GROUP_EMAIL.'$/mi', '', $email);

        $query = $this->_db->select()->from(array('zgi'=>'zanby_groups__items'),array('zgi.id','zgi.type'))->where('zgi.group_path = ?',$email);
        $result = $this->_db->query($query);
        $row = $result->fetchRow();
        if ( sizeof($row) == null ) return null;
                       
        return Warecorp_Group_Factory::loadById($row['id'],$row['type']);
    }
    
    /**
     * set private types
     * @param array|string|string_delimiter_by_; $newVal
     * @return Warecorp_Group_List
     * @version minimum core 1.000.2
     * @author Andrey Kondratiev Sukharev
     */    
    public function setPrivate($newVal) {
        if ( is_array($newVal) ) {
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_GroupPrivacy::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect group type');
                }
            }
        } elseif ( strpos($newVal, ';') ) {
            $newVal = explode(';', $newVal);
            foreach ($newVal as &$_value) {
                $_value = trim($_value);
                if ( !Warecorp_Group_Enum_GroupPrivacy::isIn($_value) ) {
                   throw new Zend_Exception('Incorrect privacy');
                }
            }
        } else {
            if ( !Warecorp_Group_Enum_GroupPrivacy::isIn($newVal) ) {
                throw new Zend_Exception('Incorrect privacy');
            }
            $newVal = array($newVal);
        }
        $this->_privates = $newVal;
        return $this;    	
    }
    
    /**
     * get private types
     * @author Andrey Kondratiev
     * @version minimum core 1.000.2
     * @return array|NULL
     */
    public function getPrivate() {
        //if ( $this->_privates === null ) $this->_privates = array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY );
        return $this->_privates;    	
    }    
    
}
?>
