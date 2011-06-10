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
 * Factory of Warecorp_Group_Base successors
 * @author Yury Nelipovich
 */
class BaseWarecorp_User_Profile_Factory
{
    public static function getProfile($id)
    {
        if (Warecorp::checkHttpContext('zccf', 'zccf-alt', 'zccf-base')) {
            return new ZCCF_User_Profile($id);
        }elseif (Warecorp::checkHttpContext(array('z1sky','cpp','zea','z350','zcon','zftn') )) {
            return new Z1SKY_User_Profile($id);
        }

        switch (HTTP_CONTEXT) {
        case 'at'   :   return new AT_User_Profile($id);
        case 'zbak' :   return new ZBAK_User_Profile($id);
        case 'zntc' :   return new ZNTC_User_Profile($id);
        default     :   return null;
        }
    }
}

