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
 * @package Warecorp_Group_Enum
 * @author Andrei kondratiev
 * @version minimum core 1.000.2
 * @copyright Copyright (c) 2008
 */
class BaseWarecorp_Group_Enum_GroupPrivacy
{
    const GROUP_PRIVACY_PUBLIC     = 0;
    const GROUP_PRIVACY_PRIVATE    = 1;
    
    /**
     * check is value in enum
     * @param mixed $value
     * @return boolean
     * @version minimum core 1.000.2
     * @author Andrei Kondratiev
     */
    public static function isIn($value)
    {
        if ( 
            $value != self::GROUP_PRIVACY_PUBLIC && 
            $value != self::GROUP_PRIVACY_PRIVATE
        ) {
        	return false;
        }
        return true;
    }
}
