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

class BaseWarecorp_Global_Search extends Warecorp_Search
{
    public $defaultOrder = '@weight DESC, @id DESC';

    public $paramsOrder = null;

    /**
    * @desc orders which should be applyed to search result. By default it will be weight. May be it will be enumeration. Will see ...
    */
    private $orders = null;

    /**
    * @desc list of search areas like groups, members, lists, photos, videos, etc. Could be changed from config file
    */
    private $_searchAreas = array();
    /**
    * @desc list of sphinx indexes for search
    */
    private $_indexesEnumeration = array();

    /**
    * @desc default search settings for sphinx like private/public, etc
    *
    */
    private $_searchSettings = array();

    private $_searchEngine = null;
    /**
    * parsed search params
    */
    private $_keywords = array();

    private $_result = null;
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->_searchEngine = new Warecorp_Data_Search();
        $this->_searchEngine->initGlobal();
    }

    public function getPagerLink($params)
    {
        $_orders = $this->getOrders();
        $link  = $params['_url'];
        $link .= empty($params['filter']) ? "" : "/filter/".$params['filter'] ;
        $link .= empty($params['order']) || !isset($_orders[$params['order']]) ? "" : "/order/".$params['order'] ;
        $link .= empty($params['direction']) || !in_array($params['direction'], array('asc','desc')) ? "" : "/direction/".$params['direction'];
        return $link;

    }

    public function getOrders()
    {
        if ($this->orders === null) {
            $this->orders = array('weight'=>'@weight DESC, @id DESC');
        }
        return $this->orders;
    }

    public function setKeywords($keywords = "")
    {
        $this->_keywords = $keywords;
    }

    public function getKeywords($keywords = "")
    {
        return $this->_keywords;
    }

    private function getDefaultOrderBy()
    {
        return $this->defaultOrder;
    }

    public function getResultPairs()
    {
        return $this->_searchEngine->getResultPairs();
    }
    public function getResultIE()
    {
        return $this->_searchEngine->getResultIE();
    }

    public function getResultSphinx()
    {
        return $this->_searchEngine->getResultSphinx();
    }

    public function searchByCriterios ($params = array())
    {
        error_reporting(E_ALL);
        $query = $this->_keywords;

        //var_dump($params);
        if ( isset($params['city']) ){
            if (is_array($params['city'])) {
                $this->_searchEngine->SetFilter ( "city_id", $params['city']);
            }else{
                $this->_searchEngine->SetFilter ( "city_id", array( intval($params['city']) ) );
            }
        }

        if ( isset($params['state']) ) {
            // set state filter
            if (is_array($params['state'])) {
                $this->_searchEngine->SetFilter ( "state_id", $params['state'] );
            } else {
                $this->_searchEngine->SetFilter ( "state_id", array( $params['state'] ) );
            }
        }

        if ( isset($params['country']) ) {
             // set country filter
            if (is_array($params['country'])) {
                $this->_searchEngine->SetFilter ( "country_id", $params['country'] );
            } else {
                $this->_searchEngine->SetFilter ( "country_id", array( $params['country'] ) );
            }
        }

        if ( isset($params['where']) ) {
            $location = &$params['where'];
            if ( isset($location['city']) && is_numeric($location['city']) )
                $this->_searchEngine->SetFilter('city_id', array($location['city']));
            if ( isset($location['state']) && is_numeric($location['state']) )
                $this->_searchEngine->SetFilter('state_id', array($location['state']));
            if ( isset($location['country']) && is_numeric($location['country']) )
                $this->_searchEngine->SetFilter('country_id', array($location['country']));
        }

        if ( isset($params['category']) ) {
            if (is_array($params['category'])) {
                $this->_searchEngine->SetFilter ('category_id', $params['category'] );
            } else {
                $this->_searchEngine->SetFilter ('category_id', array( $params['category'] ));
            }
        }

        if ( isset($params['age_from']) || !empty($params['age_to']) ) {
            $age_from = !empty($params['age_from'])? $params['age_from']: 1;
            $age_to = !empty($params['age_to'])? $params['age_to']: 1000;
            $this->_searchEngine->SetFilterRange('age', intval($age_from), intval($age_to) );
        }

        if ( isset($params['gender']) ) {
            if ($params['gender'] == 'male'){
                $genderIndex = 1;
            }
            elseif ($params['gender'] == 'female') {
                $genderIndex = 2;
            }
            else {
                $genderIndex = 10;
            }
            $this->_searchEngine->SetFilter ( "gender", array( intval($genderIndex) ) );
        }

        if ( isset($params['list_type']) ) {
            if ( is_array($params['list_type']) )
                $this->_searchEngine->setFilter('list_type_id', $params['list_type']);
            else
                $this->_searchEngine->setFilter('list_type_id', array($params['list_type']));
        }

        if ( isset($params['when']) ) {
            $eventSearch    = new Warecorp_ICal_Search();
            $eventSearch->setUser(Zend_Registry::get("User"));
            $eventSearch->parseParams($params);
            $params['where'] = implode(', ', array_reverse(array_unique($eventSearch->whereParts)));
            //$params['when'] = isset($this->params['when']) ? trim($this->params['when']) : "";

            $eventSearch->setDefaultOrder();
            $events = $eventSearch->searchByCriterions();
            $events[0] = 0;
            $this->_searchEngine->SetFilter ( "entity_id", array(6) );
            $this->_searchEngine->SetFilter ( "@id", $events );
        }

        $this->setBlockedUserFilter($this->_searchEngine);

        $this->_searchEngine->SetFilter ( 'private', array( 0 ));
        $this->_searchEngine->SetSort( $this->defaultOrder );
        $this->_searchEngine->Query( $query );
        $this->excludeObjects();
    }

    public function excludeObjects ()
    {
        //$tmp = $this->_searchEngine->getResultSphinx();
        //var_dump("fields", $tmp['fields'], "attrs", $tmp['attrs'], $tmp);
        //exit();

        $user = Zend_Registry::get("User");
        $tz = ($user->getTimezone()) ? $user->getTimezone() : 'UTC';
        $defaultTz = date_default_timezone_get();
        date_default_timezone_set($tz);
        $nowDate = new Zend_Date;
        date_default_timezone_set($defaultTz);
        $list = new Warecorp_ICal_Event_List();
        $list->setTimezone($tz);

        foreach ($this->_searchEngine->getResultIE() as $_key => $_value){
            if ($_value == 2){
                $temp_object = Warecorp_Global_Factory::loadObject($_key, $_value);
                if ( $temp_object !== null && $temp_object->getGroupType() != "simple" ){
                    $this->_searchEngine->resetResultById($_key);
                }
            }
            elseif ($_value == 6) {
                if (!Warecorp_Global_Factory::isRecordExist($_key, $_value)){
                    $this->_searchEngine->resetResultById($_key);
                    continue;
                }
                $event = Warecorp_Global_Factory::loadObject($_key, $_value); //loading event
                if ( !$event->getRrule() && !$event->getDtend()->isEarlier($nowDate) ) {
                    continue;
                } elseif ( $event->getRrule() ) {
                    $event = $event->getRootEvent();
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
                    if ( !$event->getDtend()->isEarlier($nowDate) ) {
                        continue;
                    }
                }
                $this->_searchEngine->resetResultById($_key);
            } else {
                if (!Warecorp_Global_Factory::isRecordExist($_key, $_value)){
                    $this->_searchEngine->resetResultById($_key);
                }
            }
        }
    }
}
