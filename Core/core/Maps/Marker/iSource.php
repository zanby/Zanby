<?php 

interface Maps_Marker_iSource 
{
    /**
    * @desc receive additional params which required for internal processing of buisness logic
    */
    public function setParams( $params );

    /**
    * @desc method return markers as array for current viewport (mrkers between sw and ne coordintes)
    * responce array('id' => $id, 'lat' => $lat, 'lng' => $lng)
    */
    public function getMarkersForViewport( $viewport, $zoom );

    /**
    * @desc prepeare marker for sending to JS application
    */
    public function getMapMarkers( $idList );
    
    /**
    * @desc prepare marker info for sending to JS application
    */
    public function getMarkerInfoData( $id );

}