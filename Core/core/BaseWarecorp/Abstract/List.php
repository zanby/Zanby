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
 * @package Warecorp_Abstract
 * @author Artem Sukharev
 * @copyright  Copyright (c) 2006
 */
abstract class BaseWarecorp_Abstract_List 
{
	/**
	 * DB Connection object
	 */
	protected $_db;
	/**
	 * Current Page for list
	 */
	protected $_currentPage;
	/**
	 * number of items per page
	 */
	protected $_listSize;
	/**
	 * order string for results
	 */
	protected $_order;
	/**
	 * return results as assoc
	 */
	protected $_asAssoc = false;
	
	/**
	 * name of db field for assoc array key
	 */
	protected $_assocKey;
	
	/**
	 * name of db field for assoc array value
	 */
	protected $_assocValue;
	
	/**
	 * additional where for select
	 */
	protected $_where;
	
	 /**
     * ids of items for include
     */
    protected $_includeIds;
    
    /**
     * ids of items for exclude
     */
    protected $_excludeIds;
	
	/**
	 * Constructor
	 * @return void
	 * @author Artem Sukharev
	 */
	public function  __construct()
	{
		$this->_db = Zend_Registry::get("DB");
	}
	
    /**
     * return current page
     * @return int current page
     * @author Artem Sukharev
     */
    public function getCurrentPage()
    {
        return $this->_currentPage;
    }
    
    /**
     * set current page
     * @param int page - begin from 1
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function setCurrentPage($page)
    {
    	if ( !is_numeric($page) || $page < 1 ) $page = 1;
        $this->_currentPage = $page;
        return $this;
    }
	
    /**
     * return number of items per page
     * @author Artem Sukharev
     */
    public function getListSize()
    {
        return $this->_listSize;
    }

    /**
     * set number of items per page
     * @param int $size
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function setListSize($size)
    {
    	if ( !is_numeric($size) || $size < 1 ) $size = 1;
        $this->_listSize = $size;
        return $this;
    }

    /**
     * return order string
     * @author Artem Sukharev
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * set order string
     * overlay in child classes for validate possible value
     * @param string $order
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function setOrder($order)
    {
        $this->_order = $order;
        return $this;
    }
    
    /**
     * set assoc mode
     * @param boolean $mode
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function returnAsAssoc($mode = true)
    {
    	$this->_asAssoc = (boolean) $mode;
    	return $this;
    }

    /**
     * return assoc mode
     * @return boolean
     * @author Artem Sukharev
     */
    public function isAsAssoc()
    {
    	return (boolean) $this->_asAssoc;
    }
    
    /**
     * set name of db field for assoc array key
     * @param string $fieldName
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function setAssocKey($fieldName)
    {
    	$this->_assocKey = $fieldName;
    	return $this;
    }

    /**
     * set name of db field for assoc array key
     * @author Artem Sukharev
     */
    public function getAssocKey()
    {
    	return $this->_assocKey;    	
    }
    
    /**
     * set name of db field for assoc array value
     * @param string $fieldName
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function setAssocValue($fieldName)
    {
        $this->_assocValue = $fieldName;
        return $this;
    }

    /**
     * set name of db field for assoc array value
     * @author Artem Sukharev
     */
    public function getAssocValue()
    {
        return $this->_assocValue;
    }
    
    /**
     * return additional where
     * @return string
     * @author Artem Sukharev
     */
    public function getWhere()
    {
        if ( $this->_where === null ) return '';
        else return join(' ', $this->_where); 
    }
    
    /**
     * add where as AND
     * @param string $cond
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function addWhere($cond)
    {
        if (func_num_args() > 1) {
            $val = func_get_arg(1);
            $cond = $this->_db->quoteInto($cond, $val);
        }
        if ($this->_where) {
            $this->_where[] = 'AND ' . $cond;
        } else {
            $this->_where[] = $cond;
        }
        return $this;
    }
    
    /**
     * add where as OR
     * @param string $cond
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function addWhereOr($cond)
    {
        if (func_num_args() > 1) {
            $val = func_get_arg(1);
            $cond = $this->_db->quoteInto($cond, $val);
        }
        if ($this->_where) {
            $this->_where[] = 'OR ' . $cond;
        } else {
            $this->_where[] = $cond;
        }
        return $this;
    }
    
    /**
     * remove all aditional where for select
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function clearWhere()
    {
    	$this->_where = null;
    }
    
    /**
     * set include Ids
     * @param array $newVal - ids of items
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */    
    public function setIncludeIds($newVal)
    {
    	if ( !is_array($newVal) ) $newVal = array($newVal);
    	$this->_includeIds = $newVal;
    	return $this;
    }
    
    /**
     * return include ids
     * @return array
     * @author Artem Sukharev
     */
    public function getIncludeIds()
    {
    	return $this->_includeIds;
    }

    /**
     * set exclude Ids
     * @param array $newVal - ids of items
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */    
    public function setExcludeIds($newVal)
    {
        if ( !is_array($newVal) ) $newVal = array($newVal);
        $this->_excludeIds = $newVal;
        return $this;
    }
    
    /**
     * return exclude ids
     * @return array
     * @author Artem Sukharev
     */
    public function getExcludeIds()
    {
        return $this->_excludeIds;
    }
    
    /**
     * reset all params of list
     * @return Warecorp_Abstract_List
     * @author Artem Sukharev
     */
    public function resetList()
    {
        $this->_currentPage    = null;
        $this->_listSize       = null;
        $this->_asAssoc        = false;
        $this->_assocKey       = null;
        $this->_assocValue     = null;
        $this->_excludeIds     = null;
        $this->_includeIds     = null;
        $this->_listSize       = null;
        $this->_membersRole    = null;
        $this->_membersStatus  = null;
        $this->_order          = null;
        $this->clearWhere();
        return $this;
    }
    
    /**
     *  return list of all items
     *  @return array of objects
     *  @author Artem Sukharev
     */
    abstract public function getList();
    
    /**
     * return number of all items
     * @return int count
     * @author Artem Sukharev
     */
    abstract public function getCount();
}
