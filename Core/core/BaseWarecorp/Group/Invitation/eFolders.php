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
 * Перечисление возможных папок для системы приглашений
 * @author Andrew Peresalyak
 * @version 1.0
 * @created 24-Jul-2007 13:48:31
 */
class BaseWarecorp_Group_Invitation_eFolders
{
	const SENT         = 1;
	const DRAFT        = 2;
	
	static function toInteger($var){	    
	    switch (strtolower($var)){
	        case 'sent':  return Warecorp_Group_Invitation_eFolders::SENT;
	        case 'draft': return Warecorp_Group_Invitation_eFolders::DRAFT;
	        default: throw new Zend_Exception('Wrong parametr \'' . $val . '\'.');
	    }
	}
	
	static function toString($var){
	    switch ($var){
	        case Warecorp_Group_Invitation_eFolders::SENT:  return 'sent';
	        case Warecorp_Group_Invitation_eFolders::DRAFT: return 'draft';
	        default: throw new Zend_Exception('Wrong parametr \'' . $val . '\'.');
	    }
	}
}
