<?php
        
        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
        //$cache->clean();
        
        $cfgLifetime = Z1SKY_GMap_Utils::getMapMarkerTTL()*60;
        $result_cache_string = 'getSearchEventMarkers_'.($this->_page->_user->getId()?'registered':'anonymous');
        if (!empty($this->params['width'])) $result_cache_string.='_width_'.$this->params['width'];
        if (!empty($this->params['height'])) $result_cache_string.='_height_'.$this->params['height'];
        if (!empty($this->params['zoomLevel'])) $result_cache_string.='_zoomLevel_'.$this->params['zoomLevel'];
        if (!empty($this->params['eventWhen'])) $result_cache_string.='_eventWhen_'.str_replace(' ','_',$this->params['eventWhen']);
        if (!empty($this->params['eventToDisplayId'])) $result_cache_string.='_eventToDisplayId_'.$this->params['eventToDisplayId'];   
        if (!empty($this->params['ne'])) $result_cache_string.='_ne_'.$this->params['ne'];   
        if (!empty($this->params['sw'])) $result_cache_string.='_sw_'.$this->params['sw'];   
        

        $_result = $cache->load($result_cache_string);
        
        if (empty($_result)) { //should be removed true
            
            $cacheAdditionalKey = '';
            $customCenter = array();
            $maxMinCoordinates = array();
            $autoPosition = 0;
            $markersArray = array();
            
            $markersD = array();
            $markersDistance = array();
            $markersArrayD = array();
            $markersArrayDistance = array();
            
            $eventsList = array();
            
            $Warecorp_Nda_Item = new Warecorp_Nda_Item();
            
            $objNda = new Warecorp_Nda_Item;
            if (isset($this->params['eventToDisplayId'])) {
                 $objNda->loadById(intval($this->params['eventToDisplayId']));
                 $cacheAdditionalKey.='eventToDisplayId'.intval($this->params['eventToDisplayId']);       
            }
            
            if (isset($this->params['zoomLevel'])) {
                if (strpos($this->params['zoomLevel'], 'District') === 0) {
                    $parr = explode('_',str_replace('District', '', $this->params['zoomLevel']));
                    if (isset($parr[0])) $this->params['district'] = intval($parr[0]);
                    if (isset($parr[1])) $this->params['state'] = intval($parr[1]);
                    $_SESSION['event_search_widget']['district'] = $this->params['district'];
                    $state = Warecorp_Location_State::create($this->params['state']);
                    if (!empty($state->id)) {
                        $_SESSION['event_search_widget']['state_code'] = $state->code;    
                    }
                } elseif (strpos($this->params['zoomLevel'], 'State') === 0) {
                    $this->params['state'] = intval(str_replace('State', '', $this->params['zoomLevel']));  
                    $state = Warecorp_Location_State::create($this->params['state']);
                    if (!empty($state->id)) {
                        $_SESSION['event_search_widget']['state_code'] = $state->code;    
                    }
                }
                $cacheAdditionalKey.='zoomLevel'.$this->params['zoomLevel'];   
            }
            
            if (!empty($this->params['eventWhen'])) {
                $this->params['when'] = str_replace('_', ' ', $this->params['eventWhen']);
                $cacheAdditionalKey.='eventWhen'.$this->params['eventWhen'];
            }
            
            if (!isset($this->params['state']) && !isset($this->params['district'])){
                if ($this->_page->_user->getId() > 0){
                    $this->params['state']      = 0; // Warecorp_Location_State::findByCode($this->_page->_user->getProfile()->getDistrictState(), 1)->id;
                    $this->params['district']   = 0; //$this->_page->_user->getProfile()->getDistrictNumber();                                         
                }
                else{
                    $this->params['state']      = 0;
                    $this->params['district']   = 0;
                }
                $this->params['preset']     = 'new';
            }
            
            $events = null;
            $_SESSION['EVENTS_MARKERS_CACHE_ADDITIONAL_KEY'] = $cacheAdditionalKey;
            $eventsD = $cache->load('CPP_search_events_d_'.md5($cacheAdditionalKey));
            $eventsDistance = $cache->load('CPP_search_events_distance_'.md5($cacheAdditionalKey));
            
            if (!is_array($eventsD) || !is_array($eventsDistance)  || (isset($this->params['preset']) && ($this->params['preset'] == 'new')) ) { // new search
            
                $eventsD = array();                            
                $_SESSION['event_search_widget'] = array();
                $this->params['keywords'] = isset($this->params['keywords']) ? trim($this->params['keywords']) : "";
                
                $s = &$_SESSION['event_search_widget'];
                $s['keywords']  = '';
                $s['state']     = isset($this->params['state'])     ? $this->params['state']  : "0";
                $s['district'] = isset($this->params['district']) ? $this->params['district'] : "0";
                $s['when'] = isset($this->params['when']) ? $this->params['when'] : "all future";
                
                $s['nda'] = ($objNda->getId())?($objNda->getId()):0;
                
                $state       = Warecorp_Location_State::create($s['state']);
                $s['state_code'] = $state->code;      
                $districtStr = ($s['district'] == 0)?'%':$s['district'];
                $ndaEventsIds = array();
                if ($s['nda'] != 0){
                    $ndaEventList = new Warecorp_Nda_Event_List($objNda);
                    $ndaEventsIds = $ndaEventList->getEventListByNDA();
                }  
                if (($s['nda'] != 0 && count($ndaEventsIds) > 0) || $s['nda'] == 0){
                    $groupListObj = new Warecorp_Group_List();
                    $CDGroups     = $groupListObj->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
                                           ->setPrivate(Warecorp_Group_Enum_GroupPrivacy::GROUP_PRIVACY_PUBLIC)
                                           ->addWhere('context_regional_flag like "%\_'.$state->code.$districtStr.'"')
                                           ->returnAsAssoc()->getList();
                    
                    if (count($CDGroups) > 0){
                        $eventsSearch = new Warecorp_ICal_Search();
                        //$eventsSearch->countryId = 1;
                        $eventsSearch->setUser($this->_page->_user)->setFilter('owner','groups', $CDGroups);
                        $eventsSearch->setReturnAsObjects(false)->parseParamsWhen($s);
                     
                        $eventsD = $eventsSearch->searchByCriterions();
                        if ($s['nda'] != 0) {
                            $eventsD = array_intersect($eventsD, $ndaEventsIds);
                        }
                        
                    }
                    
                    $eventsDistance = array();
                    if ( $s['state'] != 0 && $s['district'] != 0 ) {
                        $districtGroup = Warecorp_Group_Factory::loadByRegionalFlag('z1sky_district_'.$state->code.$s['district']);
                        
                        $searchByDistance = new Z1SKY_Search_Distance();
                        if (count($ndaEventsIds) > 0) { 
                            $searchByDistance->setIncludeIds( $ndaEventsIds );
                        }
                        //echo $districtGroup->getLatitude().", ".$districtGroup->getLongitude();
                        $distanceResults = $searchByDistance->getEventListByDistance( $districtGroup->getLatitude(), $districtGroup->getLongitude(), 20000.0 );
                        
                        //echo " distanceResults - "; print_r($distanceResults);
                        
                        if (count($distanceResults) > 0){
                                
                            $eventsSearchDistance = new Warecorp_ICal_Search();
                            //$eventsSearchDistance->countryId = 1;
                            $params = array( "when" => $s['when'] );
                            $eventsSearchDistance->setUser($this->_page->_user);
                            $eventsSearchDistance->setIncludeIds( $distanceResults );
                            $eventsSearchDistance->setReturnAsObjects(false)->parseParamsWhere($params);
                            $eventsDistance = $eventsSearchDistance->searchByCriterions();
                        }            
                        //echo " distanceSearchResults - "; print_r($eventsDistance);
                    }elseif ($s['state'] != 0) {

                        $eventsSearch = new Warecorp_ICal_Search();
                        $eventsSearch->countryId = 1;
                        $eventsSearch->stateId = $state->id;
                        $params = array( "when" => $s['when'] );
                        $eventsSearch->setUser($this->_page->_user);
                        $eventsSearch->setReturnAsObjects(false)->parseParamsWhen($params);
                        $eventsSearch->defaultOrder = 'ce.event_dtstart asc'; 
                        $eventsDistance = $eventsSearch->searchByCriterions();
                    }
        
                    $events = array_merge($eventsD, $eventsDistance );
                }    
            }
            else{        
                $events = array_merge($eventsD, $eventsDistance );
            }
            
            $eventsSearch = new Warecorp_ICal_Search();
            
            $paramsOr = array('page' => 1, 'order' => 'date', 'direction' => 'asc', 'when' => "all future");
            $eventsSearch->parseParamsWhere( $paramsOr );
            $events = $eventsSearch->getOrdered($paramsOr, $events, 10000);
            
            $events = array_unique($events);
            //var_dump($events);
            if ($events === null) {$events = array();}
            if ($eventsD === null) {$eventsD = array();}
            if ($eventsDistance === null) {$eventsDistance = array();}
            
            $cache->save($eventsD, 'CPP_search_events_d_'.md5($cacheAdditionalKey), array(), 7200);                  
            $cache->save($eventsDistance, 'CPP_search_events_distance_'.md5($cacheAdditionalKey), array(), 7200);
            
            list ($neLat, $neLng) = explode(',', $this->params['ne']);
            list ($swLat, $swLng) = explode(',', $this->params['sw']);

            $mapClusterer = new Warecorp_Widget_Map_Clusterer();
            $mapClusterer->setZoomLevel($this->params['currentZoomLevel']);
            $mapClusterer->setMapType("eventSearch");
            $mapClusterer->setMapSize($this->params['width'], $this->params['height']);
            $mapClusterer->setCacheHash(md5($result_cache_string));
            $mapClusterer->setViewPort($neLat, $neLng, $swLat, $swLng);
            $markers = $mapClusterer->getClustersMarkersArray($events); 
            $clustersMarkers = Warecorp_Widget_Map_Clusterer::getClusterMarkers($markers);
            $viewPort = $mapClusterer->getPrepearedViewport();

            foreach ($markers['markers'] as $key => $event) {
                 $eventsList[] = new Warecorp_ICal_Event($key);
            }

            $summary = new Z1SKY_GMap_GroupedMarkerSummary();
            if (!empty($eventsList)) {
                Warecorp::loadSmartyPlugin('modifier.truncate.php');
                $markerBuilder = new Z1SKY_GMap_MarkerBuilder();
                $markers = array();
                foreach ($eventsList as $item) {
                    
                    if ($marker = $markerBuilder->buildMarker($item)) {
                        $marker->setUrlTarget('_parent');
                        $marker->setId($item->getId());
                        $_nda = $Warecorp_Nda_Item->hasEvent($item);
                            $HTML_params = array(
                                'A' => ($item->getEventPicture()!==null)?($item->getEventPicture()->setWidth(37)->setHeight(38)->getImage($this->_page->_user)):($this->AppTheme->images.'/decorators/event/fakeImage.gif'),
                                'T' => htmlspecialchars( smarty_modifier_truncate(strip_tags($item->getTitle()),30,'...',true) ),
                                'U' => $marker->getUrl(),
                                'T1' => $marker->getUrlTarget(),
                                'D' => htmlspecialchars( smarty_modifier_truncate(strip_tags($item->getDescription()),170,'...',true) ),
                                'DT' => $item->displayDate('list.view', $this->_page->_user),
                                'N' => ($_nda)?'<br><a target="'.$marker->getUrlTarget().'" href="'.BASE_URL.'/en/event/'.htmlspecialchars($_nda->getPath()).'/">'.htmlspecialchars($_nda->getName()).'</a>':'',
                                'TT' => 'e'
                            );
                            //$marker->setHtml($HTML_params);
                            $eGroup = $item->getOwner();
                            if ($eGroup->isCongressionalDistrict()) {
                                $summary->addDistrict($item->entityStateId());
                            } elseif ($eGroup->getMainGroupUID()) {
                                $summary->addOrganization($item->entityStateId(), $eGroup->getMainGroupUID());
                            }
                            if ($item->getMarkerGroupId()) {
                            $gr = Warecorp_Group_Factory::loadById($item->getMarkerGroupId());
                            if ( $gr->getMapMarkerHash() ) {
                                $marker->setIcon($gr->getMarker()->getSrcImg());
                            } else {
                                $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                            }
                            $marker->setTooltipText($gr->getName());
                        } else {
                            $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                        }
                            
    
                        if (in_array($item->getId(),$eventsD)) {
                            $markersD[] = $marker;  
                        }
                        $markersDistance[] = $marker;
                           
                    }
                }
                    
                foreach ($markersD as $marker) {
                    $markersArrayD[] = $marker->toArray();
                }
                foreach ($markersDistance as $marker) {
                    $markersArrayDistance[] = $marker->toArray();
                }
            }
            
            $allMarkers = array_merge($markersArrayDistance, $clustersMarkers);
            //print_r($allMarkers);

            $zoomLevel = $this->params['currentZoomLevel'];
            
            $markersArray = array(
                array('zoom'=>array($zoomLevel,$zoomLevel), "places"=>$allMarkers)
            );    
            $templates = array();
           //$templates = array('e' => $this->view->getContents('googleMaps/event.tpl'), 's' => $this->view->getContents('googleMaps/state.tpl'));
            $_result = array('templates'=>$templates, 'markersArray'=>$markersArray, 'autoPosition'=>$autoPosition, 'maxMinCoordinates' => $maxMinCoordinates, 'zoomLevel' => $zoomLevel, 'clusteringZoomLevel'=>intval(Z1SKY_GMap_Utils::getMapClusteringZoomLevel()), 'viewport' => $viewPort) ;
            
            //['nelat'], 'viewportNElng' => $viewPort['nelng'], 'viewport' => $viewPort['swlat'], 'viewportSWlng' => $viewPort['swlng'] 
            
            $cache->save($_result, $result_cache_string, array(), $cfgLifetime);
        } 

        $this->printMarkersCacheHeaders();  

        echo Zend_Json::encode($_result);exit;
        
        

    
