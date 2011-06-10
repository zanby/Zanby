<?php //   public function getGroupMarkersAction() {

        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
        //$cache->clean();

        $cfgLifetime = Z1SKY_GMap_Utils::getMapMarkerTTL()*60;
        $result_cache_string = 'getGroupMarkers_'.($this->_page->_user->getId()?'registered':'anonymous');
        if (!empty($this->params['width'])) $result_cache_string.='_width_'.$this->params['width'];
        if (!empty($this->params['height'])) $result_cache_string.='_height_'.$this->params['height'];
        if (!empty($this->params['groupContext'])) $result_cache_string.='_groupContext_'.$this->params['groupContext'];
        if (!empty($this->params['displayRange'])) $result_cache_string.='_displayRange_'.$this->params['displayRange'];
        if (!empty($this->params['ne'])) $result_cache_string.='_ne_'.$this->params['ne'];   
        if (!empty($this->params['sw'])) $result_cache_string.='_sw_'.$this->params['sw'];   

        $_SESSION['GROUPS_MARKERS_RESULT_CACHE_STRING'] = 'all_markers_'.$result_cache_string;
        $_result = $cache->load($result_cache_string);
        $_all = $cache->load('all_markers_'.$result_cache_string);

        if ( empty($_result) || empty($_all) ) {
            $maxMinCoordinates = array();
            $autoPosition = 0;
            $allGroups = array();

            if (!empty($this->params['groupContext'])) {
                $group = Warecorp_Group_Factory::loadById(intval($this->params['groupContext']));
            }

            //Layers Zoom Level
            if (empty($this->params['displayRange']) || ($group->getId() && $group->getGroupType() == 'family') ) {
                $_country = ($group->getId())?($group->getCountry()):(new Warecorp_Location_Country(1));
                $maxMinCoordinates = $_country->getMaxMinCoordinates();
                $zoomLevel = 'national';
            }

            //
            if ($group->getId()) {
            //FAMILY
                if ($group->getGroupType() == 'family') {
                    if (empty($this->params['displayRange'])) {
                        $groupListObj = new Warecorp_Group_List();
                        $allGroups = $groupListObj->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
                            ->setPrivate(Warecorp_Group_Enum_GroupPrivacy::GROUP_PRIVACY_PUBLIC)->returnAsAssoc();

                        // :) Bug #7043 - should display regular groups too
                        $allGroups->addWhere('context_regional_flag NOT LIKE "z1sky\_district\_%"');

                    } else {
                        $allGroups = $group->getGroups();
                        $allGroups->addWhere('context_regional_flag NOT LIKE "z1sky\_district\_%"');
                    }

                //SIMPLE
                } elseif ($group->getGroupType() == 'simple') {
                    $groupListObj = new Warecorp_Group_List();
                    $allGroups = $groupListObj->setTypes(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
                        ->setPrivate(Warecorp_Group_Enum_GroupPrivacy::GROUP_PRIVACY_PUBLIC)->returnAsAssoc();

                    if (empty($this->params['displayRange'])) {
                    // :) Bug #7043 - should display regular groups too
                        $allGroups->addWhere('context_regional_flag NOT LIKE "z1sky\_district\_%"');
                    //JUST_MY_DISTRICT
                    } else {
                    //district coordinates
                        $_tmp = $group->getCongressionalDistrict();
                        $maxMinCoordinates = Z1SKY_Location_District :: getCoordinates(array(substr($_tmp, 2)), substr($_tmp, 0, 2));
                        $zoomLevel = 'district';
                        $allGroups->addWhere('context_regional_flag like "%\_'.$group->getCongressionalDistrict().'"');
                    }
                }


                $allGroups = $allGroups->getList();

                reset($allGroups);
                list($tempId, $tempGroup) = each($allGroups);reset($allGroups);

                if ( !empty($tempGroup) ) {
                    if ( $tempGroup instanceof Warecorp_Group_Base ) {
                        $grps = array();
                        foreach ( $allGroups as &$group )
                            $grps[] = $group->getId();
                        $cache->save($grps, 'all_markers_'.$result_cache_string, array(), $cfgLifetime);
                        unset($grps);
                    }
                    else {
                        $cache->save(array_keys($allGroups), 'all_markers_'.$result_cache_string, array(), $cfgLifetime);
                    }
                }
                else {
                    $cache->save(array(), 'all_markers_'.$result_cache_string, array(), $cfgLifetime);
                }
                unset($tempGroup, $tempId);
            }
            
            list ($neLat, $neLng) = explode(',', $this->params['ne']);
            list ($swLat, $swLng) = explode(',', $this->params['sw']);
            
            $mapClusterer = new Warecorp_Widget_Map_Clusterer();
            $mapClusterer->setZoomLevel($this->params['currentZoomLevel']);
            $mapClusterer->setMapType("groupSearch");
            $mapClusterer->setMapSize($this->params['width'], $this->params['height']);
            $mapClusterer->setCacheHash(md5($result_cache_string));
            $mapClusterer->setViewPort($neLat, $neLng, $swLat, $swLng);
            $markers = $mapClusterer->getClustersMarkersArray(array_keys($allGroups)); 
            $clustersMarkers = Warecorp_Widget_Map_Clusterer::getClusterMarkers($markers);
            $viewPort = $mapClusterer->getPrepearedViewport(); 
            
            $summary = new Z1SKY_GMap_GroupedMarkerSummary();
            Warecorp::loadSmartyPlugin('modifier.truncate.php');
            $markerBuilder = new Z1SKY_GMap_MarkerBuilder();
            $markersArray = array();
            $allGroups = array();
            
            foreach ($markers['markers'] as $key => $event) {
                 $allGroups[$key] = Warecorp_Group_Factory::loadById($key);
            }    

            foreach ($allGroups as $id => $item) {
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
                        $gr = Warecorp_Group_Factory::loadByGroupUID($item->getMainGroupUID());
                        if ($gr->getMapMarkerHash()) {
                            $marker->setIcon($gr->getMarker()->getSrcImg());
                        //$marker->setTooltipText($gr->getName());
                        } else {
                            $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                        }
                    } else {
                        $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                    }
                    $markersArray[] = $marker;
                }
            }

            //$markersArray = Z1SKY_GMap_GroupedMarker::groupMarkers($markersArray);

            foreach ($markersArray as &$marker) {
                $marker = $marker->toArray();
            }

            $zoomLevel = $zoomLevel = $this->params['currentZoomLevel']; 

            $allMarkers = array_merge($markersArray, $clustersMarkers); 
            
            $markersArray = array(
                array('zoom'=>array($zoomLevel,$zoomLevel), "places"=>$allMarkers)
            );    
            $templates = array('g' => $this->view->getContents('googleMaps/group.tpl'), 's' => $this->view->getContents('googleMaps/state.tpl'));
            $_result = array('templates'=>$templates, 'markersArray'=>$markersArray, 'autoPosition'=>$autoPosition, 'maxMinCoordinates' => $maxMinCoordinates, 'zoomLevel' => $zoomLevel, 'viewport'=>$viewPort ) ;


            $cache->save($_result, $result_cache_string, array(), $cfgLifetime);
            $this->printMarkersCacheHeaders();
        } else {
            $this->printMarkersCacheHeaders();
        }

        echo Zend_Json::encode($_result);exit;
//    }