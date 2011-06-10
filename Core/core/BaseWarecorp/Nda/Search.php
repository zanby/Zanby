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
class BaseWarecorp_Nda_Search
{
    private $timeInterval = array();
    private $includeIds   = array();
    private $excludeIds   = array();
    private $keywords;
    private $asObject = false;
    private $timezone;
    private $_db;
    private $_rootCountry = null;   

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setTimezone($value)
    {
        $this->timezone = $value;
        return $this;
    }

    public function __construct()
    {
        $user = Zend_Registry::get("User");
        if ($user) {
            $this->timezone =  (null == ($user->getTimezone())) ? $user->getTimezone() : 'UTC';
        } else {
            $timezone = 'UTC';
        }

        if (null === ($this->_db = Zend_Registry::get('DB'))) throw new Warecorp_Exception("Database isn't connected");
    }

    public function getIncludeIds()
    {
        return $this->includeIds;
    }

    public function setIncludeIds($value)
    {
        if (!is_array($value)) $this->includeIds = array($value);
        else $this->includeIds = $value;
        return $this;
    }

    public function getExcludeIds()
    {
        return $this->excludeIds;
    }

    public function setExcludeIds($value)
    {
        if (!is_array($value)) $this->excludeIds = array($value);
        else $this->excludeIds = $value;
        return $this;
    }

    public function getReturnAsObjects()
    {
        return $this->asObject;
    }

    public function setReturnAsObject($value)
    {
        $this->asObject = (bool) $value;
        return $this;
    }

    function setKeywords($input)
    {
        $this->keywords = Warecorp_Search::clearInput($input);
        return $this;
    }

    /**
     * 
     */
    function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * 
     */
    public function parseParams(&$params)
    {
        /**
         * set KEYWORDS
         */
        $this->parseParamsKeywords($params);

      //  $this->parseParamsWhere($params); 
        /**
         * parse WHEN
         */
        $this->parseParamsWhen($params);
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
    $params['where'] = isset($params['where']) ? trim($params['where']) : "";
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
                  ->join(array('zlc' => 'zanby_location__countries'), 'zls.country_id = zlc.id')
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
                $query->from(array('zlc' => 'zanby_location__countries'), array('country_id' => 'zlc.id', 'country_name' => 'zlc.name' ))
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
                    $begin->setHour(0)->setMinute(0)->setSecond(0);
                    $end = clone $begin;
                    $end->add(1, Zend_Date::DAY);
                    $this->timeInterval['begin'] = $begin->toString("yyyy-MM-ddTHHmmss");
                    $this->timeInterval['end'] = $end->toString("yyyy-MM-ddTHHmmss");
                    break;
                case "this week" :
                    $begin = new Zend_Date();
                    $begin = Warecorp_ICal_Event_List::getDateFirstDayOfWeek($begin, 'SU', $user->getTimezone());
                    $begin->setHour(0)->setMinute(0)->setSecond(0);
                    $end = clone $begin;
                    $end->add(7, Zend_Date::DAY);
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
                    $begin->setHour(0)->setMinute(0)->setSecond(0)->setDay(1);
                    $end = clone $begin;
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
                case "this year" :
                    $begin = new Zend_Date();
                    $begin->setHour(0)->setMinute(0)->setSecond(0)->setDay(1)->setMonth(1);
                    $end = clone $begin;
                    $end->add(1, Zend_Date::YEAR);
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
     *@param void
     *@return bool
     */
    public function isFutureNda()
    {
        $defaultTZ = date_default_timezone_get();
        date_default_timezone_set($this->getTimezone());
        $objDate = new Zend_Date();
        date_default_timezone_set($defaultTZ);

        $query = $this->_db->select();
        $query->from('calendar_event_nda_items', array('count' => new Zend_Db_Expr('COUNT(*)')))
              ->where("nda_etdate > ?", $objDate->toString("yyyy-MM-ddTHHmmss"));
        $result = $this->_db->fetchRow($query);
        return (bool)$result['count'];
    }

    protected function setTimeFilter(&$query, &$intervalBegin, &$intervalEnd)
    {
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set($this->getTimezone());

        if(!empty($this->timeInterval['end'])) {
            $dateObjBeg = new Zend_Date($this->timeInterval['begin'], Zend_Date::ISO_8601);
            $dateObjEnd = new Zend_Date($this->timeInterval['end'], Zend_Date::ISO_8601);
            $dateObjEnd->sub(1, Zend_Date::SECOND);

            $query->where  ('(ceni.nda_sdate <= ?', $dateObjBeg->toString('yyyy-MM-dd HH:mm:ss'))
                  ->where  ('ceni.nda_edate >=  ?', $dateObjBeg->toString('yyyy-MM-dd HH:mm:ss'))
                  ->orWhere('ceni.nda_sdate <=  ?', $dateObjEnd->toString('yyyy-MM-dd HH:mm:ss'))
                  ->where  ('ceni.nda_edate >= ?', $dateObjEnd->toString('yyyy-MM-dd HH:mm:ss'))
                  ->orWhere('ceni.nda_sdate >=  ?', $dateObjBeg->toString('yyyy-MM-dd HH:mm:ss'))
                  ->where  ('ceni.nda_edate <= ?)', $dateObjEnd->toString('yyyy-MM-dd HH:mm:ss'));
        }
        else {
            $dateObj = new Zend_Date($this->timeInterval['begin'], Zend_Date::ISO_8601);
            $query->where('ceni.nda_edate >= ?', $dateObj->toString('yyyy-MM-dd HH:mm:ss'));
        }
        date_default_timezone_set($defaultTimezone);
    }

    public function getPreparedKeyword($value)
    {
        if (is_array($value)) $keyword = join(" ", $value);
        else $keyword = $value;
        $keyword = str_replace("\\", "\\\\\\\\", $keyword);
        $keyword = str_replace("'", "\'", $keyword);
        $keyword = str_replace('%', '\%', $keyword);
        $keyword = str_replace('_', '\_', $keyword);
        return "%".$keyword."%";
    }

    public function searchByCriterions()
    {

        $user = Zend_Registry::get('User');

        $query = $this->_db->select();
        $fields = array('ceni.nda_id');

        $query->from(array('ceni' => 'calendar_event_nda_items'), $fields);

        if ( $this->keywords ) {
            $query->where("ceni.nda_name LIKE BINARY '" . $this->getPreparedKeyword($this->keywords) . "'");
        }

        if ($this->getIncludeIds()){
            $query->where('ceni.nda_id IN (?)', $this->getIncludeIds());
        }
        if ($this->getExcludeIds()){
            $query->where('ceni.nda_id NOT IN (?)', $this->getExcludeIds());
        }

        if (!empty($this->timeInterval)) {
            return $this->applyTimeInterval($query);//$events;
        } else {
            return $this->_db->fetchCol($query);
        }
    }

    protected function applyTimeInterval(&$query)
    {
        $intervalBegin = $intervalEnd = null;
        $this->setTimeFilter($query, $intervalBegin, $intervalEnd);
        $query->where("ceni.nda_status = ?", "public");

        $items = $this->_db->fetchAll($query);

        foreach ($items as $key=>&$e) {
            if (false == $this->getReturnAsObjects()){
                $e = $e['nda_id'];
            } else {
                $e = new Warecorp_Nda_Item($e['nda_id']);
            }
        }
        return $items;
    }
}
