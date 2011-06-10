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

class BaseWarecorp_ICal_AccessManager_ESA extends Warecorp_ICal_AccessManager
{
    /**
     * Return instance of Access Manager
     * @return Warecorp_ICal_AccessManager
     */
    static public function getInstance()
    {
        if ( !self::$instance ) {
            self::$instance = new Warecorp_ICal_AccessManager_ESA();
        }
        return self::$instance;
    }

    public static function canViewEvents($objContext, Warecorp_User $objChallenger) {
        if ( $objContext instanceof Warecorp_Group_Base && !$objChallenger->getId() )
            return false;
        return parent::canViewEvents($objContext, $objChallenger);
    }

    public static function canViewPublicEvents($objContext, Warecorp_User $objChallenger) {
        if ( $objContext instanceof Warecorp_Group_Base && !$objChallenger->getId() )
            return false;
        return parent::canViewPublicEvents($objContext, $objChallenger);
    }

    public static function canViewSharedEvents($objContext, Warecorp_User $objChallenger) {
        if ( $objContext instanceof Warecorp_Group_Base && !$objChallenger->getId() )
            return false;
        return parent::canViewSharedEvents($objContext, $objChallenger);
    }
}
