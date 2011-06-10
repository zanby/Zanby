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
* @package Warecorp_Nda
*/
class BaseWarecorp_Nda_Event_List extends Warecorp_ICal_List_Abstract
{
    private $nda;
    private $fetchMode;
    private $DbConn;
    private $when;

   /**
    */
    public function __construct(Warecorp_Nda_Item $nda = null)
    {
        if ($nda !== null) $this->nda = $nda;
        if (null === ($this->DbConn = Zend_Registry::get("DB"))) throw new Warecorp_Exception("Database connection isn't set");
    }

    public function setWhen($value)
    {
        $this->when = $value;
        return $this;
    }

    public function getWhen()
    {
        return $this->when;
    }

   /**
    */
    public function setNda(Warecorp_Nda_Item $nda)
    {
        $this->nda = $nda;
        return $this;
    }

   /**
    */
    public function getNda()
    {
        if (null === $this->nda) throw new Warecorp_Exception("NDA wasn't set");
        return $this->nda;
    }

   /**
    */
    public function getFetchMode()
    {
        return $this->fetchMode;
    }

   /**
    */
    public function setFetchMode($value)
    {
        if (!Warecorp_Nda_List_Enum_FetchMode::in_enum($value)) throw new Warecorp_Exception('Incorrect FetchMode');
        else $this->fetchMode = $value;
        return $this;
    }

    public function getEventListByNDA()
    {
            $query = $this->DbConn->select();   
            $query->from(array('cenr' => 'calendar_event_nda_relations'), array('event_id'))
                    ->where('nda_id = ?', $this->nda->getId());
            $result = $this->DbConn->fetchCol($query);
            if ($this->getFetchMode() == Warecorp_Nda_List_Enum_FetchMode::OBJECT) {
                if (count($result)) {
                    foreach ($result as &$event) $event = new Warecorp_ICal_Event($event);
                } 
            }
            return $result;         
    }

   /**
    */
    public function getList()
    {
        $query = $this->DbConn->select();

        if ( $this->getPage() !== null && $this->getSize() !== null ) {
            $query->limitPage($this->getPage(), $this->getSize());
        }

        if ( $this->getFetchMode() == Warecorp_Nda_List_Enum_FetchMode::OBJECT ) {
            $query->from(array('cenr' => 'calendar_event_nda_relations'), array('event_id'))
                  ->where('nda_id = ?', $this->nda->getId());
            $result = $this->DbConn->fetchCol($query);

            if (strlen($this->getWhen()) && strcmp($this->getWhen(), 'all') && count($result)) {
                if ($this->getWhen() == 'future') {
                    $search = new Warecorp_Nda_Event_List_Standart();
                    $search->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)
                           ->setIncludeIds($result)
                           ->setExpiredEventFilter(false)
                           ->setCurrentEventFilter(true);

                    if ($this->getSize() && $this->getPage()) {
                        $search->setSize($this->getSize());
                        $search->setPage($this->getPage());
                    }
                    $result = $search->getList();
                }
                if ($this->getWhen() == 'expired') {
                    $search = new Warecorp_Nda_Event_List_Standart();
                    $search->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)
                           ->setIncludeIds($result)
                           ->setExpiredEventFilter(true)
                           ->setCurrentEventFilter(false);
                    if ($this->getSize() && $this->getPage()) {
                        $search->setSize($this->getSize());
                        $search->setPage($this->getPage());
                    }
                    $result = $search->getList();
                }
            }
            else {

                if (count($result)) {
                    foreach ($result as &$event) $event = new Warecorp_ICal_Event($event);
                }
            }
        } elseif ( $this->getFetchMode() == Warecorp_Nda_List_Enum_FetchMode::ASSOC ) {
            throw new Warecorp_ICal_Exception('Method is not emplement now');
        }

        return $result;
    }

   /**
    */
    public function getCount()
    {
        if (strlen($this->getWhen()) && strcmp($this->getWhen(), 'all')) {
            $query = $this->DbConn->select();
            $query->from(array('cenr' => 'calendar_event_nda_relations'), array('event_id'))
                  ->where('nda_id = ?', $this->nda->getId());
            $result = $this->DbConn->fetchCol($query);

            if ($this->getWhen() == 'future') {
                    $search = new Warecorp_Nda_Event_List_Standart();
                    $search->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)
                           ->setIncludeIds($result)
                           ->setExpiredEventFilter(false)
                           ->setCurrentEventFilter(true)
                           ->getList();
                    return $search->getCount();
                }
                if ($this->getWhen() == 'expired') {
                    $search = new Warecorp_Nda_Event_List_Standart();
                    $search->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)
                           ->setIncludeIds($result)
                           ->setExpiredEventFilter(true)
                           ->setCurrentEventFilter(false)
                           ->getList();
                    return $search->getCount();
                }
        }
        else {
            $query = $this->DbConn->select();
            $query->from("calendar_event_nda_relations", array('count' => new Zend_Db_Expr('COUNT(*)')))
                ->where("nda_id = ?", $this->nda->getId());
            $result = $this->DbConn->fetchRow($query);
            return $result['count'];
        }
    }
}
