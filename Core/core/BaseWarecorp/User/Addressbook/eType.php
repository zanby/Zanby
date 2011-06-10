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
 * @author Eugene  Halauniou
 * @version 1.0
 * @created 25-сен-2007 16:23:44
 */
class BaseWarecorp_User_Addressbook_eType
{
    const CUSTOM_USER = 'custom_user';
    const USER = 'user';
    const GROUP = 'group';
    const CONTACT_LIST = 'contact_list';
    const GROUP_MEMBER = 'groupmember';
    const FRIEND = 'friend';
    protected static $values_array = array('custom_user', 'user', 'group', 'contact_list', 'groupmember', 'friend');

    /**
     * check is value in enum
     * 
     * @param value
     */
    public static function isIn($value)
    {
        return in_array($value, Warecorp_User_Addressbook_eType::$values_array);
    }

    /**
     * translate string values from enum to class names
     * 
     * @param value
     */
    public static function translate($value)
    {
        if (!Warecorp_User_Addressbook_eType::isIn($value)) throw new Warecorp_Exception('Wrong className');
        switch ($value) {
            case Warecorp_User_Addressbook_eType::$values_array[0] : return 'Warecorp_User_Addressbook_CustomUser';
            case Warecorp_User_Addressbook_eType::$values_array[1] : return 'Warecorp_User_Addressbook_User';
            case Warecorp_User_Addressbook_eType::$values_array[2] : return 'Warecorp_User_Addressbook_Group';
            case Warecorp_User_Addressbook_eType::$values_array[3] : return 'Warecorp_User_Addressbook_ContactList';
            case Warecorp_User_Addressbook_eType::$values_array[4] : return 'Warecorp_User_Addressbook_GroupMember';
            case Warecorp_User_Addressbook_eType::$values_array[5] : return 'Warecorp_User_Addressbook_Friend';
        }
    }
    
    public static function getContactTypes()
    {
        return Warecorp_User_Addressbook_eType::$values_array;
    }
    
}
