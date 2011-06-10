<?php

interface  Maps_Clusterer_iClusterer
{
    
    /**
    * @desc setting of current viewport. Calculating the apsolute coordinates for viewport. 
    * $neLat, $neLng, $swLat, $swLng - is fload values. Coordinates of current (or required) viewport
    */
    public function setViewPort($neLat, $neLng, $swLat, $swLng);
            
            
    /**
    * @desc get processed viewport as array('nelat' => $val, 'nelng' => $val, 'swlat' => $val, 'swlng' => $val). Also viewport couldbe the same as created viewport 
    */
    public function getPrepearedViewport();

    /**
    * Function required for calculation of clusters. Required params array with id, lat, lng.
    * @params array = {'id', 'lat', 'lng'}
    * @return  $markersForViewPort = array("clusters" => $this->clustersResult, "markers" => $this->markersResult);
    */
    public function getClusters(Array $idLatLng);

    /**
    * @desc method which compose marker for JS app from internal data structure
    * @return 
    *       Array ( 'id'    => null,
    *               'type'  => 'cluster', - value clusterer is mandatory
                    'lat'   => latitude,
                    'lng'   => longitude,
                    'icon'  => '/maps/img/markers/m4.png', - path to icon
                    'count' => count of markers which grouped into cluster
    */
    public function getClusterMarkers($clusters);
}