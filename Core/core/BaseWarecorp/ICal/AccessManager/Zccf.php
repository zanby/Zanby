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

class BaseWarecorp_ICal_AccessManager_Zccf extends Warecorp_ICal_AccessManager_EIA
{
    /**
     * Return instance of Access Manager
     * @return Warecorp_ICal_AccessManager
     */
    static public function getInstance()
    {
        if ( !self::$instance ) {
            self::$instance = new Warecorp_ICal_AccessManager_Zccf();
        }
        return self::$instance;
    }
    
    /**
    * CHECKED
    * @param Warecorp_User|Warecorp_Group_Base $objContext
    * @param Warecorp_User $objChallenger
    */
    public static function canCreateEvent($objContext, Warecorp_User $objChallenger)
    {
        if ( $objContext instanceof Warecorp_User ) {
            return false;
        } elseif ( $objContext instanceof Warecorp_Group_Base ) {
            if ( null === $objChallenger->getId() ) return false;
            switch ( $objContext->getGroupType() ) {
                case 'simple'       : 
                    return false;
                    break;
                case 'family'       :
                    if ( !Zend_Registry::isRegistered('globalGroup') ) return false;
                    $objGlobalGroup = Zend_Registry::get('globalGroup');
                    if ( !$objGlobalGroup || !$objGlobalGroup->getId() || $objContext->getId() != $objGlobalGroup->getId() ) return false;
                    
                    return Warecorp_Group_AccessManager::canUseCalendar($objContext, $objChallenger);
                    break;
                default : throw new Zend_Exception("Incorrect Group Type");
            }
            return false;
        } else {
            throw new Warecorp_ICal_Exception('Incorrect Content Object');
        }

        return false;
    }
}