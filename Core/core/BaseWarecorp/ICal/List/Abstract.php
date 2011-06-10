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
 * Enter description here...
 */
abstract class BaseWarecorp_ICal_List_Abstract
{
	/**
	 * current page value
	 */
	protected $_page;
	
	/**
	 * number of items per page
	 */
	protected $_size;

	/**
	 * order string
	 */
	protected $_order;
	
	/**
	 * mode fetching results
	 */
	protected $_fetchMode;
	
	/**
	 * data base field name used as key for returned pairs array
	 */
	protected $_pairsModeKey;
	
	/**
	 * data base field name used as value for returned pairs array 
	 */
	protected $_pairsModeValue;
	
	/**
	 * data base fields for assoc select
	 */
	protected $_assocFields;
	
	/**
	 * @return int
	 */
	public function getPage ()
	{
		return $this->_page;
	}
	
	/**
	 * @param int $_page
	 * @return Warecorp_ICal_List_Abstract
	 */
	public function setPage ($_page)
	{
		if ( floor($_page) > 0 ) $this->_page = floor($_page);
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getSize ()
	{
		return $this->_size;
	}

	/**
	 * @param int $_size
	 * @return Warecorp_ICal_List_Abstract
	 */
	public function setSize ($_size)
	{
		if ( floor($_size) > 0 ) $this->_size = floor($_size);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOrder ()
	{
		return $this->_order;
	}
	
	/**
	 * @param string $_order
	 * @return Warecorp_ICal_List_Abstract
	 */
	public function setOrder ($_order)
	{
		$this->_order = $_order;
		return $this;
	}
	
	/**
	 * @return string from enum Warecorp_ICal_List_Enum_FetchMode
	 */
	public function getFetchMode ()
	{
		if ( $this->_fetchMode === null ) $this->_fetchMode = Warecorp_ICal_List_Enum_FetchMode::ASSOC;
		return $this->_fetchMode;
	}
	
	/**
	 * @param string from enum $_fetchMode - value from Warecorp_ICal_List_Enum_FetchMode
	 * @return Warecorp_ICal_List_Abstract
	 */
	public function setFetchMode ($_fetchMode)
	{
		if ( Warecorp_ICal_List_Enum_FetchMode::in_enum($_fetchMode) ) $this->_fetchMode = $_fetchMode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPairsModeKey()
	{
		if ( $this->_pairsModeKey === null ) throw new Warecorp_Exception('Pairs Key Field is not set.');
		return $this->_pairsModeKey;
	}
	
	/**
	 * @param string $_dataBaseFieldName
	 * @return Warecorp_ICal_List_Abstract
	 */
	public function setPairsModeKey($_dataBaseFieldName)
	{
		$this->_pairsModeKey = $_dataBaseFieldName;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getPairsModeValue()
	{
		if ( $this->_pairsModeValue === null ) throw new Warecorp_Exception('Pairs Value Field is not set.');
		return $this->_pairsModeValue;
	}
	
	/**
	 * @param string $_dataBaseFieldName
	 * @return Warecorp_ICal_List_Abstract
	 */
	public function setPairsModeValue($_dataBaseFieldName)
	{
		$this->_pairsModeValue = $_dataBaseFieldName;
		return $this;
	}
	
	/**
	 * @return array of string
	 */
	public function getAssocFields()
	{
		if ( $this->_assocFields === null ) $this->_assocFields = array('*');
		return $this->_assocFields;
	}
	
	/**
	 * set data base fields for assoc mode select
	 * @param array of string
	 */
	public function setAssocFields($_fieldsArray)
	{
		$this->_assocFields = $_fieldsArray;
		return $this;
	}
	/**
	 * Constructor
	 * @param Zend_Db_Table_Abstract $Connection - database connection object
	 */
	public function __construct()
	{
		$this->DbConn = Zend_Registry::get('DB');
		if ( $this->DbConn === null ) throw new Warecorp_Exception('Database connection is not set.');    	
	}
	
}
