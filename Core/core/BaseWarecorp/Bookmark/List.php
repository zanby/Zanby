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
 * @package Warecorp_Bookmark
 * @author Artem Sukharev
 */
class BaseWarecorp_Bookmark_List extends Warecorp_Abstract_List 
{
	/**
	 * status [field 'active']
	 */
	private $_status;
	
	/**
	 * set status
	 * @param array of int status (0|1)
	 * @return Warecorp_Bookmark_List
	 * @author Artem Sukharev
	 */
	public function setStatus($status)
	{
		if ( !is_array($status) ) $status = array($status);
		$this->_status = $status;
		return $this;
	}
	/**
	 * return status
	 * @return array of int (0|1)
	 * @author Artem Sukharev
	 */
	public function getStatus()
	{
		if ( $this->_status === null ) return array(1);
		else return $this->_status;
	}
	
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
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
            $fields[] = ( $this->getAssocKey() === null ) ? 'zbs.id' : $this->getAssocKey();
            $fields[] = ( $this->getAssocValue() === null ) ? 'zbs.name' : $this->getAssocValue();
            $query->from(array('zbs' => 'zanby_bookmark__services'), $fields);  
        } else {
            $query->from(array('zbs' => 'zanby_bookmark__services'), 'zbs.id');
        }
        $query->where('active IN (?)', $this->getStatus());
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
            foreach ( $items as &$item ) $item = new Warecorp_Bookmark_Item($item);
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
        $query->from(array('zbs' => 'zanby_bookmark__services'), new Zend_Db_Expr('COUNT(zbs.id)'));
        $query->where('active IN (?)', $this->getStatus());
        return $this->_db->fetchOne($query);
    }
}
