<?php 
/**
* @desc marker manager is a class which required for integration between clusteres and marker source. 
* There are implemented cached or temporary data which could be received from clusterer or marker sources. 
* Also in that class could be redefined clusteres processor or marker source. It's means that developer could 
* write own processor or data sourse specific for current map.
* There are exists ability for disabling map clusterer. 
*/

class Maps_Marker_Manager{
    
    private $_params = array();
    private $_clusterer = null;
    private $_markerSource = null;
    
    private $_prepearedViewPort = array();
    private $_originalViewPort = array();
    private $_currentZoomLevel = null;
    private $_isClustererEnabled = true;
    
    public function __construct() { 
    
    }
    
    private function isClustererEnabled() {
         return $this->_isClustererEnabled;
    }
       
    public function setParams($params) {
        $this->_params = $params;

        if ( !isset( $this->_params['currentZoomLevel']) ) {
           throw new Exception('Current zoom level is not set!'); 
        }
        else {
            $this->_currentZoomLevel = $this->_params['currentZoomLevel'];
        }
        
        if ( !isset( $this->_params['sw'],  $this->_params['ne']) ) {
           throw new Exception('Current viewport is not set!'); 
        }
        else {
            list ($neLat, $neLng) = explode(',', $this->_params['ne']);
            list ($swLat, $swLng) = explode(',', $this->_params['sw']);
            $this->setViewPort($neLat, $neLng, $swLat, $swLng);
        }        
    }
    
    public function getParams() {
        return $this->_params;
    }
    
    public function getZoom() {
        return $this->_currentZoomLevel;
    }
    
    public function setZoom($value) {
        $this->_currentZoomLevel = $value;
    }

    public function setViewPort($neLat, $neLng, $swLat, $swLng)
    {   
        if ( $this->_currentZoomLevel <= 2 ) {
            $this->_originalViewPort['nelat'] = 90;
            $this->_originalViewPort['nelng'] = 180;
            $this->_originalViewPort['swlat'] = -90;
            $this->_originalViewPort['swlng'] = -180;
            /*
            Array
            (
                [nelat] => 89.667138
                [nelng] => 180
                [swlat] => -87.850622
                [swlng] => -180
            )
            */
        } else {        
            $this->_originalViewPort['nelat'] = $neLat;
            $this->_originalViewPort['nelng'] = $neLng;
            $this->_originalViewPort['swlat'] = $swLat;
            $this->_originalViewPort['swlng'] = $swLng;
        }        

        if ( !$this->isClustererEnabled() ) {
            $this->_prepearedViewPort['nelat'] = $neLat;
            $this->_prepearedViewPort['nelng'] = $neLng;
            $this->_prepearedViewPort['swlat'] = $swLat;
            $this->_prepearedViewPort['swlng'] = $swLng;
        } 
        else {
            $this->_prepearedViewPort = $this->getClustererEngine()->setViewPort($neLat, $neLng, $swLat, $swLng)->getViewport();
        }

        return $this;
    }
    
    
    public function getViewPort(){
        return $this->getPrepearedViewPort();
    }
    
    public function getPrepearedViewPort(){
        return $this->_prepearedViewPort;
    }

    public function getOriginalViewPort(){
        return $this->_originalViewPort;
    }
    
    public function setClustererEngine($engine)
    {
        if ($engine instanceof Maps_Clusterer_iClusterer){
            $this->_clusterer = $engine;
        } 
        else {
            throw new Exception('Not correct clusterer interface');
        }
    }    
    
    public function getClustererEngine()
    {
        if ($this->_clusterer === null){
            $this->_clusterer = new Maps_Clusterer_Standard();
        }
        return $this->_clusterer;
    }    
    
    public function setMarkerSource($engine)
    {
        if ($engine instanceof Maps_Marker_iSource){
            $this->_markerSource = $engine;
        } 
        else {
            throw new Exception('Not correct marker source interface');
        }
    }    
    
    public function getMarkerSource()
    {
        if ($this->_markerSource === null){
            $this->_markerSource = new Maps_Marker_Standard();
        }
        return $this->_markerSource;
    }    

    public function getMapData() {
    
        // sending params into markers source
        $this->getMarkerSource()->setParams( $this->getParams() );
        
        // receiving the markers 
        $rawMarkers = $this->getMarkerSource()->getMarkersForViewport($this->getViewPort(), $this->getZoom());

        $markers = array();
        if ( count($rawMarkers) != 0 ){ 
            if ( $this->isClustererEnabled() ) { 

                // setup current zoom lavel
                $this->getClustererEngine()->setZoomLevel( $this->getZoom() );
                
                // receiving data from clusterer 
                $clusteredData = $this->getClustererEngine()->getClusters($rawMarkers);

                // get array with markers of clusters
                $markerCls = $this->getClustererEngine()->getClusterMarkers($clusteredData);
                
                // get array with plain markers
                $markersPlain = $this->getMarkerSource()->getMapMarkers($clusteredData['markers']);               
                
                $markers = array_merge( $markerCls, $markersPlain);        
            }
            else {
                $markers = $this->getMarkerSource()->getMapMarkers($rawMarkers);
            }
        }

        $zoom = array((int)$this->getZoom(), $this->getZoom() + 1);

        $mArray[] = array('zoom' => $zoom, 'places' => $markers);
        
        $_result = array( 'markersArray'=>$mArray, 'zoomLevel' => $this->getZoom(), 'viewport'=> $this->getViewPort(), 'rawMarkers' => $rawMarkers ) ;

        return Maps_Marker_Manager::getJson($_result);
    }

    public function getMarkerInfoData($id) {
        return Maps_Marker_Manager::getJson( $this->getMarkerSource()->getMarkerInfoData($id) );
    
    }    
    
    public static function getJson (array $input) {
        return Zend_Json::encode($input);
    }

}