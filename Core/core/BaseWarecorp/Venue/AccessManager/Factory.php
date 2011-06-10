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

class BaseWarecorp_Venue_AccessManager_Factory
{
    static public function create($context = null)
    {
        if ( null === $context ) $context = HTTP_CONTEXT;        
        /**
         * if we need specific access manager for any implementation
         * you must : create class in AccessManager forlder with name some http_context
         * Name of new class should be : first letter is uppercase, other symbols - lowercase
         * 
         * Look if specific class for implementation exsits
         * if yes - load it, if don't exist - look at IMPLEMENTATION_TYPE or load default access manager
         */
        try { 
            $className = 'Warecorp_Venue_AccessManager_'.ucfirst(strtolower($context));
            @Zend_Loader::loadClass($className);
            return Warecorp_Venue_AccessManager::getInstance($className);
        } catch (Exception $ex) {
            if ( defined('IMPLEMENTATION_TYPE') ) {
                if ( 'ESA' == IMPLEMENTATION_TYPE ) return Warecorp_Venue_AccessManager_ESA::getInstance();
                else return Warecorp_Venue_AccessManager_EIA::getInstance();
            } else {
               return Warecorp_Venue_AccessManager::getInstance();
            }                   
        }
    }
}
