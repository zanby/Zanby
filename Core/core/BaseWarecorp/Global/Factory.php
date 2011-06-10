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
 * Search class for global search 
 * Global search will be availeble only with sphinx
 * @package Warecorp_Global_Search
 * @author Michail Pianko
 */

class BaseWarecorp_Global_Factory
{
    public function loadObject($id, $entityId)
    {       
        $user = Zend_Registry::get("User");
        $tz = ($user->getTimezone()) ? $user->getTimezone() : 'UTC';
        $defaultTz = date_default_timezone_get();
        date_default_timezone_set($tz);
        $nowDate = new Zend_Date;
        date_default_timezone_set($defaultTz);
        $list = new Warecorp_ICal_Event_List();
        $list->setTimezone($tz);    
        switch ($entityId) { 
            case 1  :
                return new Warecorp_User('id', $id);
            case 2  :
                return Warecorp_Group_Factory::loadById($id); 
            case 4  :
                return Warecorp_Photo_Factory::loadById($id);
            case 5 :
                return new Warecorp_Document_Item($id);
            case 6 :
                $event = new Warecorp_ICal_Event($id);
                if ( !$event->getRrule() && !$event->getDtend()->isEarlier($nowDate) ) {
                    $resultsList[] = $event;
                } elseif ( $event->getRrule() ) {
                    $strFirstDate = $list->findFirstEventDate($event, $nowDate->toString("yyy-MM-ddTHHmmss"));
                    if ( $strFirstDate !== null ) {
                        if ( !$user || null == $user->getId() ) {
                            $DurationSec = $event->getDurationSec();
                            $event->setDtstart($strFirstDate);
                            $objEndDate = clone $event->getDtstart();
                            $objEndDate->add($DurationSec, Zend_Date::SECOND);
                            $event->setDtend($objEndDate->toString('yyyy-MM-ddTHHmmss'));
                        } else {
                            $DurationSec = $event->getDurationSec();
                            $event->setTimezone($tz);
                            $event->setDtstart($strFirstDate);
                            $objEndDate = clone $event->getDtstart();
                            $objEndDate->add($DurationSec, Zend_Date::SECOND);
                            $event->setDtend($objEndDate->toString('yyyy-MM-ddTHHmmss'));
                        }
                    }
                    $resultsList[] = $event;
                }
                return $event;
            case 21 :
                return new Warecorp_List_Item($id);
            case 37 :
                return Warecorp_Video_Factory::loadById($id);
            case 40 :
                return new Warecorp_DiscussionServer_Post($id);
            default : 
                return null;//die ('unknown object '.$id." - ".$entityId);
        }        
    }
    public static function isRecordExist($id, $entityId)
    {        
        $_db = &Zend_Registry::get("DB");
        switch (intval($entityId)) { 
            case 1  :
                $sql = $_db->select()->from('zanby_users__accounts', 'id')->where('id=?', $id);
                break;
            case 2  :
                $sql = $_db->select()->from('zanby_groups__items', 'id')->where('id=?', $id);
                break;
            case 4  :
                $sql = $_db->select()->from('zanby_galleries__photos', 'id')->where('id=?', $id);
                break;
            case 5 :
                $sql = $_db->select()->from('zanby_documents__items', 'id')->where('id=?', $id); 
                break;
            case 6 :
                $sql = $_db->select()->from('calendar_events', 'event_id')->where('event_id=?', $id); 
                break;
            case 21 :
                $sql = $_db->select()->from('zanby_lists__items', 'id')->where('id=?', $id); 
                break;
            case 37 :
                $sql = $_db->select()->from('zanby_videogalleries__videos', 'id')->where('id=?', $id);
                break;
            case 40 :
                $sql = $_db->select()->from('zanby_discussion__posts', 'post_id')->where('post_id=?', $id);
                break;
            default : 
                return false;//die ("unknown object type '".$id."' - '".$entityId."'");
        }   
        $row = $_db->fetchRow($sql);
        if ($row) {
            return true;
        } 
        return false;
    }

}                     
