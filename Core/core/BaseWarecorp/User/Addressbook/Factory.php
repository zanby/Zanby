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
 * @author Dmitry Strupinsky, Andrew Peresalyak, Ivan Khmurchik
 * @version 1.0
 * @created 25-сен-2007 16:23:44
 */
class BaseWarecorp_User_Addressbook_Factory
{

    /**
     * load contact object dependent type
     * 
     * @param contactId
     */
    public static function loadById($contactId, $entity_id = 0)
    {
    	if ($contactId == 0 && $entity_id != 0) {
        	return new Warecorp_User_Addressbook_Group($entity_id);
        }
    	$list = new  Warecorp_User_Addressbook_List();
        $className = $list->getClassNameById($contactId);        
        $class = Warecorp_User_Addressbook_eType::translate($className);
//      $abstract = new Warecorp_User_Addressbook_Abstract('id', $contactId);
//      $class = Warecorp_User_Addressbook_eType::translate($abstract->getClassName());
        if ($class == 'Warecorp_User_Addressbook_ContactList') return new $class(true, 'id', $contactId);
        return new $class('id', $contactId);
    }

}
