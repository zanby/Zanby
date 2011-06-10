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
 * @package Warecorp_Feed
 * @author Pavel Shutin
 */
class BaseWarecorp_Feed_Events
{
    /**
     *
     * @param Array $arrEvents array of events for feed
     * @param timezone $currentTimezone Current Timezone
     * @param Warecorp_User|Warecorp_Group_Base $context context object
     * @param string $itemLinkPrefix
     * @return Warecorp_Feed_Writer_Feed
     */
    public static function getFeed($arrEvents, $currentTimezone, $context = NULL) {
        $feed = new Warecorp_Feed_Writer_Feed();
        
    
        if ($context instanceof Warecorp_User) {
            $name = $context->getLogin();
        } elseif ($context instanceof Warecorp_Group_Base) {
            $name = $context->getName(); 
        } else {
            throw new Zend_Exception();
        }
        
        $feed->setTitle($name. " Events");
        $feed->setDescription($name . " calendar events ");
        $feed->setLink($context->getOwnerPath('calendar.map.view'));
        $feed->setFeedLink($context->getOwnerPath('calendar.event.feed'),'rss');
        $feed->setLanguage(LOCALE);
        $feed->setDateModified(time());
        
        foreach ($arrEvents as $event) {
            $entry = $feed->createEntry();
            $entry->setTitle($event->getTitle());
            $entry->setLink($event->entityURL());

            $description = "";

            $pic = $event->getEventPicture();

            if ($pic) {
                $w = $pic->getOriginalWidth();
                $h = $pic->getOriginalHeight();
                if ($w>100 || $h>100) {
                    $scale = ($w/100 > $h/100) ? $w/100 : $h/100;
                    $pic->setWidth(floor($w/$scale));
                    $pic->setHeight(floor($h/$scale));
                }
               $description .= "<img src='".$pic->getImage()."' /><br />";
            }
            $objEventDtstartLocal = $event->convertTZ($event->getDtstart(), $currentTimezone);
            $objEventDtendLocal = $event->convertTZ($event->getDtend(), $currentTimezone);
            if ($objEventDtstartLocal->toString('MM/dd/yyyy') != $objEventDtendLocal->toString('MM/dd/yyyy')) {
                $description = Warecorp::t("Event takes place on").' '.$objEventDtstartLocal->toString('MM/dd/yyyy').' - '.$objEventDtendLocal->toString('MM/dd/yyyy');
            } else {
                $description .= Warecorp::t("Event takes place on").' '.$objEventDtstartLocal->toString('MM/dd/yyyy');
            }


            if ($event->isAllDay()) {
                $description .= ' '.Warecorp::t("All Day");
            } else {
                $description .= ' '.$objEventDtstartLocal->toString('h:mm').$objEventDtstartLocal->get(Zend_Date::MERIDIEM).' - '.$objEventDtendLocal->toString('h:mm').$objEventDtendLocal->get(Zend_Date::MERIDIEM);
                if ($event->isTimezoneExists()) {
                    $description .= ' '.$objEventDtstartLocal->get(Zend_Date::TIMEZONE);
                }
            }
            $location = $event->getEventVenue();
            if ($location) {
                $description .= ' <br/>';
                $description .= Warecorp::t("at").' '.$location->getAddress1().' '.$location->getAddress2().' '.$location->getCity()->name.', '.$location->getCity()->getState()->code;
            }
            $description .= ' <br/>';
            if ($event->getDescription() != '') {
                $description .= Warecorp::t("Description").': '.$event->getDescription().' <br/>';
            }
            $description .= Warecorp::t("Host").': '.$event->getCreator()->getLogin().'<br/>';
            if ($event->getRrule()) {
                $description .= Warecorp::t("With repeatings");
            }
            
            $entry->setDescription($description);
            
            $feed->addEntry($entry);
        }
        return $feed;
    }

}
