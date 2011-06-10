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
 * Search class
 * @package Warecorp_ICal_Search
 */

class BaseWarecorp_ICal_Search extends Warecorp_Search
{

    private $resByType;
    public  $defaultOrder;
    public  $paramsOrder;
    public  $countryId;
    public  $stateId;
    public  $cityId;
    public  $categoryId;
    private $_rootCountry = null;
    public  $whereParts     = array();
    public  $timeInterval   = array();
    private $returnAsObjects = false;
    private $user = null;  //  User object
    private $countOrder = 0;

    /**
     * Constructor
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

   /**
    * @return self
    */
    public function setUser(Warecorp_User $user)
    {
        $this->user = $user;
        return $this;
    }

   /**
    * @return Warecorp_User|null
    */
    public function getUser()
    {
        return $this->user;
    }

    public function getOrderedCount(){
        return $this->countOrder;
    }

    /**
     * @author Komarovski
     */
    public function setReturnAsObjects($value = null){
        $this->returnAsObjects = empty($value)?false:true;
        return $this;
    }
    public function getReturnAsObjects(){
        return $this->returnAsObjects;
    }

    /**
     * Set keywords, parse location, parse time.
     * @author Vitaly Targonski
     */
    public function parseParams(&$params)
    {
        /**
         * set KEYWORDS
         */
        $this->parseParamsKeywords($params);

        /**
         * parse WHEN
         */
        $this->parseParamsWhen($params);

        /**
         * parse WHERE
         */
        $this->parseParamsWhere($params);
    }

    /**
     * @author Artem Sukharev
     */
    public function parseParamsKeywords(&$params)
    {
        if (isset($params['keywords'])) {
            $this->setKeywords($params['keywords']);
        }
    }

    /**
     * @author Artem Sukharev
     */
    public function parseParamsWhen(&$params)
    {
        if ( isset($params['when']) ) {
            $user = Zend_Registry::get('User');
            $defTZ = date_default_timezone_get();
            date_default_timezone_set( $user->getTimezone() ? $user->getTimezone() : 'UTC' );

            $params['when'] = strtolower(trim($params['when']));
            switch ( $params['when'] ) {
                // basic:
                case "today" :
                    $begin = new Zend_Date();
                    $end = clone $begin;
                    $end->add(1, Zend_Date::DAY);
                    $end = $end->setHour(0)->setMinute(0)->setSecond(0);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "this week" :
                    $begin = new Zend_Date();
                    $end = clone $begin;
                    $end = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($end, 'SU', $user->getTimezone());
                    $end->add(7, Zend_Date::DAY);
                    $end->setHour(0)->setMinute(0)->setSecond(0);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "next week" :
                    $begin = new Zend_Date();
                    $begin->add(7, Zend_Date::DAY);
                    $begin = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($begin, 'SU', $user->getTimezone());
                    $begin->setHour(0)->setMinute(0)->setSecond(0);
                    $end = clone $begin;
                    $end->add(7, Zend_Date::DAY);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "this month" :
                    $begin = new Zend_Date();
                    $end = clone $begin;
                    $end->setHour(0)->setMinute(0)->setSecond(0)->setDay(1);
                    $end->add(1, Zend_Date::MONTH);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "next month" :
                    $begin = new Zend_Date();
                    $begin->add(1, Zend_Date::MONTH);
                    $begin->setHour(0)->setMinute(0)->setSecond(0)->setDay(1);
                    $end = clone $begin;
                    $end->add(1, Zend_Date::MONTH);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "next three months" :
                    $begin = new Zend_Date();
                    //$begin->add(1, Zend_Date::MONTH);
                    //$begin->setHour(0)->setMinute(0)->setSecond(0)->setDay(1);
                    $end = clone $begin;
                    $end->add(3, Zend_Date::MONTH);
                    $end->setHour(0)->setMinute(0)->setSecond(0)->setDay(1);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    //print_r($this->timeInterval);
                    break;
                case "this year" :
                    $begin = new Zend_Date();
                    //$begin->setHour(0)->setMinute(0)->setSecond(0)->setDay(1)->setMonth(1);
                    $end = clone $begin;
                    $end->add(1, Zend_Date::YEAR);
                    $end->setHour(0)->setMinute(0)->setSecond(0)->setDay(1);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "next year" :
                    $begin = new Zend_Date();
                    $begin->add(1, Zend_Date::YEAR);
                    $begin->setHour(0)->setMinute(0)->setSecond(0)->setDay(1)->setMonth(1);
                    $end = clone $begin;
                    $end->add(1, Zend_Date::YEAR);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "all future" :
                case "future" :
                    $begin = new Zend_Date();
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = null;
                    break;
                case "this weekend" :
                    $begin = new Zend_Date();
                    $begin = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($begin, 'SU', $user->getTimezone());
                    $begin->setHour(0)->setMinute(0)->setSecond(0);
                    $begin->add(6, Zend_Date::DAY);
                    $end = clone $begin;
                    $end->add(2, Zend_Date::DAY);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "next weekend" :
                    $begin = new Zend_Date();
                    $begin->add(7, Zend_Date::DAY);
                    $begin = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($begin, 'SU', $user->getTimezone());
                    $begin->setHour(0)->setMinute(0)->setSecond(0);
                    $begin->add(6, Zend_Date::DAY);
                    $end = clone $begin;
                    $end->add(2, Zend_Date::DAY);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "next 7 days" :
                    $begin = new Zend_Date();
                    $begin->add(1, Zend_Date::DAY);
                    $begin->setHour(0)->setMinute(0)->setSecond(0);
                    $end = clone $begin;
                    $end->add(7, Zend_Date::DAY);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                    // other:
                case "yesterday" :
                    $begin = new Zend_Date();
                    $begin->sub(1, Zend_Date::DAY);
                    $begin->setHour(0)->setMinute(0)->setSecond(0);
                    $end = clone $begin;
                    $end->add(1, Zend_Date::DAY);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "tomorrow" :
                    $begin = new Zend_Date();
                    $begin->add(1, Zend_Date::DAY);
                    $begin->setHour(0)->setMinute(0)->setSecond(0);
                    $end = clone $begin;
                    $end->add(1, Zend_Date::DAY);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                default:
                    /**
                     * any date
                     */
                    $time = strtotime($params['when']);
                    if ($time !== false) {
                        $params['when'] = date('M j, Y', $time);
                        $begin = new Zend_Date($time);
                        $begin->setHour(0)->setMinute(0)->setSecond(0);
                        $end = clone $begin;
                        $end->add(1, Zend_Date::DAY);
                        $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                        $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    } else {
                        $params['when'] = "";
                        /**
                         * if param when was incorrect - set to all future
                         * @author Artem Sukharev
                         */
                        $params['when'] = "all future";
                        $begin = new Zend_Date();
                        $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                        $this->timeInterval['end'] = null;
                    }
                    break;
            }
            date_default_timezone_set($defTZ);
        }
        /**
         * if param when was not specified - set it to `all future`
         * @author Artem Sukharev
         */
        else {
            $user = Zend_Registry::get('User');
            $defTZ = date_default_timezone_get();
            date_default_timezone_set($user->getTimezone());

            $params['when'] = "all future";
            $begin = new Zend_Date();
            $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
            $this->timeInterval['end'] = null;

            date_default_timezone_set($defTZ);
        }
    }

    /**
    * @desc setter for main country
    * _rootCountry required for restrict country list during parsing where parameters
    */
    public function setRootCountry($country_id)
    {
        $this->_rootCountry = $country_id;
    }

    /**
    * @desc getter for main country
    */
    public function getRootCountry()
    {
        return $this->_rootCountry;
    }

    /**
     * @author Artem Sukharev
     */
    public function parseParamsWhere(&$params)
    {
        if (isset($params['city'])) {
            $this->cityId = $params['city'];
        }
        if (isset($params['state'])) {
            $this->stateId = $params['state'];
        }
        if (isset($params['country'])) {
            $this->countryId = $params['country'];
        }

        $params['where'] = isset($params['where']) ? trim($params['where']) : "";
        if (is_array($params['where'])) $params['where'] = implode(', ', array_reverse(array_unique($params['where'])));
        if ( !empty($params['where']) ) {
            $whereParts = preg_split("/\s*,+\s*/", $params['where']); // split by " , "
            $whereParts = array_slice(array_unique($whereParts), 0, 3); // take only 3 unique parts (Country, State, City)
            if (empty($whereParts)) return; // WHERE is empty
            /**
             * try to get country
             */
            $query = $this->_db->select();
            $query->from(array('zlc' => 'zanby_location__countries'), array('zlc.id', 'zlc.name', 'zlc.code'))
                  ->orWhere('zlc.name IN (?)', $whereParts)
                  ->orWhere('zlc.code IN (?)', $whereParts)
                  ->limit(1);
                  if ($this->getRootCountry() !== null) {
                       $query->where('zlc.id = ?', $this->getRootCountry());

                  }
            $country = $this->_db->fetchRow($query); // try to get country
            $query = $this->_db->select();
            if ( !empty($country['name']) ) {
                $this->countryId = $country['id'];
                $this->whereParts[] = $country['name'];
                $whereParts = array_diff($whereParts, $country);  // exclude country name or code
                $query->where('zls.country_id = ?', $country['id']);
            }
            if ( empty($whereParts) ) return;  // specified only country

            /**
             * try to get state
             */
            $query->from(array('zls' => 'zanby_location__states'), array('zls.id', 'zls.name', 'zls.code'))
                  ->where('( zls.name IN (?)', $whereParts)
                  ->orWhere('zls.code IN (?) )', $whereParts)
                  ->limit(1);
            $state = $this->_db->fetchRow($query);
            $query = $this->_db->select();
            if ( !empty($state['name']) ) {
                $this->stateId = $state['id'];
                $this->whereParts[] = $state['name'];
                $whereParts = array_diff($whereParts, $state);  // exclude state name or code
                $query->where('zlci.state_id = ?', $state['id'])
                      ->from(array('zlci' => 'zanby_location__cities'), array('zlci.id', 'zlci.name', 'zlci.state_id'));
            } elseif( !empty($country['name']) ) { // there isn't state in specified location, try get state name by city
                $query->join(array('zls' => 'zanby_location__states'), 'zls.id = zlci.state_id', array('state_name' => 'zls.name'))
                      ->join(array('zlc' => 'zanby_location__countries'), 'zls.country_id = zlc.id', array())
                      ->where('zls.country_id = ?', $country['id'])
                      ->from(array('zlci' => 'zanby_location__cities'), array('zlci.id', 'zlci.name', 'zlci.state_id'));
            } else {
                $query->from(array('zlci' => 'zanby_location__cities'), array('zlci.id', 'zlci.name', 'zlci.state_id'));
            }
            if (empty($whereParts)) return; // specified only country and state

            /**
             * try to get city
             */
            $query->where('zlci.name IN (?)', $whereParts)->limit(1);
            $city = $this->_db->fetchRow($query);
            if (!empty($city['name'])) {
                if (empty($state['name']) && empty($country['name'])) { // specified only city name, try get state and country by city
                    $query = $this->_db->select();
                    $query->from(array('zlc' => 'zanby_location__countries'), array('country_id' => 'zlc.id', 'country_name' => 'zlc.name'))
                          ->join(array('zls' => 'zanby_location__states'), 'zls.country_id = zlc.id', array('state_id' => 'zls.id', 'state_name' => 'zls.name'))
                          ->where('zls.id = ?', $city['state_id'])
                          ->limit(1);
                    $state = $this->_db->fetchRow($query);
                    if (!empty($state['state_name']) && !empty($state['country_name'])) {
                        $this->countryId    = $state['country_id'];
                        $this->whereParts[] = $state['country_name'];
                        $this->stateId      = $state['state_id'];
                        $this->whereParts[] = $state['state_name'];
                        //print "*";
                    }
                } elseif (empty($state['name']) && !empty($country['name']) && !empty($city['state_name'])) { // specified country name and city name, try get state by city & country
                    $this->stateId      = $city['state_id'];
                    $this->whereParts[] = $city['state_name'];
                }
                $this->cityId = $city['id'];
                $this->whereParts[] = $city['name'];
            }
        }
    }

    /**
     * Return url for pager
     * @param array $params
     * @author Vitaly Targonski
     */
    static public function getPagerLink($params)
    {
        $link  = '';
        $link .= empty($params['order']) ? "" : "/order/".$params['order'] ;
        $link .= empty($params['direction']) || !in_array($params['direction'], array('asc','desc')) ? "" : "/direction/".$params['direction'];
        $link .= empty($params['filter']) || empty($params['filterid']) ? "" : "/filter/".$params['filter']."/filterid/".$params['filterid'];
        return $link;
    }

    /**
     * get possible orders
     * @return array
     * @author Vitaly Targonski
     */
    static public function getOrders()
    {
        return array('date'=>'ce.event_dtstart', 'title'=>'ce.event_title', 'venue'=>'zev.name');
    }

    /**
     * set defauult order
     * @author Vitaly Targonski
     */
    public function setDefaultOrder()
    {
        //if (WITH_SPHINX){
        // temporary reasch by using sphinx is blocked for events
        if (false) {
            if (empty($this->keywords)) {
                $this->defaultOrder = 'event_title ASC';
                $this->paramsOrder = array('order'=>'title', 'direction'=>'asc');
            } elseif (is_array($this->keywords) && count($this->keywords)) {
                // if is set keywords then order by keyword relevance
                $this->defaultOrder = '@weight DESC';
            }
        }
        else{
            if (empty($this->keywords)) {
                $this->defaultOrder = 'ce.event_title';
                $this->paramsOrder = array('order'=>'title', 'direction'=>'asc');
            } elseif (is_array($this->keywords) && count($this->keywords)) {
                // if is set keywords then order by keyword relevance
                $this->defaultOrder = 'SUM(vetu.w) DESC';
            }

        }
    }

    /**
     * get ordered events IDs
     * @author Vitaly Targonski
     */
    public function getOrdered($params, $events, $size = 10)
    {
        if (isset($params['filter']) && isset($params['id'])) {
            $this->setFilter($params['filter'], $params['id']);
        }
        $user = Zend_Registry::get('User');
        $_in = (count($events)) ? ($events) : "";
        $_orders = $this->getOrders();
        $query = $this->_db->select();
        $this->parseParamsWhen($params);

        if ($params['order'] == 'date' && !empty($this->timeInterval)) {
            $event_gmt_dtstart = new Zend_Db_Expr("convert_tz(concat(substr(`ce`.`event_dtstart`,1,10),_utf8' ',substr(`ce`.`event_dtstart`,12,2),_utf8':',substr(`ce`.`event_dtstart`,14,2),_utf8':',substr(`ce`.`event_dtstart`,16,2)), IF (`ce`.`event_timezone` IS NULL, ".$this->_db->quoteInto('?', $user->getTimezone()).", `ce`.`event_timezone`) ,_utf8'GMT')");
            $query->from(array('ce' => 'calendar_events'), array('ce.event_id', 'event_dtstart' => $event_gmt_dtstart))
                  ->distinct()
                  ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = ce.event_owner_id AND ce.event_owner_type = 'group'", array())
                  ->joinLeft(array('cer' => 'calendar_event_rrules'), "ce.event_id = cer.rrule_event_id", array('cer.rrule_event_id'))
                  ->where('ce.event_privacy = 0')
                  ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
                  ->where('NOT ISNULL(ce.event_owner_id)')  // exlude event exceptions
                  ->where('ce.event_id IN (?)', $_in);

            $intervalBegin = $intervalEnd = null;
            $this->setTimeFilter($query, $intervalBegin, $intervalEnd);

            $objEventList = new Warecorp_ICal_Event_List();

            //echo $query->__toString();
            $events = $this->_db->fetchAll($query);

            foreach ($events as $key=>&$e) {
                $e = new Warecorp_ICal_Event($e['event_id']); //FIXME: если будет много евентов, будет сильно тормозить...
                if ($e->getRrule() instanceof Warecorp_ICal_Rrule) {
                    if ( $e->getTimezone() == '' ) {
                        $objEventList->setTimeZone(($user->getTimezone()) ? $user->getTimezone() : 'UTC');
                    } else {
                        $objEventList->setTimeZone($e->getTimezone());
                    }
                    if ( ($dateStart = $objEventList->findFirstEventDate($e, $intervalBegin, $intervalEnd)) ){
                        $e->setDtstart($dateStart);
                    } else {
                        unset($events[$key]);
                    }
                } else {
                    $e->setDtstart(str_replace(array(' ', ':'),array('T',''),$e->getDtstartValue()));
                }
            }
            if ($params['direction'] == 'desc') {
                usort($events, "Warecorp_ICal_Event::sortByDtstartDesc");
            } else {
                usort($events, "Warecorp_ICal_Event::sortByDtstartAsc");
            }
            $this->countOrder = count($events);
            $events = array_slice($events, ($params['page']-1)*$size, $size, true);

            foreach ($events as &$e) { $e = $e->getId(); }
            return $events;

        } elseif ($params['order'] == 'venue') {
            $query->joinLeft(array('cev' => 'calendar_event_venues'), 'ce.event_id = cev.event_id', array())
                  ->joinLeft(array('zev' => 'zanby_event__venues'), 'cev.venue_id = zev.id', array());
        }

        $query->from(array('ce' => 'calendar_events'), 'ce.event_id')
              ->distinct()
              ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = ce.event_owner_id AND ce.event_owner_type = 'group'", array())
              ->where('ce.event_privacy = 0')
              ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
              ->where('ce.event_id IN (?)', $_in)
              ->order($_orders[$params['order']].' '.$params['direction']);
              //->limitPage($params['page'], $size);

        if ($this->getIncludeIds() !== null) $query->where('ce.event_id IN (?)', $this->getIncludeIds());
        if ($this->getExcludeIds() !== null) $query->where('ce.event_id NOT IN (?)', $this->getExcludeIds());

        $events = $this->_db->fetchCol($query);
        $this->countOrder = count($events);
        $events = array_slice($events, ($params['page']-1)*$size, $size, true);
        return $events;
    }

    static public function eventDateCmpAsc($event1, $event2)
    {
        return $event1['event_dtstart'] < $event2['event_dtstart'];
    }
    static public function eventDateCmpDesc($event1, $event2)
    {
        return $event1['event_dtstart'] > $event2['event_dtstart'];
    }
    /**
     * set exclude or include event ids
     *
     */
    public function setFilter($filter, $id, $group_ids = array())
    {
        $filter = trim($filter);
        $id = trim($id);

        $user = Zend_Registry::get('User');

        $query = $this->_db->select();

        switch ($filter) {
            case "date" :
                $dateFilters = array('today'=> 'today', 'weekend'=>'this weekend', 'nweekend'=>'next weekend', 'nmonth'=>'next month', 'n7days'=>'next 7 days');
                if (isset($dateFilters[$id])) $params['when'] = $dateFilters[$id];
                $this->parseParamsWhen($params);

                $query->from(array('ce' => 'calendar_events'), array('ce.event_id'))
                      ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = ce.event_id AND ce.event_owner_type = 'group'", array())
                      ->joinLeft(array('cer' => 'calendar_event_rrules'), "ce.event_id = cer.rrule_event_id", array('cer.rrule_event_id'))
                      ->where('ce.event_privacy = 0')
                      ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
                      ->where('NOT ISNULL(ce.event_owner_id)');  // exlude event exceptions

                $intervalBegin = $intervalEnd = null;
                $this->setTimeFilter($query, $intervalBegin, $intervalEnd);

                $objEventList = new Warecorp_ICal_Event_List();
                $objEventList->setTimezone($user->getTimezone() ? $user->getTimezone() : 'UTC');

                $events = $this->_db->fetchAll($query);
                foreach ($events as $key=>&$e) {
                    if ($e['rrule_event_id']) {
                        $e = new Warecorp_ICal_Event($e['event_id']); //FIXME: если будет много евентов, поиск будет сильно тормозить...
                        /**
                         * Added according issue #4241
                         * if user is anonymous user, find first date in event timezone
                         * else find first date in user timezone
                         */
                        if ( !$user || null == $user->getId() )     $usedTimezone = $e->getTimezone() ? $e->getTimezone() : 'UTC';
                        else                                        $usedTimezone = $user->getTimezone() ? $user->getTimezone() : 'UTC';
                        $objEventList->setTimeZone($usedTimezone);

                        if (  ($dateStart = $objEventList->findFirstEventDate($e, $intervalBegin, $intervalEnd)) ) {
                            $e = $e->getId();
                        } else {
                            unset($events[$key]);
                        }
                    } else {
                        $e = $e['event_id'];
                    }
                }
                $this->setIncludeIds($events);

                break;
            case "owner" :
                switch ($id) {
                    case "friends":
                        $friends = $user->getFriendsList()->returnAsAssoc()->getList();
                        $friends = $friends ? $friends : array('');
                        $query->from(array('ce' => 'calendar_events'), 'ce.event_id')
                              ->where('ce.event_owner_type = ?', 'user')
                              ->where('ce.event_owner_id IN (?)', $friends);
                        $this->setIncludeIds($this->_db->fetchCol($query));

                        $_result = $this->_db->fetchCol($query);
                        $this->setIncludeIds(empty($_result)?array('0'):$_result);

                        break;
                    case "groups":
                        if (!empty($group_ids)) {
                            $groups = $group_ids;
                        } else {
                            $groups = $user->getGroups()->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)->returnAsAssoc()->getList();
                            $groups = $groups ? $groups : array('');
                        }
                        $query->from(array('ce' => 'calendar_events'), 'ce.event_id')
                              ->where('ce.event_owner_type = ?', 'group')
                              ->where('ce.event_owner_id IN (?)', array_keys($groups))
                              ->where('NOT ISNULL(ce.event_owner_id)');  // exlude event exceptions

                        $_result = $this->_db->fetchCol($query);
                        $this->setIncludeIds(empty($_result)?array('0'):$_result);

                        break;
                    case "families":
                        $families = $user->getGroups()->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY)->returnAsAssoc()->getList();
                        $families = $families ? $families : array('');
                        $query->from(array('ce' => 'calendar_events'), 'ce.event_id')
                              ->where('ce.event_owner_type = ?', 'group')
                              ->where('ce.event_owner_id IN (?)', array_keys($families))
                              ->where('NOT ISNULL(ce.event_owner_id)');  // exlude event exceptions

                        $_result = $this->_db->fetchCol($query);
                        $this->setIncludeIds(empty($_result)?array('0'):$_result);

                        break;
                    case "other":
                        $friends = $user->getFriendsList()->returnAsAssoc()->getList();
                        $friends = $friends ? $friends : array('');
                        $query->from(array('ce' => 'calendar_events'), 'ce.event_id')
                              ->where('ce.event_owner_type = ?', 'user')
                              ->where('ce.event_owner_id IN (?)', $friends)
                              ->where('NOT ISNULL(ce.event_owner_id)');  // exlude event exceptions
                        $this->setExcludeIds($this->_db->fetchCol($query));

                        $groups = $user->getGroups()->returnAsAssoc()->getList();
                        $groups = $groups ? $groups : array('');
                        $query = $this->_db->select();
                        $query->from(array('ce' => 'calendar_events'), 'ce.event_id')
                              ->where('ce.event_owner_type = ?', 'group')
                              ->where('ce.event_owner_id IN (?)', array_keys($groups))
                              ->where('NOT ISNULL(ce.event_owner_id)');  // exlude event exceptions
                        $this->setExcludeIds( array_merge($this->getExcludeIds(), $this->_db->fetchCol($query)));

                        $families = $user->getGroups()->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY)->returnAsAssoc()->getList();
                        $families = $families ? $families : array('');
                        $query = $this->_db->select();
                        $query->from(array('ce' => 'calendar_events'), 'ce.event_id')
                              ->where('ce.event_owner_type = ?', 'group')
                              ->where('ce.event_owner_id IN (?)', array_keys($families))
                              ->where('NOT ISNULL(ce.event_owner_id)');  // exlude event exceptions
                        $this->setExcludeIds( array_merge($this->getExcludeIds(), $this->_db->fetchCol($query)));
                        break;
                    default:
                        break;
                }
                break;
            case "category" :
                $query->from(array('cec' => 'calendar_event_categories'), 'cec.event_id')
                      ->where('cec.category_id = ?', $id);

                $this->setIncludeIds($this->_db->fetchCol($query));
                break;
            default:
                break;
         }

        return $this;
    }

    protected  function setTimeFilter(&$query, &$intervalBegin, &$intervalEnd)
    {
        $user = Zend_Registry::get('User');
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set( $user->getTimezone() ? $user->getTimezone() : 'UTC');

        $dateObj = new Zend_Date($this->timeInterval['begin'], Zend_Date::ISO_8601);
        $dateObj->setTimezone('UTC');
        $intervalBegin = $dateObj->toString('yyyy-MM-dd HH:mm:ss');

        //$event_gmt_dtstart = "IF(event_is_allday=1, concat(substr(`ce`.`event_dtstart`,1,10),_utf8' ',substr(`ce`.`event_dtstart`,12,2),_utf8':',substr(`ce`.`event_dtstart`,14,2),_utf8':',substr(`ce`.`event_dtstart`,16,2)),  convert_tz(concat(substr(`ce`.`event_dtstart`,1,10),_utf8' ',substr(`ce`.`event_dtstart`,12,2),_utf8':',substr(`ce`.`event_dtstart`,14,2),_utf8':',substr(`ce`.`event_dtstart`,16,2)), IF (`ce`.`event_timezone` IS NULL, ".$this->_db->quoteInto('?', $user->getTimezone()).", `ce`.`event_timezone`) ,_utf8'GMT'))";
        //$event_gmt_dtend = "IF(event_is_allday=1, concat(substr(`ce`.`event_dtend`,1,10),_utf8' ',substr(`ce`.`event_dtend`,12,2),_utf8':',substr(`ce`.`event_dtend`,14,2),_utf8':',substr(`ce`.`event_dtend`,16,2)),  convert_tz(concat(substr(`ce`.`event_dtend`,1,10),_utf8' ',substr(`ce`.`event_dtend`,12,2),_utf8':',substr(`ce`.`event_dtend`,14,2),_utf8':',substr(`ce`.`event_dtend`,16,2)), IF (`ce`.`event_timezone` IS NULL, ".$this->_db->quoteInto('?', $user->getTimezone()).", `ce`.`event_timezone`) ,_utf8'GMT'))";

        /**
         * Changed according to issue #4241
         */
        if ( $user && $user->getId() !== null ) {
            $event_gmt_dtstart = "
                IF( event_is_allday = 1,
                    `ce`.`event_dtstart_date`,
                    convert_tz(
                        `ce`.`event_dtstart_date`,
                        IF (`ce`.`event_timezone` IS NULL, ".$this->_db->quoteInto('?', $user->getTimezone()).", `ce`.`event_timezone`) ,
                        _utf8'GMT')
                    )
            ";
            $event_gmt_dtend = "
                IF( event_is_allday = 1,
                    `ce`.`event_dtend_date`,
                    convert_tz(
                        `ce`.`event_dtend_date`,
                        IF (`ce`.`event_timezone` IS NULL, ".$this->_db->quoteInto('?', $user->getTimezone()).", `ce`.`event_timezone`) ,
                        _utf8'GMT')
                    )
            ";
        } else {
            $event_gmt_dtstart = "`ce`.`event_dtstart_date`";
            $event_gmt_dtend = "`ce`.`event_dtend_date`";
        }

        $query->where('(('.$event_gmt_dtstart.' >= ? AND ISNULL(cer.rrule_event_id)) OR NOT ISNULL(cer.rrule_event_id) )', $intervalBegin);
        $intervalEnd = null;
        if(!empty($this->timeInterval['end'])) {
            $dateObj = new Zend_Date($this->timeInterval['end'], Zend_Date::ISO_8601);
            $dateObj->setTimezone('UTC');
            $dateObj->sub(1, Zend_Date::SECOND);
            $intervalEnd = $dateObj->toString('yyyy-MM-dd HH:mm:ss');
            $query->where($event_gmt_dtstart.' <= ?', $intervalEnd);
        }
        date_default_timezone_set($defaultTimezone);
    }

    /**
     * get events IDs by criterions
     * @author Vitaly Targonski
     */
    public function searchByCriterions()
    {

        // temporary reasch by using sphinx is blocked for events
        //if (WITH_SPHINX){
        if (false){
            if (!empty($this->timeInterval)){
                $returnAsObjects = $this->getReturnAsObjects();
                if ($this->getReturnAsObjects()){
                    $this->setReturnAsObjects(false);
                }
                $query = $this->_db->select();
                $fields = array('ce.event_id', 'cer.rrule_event_id');
                $query->from(array('ce' => 'calendar_events'), $fields)
                      ->joinLeft(array('cer' => 'calendar_event_rrules'), "ce.event_id = cer.rrule_event_id", array());
                $timeEvents = $this->applyTimeInterval($query);
                $this->setReturnAsObjects($returnAsObjects);
            }

            // create object Warecorp_Data_Search
            $cl = new Warecorp_Data_Search();
            // initialization
            $cl->init('event');
            $query = "";

            $this->setBlockedUserFilter($cl);

            $cl->setFilter('group_private', array( 0 ) );
            $cl->setFilter('event_privacy', array( 0 ) );
            if ( is_array($this->keywords) && count($this->keywords) ) {
                $query = implode(' ', $this->keywords);
            }
            if ($this->getIncludeIds() && count($this->getIncludeIds()) ){
                $cl->setFilter('event_id', $this->getIncludeIds());
            }

            if (isset($timeEvents) && count($timeEvents) ){
                $cl->setFilter('event_id', $timeEvents);
            }

            if ($this->getExcludeIds() && count($this->getExcludeIds())){
                $cl->setFilter('event_id', $this->getExcludeIds(), true);
            }
            if ($this->cityId !== null) {
                $cl->setFilter('city_id', array( $this->cityId ));
            }
            if ($this->stateId !== null) {
                $cl->setFilter('state_id', array( $this->stateId));
            }
            if ($this->countryId !== null) {
                $cl->setFilter('country_id', array( $this->countryId ));
            }

            if ($this->defaultOrder) $cl->SetSort($this->defaultOrder);

            $cl->Query($query);

            if (!$this->getReturnAsObjects()){
                return array_values($cl->getResultPairs());
            }
            else {
                $result = array();
                foreach ($cl->getResultPairs() as $id){
                    $result[$id] = new Warecorp_ICal_Event($id);
                }
                return $result;
            }
        }
        else{
           /**
            * Variable under this block ($user) change to class property Warecorp_ICal_Search::$user
            */
            //  $user = Zend_Registry::get("User");

            $query = $this->_db->select();
            $fields = array('ce.event_id');
            if (!empty($this->timeInterval)) {
                $fields[] = 'cer.rrule_event_id';
            }

           /**
            * User can see all events what sharing for him,
            * and event's privacy don't affect it.
            *
            * From:
            * $query->from(array('ce' => 'calendar_events'), $fields)
            *       ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = ce.event_owner_id AND ce.event_owner_type = 'group'")
            *       ->where('ce.event_privacy = 0')
            *       ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
            *       ->where('NOT ISNULL(ce.event_owner_id)');  // exlude event exceptions
            *
            * Redmine bug #2348
            */
            $user_id = (null !== $this->getUser() && null !== $this->getUser()->getId()) ? $this->getUser()->getId() : 0;

            $query->from(array('ce' => 'calendar_events'), $fields)
                  ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = ce.event_owner_id AND ce.event_owner_type = 'group'", array())
                  ->joinLeft(array('ces' => 'calendar_event_sharing'), 'ce.event_id = ces.event_id', array())
                  ->where('(ce.event_privacy = 0')
                  ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
                  ->orWhere('ces.event_owner_type = ?', 'user')
                  ->where('ces.event_owner_id = ? )', $user_id)
                  ->where('NOT ISNULL(ce.event_owner_id)');  // exlude event exceptions
            /** **/
            if ( is_array($this->keywords) && count($this->keywords) ) {
                $query->join(array('vetu' => 'view_events__tags_used'), 'ce.event_id = vetu.event_id', array())
                      ->where('vetu.tag_name IN (?)', $this->keywords)
                      ->group('ce.event_id');
            }

            if ($this->cityId !== null || $this->stateId !== null || $this->countryId !== null) {
                $query->join(array('vel' => 'view_events__locations'), 'ce.event_id = vel.event_id', array());

                if ($this->cityId !== null) {
                    $query->where('vel.city_id IN (?)', $this->cityId);
                }
                if ($this->stateId !== null) {
                    $query->where('vel.state_id = ?', $this->stateId);
                }
                if ($this->countryId !== null) {
                    $query->where('vel.country_id = ?', $this->countryId);
                }
            }

            if ($this->defaultOrder) $query->order($this->defaultOrder);
            if ($this->getIncludeIds()){
                $query->where('ce.event_id IN (?)', $this->getIncludeIds());
            }
            if ($this->getExcludeIds()){
                $query->where('ce.event_id NOT IN (?)', $this->getExcludeIds());
            }

            if ( $this->categoryId ) {
                $query->joinLeft(array('cec' => 'calendar_event_categories'), 'cec.event_id = ce.event_id', array());
                $query->where('cec.category_id IN (?)', $this->categoryId);
            }

            if (!empty($this->timeInterval)) {
                $query->joinLeft(array('cer' => 'calendar_event_rrules'), "ce.event_id = cer.rrule_event_id", array());
                return $this->applyTimeInterval($query);//$events;
            } else {
                return $this->_db->fetchCol($query);
            }
        }
    }

    /**
     *
     */
    protected function applyTimeInterval(&$query)
    {
        $user = Zend_Registry::get('User');
        $intervalBegin = $intervalEnd = null;
        $this->setTimeFilter($query, $intervalBegin, $intervalEnd);
        $events = $this->_db->fetchAll($query);
        $eventsIds = array();

        $objEventList = new Warecorp_ICal_Event_List();
        $objEventList->setTimeZone( $user->getTimezone() ? $user->getTimezone() : 'UTC' );

        foreach ($events as $key=>&$e) {
            if ( !in_array($e['event_id'], $eventsIds) ) {
                $eventsIds[] = $e['event_id'];
                if ($e['rrule_event_id']) {
                    $e = new Warecorp_ICal_Event($e['event_id']); //FIXME: если будет много евентов, поиск будет сильно тормозить...
                    /**
                     * Added according issue #4241
                     * if user is anonymous user, find first date in event timezone
                     * else find first date in user timezone
                     */
                    if ( !$user || null == $user->getId() )     $usedTimezone = $e->getTimezone() ? $e->getTimezone() : 'UTC';
                    else                                        $usedTimezone = $user->getTimezone() ? $user->getTimezone() : 'UTC';
                    $objEventList->setTimeZone($usedTimezone);

                    if (  ($dateStart = $objEventList->findFirstEventDate($e, $intervalBegin, $intervalEnd)) ) {
                        if (false == $this->getReturnAsObjects()){
                            $e = $e->getId();
                        } else {
                            /**
                             * Added according issue #4241
                             */
                            if ( !$user || null == $user->getId() ) {
                                $DurationSec = $e->getDurationSec();
                                $e->setDtstart($dateStart);
                                $objEndDate = clone $e->getDtstart();
                                $objEndDate->add($DurationSec, Zend_Date::SECOND);
                                $e->setDtend($objEndDate->toString('yyyy-MM-ddTHHmmss'));
                            } else {
                                $DurationSec = $e->getDurationSec();
                                $e->setTimezone($user->getTimezone() ? $user->getTimezone() : 'UTC');
                                $e->setDtstart($dateStart);
                                $objEndDate = clone $e->getDtstart();
                                $objEndDate->add($DurationSec, Zend_Date::SECOND);
                                $e->setDtend($objEndDate->toString('yyyy-MM-ddTHHmmss'));
                            }
                        }
                    } else {
                        unset($events[$key]);
                    }

                } else {
                    if (false == $this->getReturnAsObjects()){
                        $e = $e['event_id'];
                    } else {
                        $e = new Warecorp_ICal_Event($e['event_id']);
                    }

                }
            } else {
                unset($events[$key]);
            }
        }
        unset($user);
        unset($objEventList);
        unset($eventsIds);
        return $events;
    }

    /**
     * Get all events with Worldwide venue
     * @return array
     * @author Vitaly Targonski
     */
    public function searchByWorldwide()
    {
        $query = $this->_db->select();
        $query->from(array('ce' => 'calendar_events'), array('ce.event_id','rrule_event_id'))
              ->join(array('cev' => 'calendar_event_venues'), 'ce.event_id = cev.event_id', array())
              ->join(array('zev' => 'zanby_event__venues'), 'cev.venue_id = zev.id', array())
              ->join(array('zevc' => 'zanby_event__venue_categories'), 'zev.category_id = zevc.id', array())
              ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = ce.event_owner_id AND ce.event_owner_type = 'group'", array())
              ->joinLeft(array('cer' => 'calendar_event_rrules'), "ce.event_id = cer.rrule_event_id", array())
              ->where('ce.event_privacy = 0')
              ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
              ->where('zevc.type = ?', 'worldwide')
              ->where("UNIX_TIMESTAMP(IF(ISNULL(cer.rrule_id),
                          IF(ISNULL(`ce`.event_timezone),
                             `ce`.`event_dtend_date`,
                             convert_tz(`ce`.event_dtend_date, `ce`.`event_timezone`, _utf8'GMT')
                            ),
                          IF(ISNULL(cer.rrule_until_date),
                             NOW() + INTERVAL 1 HOUR,
                             IF(ISNULL(`ce`.event_timezone),
                                `cer`.`rrule_until_date`,
                                convert_tz(cer.rrule_until_date, `ce`.`event_timezone`, _utf8'GMT')
                               )
                            )
                         ))>= UNIX_TIMESTAMP()");

        if ($this->defaultOrder) $query->order($this->defaultOrder);

        if (!empty($this->timeInterval)) {
            return $this->applyTimeInterval($query);
        } else {
            return $this->_db->fetchCol($query);
        }
    }

    /**
     * Get all events with $type if $type == 0 return events with any type
     * @param int $category
     * @return array
     */
    public function searchByCategory($category = 0)
    {
        $query = $this->_db->select();
        $query->from(array('ce' => 'calendar_events'), array('ce.event_id','rrule_event_id'))
              ->join(array('cec' => 'calendar_event_categories'), 'ce.event_id = cec.event_id', array())
              ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = ce.event_owner_id AND ce.event_owner_type = 'group'", array())
              ->joinLeft(array('cer' => 'calendar_event_rrules'), "ce.event_id = cer.rrule_event_id", array())
              ->where('ce.event_privacy = 0')
              ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
              ->where("UNIX_TIMESTAMP(IF(ISNULL(cer.rrule_id),
                          IF(ISNULL(`ce`.event_timezone),
                             `ce`.`event_dtend_date`,
                             convert_tz(`ce`.event_dtend_date, `ce`.`event_timezone`, _utf8'GMT')
                            ),
                          IF(ISNULL(cer.rrule_until_date),
                             NOW() + INTERVAL 1 HOUR,
                             IF(ISNULL(`ce`.event_timezone),
                                `cer`.`rrule_until_date`,
                                convert_tz(cer.rrule_until_date, `ce`.`event_timezone`, _utf8'GMT')
                               )
                            )
                         ))>= UNIX_TIMESTAMP()");
        if ($category !== 0) {
            $query->where('cec.category_id = ?', $category);
        }

        if ($this->defaultOrder) $query->order($this->defaultOrder);

        if (!empty($this->timeInterval)) {
            return $this->applyTimeInterval($query);
        } else {
            return $this->_db->fetchCol($query);
        }
    }

    /**
     * Return countries list, only with venues
     * @param int $limit
     * @return array
     * @author Vitaly Targonski
     */
    public function getTopCountriesList($limit = 0)
    {
        $query = $this->_db->select();
        $query->from(array('vel' => 'view_events__locations'), array('vel.country_id', 'vel.country_name', 'cnt' => new Zend_Db_Expr('COUNT(vel.event_id)')))
              ->group('vel.country_id')
              ->order('cnt DESC');
        if ($limit != 0) $query->limit($limit);
        return ($this->_db->fetchAll($query));
    }

    /**
     * Return cities list, only with venues
     * @param int $limit
     * @return array
     * @author Vitaly Targonski
     */
    public function getTopCitiesList($limit = 0)
    {
        $query = $this->_db->select();
        $query->from(array('vel' => 'view_events__locations'), array('vel.city_id', 'vel.city_name', 'cnt' => new Zend_Db_Expr('COUNT(vel.event_id)')))
              ->group('vel.city_name')
              ->order('cnt DESC');
        if ($limit != 0) $query->limit($limit);
        return ($this->_db->fetchAll($query));
    }

    /**
     * Return categories list, only categories with events
     * @param int $limit
     * @return array
     * @author Vitaly Targonski
     */
    public function getCategoriesList()
    {
        $query = $this->_db->select();
        $query->from(array('cc' => 'calendar_categories'), array('id' => 'cc.category_id', 'name' => 'cc.category_name'))
              ->join(array('cec' => 'calendar_event_categories'),'cc.category_id = cec.category_id', array())
              ->join(array('ce' => 'calendar_events'),'cec.event_id = ce.event_id', array('cnt' => new Zend_Db_Expr('COUNT(ce.event_id)')))
              ->joinLeft(array('zgi' => 'zanby_groups__items'), "zgi.id = ce.event_owner_id AND ce.event_owner_type = 'group'", array())
              ->joinLeft(array('cer' => 'calendar_event_rrules'), "ce.event_id = cer.rrule_event_id", array())
              ->where('ce.event_privacy = 0')
              ->where('(zgi.private = 0 OR ISNULL(zgi.private))')
              ->where("UNIX_TIMESTAMP(IF(ISNULL(cer.rrule_id),
                          IF(ISNULL(`ce`.event_timezone),
                             `ce`.`event_dtend_date`,
                             convert_tz(`ce`.event_dtend_date, `ce`.`event_timezone`, _utf8'GMT')
                            ),
                          IF(ISNULL(cer.rrule_until_date),
                             NOW() + INTERVAL 1 HOUR,
                             IF(ISNULL(`ce`.event_timezone),
                                `cer`.`rrule_until_date`,
                                convert_tz(cer.rrule_until_date, `ce`.`event_timezone`, _utf8'GMT')
                               )
                            )
                         ))>= UNIX_TIMESTAMP()")
              ->group('cc.category_id')
              ->order('cc.category_name ASC');
//              print_r($query->__toString());
        return $this->_db->fetchAll($query);
    }

    /**
     * Return all countries list with counts of events
     * @return array
     * @author Vitaly Targonski
     */
    public function getCountriesList()
    {
        $query = $this->_db->select();
        $query->from(array('zlc' => 'zanby_location__countries'), array('zlc.id', 'zlc.name'))
              ->joinLeft(array('vel' => 'view_events__locations'), 'zlc.id = vel.country_id', array('cnt' => new Zend_Db_Expr('COUNT(vel.event_id)')))
              ->group('zlc.id')
              ->order(array('zlc.sort_order ASC', 'zlc.name ASC'));
        return ($this->_db->fetchAll($query));
    }

    /**
     * Return all states list with counts of events
     * @param int - Country ID
     * @return array
     * @author Vitaly Targonski
     */
    public function getStatesList($countryId = null)
    {
        $query = $this->_db->select();
        $query->from(array('zls' => 'zanby_location__states'), array('zls.id', 'zls.name'))
              ->joinLeft(array('vel' => 'view_events__locations'), 'zls.id = vel.state_id', array('cnt' => new Zend_Db_Expr('COUNT(vel.event_id)')))
              ->group('zls.id')
              ->order('zls.name ASC');
        if ($countryId !== null) {
            $query->where('zls.country_id = ?', $countryId);
        }
        return ($this->_db->fetchAll($query));
    }

    /**
     * Return all citites list with counts of events
     * @param int - State ID
     * @return array
     * @author Vitaly Targonski
     */
    public function getCitiesList($stateId = null)
    {
        $query = $this->_db->select();
        $query->from(array('zlc' => 'zanby_location__cities'), array('zlc.id', 'zlc.name'))
              ->joinLeft(array('vel' => 'view_events__locations'), 'zlc.id = vel.city_id', array('cnt' => new Zend_Db_Expr('COUNT(vel.event_id)')))
              ->group('zlc.id')
              ->order('zlc.name ASC');
        if ($stateId !== null) {
            $query->where('zlc.state_id = ?', $stateId);
        }
        return ($this->_db->fetchAll($query));
    }

   /**
    * Return all unique city_id and them names associated with one public event at least.
    * @author Roman Gabrusenok
    * @param int $stateId
    * @return array Array ( ['id'] => ... , ['name'] => ... )
    */
    public function getCitiesListWithEvents( $stateId = null )
    {
        $query = $this->_db->select()->distinct('vel.city_id');
        $query->from(array('vel' => 'view_events__locations'), array('id' => 'vel.city_id'))
              ->joinLeft(array('zlc' => 'zanby_location__cities'), 'vel.city_id = zlc.id', array('name' => 'zlc.name'))
              ->order('zlc.name ASC');
        if ( null !== $stateId ) {
            $query->where('vel.state_id = ?', $stateId);
        }
        return $this->_db->fetchAll($query);
    }

   /**
    * Return all unique state_id and them names associated with one public event at least.
    * @author Roman Gabrusenok
    * @param int $countryId
    * @return array Array ( ['id'] => ... , ['name'] => ... )
    */
    public function getStatesListWithEvents( $countryId = null )
    {
        $query = $this->_db->select()->distinct('vel.state_id');
        $query->from(array('vel' => 'view_events__locations'), array('id' => 'vel.state_id'))
              ->joinLeft(array('zls' => 'zanby_location__states'), 'vel.state_id = zls.id', array('name' => 'zls.name'))
              ->order('zls.name ASC');
        if ( null !== $countryId ) {
            $query->where('vel.country_id = ?', $countryId);
        }
        return $this->_db->fetchAll($query);
    }

   /**
    * Return all unique country_id and them names associated with one public event at least.
    * @author Roman Gabrusenok
    * @return array Array ( ['id'] => ... , ['name'] => ... )
    */
    public function getCountriesListWithEvents()
    {
        $query = $this->_db->select()->distinct('vel.country_id');
        $query->from(array('vel' => 'view_events__locations'), array('id' => 'vel.country_id'))
              ->joinLeft(array('zlc' => 'zanby_location__countries'), 'vel.country_id = zlc.id', array('name' => 'zlc.name'))
              ->order('zlc.name ASC');
        return $this->_db->fetchAll($query);
    }
}
