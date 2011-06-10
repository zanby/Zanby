<?php

class BaseWidgetController extends Warecorp_Controller_Action
{
    public $params;
    public $AppTheme;
    private $currentWidget;
    public $output;
    
    public function init()
    {
        Warecorp::addTranslation('/modules/widget/widget.controller.php.xml');
        parent::init();
        $this->params = $this->_getAllParams();
        $this->AppTheme = Zend_Registry::get('AppTheme');
    }
    
    /* ================================================================================================================= */
    public function appendScripts() {
       // foreach ($this->currentWidget->baseScriptsToAppendHeader as $fileUrl) {
       //     $this->output.="document.write('<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>');";
       // }
       // foreach ($this->currentWidget->scriptsToAppendHeader as $fileUrl) {
      //      $this->output.="document.write('<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>');";
      //  }
        foreach ($this->currentWidget->baseScriptsToAppendApplication as $fileUrl) {
            $this->output.="document.write('<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>');";
        }
        foreach ($this->currentWidget->scriptsToAppendApplication as $fileUrl) {
            $this->output.="document.write('<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>');";
        }        
    }
    
    public function appendHeaderScripts() {
        $output = '';
        foreach ($this->currentWidget->baseScriptsToAppendHeader as $fileUrl) {
            $output.="<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>";
        }
        foreach ($this->currentWidget->scriptsToAppendHeader as $fileUrl) {
            $output.="<script type=\"text/javascript\" src=\"".$fileUrl."\"></script>";
        }
        return $output;
    }
    
    public function getSearchEventMarkersAction ()                      { include_once (PRODUCT_MODULES_DIR."/widget/getSearchEventMarkersAction.php"); }
    public function getNdaEventMarkersAction ()                         { include_once (PRODUCT_MODULES_DIR."/widget/getNdaEventMarkersAction.php");    }    
    public function getSearchGroupMarkersAction ($returnAsArray=false)  { include_once (PRODUCT_MODULES_DIR."/widget/getSearchGroupMarkersAction.php"); }
    public function getEventMarkersAction()                             { include_once (PRODUCT_MODULES_DIR."/widget/getEventMarkersAction.php");       } 
    public function getGroupMarkersAction()                             { include_once (PRODUCT_MODULES_DIR."/widget/getGroupMarkersAction.php");       } 
    
    /* ================================================================================================================= */
    public function indexAction() 
    {
    	$this->currentWidget = Warecorp_Widget_Factory :: loadByParams ($this->params);
        
    	if (!$this->currentWidget->getCloneId()) $this->currentWidget->setCloneId( rand(time()-3600, time()) );
        $this->currentWidget->setContainerId( 'widgetContainer'.$this->currentWidget->getCloneId() );
         
        //@TODO change this 
        if ($this->currentWidget->getContainerType() == 'iniframe') {
            
        	$_paramsString = $this->currentWidget->getParamsAsQueryString(array('containerType'));
        	$_addonContent='';
        	if ($this->currentWidget->kmlControlInternalId) $_addonContent = "<div class='kmlContainer'><a class='kmlLink' id='getKMLLink' href='javascript:void(0)'>".Warecorp::t('download KML')."</a></div>";
            //print $_paramsString;die;
            //echo "<html><head><script type='text/javascript' src='".$this->AppTheme->common->js.'/jquery/jquery-1.3.2.js'."'></script></head><body style='padding:0;margin:0;'><script type='text/javascript' src='http://z1sky-cpp.komarovski.buick/widget.js?wtype=map&needDistrictLayer=1&width=542&height=300&country=United%States&zoom=4&showGroupMarkers=1'></script></body></html>";
            echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><link rel='stylesheet' type='text/css' href='".$this->AppTheme->css."/widgets/".$this->currentWidget->getWType().".css' media='screen' /><script type='text/javascript' src='".$this->AppTheme->common->js.'/jquery/jquery-1.3.2.js'."'></script>".$this->appendHeaderScripts()."</head><body style='padding:0px;margin:0px;'><script type='text/javascript'>var AppTheme = {base_url:'".BASE_URL."'}; function getwmObj(){return wmObj".$this->currentWidget->getCloneId().";} </script><script type='text/javascript' src='".BASE_URL."/widget.js".$_paramsString."'></script>".$_addonContent."</body></html>";
            exit;
        }
        
        $this->output = '';
        $this->output .= $this->currentWidget->getWriteContainer();
        $this->appendScripts();
        //$this->output .= "$(function(){".$this->currentWidget->getInitFunction()."});";
        $this->output .= "document.write('<script type=\"text/javascript\"> $(function(){".$this->currentWidget->getInitFunction()."});</script>');";
        //$this->output .= "document.write('<script type=\"text/javascript\">".$this->currentWidget->getInitFunction()."</script>');";
        header('Content-type: text/javascript');
        echo $this->output;
        
        exit;
    }

    private function renderFromTemplate($template, $params){
        $result = $template;
        foreach($params as $key => &$value) {
            if ($key != 'TT') {
                $result = str_replace("[*".$key."*]", $value, $result);
            }
        }
        return $result;
    }

    public function getXMLAction() {
        //http://z1sky-cpp.komarovski.buick/en/widget/getSearchGroupMarkers/width/730/height/400
        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
    	$cfgLifetime = 60;
        if ($dom = $cache->load('hivemapstest')) {
            header ("Content-Type:text/xml");
            echo $dom;
            exit();
        }

        $allGroups = $this->getSearchGroupMarkersAction(true);

        $markerBuilder = new Z1SKY_GMap_MarkerBuilder();
        $markersArray = array();

        $dom = new DOMDocument('1.0', 'UTF-8');
        $node = $dom->createElementNS('http://zanby.com', 'xml');
        $dnode = $dom->appendChild($node);


        foreach ($allGroups as $item) {
            if ($marker = $markerBuilder->buildMarker($item)) {
                $markersArray[] = $marker;
                $marker = $marker->toArray();

                $itemnode = $dom->createElement('node');

                $tmpnode = $dom->createElement('id', $item->getId());
                $itemnode->appendChild($tmpnode);

                $tmpnode = $dom->createElement('title', htmlentities($marker['title']));
                $itemnode->appendChild($tmpnode);

                $tmpnode = $dom->createElement('cntry', htmlentities($item->getCity()->getState()->getCountry()->code));
                $itemnode->appendChild($tmpnode);

                $tmpnode = $dom->createElement('city',  htmlentities($item->getCity()->name));
                $itemnode->appendChild($tmpnode);

                $tmpnode = $dom->createElement('lat', $marker['latitude']);
                $itemnode->appendChild($tmpnode);

                $tmpnode = $dom->createElement('lon', $marker['longitude']);
                $itemnode->appendChild($tmpnode);

                $tmpnode = $dom->createElement('type', $marker['type']);
                $itemnode->appendChild($tmpnode);

                $dnode->appendChild($itemnode);
            }
        }
    $cache->save($dom->saveXML(), 'hivemapstest', array(), $cfgLifetime);
    //echo("<PRE>");
    header ("Content-Type:text/xml");
    echo $dom->saveXML();
    //echo("</PRE>");
        exit();

        
    }

    /*<node>
    <id>4330</id>
    <title>[4578] 350 Climate Action Festival</title>
    <desc>&lt;p&gt;October 24 in order to celebrate the day, a free style polo will be organised in Chitral town. This one day festival will be used as an alternative media in order to share information with the general public and to mobilise them toward the protection of the natural environment in the mountainous...&lt;/p&gt;</desc>
    <first>Shams</first>

    <last>Uddin</last>
    <keywords></keywords>
    <cntry>PK</cntry>
    <city>Chitral</city>
    <lat>35.921895</lat>
    <lon>74.274112</lon>

    <state></state>
    <address>&lt;div class="location vcard"&gt;&lt;div class="adr"&gt;
&lt;span class="locality"&gt;Chitral&lt;/span&gt;, &lt;span class="postal-code"&gt;17200&lt;/span&gt;&lt;div class="country-name"&gt;Pakistan&lt;/div&gt;

&lt;/div&gt;&lt;/div&gt;
</address>
    <street>&lt;br /&gt;Chitral Town
Chitral Polo Ground</street>
    <zip>17200</zip>
  </node>*/
    public function getXMLDetailsAction() {
        $tid = $this->params['id'];          
        $type = $this->params['type'];  
        //$tid = $this->params['type'];
        /*$group = Z1SKY_Group_Factory::loadByGroupUID('z1sky');
        $allGroups = $group->getGroups()->getList();*/
        $markersArray = array();
        $result = array();
        $result['type'] = $type;
        if ($type == 'group')
        {
            $group = Z1SKY_Group_Factory::loadById($tid);
            $allGroups = array($group);

            $result['id'] = $group->getId();
            $result['title'] = $group->getName();
            $result['desc'] = substr($group->getDescription(), 0, 150);
            $result['firstname'] = $group->getHost()->getFirstname();
            $result['lastname'] = $group->getHost()->getLastname();
            $result['cntry'] = $group->getCity()->getState()->getCountry()->code;
            $result['city'] = $group->getCity()->name;
            $result['state'] = $group->getCity()->getState()->name;
            $result['street'] = $group->getProfile()->getAddress();
            $result['zip'] = $group->getZipcode();
            $result['url'] = $group->getGroupPath('summary');
            
        }elseif ($type = 'event'){
            $event = new Warecorp_ICAL_Event($tid);
            $result['id'] = $event->getId();
            $result['title'] = $event->getTitle();
            $result['desc'] = substr($event->getDescription(), 0, 150);
            $result['firstname'] = $event->getCreator()->getFirstname();
            $result['lastname'] = $event->getCreator()->getLastname();
            $result['cntry'] = $event->getEventVenue()->getCity()->getState()->getCountry()->code;
            $result['city'] = $event->getEventVenue()->getCity()->name;
            $result['state'] = $event->getEventVenue()->getCity()->getState()->name;
            $result['street'] = $event->getEventVenue()->getAddress1();
            $result['zip'] = $event->getEventVenue()->getZipcode();
            $result['url'] = $event->entityURL();
        }        
        echo Zend_Json::encode($result);exit;
        //echo("</PRE>");
        exit();


    }

    /**
     * @param Z1SKY_GMap_Marker $marker
     * @return array
     */
    private function markerToArray( Z1SKY_GMap_Marker $marker ) {
        return array(
            'icon'      =>  $marker->getIcon(),
            'title'     =>  $marker->getTitle(),
            'html'      =>  $marker->getHtml(),
            'address'   =>  $marker->getAddress(),
            'longitude' =>  $marker->getLongitude(),
            'latitude'  =>  $marker->getLatitude()
        );
    }

    //==================================================================================================================
    public function getKMLAction() {
        if (!empty($_SESSION['kmlOutput'])) {
            header('Content-type: application/vnd.google-earth.kml+xml');
            header("Content-Disposition: attachment; filename=data.kml");
            echo $_SESSION['kmlOutput'];
            $_SESSION['kmlOutput']='';
            exit;
        }

        ob_start();
        $dom = new DOMDocument('1.0', 'UTF-8');
        $node = $dom->createElementNS('http://www.opengis.net/kml/2.2', 'kml');
        $parNode = $dom->appendChild($node);

        $dnode = $dom->createElement('Document');
        $docNode = $parNode->appendChild($dnode);
        
        if ( !empty($this->params['kmlType']) ) {
            $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
            $markerBuilder = new Z1SKY_GMap_MarkerBuilder();
            $markersArray = array();
            switch ( $this->params['kmlType'] ) {
                case 'ndaEventMarkers':
                    $events1 = $cache->load('CPP_NDA_search_events_d_'.md5($_SESSION['NDA_MARKERS_CACHE_ADDITIONAL_KEY']));
                    $events2 = $cache->load('CPP_NDA_search_events_distance_'.md5($_SESSION['NDA_MARKERS_CACHE_ADDITIONAL_KEY']));
                    $events = array_unique(array_merge($events1, $events2));unset($events1, $events2);
                    if ( $events ) {
                        foreach ( $events as $event ) {
                            $e = new Warecorp_ICal_Event($event);
                            $marker = $markerBuilder->buildMarker($e);
                            if ($e->getMarkerGroupId()) {
                                $gr = Warecorp_Group_Factory::loadById($e->getMarkerGroupId());
                                if ($gr->getMapMarkerHash()) {
                                    $marker->setIcon($gr->getMarker()->getSrcImg());
                                } else {
                                    $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                                }
                                unset($gr);
                            }else {
                                $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.png');
                            }
                            $markersArray[] = $this->markerToArray($marker);
                            unset($e, $marker);
                        }
                    }
                    break;
                case 'searchGroupMarkers':
                    if (empty($_SESSION['GROUPS_MARKERS_CACHE_ADDITIONAL_KEY'])) $_SESSION['GROUPS_MARKERS_CACHE_ADDITIONAL_KEY'] = '';
                    $groups = $cache->load('CPP_search_groups_d_'.md5($_SESSION['GROUPS_MARKERS_CACHE_ADDITIONAL_KEY']));
                    if ( $groups ) {
                        foreach ( $groups as $groupId => $group ) {
                            $group = Warecorp_Group_Factory::loadById($groupId);
                            $marker = $markerBuilder->buildMarker($group);
                            if ($group->getMapMarkerHash() || $group->isCongressionalDistrict() ) {
                                $marker->setIcon($group->getMarker()->getSrcImg());
                            } elseif ($group->getMainGroupUID()) {
                                $gr = Warecorp_Group_Factory::loadByGroupUID($group->getMainGroupUID(),Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                                if ($gr->getMapMarkerHash()) {
                                    $marker->setIcon($gr->getMarker()->getSrcImg());
                                } else {
                                    $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                                }
                                unset($gr);
                            } else {
                                $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                            }
                            $markersArray[] = $this->markerToArray($marker);
                            unset($group, $marker);
                        }
                    }
                    break;
                case 'searchEventsMarkers':
                    $events1 = $cache->load('CPP_search_events_d_'.md5($_SESSION['EVENTS_MARKERS_CACHE_ADDITIONAL_KEY']));
                    $events2 = $cache->load('CPP_search_events_distance_'.md5($_SESSION['EVENTS_MARKERS_CACHE_ADDITIONAL_KEY']));
                    $events = array_unique(array_merge($events1, $events2));unset($events1, $events2);
                    if ( $events ) {
                        foreach ( $events as $event ) {
                            $e = new Warecorp_ICal_Event($event);
                            $marker = $markerBuilder->buildMarker($e);
                            if ($e->getMarkerGroupId()) {
                                $gr = Warecorp_Group_Factory::loadById($e->getMarkerGroupId());
                                if ( $gr->getMapMarkerHash() ) {
                                    $marker->setIcon($gr->getMarker()->getSrcImg());
                                }
                                else {
                                    $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.png');
                                }
                                unset($gr);
                            }else {
                                $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.png');
                            }
                            $markersArray[] = $this->markerToArray($marker);
                            unset($e, $marker);
                        }
                    }
                    break;
                case 'groupMarkers':
                    if (empty($_SESSION['GROUPS_MARKERS_RESULT_CACHE_STRING'])) $_SESSION['GROUPS_MARKERS_RESULT_CACHE_STRING'] = '';
                    $groups = $cache->load($_SESSION['GROUPS_MARKERS_RESULT_CACHE_STRING']);
                    if ( $groups ) {
                        foreach ( $groups as $groupId ) {
                            $group = Warecorp_Group_Factory::loadById($groupId);
                            $marker = $markerBuilder->buildMarker($group);
                            if ($group->getMapMarkerHash() || $group->isCongressionalDistrict() ) {
                                $marker->setIcon($group->getMarker()->getSrcImg());
                            } elseif ($group->getMainGroupUID()) {
                                $gr = Warecorp_Group_Factory::loadByGroupUID($group->getMainGroupUID(),Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                                if ($gr->getMapMarkerHash()) {
                                    $marker->setIcon($gr->getMarker()->getSrcImg());
                                } else {
                                    $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                                }
                                unset($gr);
                            } else {
                                $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.gif');
                            }
                            $markersArray[] = $this->markerToArray($marker);
                            unset($group, $marker);
                        }
                    }
                    break;
                case 'eventMarkers':
                    $events = $cache->load($_SESSION['EVENTS_MARKERS_RESULT_CACHE_STRING']);
                    if ( $events ) {
                        foreach ( $events as $event ) {
                            $e = new Warecorp_ICal_Event($event);
                            $marker = $markerBuilder->buildMarker($e);
                            if ($e->getMarkerGroupId()) {
                                $gr = Warecorp_Group_Factory::loadById($e->getMarkerGroupId());
                                if ( $gr->getMapMarkerHash() ) {
                                    $marker->setIcon($gr->getMarker()->getSrcImg());
                                }
                                else {
                                    $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.png');
                                }
                                unset($gr);
                            }else {
                                $marker->setIcon($this->AppTheme->common->images.'/map/map_marker_default.png');
                            }
                            $markersArray[] = $this->markerToArray($marker);
                            unset($e, $marker);
                        }
                    }
                    break;
            }

            foreach ($markersArray as $key => &$item) {
                $node = $dom->createElement('Placemark');
                $placeNode = $docNode->appendChild($node);

                $node = $dom->createElement('Style');
                $node->setAttribute('id', 'PlacemarkStyle'.$key);
                $styleNode = $docNode->appendChild($node);

                $node = $dom->createElement('IconStyle');
                $iconStyleNode = $styleNode->appendChild($node);

                $node = $dom->createElement('Icon');
                $iconNode = $iconStyleNode->appendChild($node);

                $node = $dom->createElement('href', $item['icon']);//!!!http://maps.google.com/mapfiles/kml/paddle/wht-blank.png
                $hrefNode = $iconNode->appendChild($node);

                $styleNode = $dom->createElement('styleUrl', '#PlacemarkStyle'.$key);//!!!
                $placeNode->appendChild($styleNode);

                $nameNode = $dom->createElement('name', htmlentities($item['title']) );//!!!
                $placeNode->appendChild($nameNode);

                $descNode = $dom->createElement('description', htmlentities($item['html']));
                $placeNode->appendChild($descNode);

                $node = $dom->createElement('Icon');
                $iconNode = $docNode->appendChild($node);

                $hrefNode = $dom->createElement('href', $item['icon']);//!!!http://maps.google.com/mapfiles/kml/paddle/wht-blank.png
                $iconNode->appendChild($hrefNode);


                if (!empty($item['address'])) {
                    $addrNode = $dom->createElement('address', htmlentities($item['address']) );
                    $placeNode->appendChild($addrNode);
                }

                $pointNode = $dom->createElement('Point');
                $placeNode->appendChild($pointNode);

                if (!empty($item['longitude']) && !empty($item['latitude'])) {
                    $coorStr = $item['longitude']. ','. $item['latitude'];

                    $coorNode = $dom->createElement('coordinates', $coorStr);
                    $pointNode->appendChild($coorNode);
                }
            }
        }

        $_SESSION['kmlOutput'] = $dom->saveXML();
        ob_end_clean();
        echo 200;
        exit;
    }
    
    
    
    private function printMarkersCacheHeaders() {
        header("Cache-Control: public");
        header("Expires: " . date("r", time() + Z1SKY_GMap_Utils::getMapMarkerTTL()*60/2));
        header("Pragma: ");
    }
    
    private function printMarkersNoCacheHeaders() {
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache"); 
    }
    
}
