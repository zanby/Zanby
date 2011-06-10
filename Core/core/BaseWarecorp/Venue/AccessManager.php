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
 *@package Warecorp_Venue
 */
class BaseWarecorp_Venue_AccessManager
 {
 	static protected $instance = false;
 	
     /**
     * Private constructor
     */
    //protected function __construct(){}

    /**
     * Return instance of Access Manager
     * @return Warecorp_Venue_AccessManager
     */
    static public function getInstance($className = null){
        if ( !self::$instance ) {
            if ( null !== $className ) {
               self::$instance = new $className;
            } else {
               self::$instance = new Warecorp_Venue_AccessManager();
            }
        }
        return self::$instance;
    }
    
    /**
     *can View Private Venue
     *
     *@param Warecorp_ICal_Event $objEvent
     *@param Warecorp_User|Warecorp_Group_Base $objContext
     *@param Warecorp_User $objChallenger
     *@return boolean
     *@author Roman Gabrusenok
     */
     static public function canViewPrivateVenue(Warecorp_ICal_Event $objEvent, $objContext, Warecorp_User $objChallenger)
     {
         $objVenue = $objEvent->getEventVenue();
         if (null === $objVenue || null === $objVenue->getId()) return false;

         /** return true if venue not private **/
         if (!$objVenue->getPrivate()) return true;

         if ( $objContext instanceof Warecorp_User ) {
             if ($objEvent->getCreatorId() == $objChallenger->getId()) return true;
             if (null !== ($attendee = $objEvent->getAttendee()->findAttendee($objChallenger)) && ( $attendee->getAnswer() == 'YES' || $attendee->getAnswer() == 'MAYBE' )) {
                 return true;
             }
         }
         elseif ($objContext instanceof Warecorp_Group_Base) {
             switch ( $objContext->getGroupType() ) {
                 case 'simple':
                 case 'family':
                     if ($objEvent->getCreatorId() == $objChallenger->getId()) return true;
                     if (null !== ($attendee = $objEvent->getAttendee()->findAttendee($objChallenger)) && ( $attendee->getAnswer() == 'YES' || $attendee->getAnswer() == 'MAYBE' )) {
                         return true;
                     }
                     break;
                 default:
                     throw new Zend_Exception("Incorrect Group Type");
             }
         }
         else {
             throw new Zend_Exception("Incorrect Group Type");
         }
         return false;
     }
 }
