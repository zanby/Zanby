<?php //    public function getEventMarkersAction() {
        
    	$cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
    	//$cache->clean();
        
    	$cfgLifetime = Z1SKY_GMap_Utils::getMapMarkerTTL()*60;
        $result_cache_string = 'getEventMarkers_'.($this->_page->_user->getId()?'registered':'anonymous');
        if (!empty($this->params['width'])) $result_cache_string.='_width_'.$this->params['width'];
        if (!empty($this->params['height'])) $result_cache_string.='_height_'.$this->params['height'];
        if (!empty($this->params['groupContext'])) $result_cache_string.='_groupContext_'.$this->params['groupContext'];
        if (!empty($this->params['displayRange'])) $result_cache_string.='_displayRange_'.$this->params['displayRange'];
        if (!empty($this->params['eventsDisplayType'])) $result_cache_string.='_eventsDisplayType_'.$this->params['eventsDisplayType'];
        if (!empty($this->params['eventToDisplayId'])) $result_cache_string.='_eventToDisplayId_'.$this->params['eventToDisplayId'];
        if (!empty($this->params['ne'])) $result_cache_string.='_ne_'.$this->params['ne'];   
        if (!empty($this->params['sw'])) $result_cache_string.='_sw_'.$this->params['sw']; 

        $_SESSION['EVENTS_MARKERS_RESULT_CACHE_STRING'] = 'eventsMapCO'.$result_cache_string;
        $_result = $cache->load($result_cache_string);
        
        if (empty($_result)) {
        
	    	$maxMinCoordinates = array();
	        $autoPosition = 0;
	        
            $events = $cache->load('eventsMapCO'.$result_cache_string);
            if (!$events){
	    	    if (!empty($this->params['groupContext'])) {
	               $group = Warecorp_Group_Factory::loadById(intval($this->params['groupContext']));    
	            }    
	            
	            //Layers Zoom Level
	            if (empty($this->params['displayRange']) || ($group->getId() && $group->getGroupType() == 'family') ) {
	                $_country = ($group->getId())?($group->getCountry()):(new Warecorp_Location_Country(1));
	                $maxMinCoordinates = $_country->getMaxMinCoordinates();
	                $zoomLevel = 'national';    
	            }
	            
	            if ($group->getId()) {
	                //FAMILY
	                if ($group->getGroupType() == 'family'){
	                    if (empty($this->params['displayRange'])) {
	                        $groupListObj = new Warecorp_Group_List();
	                        $allGroups = $groupListObj->returnAsAssoc()->setAssocValue('id')->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
	                        ->setPrivate(Warecorp_Group_Enum_GroupPrivacy::GROUP_PRIVACY_PUBLIC);
	                        //$allGroups->addWhere('context_regional_flag like "z1sky\_district\_%"');
	                    } else {
	                        $allGroups = $group->getGroups();
	                    }
	                    
	                //SIMPLE    
	                } elseif ($group->getGroupType() == 'simple'){
	                    $groupListObj = new Warecorp_Group_List();
	                    $allGroups = $groupListObj->returnAsAssoc()->setAssocValue('id')->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
	                    ->setPrivate(Warecorp_Group_Enum_GroupPrivacy::GROUP_PRIVACY_PUBLIC);
	                
	                    if (empty($this->params['displayRange'])) {
	                        //Bug #7043 - should display regular groups too
	                	    //$allGroups->addWhere('context_regional_flag like "z1sky\_district\_%"');
	                    //JUST_MY_DISTRICT
	                    } else {
	                        //district coordinates
	                        $_tmp = $group->getCongressionalDistrict();
	                        $maxMinCoordinates = Z1SKY_Location_District :: getCoordinates(array(substr($_tmp, 2)), substr($_tmp, 0, 2));
	                        $zoomLevel = 'district';
	                        
	                        $allGroups->addWhere('context_regional_flag like "%\_'.$group->getCongressionalDistrict().'"');
	                    }   
	                }
	            }
	            
	            $allGroups = $allGroups->getList();
	            
	            $objNda = new Warecorp_Nda_Item();
	            if (!empty($this->params['eventsDisplayType']) && !empty($this->params['eventToDisplayId']) ) {
	                $objNda->loadById(intval($this->params['eventToDisplayId']));    
	            }
	            
	            $events = array();
	            
	            //NDA
	            if ($objNda->getId()) {
                        $eventsList = new Warecorp_Nda_Event_List($objNda);
	                $events = $eventsList->setFetchMode(Warecorp_Nda_List_Enum_FetchMode::ASSOC)->getEventListByNDA();
	            //ALL EVENTS	
	            } else {
                        $eventsSearch = new Warecorp_ICal_Search();
                        $eventsSearch->countryId = 1;
                        $params = array( "when" => 'future' );
                        $eventsSearch->setUser($this->_page->_user)->setFilter('owner','groups', $allGroups)->setReturnAsObjects(false)->parseParamsWhen($params);
                        $events = $eventsSearch->searchByCriterions();
	            }
                $cache->save($events, 'eventsMapCO'.$result_cache_string, array(), $cfgLifetime );
            }

            
            
            list ($neLat, $neLng) = explode(',', $this->params['ne']);
            list ($swLat, $swLng) = explode(',', $this->params['sw']);

            
            $mapClusterer = new Warecorp_Widget_Map_Clusterer();
            $mapClusterer->setZoomLevel($this->params['currentZoomLevel']);
            $mapClusterer->setMapType("eventSearch");
            $mapClusterer->setCacheHash(md5($result_cache_string));
            $mapClusterer->setViewPort($neLat, $neLng, $swLat, $swLng);
            $markers = $mapClusterer->getClustersMarkersArray($events); 
            $clustersMarkers = Warecorp_Widget_Map_Clusterer::getClusterMarkers($markers);
            $viewPort = $mapClusterer->getPrepearedViewport();
            $events = array();
            foreach ($markers['markers'] as $key => $event) {
                 $events[] = new Warecorp_ICal_Event($key);
            }

            

            $summary = new Z1SKY_GMap_GroupedMarkerSummary();
            Warecorp::loadSmartyPlugin('modifier.truncate.php');
            $markerBuilder = new Z1SKY_GMap_MarkerBuilder();
            $markersArray = array();
            $Warecorp_Nda_Item = new Warecorp_Nda_Item();

            $eventsIdsForClustering = array();
	        foreach ($events as $item) {
	                
	            if ($marker = $markerBuilder->buildMarker($item)) {
	                $marker->setUrlTarget('_parent');
                        $_nda = $Warecorp_Nda_Item->hasEvent($item);
                        $eventsIdsForClustering[] = $item->getId();
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
                        $marker->setHtml($HTML_params);

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
	                } else {
                            $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                    }
	                $markersArray[] = $marker;
	               
	            }
	        }
	        
	        foreach ($markersArray as &$marker) {
	            $marker = $marker->toArray();
	        }

            $allMarkers = array_merge($markersArray, $clustersMarkers); 
	                
	        $zoomLevel = $this->params['currentZoomLevel'];   
	        $customCenter=array('longitude' => Warecorp_Common_Functions :: getCentralCoordinateFrom2Longitudes($maxMinCoordinates['minlongitude'], $maxMinCoordinates['maxlongitude']), 'latitude' => ($maxMinCoordinates['maxlatitude']-$maxMinCoordinates['minlatitude'])/2+$maxMinCoordinates['minlatitude']);

                $markersArray = array(
                    array('zoom'=>array($zoomLevel,$zoomLevel), "places"=>$allMarkers)
                );                   $templates = array('e' => $this->view->getContents('googleMaps/event.tpl'), 's' => $this->view->getContents('googleMaps/state.tpl'));
                $_result = array('templates'=>$templates, 'markersArray'=>$markersArray, 'autoPosition'=>$autoPosition, 'maxMinCoordinates' => $maxMinCoordinates, 'zoomLevel' => $zoomLevel, 'customCenter' => $customCenter, 'viewport'=>$viewPort ) ;

	        $cache->save($_result, $result_cache_string, array(), $cfgLifetime);
            $this->printMarkersCacheHeaders();
        } else {
           $this->printMarkersCacheHeaders();   
        }
        
        echo Zend_Json::encode($_result);exit;
  //  }
