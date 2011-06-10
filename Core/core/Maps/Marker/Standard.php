<?php 
/**
* @desc sample class. 
* Class could be expanded by additional methods which required for processing data. 
* Class could be process serach forms, additional conditions. 
* Class should be controlled from action by using $params or throw addtitional methods. 
*/

class Maps_Marker_Standard implements Maps_Marker_iSource
{
    private $_params;
    
    private $data = array();
    
    public function __construct() { 
        $this->data[] = array('id' => 1, 'lat' => 10.7349874574, 'lng' => 74.001369972, "type" => "event");
        $this->data[] = array('id' => 2, 'lat' => 21.7349874574, 'lng' => -72.001369972, "type" => "event");
        $this->data[] = array('id' => 3, 'lat' => -12.7349874574, 'lng' => 71.001369972, "type" => "event");
        $this->data[] = array('id' => 4, 'lat' => 83.7349874574, 'lng' => -75.001369972, "type" => "event");
        $this->data[] = array('id' => 5, 'lat' => 44.7349874574, 'lng' => 76.001369972, "type" => "event");
        $this->data[] = array('id' => 6, 'lat' => -65.7349874574, 'lng' => 77.001369972, "type" => "event");
        $this->data[] = array('id' => 7, 'lat' => 46.7349874574, 'lng' => -78.001369972, "type" => "event");
        $this->data[] = array('id' => 8, 'lat' => 46.7349874574, 'lng' => -78.102369972, "type" => "event");
        $this->data[] = array('id' => 9, 'lat' => 46.7349874574, 'lng' => -78.00136998, "type" => "event");
        $this->data[] = array('id' => 10, 'lat' => 46.7349874574, 'lng' => -78.00136999, "type" => "event");
        $this->data[] = array('id' => 11, 'lat' => 42.9249974574, 'lng' => -78.00136996, "type" => "event");
    }
    
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
    * @desc return amrkers for current viewport (markers between ne and sw coordinates).
    * @return array markers with id, lat, lng - data for current viewport 
    * Example 
    * array ( 1 => array('lng' => -78.0215699748, 'lat' => 45.9729894574), 37 => array('lng' => 25.0983742, 'lat' => 15.38837));
    * 
    */
    public function getMarkersForViewport( $viewport, $zoom ) {
        $result = array();
        if ($viewport['swlng'] < $viewport['nelng']) {
            $swlng = $viewport['nelng'];
            $nelng = $viewport['swlng'];
        } else {
            $nelng = $viewport['nelng'];
            $swlng = $viewport['swlng'];
        }
        
        foreach ($this->data as $value) {
            if ($viewport['nelat'] > $value['lat'] &&
                $viewport['swlat'] < $value['lat'] &&
                $nelng < $value['lng'] &&
                $swlng > $value['lng'] ) {
                    $result[$value['id']] = array('lng' => $value['lng'], 'lat' => $value['lat']);
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
        //@todo for real parser required integration with data source for current object
        $data = array ( "type" => "object",
                        "id" => $id,
                        "title" => "Object name - ".$id,
                        "desc" => "Description for sample object. It will be dispalyed in the cloud",
                        "firstname" => "Firstname",
                        "lastname" => "Lastname",
                        "cntry" => "Country",
                        "city" => "City", 
                        "state" => "State",
                        "street"=> "My street",
                        "zip"=> "12345",
                        "url" => "http://example.com/");
        return $data;
    }
    

}