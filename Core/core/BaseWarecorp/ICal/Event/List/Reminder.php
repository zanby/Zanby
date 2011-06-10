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

class BaseWarecorp_ICal_Event_List_Reminder extends Warecorp_ICal_List_Abstract 
{
    private $event;
    private $add;
    
    /**
     * data base field name used as key for returned pairs array
     */
    protected $_pairsModeKey = 'reminder_id';
    
    /**
     * data base field name used as value for returned pairs array 
     */
    protected $_pairsModeValue = 'reminder_id';
    
    /**
     * data base fields for assoc select
     */
    protected $_assocFields = array('reminder_id', 'reminder_id');
    
    /**
     * Constructor
     * @param Zend_Db_Table_Abstract $Connection - database connection object
     */
    public function __construct(Warecorp_ICal_Event $objEvent = null)
    {
        parent::__construct();
        if ( null !== $objEvent ) $this->setEvent($objEvent);
    }
    /**
     * 
     */
    public function setEvent(Warecorp_ICal_Event $newVal)
    {
        $this->event = $newVal;
    }
    /**
     * 
     */
    public function getEvent()
    {
        if ( null === $this->event ) throw new Warecorp_ICal_Exception('Event isn\'t set');
        return $this->event;
    }
    /**
     * return number of items
     */
    public function getCount()
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_reminders', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
        $query->where('reminder_event_id = ?', $this->getEvent()->getId());
        $result = $this->DbConn->fetchOne($query);
        return $result;
    }
    
    /**
     * @return Warecorp_ICal_Reminder array
     * @throw Warecorp_ICal_Exception
     */
    public function getList()
    {
        $query = $this->DbConn->select();
        
        if ( $this->getPage() !== null && $this->getSize() !== null ) {
            $query->limitPage($this->getPage(), $this->getSize());
        }
        
        if ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::OBJECT ) {
            $query->from('calendar_event_reminders', array('reminder_id'));
            $query->where('reminder_event_id = ?', $this->getEvent()->getId());
            $result = $this->DbConn->fetchCol($query);
            if ( sizeof($result) != 0 ) {
                foreach ( $result as &$item ) $item = new Warecorp_ICal_Reminder($item);
            }
        } elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::ASSOC ) {
            throw new Warecorp_ICal_Exception('Method is not emplement now');
            /*
            $query->from('calendar_event_categories', $this->getAssocFields());
            $query->where('event_id = ?', $this->getEvent()->getId());
            $result = $this->DbConn->fetchAll($query);
            */
        } elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::PAIRS ) {
            throw new Warecorp_ICal_Exception('Method is not emplement now');
            /*
            $query->from('calendar_event_categories', array($this->getPairsModeKey(), $this->getPairsModeValue()));
            $query->where('event_id = ?', $this->getEvent()->getId());
            $result = $this->DbConn->fetchPairs($query);
            */
        }
        return $result;
    }
    
    /**
    * 
    */
    public function add(Warecorp_ICal_Reminder $objReminder)
    {
        $this->add[] = $objReminder;
    }
    
    /**
    * Данный метод не должен вызываться нигде, кроме как 
    * в методе Warecorp_ICal_Event::save()
    */
    public function save()
    {    
        $this->deleteEventsAll();
        if ( null !== $this->add && sizeof($this->add) != 0 ) {
            foreach ( $this->add as $objReminder ) {
                $objReminder->setEventId($this->getEvent()->getId());
                $objReminder->save();
            }
        }
    }
    
    /**
    * @desc 
    */
    public function deleteEventsAll()
    {
        $where = $this->DbConn->quoteInto('reminder_event_id = ?', $this->getEvent()->getId());
        $this->DbConn->delete('calendar_event_reminders', $where);
    }
    
    public function updateChilds()
    {
        $refs = Warecorp_ICal_Event_List_Standard::getListByRootId($this->getEvent()->getId());
        if ( sizeof($refs) != 0 ) {
            $reminders = $this->getEvent()->getReminders()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
            if ( sizeof($reminders) != 0 ) {
                foreach ( $refs as &$ref ) {
                    $ref->getReminders()->deleteEventsAll();
                    foreach ( $reminders as &$reminder ) {
                        $tmpReminder = clone $reminder;
                        $tmpReminder->setId(null);
                        $tmpReminder->setEventId($ref->getId());
                        $tmpReminder->save();
                    }
                }
            } else {
                foreach ( $refs as &$ref ) {
                    $ref->getReminders()->deleteEventsAll();
                }
            }
        }
    }
    
}
