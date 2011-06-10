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
 * @package    Warecorp_Group_Category
 * @copyright  Copyright (c) 2007
 * @author Artem Sukharev
 */
class BaseWarecorp_Group_Category_List extends Warecorp_Abstract_List 
{
    
    private $relation='all';
    
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
            $fields[] = ( $this->getAssocKey() === null ) ? 'zgc.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zgc.name' : $this->getAssocValue();
            $query->from(array('zgc' => 'zanby_groups__categories'), $fields);  
        } else {
            $query->from(array('zgc' => 'zanby_groups__categories'), 'zgc.id');
        }
        if ( $this->getWhere() ) $query->where($this->getWhere());
        // relation filter
        if ( $this->getRelation()!=='all') $query->where('FIND_IN_SET(?,zgc.relation)>0',$this->getRelation());
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = new Warecorp_Group_Category($item);
        }
        return $items;
    }

    public function setRelation($newValue)
    {
        if(in_array($newValue,array('all','simple','family'))) {
            $this->relation = $newValue;
            return $this;
        } else {
            return false;
        }
    }
    public function getRelation()
    {
        return $this->relation;
    }
    
    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev
     */
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from(array('zgc' => 'zanby_groups__categories'), new Zend_Db_Expr('COUNT(zgc.id)'));
        if ( $this->getWhere() ) $query->where($this->getWhere());
        // relation filter
        if ( $this->getRelation()!=='all') $query->where('FIND_IN_SET(?,zgc.relation)>0;',$this->getRelation());
        
        return $this->_db->fetchOne($query);
    }
}
