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

class BaseWarecorp_ICal_Category
{
    private $DbConn;
    private $eventId;
    private $categoryId;
    
    private $category;
    
    public function setEventId($newValue)
    {
        $this->eventId = $newValue;
        return $this;
    }
    
    public function getEventId()
    {
        return $this->eventId;
    }
    
    public function setCategoryId($newValue)
    {
        $this->categoryId = $newValue;
        return $this;
    }
    
    public function getCategoryId()
    {
        return $this->categoryId;
    }
 
    public function getCategory()
    {
        if ( null === $this->category ) {
            $this->category = new Warecorp_ICal_Category_Item($this->getCategoryId());
        }
        return $this->category;
    }
 
    public function __construct($event_id = null, $categoryId = null)
    {
        $this->DbConn = Zend_Registry::get('DB');
        if ( null !== $event_id && null !== $categoryId ) $this->load($event_id, $categoryId);
    }
    
    public function load($event_id, $categoryId)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_categories', array('*'));
        $query->where('event_id = ?', $event_id);
        $query->where('category_id = ?', $categoryId);
        $result = $this->DbConn->fetchRow($query);
        if ( $result ) {
            $this->setEventId($result['event_id']);
            $this->setCategoryId($result['category_id']);
        }
    }
    
    public function save()
    {
        $data = array();
        $data['event_id'] = $this->getEventId();
        $data['category_id'] = $this->getCategoryId();
        $this->DbConn->insert('calendar_event_categories', $data);
    }
}
