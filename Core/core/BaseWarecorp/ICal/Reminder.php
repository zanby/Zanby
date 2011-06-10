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

class BaseWarecorp_ICal_Reminder
{
    private $DbConn;
    private $id;
    private $eventId;
    private $duration;
    private $entireGuests;
    
    private $event;
    
    public function setId($newValue)
    {
        $this->id = $newValue;
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setEventId($newValue)
    {
        $this->eventId = $newValue;
        return $this;
    }
    
    public function getEventId()
    {
        if ( null === $this->eventId ) throw new Warecorp_ICal_Exception('Event is not set');
        return $this->eventId;
    }
    
    public function setDuration($newValue)
    {
        $this->duration = $newValue;
        return $this;
    }
    
    public function getDuration()
    {
        return $this->duration;
    }
    
    public function setEntireGuests($newValue)
    {
        $this->entireGuests = $newValue;
        return $this;
    }
    
    public function getEntireGuests()
    {
        return (boolean) $this->entireGuests;
    }
    
    public function __construct($reminderId = null)
    {
        $this->DbConn = Zend_Registry::get('DB');
        if ( null !== $reminderId ) $this->loadById($reminderId);
    }
    
    public function loadById($reminderId)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_reminders', array('*'));
        $query->where('reminder_id = ?', $reminderId);
        $result = $this->DbConn->fetchRow($query);
        if ( $result ) {
            $this->setId($result['reminder_id']);
            $this->setEventId($result['reminder_event_id']);
            $this->setDuration($result['reminder_duration']);
            $this->setEntireGuests($result['reminder_entire_guests']);
        }
    }
    
    public function save()
    {
        $data = array();
        $data['reminder_event_id']      = $this->getEventId();
        $data['reminder_duration']      = $this->getDuration();
        $data['reminder_entire_guests'] = (int) $this->getEntireGuests();
        if ( null === $this->getId() ) {
            $this->DbConn->insert('calendar_event_reminders', $data);
        } else {
            $where = $this->DbConn->quoteInto('reminder_id = ?', $this->getId());
            $this->DbConn->update('calendar_event_reminders', $data, $where);
        }
    }
    
    public function delete()
    {
    }
}
