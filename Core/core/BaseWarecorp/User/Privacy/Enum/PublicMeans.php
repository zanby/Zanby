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
 * @package Warecorp_User_Privacy_Enum
 * @copyright Copyright (c) 2007
 */
class BaseWarecorp_User_Privacy_Enum_PublicMeans
{
    const PUBLIC_IS_EVERYONE                = '1';
    const PUBLIC_IS_FRIENDS_GROUPS_NETWORK  = '2';
    const PUBLIC_IS_FRIENDS_GROUPS          = '3';
    const PUBLIC_IS_FRIENDS_NETWORK         = '4';
    const PUBLIC_IS_FRIENDS                 = '5';
    
    /**
     * check is value in enum
     * @param mixed $value
     * @return boolean
     * @author Vitaly Targonsky
     */
    public static function isIn($value)
    {
        if ( 
            $value != self::PUBLIC_IS_EVERYONE && 
            $value != self::PUBLIC_IS_FRIENDS_GROUPS_NETWORK && 
            $value != self::PUBLIC_IS_FRIENDS_GROUPS &&
            $value != self::PUBLIC_IS_FRIENDS_NETWORK &&
            $value != self::PUBLIC_IS_FRIENDS 
        ) {
            return false;
        }
        return true;
    }
    /**
     * get string for select
     * @param mixed $value
     * @return boolean
     * @author Vitaly Targonsky
     */
    public static function translate($value)
    {
        switch ($value) {
            case self::PUBLIC_IS_EVERYONE               : $value = 'Everyone on '. SITE_NAME_AS_STRING; break;
            case self::PUBLIC_IS_FRIENDS_GROUPS_NETWORK : $value = 'My Friends, My Groups, Friends of Friends'; break;
            case self::PUBLIC_IS_FRIENDS_GROUPS         : $value = 'My Friends and My Groups'; break;
            case self::PUBLIC_IS_FRIENDS_NETWORK        : $value = 'My Friends and Friends of Friends'; break;
            case self::PUBLIC_IS_FRIENDS                : $value = 'My Friends'; break;
            default : throw new Zend_Exception('Incorrect Type');
        }
        return $value;
    }
    
    public static function getPublicMeansAssoc()
    {
        return array(
            self::PUBLIC_IS_EVERYONE => self::translate(self::PUBLIC_IS_EVERYONE),
            self::PUBLIC_IS_FRIENDS_GROUPS_NETWORK => self::translate(self::PUBLIC_IS_FRIENDS_GROUPS_NETWORK),
            self::PUBLIC_IS_FRIENDS_GROUPS => self::translate(self::PUBLIC_IS_FRIENDS_GROUPS),
            self::PUBLIC_IS_FRIENDS_NETWORK => self::translate(self::PUBLIC_IS_FRIENDS_NETWORK),
            self::PUBLIC_IS_FRIENDS => self::translate(self::PUBLIC_IS_FRIENDS),
        );
    }
}
