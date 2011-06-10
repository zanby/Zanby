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

class BaseWarecorp_ICal_Event_Marker implements Maps_Marker_iSource
{
    protected $_params;
    
    protected $data = array();
    
    protected $_user;
    protected $_timezone;
    protected $_showBubbleCountry = true;
    protected $_showBubbleState = true;
    
    public function __construct() {}
    
    /**
    * @desc function for receiving params from map. $params should incoude all params or params which required for implementing buisness logic
    * @param $param - array ('key' => 'value');
    * @return $this 
    */
    
    public function setParams( $params ) {
        $this->_params = $params;
        return $this;
    }
    
    /**
     * set current user, that who looks at event
     * @param Warecorp_User $objUser
     * @return Maps_Marker_iSource
     */
    public function setUser( Warecorp_User $objUser )
    {
        $this->_user = $objUser;
    }
    
    /**
     * return object of current user
     * @return Warecorp_User
     */
    public function getUser(  )
    {
        if ( null === $this->_user || !($this->_user instanceof Warecorp_User)  ) throw new Exception("Incorrect user object");
        return $this->_user;
    }
    
    /**
     * set current timezone
     * @param string $timezone
     * @return Maps_Marker_iSource
     */
    public function setTimezone( $timezone )
    {
        $this->_timezone = $timezone;
        return $this;
    }

    /**
     * Return current timezone
     * @return string
     */
    public function getTimezone(  )
    {
        if ( null === $this->_timezone ) return date_default_timezone_get();
        return $this->_timezone;
    }
    
    /** 
     * setup mode to displate country name on map info buble
     * @param $bool
     */
    public function showBubbleCountry( $bool )
    {
        $this->_showBubbleCountry = (boolean) $bool;
    }

    /** 
     * setup mode to displate state name on map info buble
     * @param $bool
     */    
    public function showBubbleState( $bool )
    {
        $this->_showBubbleState = (boolean) $bool;
    }
    
    public function initMarkers()
    {        
        $this->data = array();
        //var_dump($this->_params);
        $cache = Warecorp_Cache::getFileCache();
        $useCahce = (boolean) isset($this->_params['mapCache']) && !empty($this->_params['mapCache']) && $cache;
        if ( $useCahce && $data = $cache->load($this->_params['mapCache'].'_data') ) {
            $this->data = $data;
        } elseif ( $useCahce && $arrEventIds = $cache->load($this->_params['mapCache']) ) {            
            if ( sizeof($arrEventIds) != 0 ) {
                foreach ( $arrEventIds as $eventId ) {
                    if ( !isset($this->data[$eventId]) ) {
                        $objEvent = new Warecorp_ICal_Event( $eventId );
                        if ( $objVenue = $objEvent->getEventVenue() ) {                            
                            if ( $c = $objVenue->getGeoCoordinates() ) {
                                $this->data[$eventId] = array('id' => $eventId, 'lat' => $c['lat'], 'lng' => $c['lng'], "type" => "event");
                            }
                        }
                    }
                }
                $cache->save($this->data, $this->_params['mapCache'].'_data', array('MAP_VIEW_CACHE'), 60*60*10);
                $cache->remove($this->_params['mapCache']);                
            }
        }
    }
    
    /**
    * @desc return amrkers for current viewport (markers between ne and sw coordinates).
    * @return array markers with id, lat, lng - data for current viewport 
    * Example 
    * array ( 1 => array('lng' => -78.0215699748, 'lat' => 45.9729894574), 37 => array('lng' => 25.0983742, 'lat' => 15.38837));
    * 
    */
    public function getMarkersForViewport( $viewport, $zoom ) 
    {
        $this->initMarkers();
        $result = array();
        if ($viewport['swlng'] < $viewport['nelng']) {
            $swlng = $viewport['nelng'];
            $nelng = $viewport['swlng'];
        } else {
            $nelng = $viewport['nelng'];
            $swlng = $viewport['swlng'];
        }
        
        if ( isset($this->data) && !empty($this->data) && sizeof($this->data) != 0 ) {
            foreach ($this->data as $value) {
                if ($viewport['nelat'] > $value['lat'] &&
                    $viewport['swlat'] < $value['lat'] &&
                    $nelng < $value['lng'] &&
                    $swlng > $value['lng'] ) {
                        $result[$value['id']] = array('lng' => $value['lng'], 'lat' => $value['lat']);
                }
            }
        }
        return $result;
    }
    
    public function getMapMarkers($idList){
        /**
        * @todo  get marker decorator for current marker types
        * #desc
        * possible params for JS application 
        * id - identifier of object 
        * lat - latitude
        * lng - logitude
        * type - type of object. Could be used on JS side for extra processing, chaging look and fill, implementing custom logic
        * icon - path to icon. Could be URL, or relative path from DOC_ROOT
        * tooltip - short decstiption for marker which displayed on mouse over
        * 
        * Suggestion: leave that minimal set of data without changes. In other cases trafic between client and server can be too large. 
        */
        $markersLst = array();
        foreach ($idList as $id => $marker) {
            $markersLst[] = array('id' => $id, "lat" => $marker['lat'], "lng" => $marker['lng'], "type" => "event");
        }
        return $markersLst;
    }
    
    public function getMarkerInfoData( $id ) {

        $eventsForOnePoint = $this->getMarkersForLatLan( $id );
        $LocTabHtml = $InfoTabHtml = '';
        foreach ($eventsForOnePoint as $currentId) {

            $objEvent = new Warecorp_ICal_Event( $currentId );
            $objVenue = $objEvent->getEventVenue();
            $objCity = $objVenue->getCity();
            $objState = $objCity->getState();
            $objCountry = $objState->getCountry();
            $eventUrl = $objEvent->entityURL();
            $eventDescription = $objEvent->getDescription();
            if ( strlen($eventDescription) > 100 ) $eventDescription = substr($eventDescription, 0, 100) . '...';

            $LocTabHtml .= "<div class='titleMap'>{$objEvent->getTitle()}</div>";
            
            $LocTabHtml .= "<div class='datetimeMap'>".$objEvent->displayDate('list.view', $this->getUser(), $this->getTimezone())."</div>";
            
            $LocTabHtml .= 
                "<div class='addressMap'>{$objVenue->getName()}<br/>{$objVenue->getAddress1()}<br/>".
                ($objVenue->getAddress2() ? $objVenue->getAddress2().'<br/>' : '');
            $LocTabHtml .= ( $this->_showBubbleCountry ) ? $objCountry->name."<br/>" : "";
            $LocTabHtml .= ( $this->_showBubbleState ) ? $objState->name."<br/>" : "";
            
            $LocTabHtml .= 
                "{$objCity->name}</div>".
                "<div class='linkMap'><a target='_blank' href='{$eventUrl}' >View Event</a></div><br/>";

                /*
            $InfoTabHtml .= 
                (!empty($eventDescription)) 
                ? 
                "<div class='titleMap'>{$objEvent->getTitle()}</div>"."<div class='addressMap'>".strip_tags($eventDescription)."</div>".
                "<div class='linkMap'><a target='_blank' href='{$eventUrl}' >View Event</a></div><br/>" 
                : '';
                 * 
                 */
        }

        $LocTabHtml = "<div id='infoTabLocation' style='overflow-x:hidden; overflow-y:auto; max-height:160px; margin-top:15px;'>".$LocTabHtml."</div>";
        $InfoTabHtml = (!empty($InfoTabHtml)) ? "<div id='infoTabInfo' style='overflow-x:hidden; overflow-y:auto; max-height:160px; margin-top:15px;'>".$InfoTabHtml."</div>" : '';

        
        //@todo for real parser required integration with data source for current object
        $data = array ( 
            "id" => $id,
            "LocTabHtml" => $LocTabHtml,
            "InfoTabHtml" => $InfoTabHtml 
        );
        return $data;
    }

    public function getMarkersForLatLan($eventId) {
        $this->initMarkers();

        $markersList = array_keys( $this->data );

        if (count($markersList) == 0) return array();

        $objEvent = new Warecorp_ICal_Event( $eventId );
        $objVenue = $objEvent->getEventVenue();
        $c = $objVenue->getGeoCoordinates();

        $sql = "select distinct ce.event_id".
                    " from zanby_event__venues as zev".
                    " join calendar_event_venues as cev on (zev.id = cev.venue_id)".
                    " join calendar_events as ce on (cev.event_id = ce.event_id)".
                    " where lat = '".$c['lat']."' and lng = '".$c['lng']."' and ce.event_id in (".join(', ',$markersList).")";
        //@todo - remove SQL query implement sphinx
        $_db = Zend_Registry::get('DB');
        $eventsList = $_db ->fetchAssoc($sql);
        return $eventsList;
    }

}