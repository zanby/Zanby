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
 *
 * @package    Warecorp_Category
 * @copyright  Copyright (c) 2006
 * @author Dmitry Kostikov
 */

/**
 * group category class
 *
 */
class BaseWarecorp_Venue_CategoryList extends Warecorp_Abstract_List
{
    private $_type;

    /**
     * sets venue type
     *
     * @param string $newVal
     * @return void
     * @author Eugene Kirdzei
     */
    public function setType($newVal)
    {
        if (!Warecorp_Venue_Enum_VenueType::isIn($newVal)) {
            $newVal = null;
        }
        $this->_type = $newVal;
    }
             
    /**
     * return type
     *
     * @return string
     * @author Eugene Kirdzei
     */
    public function getType()
    {
        if (null === $this->_type) $this->setType(null);
        return $this->_type;
    }
    
    /**
     * return venue categories list
     *
     * @return array
     * @author Eugene Kirdzei
     */
    public function getList()
    {
        $query = $this->_db->select();
        if ( $this->isAsAssoc() ) {
            $fields = array();
            $fields[] = ( $this->getAssocKey() === null ) ? 'id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'name' : $this->getAssocValue();
            $query->from('zanby_event__venue_categories', $fields);  
        } else {
            $query->from('zanby_event__venue_categories', 'id');
        }
        if ( $this->getWhere() ) $query->where( $this->getWhere() );
        
        if ($this->getType()) {
            $query->where('type = ?', $this->getType());
        }
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
            foreach ( $items as &$item ) $item = new Warecorp_Venue_Category($item);
        }
        return $items;        
    }    
    
    public function getCount()
    {
    	throw new Zend_Exception('Incorrect method');
    }
}
?>
