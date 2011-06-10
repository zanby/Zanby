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


class BaseWarecorp_Widget_Map_Clusterer
{
    protected $viewPortBorders;
    protected $viewPortPrepearedBorder;
    protected $stepOfClustering = 6;
    protected $gridSize = null;
    protected $markersLimit = 20;
    protected $cacheLifetime = 600;
    protected $minCountInCluster = 3;
    protected $viewPortClustersIDs = array();
    protected $markersResult = array();
    protected $clustersResult = array();
    private $cache = null;
    private $zoomLevel = 0;
    private $mapType = '';
    private $mapSize = array( 'w' => 0, 'h' => 0);
    private $hash = '';
    
    private $cachedClusters = array();
    private $lostClusters = array();
    
    
    public function __construct()
    {
        $this->cache = Warecorp_Cache::getCache();
    }
    
    public function setMapSize($width, $height)
    {
        $this->mapSize['w'] = $width;
        $this->mapSize['h'] = $height;
        return $this; 
    }
    
    public function getMapSize()
    {
        return $this->mapSize;
    }
    
    public function setZoomLevel($value)
    {
        $this->zoomLevel = $value;
        return $this; 
    }
    
    public function getZoomLevel()
    {
        return $this->zoomLevel;
    }
    
    public function setCacheHash($value)
    {
        $this->hash = $value;
        return $this; 
    }
    
    public function getCacheHash()
    {
        return $this->hash;
    }
    
    public function setMapType($value)
    {
        $this->mapType = $value;
        return $this; 
    }
    
    public function getMapType()
    {
        return $this->mapType;
    }
    
    public function prepareGridSize($multiplication = 1)
    {
        $this->gridSize = abs(($this->viewPortBorders['nelng'] - $this->viewPortBorders['swlng'])/$this->stepOfClustering)*$multiplication;
        return $this->gridSize; 
    }
    
    public function getPrepearedViewport()
    {
        return $this->viewPortPrepearedBorder;
    }
    
    public function setViewPort($neLat, $neLng, $swLat, $swLng)
    {   
        $this->viewPortBorders['nelat'] = $neLat;
        $this->viewPortBorders['nelng'] = $neLng;
        $this->viewPortBorders['swlat'] = $swLat;
        $this->viewPortBorders['swlng'] = $swLng;
        
        $this->prepareGridSize();

        $deltaValue = 0; //($this->gridSize / ($this->stepOfClustering * 10));
        $this->viewPortPrepearedBorder['nelat'] = ceil(($neLat - $deltaValue)/$this->gridSize)*$this->gridSize;
        $this->viewPortPrepearedBorder['nelng'] = floor(($neLng + $deltaValue)/$this->gridSize)*$this->gridSize;
        $this->viewPortPrepearedBorder['swlat'] = ceil(($swLat - $deltaValue)/$this->gridSize)*$this->gridSize;
        $this->viewPortPrepearedBorder['swlng'] = floor(($swLng + $deltaValue)/$this->gridSize)*$this->gridSize;
        
        $this->viewPortClustersIDs['maxX'] = ceil(($neLat - $deltaValue)/$this->gridSize);
        $this->viewPortClustersIDs['maxY'] = floor(($neLng + $deltaValue)/$this->gridSize);
        $this->viewPortClustersIDs['minX'] = ceil(($swLat - $deltaValue)/$this->gridSize);
        $this->viewPortClustersIDs['minY'] = floor(($swLng + $deltaValue)/$this->gridSize);
        
        //var_dump($this->viewPortClustersIDs);
      //  var_dump($this->viewPortPrepearedBorder);
       // print_r($this->viewPortPrepearedBorder);
        
        return $this;
    }
    
    public function getXIndex($Lat)
    {
       return ceil($Lat/$this->gridSize);
    }
    
    public function getYIndex($Lon)
    {
       return ceil($Lon/$this->gridSize);
    }
    
    public function getEventsMarkersForViewport($events)
    {
        $eventsForViewPort = array();
        $cacheKey = $this->hash. join('_', $this->viewPortPrepearedBorder).'_'.$this->zoomLevel.'coordinatesListForViewPortEvents';
        $eventsForViewPort = $this->cache->load($cacheKey);
        if ($eventsForViewPort === false) {
            if ( count($events) == 0 ) return array();
            $viewportFilter = new Z1SKY_Search_Distance();
            $viewportFilter->setIncludeIds($events);
            $eventsForViewPort = $viewportFilter->getViewportEvents($this->viewPortPrepearedBorder);
            $this->cache->save( $eventsForViewPort, $cacheKey, array(), $this->cacheLifetime);
        }
        return $eventsForViewPort;
    }

    public function getGroupsMarkersForViewport($groups)
    {
        $groupsForViewPort = array();
        $cacheKey = $this->hash. join('_', $this->viewPortPrepearedBorder).'_'.$this->zoomLevel.'coordinatesListForViewPortGroups';
        $eventsForViewPort = $this->cache->load($cacheKey);
        if ($eventsForViewPort === false) {
            if ( count($groups) == 0 ) return array();
            $viewportFilter = new Z1SKY_Search_Distance();
            $viewportFilter->setIncludeIds($groups);
            $groupsForViewPort = $viewportFilter->getViewportGroups($this->viewPortPrepearedBorder);
            //var_dump($groups);
            $this->cache->save( $groupsForViewPort, $cacheKey, array(), $this->cacheLifetime);
        }
        return $groupsForViewPort;
    }
    
    public function getViewportObjects($objects)
    {
        switch ($this->getMapType()){
            case 'eventSearch': 
                return $this->getEventsMarkersForViewport($objects);
                break;
            case 'groupSearch': 
                return $this->getGroupsMarkersForViewport($objects);
                break;
            default: 
                die('Undefined type of object');
        }
    }

    
    public function loadClustersFromCache()
    {
        $result = array('markers' => array(), 'clusters' => array());
        for ($x = $this->viewPortClustersIDs['minX']; $x <= $this->viewPortClustersIDs['maxX']; $x++)
        {
            for ($y = $this->viewPortClustersIDs['minY']; $y <= $this->viewPortClustersIDs['maxY']; $y++)
            {
                $currentCluster = $this->loadCluster($x, $y);
                //var_dump($currentCluster);
                if (!$currentCluster) {
                    $this->lostClusters[$x][$y] = true;
                    return false;
                }elseif($currentCluster['dataType'] == 'clusters'){
                    foreach ($currentCluster['data'] as $id => $markers){
                        $result[$currentCluster['dataType']][$id] = $markers;
                    }
                }elseif($currentCluster['dataType'] == 'markers'){
                    foreach ($currentCluster['data'] as $id => $markers){
                        foreach ($markers as $markerId => $marker){
                            $result[$currentCluster['dataType']][$markerId] = $marker;
                        }
                    }
                }
            }
        }
        return $result;
    }    
    
    private function saveCluster($data, $x, $y, $dataType)
    {
        $cacheKey = 'MapCluster_x_'.$x.'_y_'.$y.'_zoom_'.$this->zoomLevel.'_hash_'.$this->hash;
        //$cacheKeyMeta = 'MapClusterMeta_x_'.$x.'_y_'.$y.'_zoom_'.$this->zoomLevel.'_hash_'.$this->hash;
        //$cacheKeySemaphore = 'MapClusterSemaphore_x_'.$x.'_y_'.$y.'_zoom_'.$this->zoomLevel.'_hash_'.$this->hash;
        
        $this->cache->save(array( "data" => $data, "dataType" => $dataType), $cacheKey, array(), $this->cacheLifetime); // should be 2x$this->cacheLifetime
        //$this->cache->save(true, $cacheKeyMeta, array(), $this->cacheLifetime);
        // $this->cache->save(true, $cacheKeyMetaSemaphore, array(), $this->cacheLifetime); //should be enabled later
    }
    
    private function loadCluster($x, $y)
    {
        $cacheKey = 'MapCluster_x_'.$x.'_y_'.$y.'_zoom_'.$this->zoomLevel.'_hash_'.$this->hash;
        $cachedCluster = $this->cache->load($cacheKey);
        return $cachedCluster;
    }
    
    private function clusteringPostCaching()
    {
        //$this->lostClusters;
    }
    
    public function getClusters($idLatLng)
    {
        if ( !is_array($idLatLng) || count($idLatLng) == 0 ) return array("clusters" => array(), "markers" => array());
        $cacheKey = $this->hash. join('_', $this->viewPortPrepearedBorder).'_'.$this->zoomLevel.'markersListForViewPort';
        $markersForViewPort = $this->cache->load($cacheKey);
        if (!$markersForViewPort) {
           
            $cachedClusters = $this->loadClustersFromCache();
            if ($cachedClusters) {
                return $cachedClusters;
            }
           
            $multiplication = 1;
            $listOfResults = array();
            $clustered = array(); 
            while (true){
                $this->prepareGridSize($multiplication);
                $clustered = array();
                
                foreach($idLatLng as $id => $coordinates)
                {
                     if ($coordinates['lat'] == 0 && $coordinates['lng']) continue;
                     $x = $this->getXIndex($coordinates['lat']);
                     $y = $this->getYIndex($coordinates['lng']);
                     
                     $clustered[$x.'|'.$y][$id]= $coordinates;
                }
                $multiplication++;
                
                if (count($clustered) <= $this->markersLimit) break;
            }
            //print_r($clustered);
            
            foreach ($clustered as $clusterId => $currentCluster){
                if (count($currentCluster) == 0){
                    unset($clustered[$clusterId]);                
                }elseif (count($currentCluster) <= $this->minCountInCluster || $this->zoomLevel > 15){
                    list( $x, $y ) = explode('|', $clusterId);
                    $this->saveCluster(array($clusterId => $currentCluster), $x, $y, 'markers');
                    foreach ($currentCluster as $id => $markers){
                        $this->markersResult[$id] = $markers;
                    }
                }else{
                    $this->clustersResult[$clusterId] = self::getAverageCoordinatesForCluster($currentCluster);                
                    list( $x, $y ) = explode('|', $clusterId);
                    $this->saveCluster(array( $clusterId =>$this->clustersResult[$clusterId]), $x, $y, 'clusters');
                }
            }  
            
            $markersForViewPort = array("clusters" => $this->clustersResult, "markers" => $this->markersResult);
            $this->cache->save( $markersForViewPort, $cacheKey, array(), $this->cacheLifetime); 
        }
        return $markersForViewPort;
    }
    
    protected static function getAverageCoordinatesForCluster($markersList)
    {
        $count = count($markersList);

        if ($count == 0) return array();
        $sumLat = 0;
        $sumLng = 0;

        foreach ($markersList as $coordinates)
        {
            $sumLat += floatval($coordinates['lat']); 
            $sumLng += floatval($coordinates['lng']);
        }
        return array('lat' => ($sumLat/$count), 'lng' => ($sumLng/$count), 'count' => $count);
    }
    
      
    public static function getClusterMarkers($clusters)
    {
        $clusteredMarkers = array();
        if (isset($clusters['clusters'])) $clusters = $clusters['clusters'];
        $theme = Zend_Registry::get('AppTheme');
        foreach ( $clusters as $cluster)
        {
            $currentMarker = new Z1SKY_GMap_Marker();
            $currentMarker->setTitle("Cluster")
                ->setDescription("")
                ->setType(Z1SKY_GMap_MarkerTypeEnum::TYPE_CLUSTER);
            $currentMarker->setLatitude($cluster['lat']);
            $currentMarker->setLongitude($cluster['lng']);     
            if ($cluster['count'] > 100) {
                $currentMarker->setIcon($theme->common->images.'/map/markers/marker4.png');
            } elseif ($cluster['count'] > 9) {
                $currentMarker->setIcon($theme->common->images.'/map/markers/marker3.png');
            } else {
                $currentMarker->setIcon($theme->common->images.'/map/markers/marker2.png');
            }
                $currentMarker->setCount($cluster['count']);
            
            $clusteredMarkers[] = $currentMarker;
        }
            
        foreach ($clusteredMarkers as &$marker) {
            $marker = $marker->toArray();
        } 
        return $clusteredMarkers;
    } 
    
    public function getClustersMarkersArray($events)
    {
        $eventsLngLat = $this->getViewportObjects($events);
        $markers = $this->getClusters($eventsLngLat); 
        return $markers;
    }
        
}
