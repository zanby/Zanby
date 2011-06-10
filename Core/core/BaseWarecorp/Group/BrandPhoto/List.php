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
 * @package    Warecorp_Group_BrandPhoto
 * @copyright  Copyright (c) 2007
 * @author Evgeny Kirdzei
 */
class BaseWarecorp_Group_BrandPhoto_List extends Warecorp_Abstract_List 
{
    private $groupId;
    
    /**
     * Class constructor
     *
     * @param int $groupId
     * @author Eugene Kirdzei
     */
    public function __construct($groupId)
    {
    	parent::__construct();
    	if ( null !== $groupId) $this->setGroupId($groupId);
    }
    /**
     * set group id for search
     *
     * @param int $newVal
     * @return self
     * @author Eugene Kirdzei
     */
    public function setGroupId($newVal)
    {
    	$this->groupId = $newVal;
    	return $this;
    }
    
    /**
     * return group id for search
     *
     * @return int
     * @author Eugene Kirdzei
     */
    public function getGroupId()
    {
    	return $this->groupId;
    }  
	
	/**
     * return list of brand photos
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
            $fields[] = ( $this->getAssocValue() === null ) ? 'id' : $this->getAssocValue();
            $query->from('zanby_groups__brand_galleries', $fields);  
        } else {
            $query->from('zanby_groups__brand_galleries');
        }
        $query->where('group_id = ?', $this->getGroupId());
        if ( $this->getWhere() ) $query->where( $this->getWhere() );
        
        if ( $this->getCurrentPage() !== null && $this->getListSize() !== null ) {
            $query->limitPage($this->getCurrentPage(), $this->getListSize());
        }
        if ( $this->getOrder() !== null ) {
            $query->order($this->getOrder());
        }
        $items = array();
        if ( $this->isAsAssoc() ) {
            $items = $this->_db->fetchPairs($query);
        } else {
            $items = $this->_db->fetchCol($query);
            foreach ( $items as &$item ) $item = new Warecorp_Group_BrandPhoto_Item($item);
        }
        return $items;        	
	}

    /**
     * return number of all items
     * @return int count
     * @author Eugene Kirdzei
     */   
    public function getCount()
    {
        $query = $this->_db->select();
        $query->from('zanby_groups__brand_galleries', new Zend_Db_Expr('COUNT(id)'))
              ->where('group_id = ?', $this->getGroupId());
        if ( $this->getWhere() ) $query->where($this->getWhere());
        return $this->_db->fetchOne($query);
    }	
	
}

?>
