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
 * Перечисление возможных заголовков, по которым можно проводить сортировку
 * @author Andrew Peresalyak
 * @version 1.0
 * @created 03-Sep-2007 16:30:32
 */
class BaseWarecorp_Group_Invitation_eHeaders
{
	const GROUP_NAME        = 1;
	const CREATION_DATE     = 2;
    const STATUS            = 3;
	const INVITATION_NAME   = 4;
	protected static $allowedFields = array('name', 'creation_date', 'status', 'subject');
	protected static $allowedHeaders = array('group_name', 'creation_date', 'status', 'subject');
	
	static function toInteger($var)
	{
	    switch (strtolower($var)){
	        case 'group_name': return Warecorp_Group_Invitation_eHeaders::GROUP_NAME;
	        default: throw new Zend_Exception('Wrong parametr \'' . $val . '\'.');
	    }
	}
	
	static function toString($var)
	{
	    switch ($var) {
	        case Warecorp_Group_Invitation_eHeaders::GROUP_NAME: return 'group_name';
	        default: throw new Zend_Exception('Wrong parametr \'' . $val . '\'.');
	    }
	}
	
	static function isAllowed($string)
	{
	    return in_array(strtolower($string), self::$allowedFields);
	}
	
	static function getAllowedHeaders()
	{
	    return self::$allowedHeaders;
	}
}
