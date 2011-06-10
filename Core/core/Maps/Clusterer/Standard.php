<?php

class Maps_Clusterer_Standard implements Maps_Clusterer_iClusterer
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
        $this->cache = Maps_Cache::getInstance();//Warecorp_Cache::getCache();
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
    
    public function setCacheLifetime($value)
    {
        $this->cacheLifetime = $value;
        return $this; 
    }
    
    public function getCacheLifetime()
    {
        return $this->cacheLifetime;
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
        //$this->gridSize = abs(($this->viewPortBorders['nelng'] - $this->viewPortBorders['swlng'])/$this->stepOfClustering)*$multiplication;
        $this->gridSize = abs(($this->viewPortBorders['nelat'] - $this->viewPortBorders['swlat'])/$this->stepOfClustering)*$multiplication;
        return $this->gridSize;
    }
    
    public function getPrepearedViewport()
    {
        return $this->viewPortPrepearedBorder;
    }
    
    public function getViewport()
    {
        return $this->getPrepearedViewport();
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
    
    public function loadClustersFromCache()
    {
        return false;
        $result = array('markers' => array(), 'clusters' => array());
        for ($x = $this->viewPortClustersIDs['minX']; $x <= $this->viewPortClustersIDs['maxX']; $x++)
        {
            for ($y = $this->viewPortClustersIDs['minY']; $y <= $this->viewPortClustersIDs['maxY']; $y++)
            {
                $currentCluster = $this->loadCluster($x, $y);
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
        $cacheKey = md5('MapCluster_x_'.$x.'_y_'.$y.'_zoom_'.$this->getZoomLevel().'_hash_'.$this->hash);
        //$cacheKeyMeta = 'MapClusterMeta_x_'.$x.'_y_'.$y.'_zoom_'.$this->getZoomLevel().'_hash_'.$this->hash;
        //$cacheKeySemaphore = 'MapClusterSemaphore_x_'.$x.'_y_'.$y.'_zoom_'.$this->getZoomLevel().'_hash_'.$this->hash;
        
        $this->cache->save(array( "data" => $data, "dataType" => $dataType), $cacheKey, array(), $this->cacheLifetime); // should be 2x$this->cacheLifetime
        //$this->cache->save(true, $cacheKeyMeta, array(), $this->cacheLifetime);
        // $this->cache->save(true, $cacheKeyMetaSemaphore, array(), $this->cacheLifetime); //should be enabled later
    }
    
    private function loadCluster($x, $y)
    {
        $cacheKey = md5('MapCluster_x_'.$x.'_y_'.$y.'_zoom_'.$this->getZoomLevel().'_hash_'.$this->hash);
        $cachedCluster = $this->cache->load($cacheKey);
        return $cachedCluster;
    }
    
    private function clusteringPostCaching()
    {
        //$this->lostClusters;
    }
    
    public function getClusters(Array $idLatLng)
    {
        if ( !is_array($idLatLng) || count($idLatLng) == 0 ) return array("clusters" => array(), "markers" => array());
        
        $cacheKey = md5($this->hash. join('_', $this->viewPortPrepearedBorder).'_'.$this->getZoomLevel().'markersListForViewPort');
        $markersForViewPort = $this->cache->load($cacheKey);
        if ( !$markersForViewPort ) {
           
            $cachedClusters = $this->loadClustersFromCache();
            if ( $cachedClusters ) return $cachedClusters;
           
            $multiplication = 1;
            $listOfResults = array();
            $clustered = array(); 
            while (true){
                $this->prepareGridSize($multiplication);
                $clustered = array();
                
                foreach( $idLatLng as $id => $coordinates ) {
                     if ($coordinates['lat'] == 0 && $coordinates['lng']) continue;
                     $x = $this->getXIndex($coordinates['lat']);
                     $y = $this->getYIndex($coordinates['lng']);
                     
                     $clustered[$x.'|'.$y][$id]= $coordinates;
                }
                $multiplication++;
                
                if (count($clustered) <= $this->markersLimit) break;
            }
            
            foreach ( $clustered as $clusterId => $currentCluster ){
                if (count($currentCluster) == 0){
                    unset($clustered[$clusterId]);                
                }elseif (count($currentCluster) <= $this->minCountInCluster || $this->getZoomLevel() > 15){
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
    
    private static function getAverageCoordinatesForCluster($markersList)
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
    
      
    public function getClusterMarkers($clusters)
    {
        $clusteredMarkers = array();
        if ( isset($clusters['clusters']) ) $clusters = $clusters['clusters'];
        
        foreach ( $clusters as $cluster ) {
            $marker = array();

            $marker['id'] = null;
            $marker['type'] = 'cluster';
            $marker['lat'] = $cluster['lat'];
            $marker['lng'] = $cluster['lng'];
            
            if ($cluster['count'] > 100) {
                $marker['icon'] = '/maps/img/markers/m4.png';
            } elseif ($cluster['count'] > 9) {
                $marker['icon'] = '/maps/img/markers/m3.png';
            } else {
                $marker['icon'] = '/maps/img/markers/m2.png';
            }
            
            $marker['count'] = $cluster['count'];
            
            $clusteredMarkers[] = $marker;
        }
            
        return $clusteredMarkers;
    }         
}