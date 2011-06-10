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

class BaseWarecorp_ICal_Category_List extends Warecorp_ICal_List_Abstract 
{
    /**
     * data base field name used as key for returned pairs array
     */
    protected $_pairsModeKey = 'category_id';
    
    /**
     * data base field name used as value for returned pairs array 
     */
    protected $_pairsModeValue = 'category_name';
    
    /**
     * Constructor
     * @param Zend_Db_Table_Abstract $Connection - database connection object
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * return number of items
     */
    public function getCount()
    {
        /*
        $query = $this->DbConn->select();
        
        $query->from('calendar_events', array('CNT' => new Zend_Db_Expr('COUNT(*)')));
        $result = $this->DbConn->fetchOne($query);
        
        return $result;
        */
    }
    
    /**
     * return list of items    
     */
    public function getList()
    {
        $query = $this->DbConn->select();
        
        if ( $this->getPage() !== null && $this->getSize() !== null ) {
            $query->limitPage($this->getPage(), $this->getSize());
        }
        
        if ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::OBJECT ) {
            throw new Warecorp_Exception('Mothod doesn\'t implement now');
            /*
            $query->from('calendar_events', 'event_id');
            $result = $this->DbConn->fetchCol($query);
            if ( sizeof($result) != 0 ) {
                foreach ( $result as &$event ) $event = new Warecorp_ICal_Event($event);
            }
            */
        } elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::ASSOC ) {
            throw new Warecorp_Exception('Mothod doesn\'t implement now');
            //$query->from($this->getEntity()->_DbTableName, $this->getAssocFields());
            //$result = $this->DbConn->fetchAssoc($query);
        } elseif ( $this->getFetchMode() == Warecorp_ICal_List_Enum_FetchMode::PAIRS ) {
            $query->from('calendar_categories', array($this->getPairsModeKey(), $this->getPairsModeValue()));
            if ( null != $this->getOrder() ) $query->order($this->getOrder());
            $result = $this->DbConn->fetchPairs($query);
        }
        return $result;
    }
}
