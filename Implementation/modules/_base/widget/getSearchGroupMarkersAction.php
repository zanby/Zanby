<?php //    public function getSearchGroupMarkersAction ($returnAsArray=false) {
        
        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
        //$cache->clean(); 
        
        $cfgLifetime = Z1SKY_GMap_Utils::getMapMarkerTTL()*60;
        $result_cache_string = 'getSearchGroupMarkers_'.($this->_page->_user->getId()?'registered':'anonymous');
        if (!empty($this->params['width'])) $result_cache_string.='_width_'.$this->params['width'];
        if (!empty($this->params['height'])) $result_cache_string.='_height_'.$this->params['height'];
        if (!empty($this->params['groupContext'])) $result_cache_string.='_groupContext_'.$this->params['groupContext'];
        if (!empty($this->params['zoomLevel'])) $result_cache_string.='_zoomLevel_'.$this->params['zoomLevel'];
        if (!empty($this->params['ne'])) $result_cache_string.='_ne_'.$this->params['ne'];   
        if (!empty($this->params['sw'])) $result_cache_string.='_sw_'.$this->params['sw'];   
        $_result = $cache->load($result_cache_string);
        
        if (empty($_result) || $returnAsArray) {
            $cacheAdditionalKey = '';
            $customCenter = array();
            $maxMinCoordinates = array();
            $autoPosition = 0;
            $markersArray = array();
            $countryId = 1;
            $groupsList = array();
            
            if (isset($this->params['zoomLevel'])) {
                if (strpos($this->params['zoomLevel'], 'District') === 0) {
                    $parr = explode('_',str_replace('District', '', $this->params['zoomLevel']));
                    if (isset($parr[0])) $this->params['district'] = intval($parr[0]);
                    if (isset($parr[1])) $this->params['state'] = intval($parr[1]);
                    $_SESSION['group_search_widget']['district'] = $this->params['district'];
                    $state = Warecorp_Location_State::create($this->params['state']);
                    if (!empty($state->id)) {
                        $_SESSION['group_search_widget']['state_code'] = $state->code;    
                    }
                } elseif (strpos($this->params['zoomLevel'], 'State') === 0) {
                    $this->params['state'] = intval(str_replace('State', '', $this->params['zoomLevel']));  
                    $state = Warecorp_Location_State::create($this->params['state']);
                    if (!empty($state->id)) {
                        $_SESSION['group_search_widget']['state_code'] = $state->code;    
                    }
                }
                $cacheAdditionalKey.='zoomLevel'.$this->params['zoomLevel'];   
            }
            
            
            $groupSearch = new Z1SKY_Group_Search();
            $groupSearch->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
            
            if (!empty($this->params['groupContext'])) {
                $this->params['organization'] = $this->params['groupContext'];
                $cacheAdditionalKey.='groupContext'.$this->params['groupContext'];
            }
                   
            if (!isset($this->params['state']) && !isset($this->params['district'])){
                if ($this->_page->_user->getId() > 0){
                    $this->params['state']      = 0;
                    $this->params['district']   = 0;                                         
                }
                else{
                    $this->params['state']      = 0;
                    $this->params['district']   = 0;
                }
                $this->params['preset']     = 'new';
            }

            $_SESSION['GROUPS_MARKERS_CACHE_ADDITIONAL_KEY'] = $cacheAdditionalKey;
            $groups = $cache->load('CPP_search_groups_d_'.md5($cacheAdditionalKey));
            
            if (!is_array($groups) || (isset($this->params['preset']) && ($this->params['preset'] == 'new')) ) { // new search
                $groups = array();                            
                $_SESSION['group_search_widget'] = array();
                $this->params['keywords'] = isset($this->params['keywords']) ? trim($this->params['keywords']) : "";
        
                $groupSearch->setKeywords($this->params['keywords']);
                $this->params['keywords'] = (is_array($groupSearch->keywords) && count($groupSearch->keywords)) ? implode(' ', $groupSearch->keywords) : "";
                
                $s = &$_SESSION['group_search_widget'];
                $s['keywords']  = '';
                $s['country']   = $countryId;
                $s['state']     = isset($this->params['state'])     ? $this->params['state']  : "0";
        
                $s['district'] = isset($this->params['district']) ? $this->params['district'] : "0";
                $s['organization'] = isset($this->params['organization']) ? $this->params['organization'] : "";
                
                $s['city'] = "0";
                $s['searchType'] = 2;
        
                $s['category']  = isset($this->params['category'])  ? $this->params['category'] : "0";
                
                $groupSearch->setDefaultOrder($s);
                $groups = $groupSearch->searchByCriterions($s);
                
                if ($groupSearch->paramsOrder !== null) {
                    $this->params['order'] = $groupSearch->paramsOrder['order'];
                    $this->params['direction'] = $groupSearch->paramsOrder['direction'];
                }
        
                $state       = Warecorp_Location_State::create($s['state']); 
        
                $districtStr = ($s['district'] == 0)?'%':$s['district'];
                 
                $s['state_code'] = $state->code;                             
                
                $groupListObj = new Warecorp_Group_List();
                /*$allGroups = $groupListObj->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
                                       ->setPrivate(Warecorp_Group_Enum_GroupPrivacy::GROUP_PRIVACY_PUBLIC)
                                       ->setIncludeIds($groups)
                                       ->addWhere('context_regional_flag like "%\_'.$state->code.$districtStr.'"')
                                       ->returnAsAssoc();
                if ($s['organization']!=''){
                     $groupListObj->addWhere('mainGroupUID = "'.$s['organization'].'"');
                }*/
                $allGroups = $groupListObj->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)           
                                       ->setPrivate(Warecorp_Group_Enum_GroupPrivacy::GROUP_PRIVACY_PUBLIC)
                 //                      ->setIncludeIds($groups)
                                       ->addWhere('context_regional_flag like "%\_'.$state->code.$districtStr.'"')
                                       ->returnAsAssoc();
                if ($s['organization']!=''){
                    $orgGroup = Warecorp_Group_Factory::loadByID($s['organization']);
                    $groupsMembersIds = $orgGroup->getGroups()->returnAsAssoc()->getList();
                    $groupsMembersIds[0] = 0; 
                    $groupListObj->setIncludeIds(array_keys($groupsMembersIds));  
                    //$groupListObj->addWhere('mainGroupUID = "'.$s['organization'].'"');
                }
                                               
                $groups =  $groupListObj->getList();                         
            }
            //var_dump($groups);
            if ($groups === null) {$groups = array();}
            $cache->save($groups, 'CPP_search_groups_d_'.md5($cacheAdditionalKey), array(), 7200);     
            
            list ($neLat, $neLng) = explode(',', $this->params['ne']);
            list ($swLat, $swLng) = explode(',', $this->params['sw']);
            
            $mapClusterer = new Warecorp_Widget_Map_Clusterer();
            $mapClusterer->setZoomLevel($this->params['currentZoomLevel']);
            $mapClusterer->setMapType("groupSearch");
            $mapClusterer->setMapSize($this->params['width'], $this->params['height']);
            $mapClusterer->setCacheHash(md5($result_cache_string));
            $mapClusterer->setViewPort($neLat, $neLng, $swLat, $swLng);
            $markers = $mapClusterer->getClustersMarkersArray(array_keys($groups)); 
            $clustersMarkers = Warecorp_Widget_Map_Clusterer::getClusterMarkers($markers);
            $viewPort = $mapClusterer->getPrepearedViewport(); 
            //var_dump($markers);
            $groups = array();
            foreach ($markers['markers'] as $key => $event) {
                 $groups[$key] = $key;
            }            
            
            if (!empty($groups)) {
                $groupListObj = new Warecorp_Group_List();
                $allGroups = $groupListObj->setIncludeIds(array_keys($groups))->getList();

                if ($returnAsArray) return $allGroups;

                $summary = new Z1SKY_GMap_GroupedMarkerSummary();
                Warecorp::loadSmartyPlugin('modifier.truncate.php');
                $markerBuilder = new Z1SKY_GMap_MarkerBuilder();
                $markers = array();

                foreach ($allGroups as $item) {
                    if ($marker = $markerBuilder->buildMarker($item)) {

                        $marker->setUrlTarget('_parent');
                        $HTML_params = array(
                                'A' => $item->getAvatar()->setWidth(37)->setHeight(38)->getImage($this->_page->_user),
                                'T' => htmlspecialchars( smarty_modifier_truncate(strip_tags($item->getName()),30,'...',true) ),
                                'D' => htmlspecialchars( smarty_modifier_truncate(strip_tags($item->getDescription()),43,'...',true) ),
                                'U' => $marker->getUrl(),
                                'T1' => $marker->getUrlTarget(),
                                'TT' => 'g'
                            );
                            $marker->setHtml($HTML_params);
                               
                        if ($item->getMapMarkerHash() || $item->isCongressionalDistrict() ) {
                            $summary->addDistrict($item->getCity()->getState()->id);
                            $marker->setIcon($item->getMarker()->getSrcImg());
                        } elseif ($item->getMainGroupUID()) {
                            $summary->addOrganization($item->getCity()->getState()->id, $item->getMainGroupUID());
                            $gr = Warecorp_Group_Factory::loadByGroupUID($item->getMainGroupUID(),Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                            if ($gr->getMapMarkerHash()) {
                                $marker->setIcon($gr->getMarker()->getSrcImg());
                                $marker->setTooltipText($gr->getName());
                            } else {
                                $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                            }
                        } else {
                            $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                        }
                        
    
                        $markersArray[] = $marker;
                           
                    }
                }
                 //  $markers = Z1SKY_GMap_GroupedMarker::groupMarkers($markers);    
                foreach ($markersArray as &$marker) {
                    $marker = $marker->toArray();
                }
                
            }
            
            $allMarkers = array_merge($markersArray, $clustersMarkers); 
            $zoomLevel = $this->params['currentZoomLevel'];   
            $markersArray = array(
                array('zoom'=>array($zoomLevel,$zoomLevel), "places"=>$allMarkers)
            );
            $templates = array('g' => $this->view->getContents('googleMaps/group.tpl'));
            $_result = array('templates'=>$templates, 'markersArray'=>$markersArray, 'autoPosition'=>$autoPosition, 'maxMinCoordinates' => $maxMinCoordinates, 'zoomLevel' => $zoomLevel, 'customCenter' => $customCenter, 'viewport' => $viewPort);
            

            $cache->save($_result, $result_cache_string, array(), $cfgLifetime);
            $this->printMarkersCacheHeaders();
        } else {
           $this->printMarkersCacheHeaders();
        }

        echo Zend_Json::encode($_result);exit;
        
//    }