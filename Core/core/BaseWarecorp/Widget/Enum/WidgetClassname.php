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
 *
 * @package    Warecorp_Widget_Enum_WidgetClassname
 * @copyright  Copyright (c) 2009
 * @author Alexander Komarovski
 */

class BaseWarecorp_Widget_Enum_WidgetClassname
{
	const CLASSNAME_MAP = 'Warecorp_Widget_Map';
	const CLASSNAME_FRIENDS  = 'Warecorp_Widget_Friends';
	const CLASSNAME_GROUPS  = 'Warecorp_Widget_Groups';
	
    public static function getClassNameByWidgetType($value) {
        switch ($value) {
        	case Warecorp_Widget_Enum_WidgetType :: TYPE_MAP: return self::CLASSNAME_MAP; break;
        	case Warecorp_Widget_Enum_WidgetType :: TYPE_FRIENDS: return self::CLASSNAME_FRIENDS; break;
        	case Warecorp_Widget_Enum_WidgetType :: TYPE_GROUPS: return self::CLASSNAME_GROUPS; break;
        }
    }
}
