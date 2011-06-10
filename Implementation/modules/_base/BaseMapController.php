<?php
class BaseMapController extends Warecorp_Controller_Action
{
    public $params;
    private $currentWidget;
    public $output;
    
    
    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        $this->params = $this->_getAllParams();
    }

    public function indexAction()
    {        
        $clat = ( isset($this->params['clat']) && !empty($this->params['clat']) ) ? $this->params['clat'] : 34.1835997426;
        $clng = ( isset($this->params['clng']) && !empty($this->params['clng']) ) ? $this->params['clng'] : -90.2299557251;
        
        $clat = Maps_Utils::getDefaultCenterLat() ? Maps_Utils::getDefaultCenterLat() : 
            ( ( isset($this->params['clat']) && !empty($this->params['clat']) ) ? $this->params['clat'] : 34.1835997426 );
        $clng = Maps_Utils::getDefaultCenterLng() ? Maps_Utils::getDefaultCenterLng() :
            ( ( isset($this->params['clng']) && !empty($this->params['clng']) ) ? $this->params['clng'] : -90.2299557251 );
        $czoom = Maps_Utils::getDefaultCenterZoom() ? Maps_Utils::getDefaultCenterZoom() :
            ( ( isset($this->params['zoom']) && !empty($this->params['zoom']) ) ? $this->params['zoom'] : 3 );

        $this->currentWidget = new Maps_Map($this->params);
        $this->currentWidget->setCenterMapCoordinates($clat, $clng, $czoom);
        $this->currentWidget->setActionURL('/en/map/markers');

        $this->currentWidget->loadParams($this->params);
        $this->currentWidget->addAdditionalControl('GLargeMapControl3D');
        //$this->currentWidget->addAdditionalControl('GSmallMapControl');
        
        if (!$this->currentWidget->getCloneId()) $this->currentWidget->setCloneId( rand(time()-3600, time()) );
        $this->currentWidget->setContainerId( 'widgetContainer'.$this->currentWidget->getCloneId() );
        echo $this->currentWidget->getMapHTML();
        exit();
    }

    public function markersAction()
    {
        $markerManager = new Maps_Marker_Manager();
        $markerManager->setMarkerSource(new Warecorp_ICal_Event_Marker());
        $markerManager->getClustererEngine()->setCacheHash($this->params['mapCache']);
        $markerManager->getClustererEngine()->setCacheLifetime(60*60*10);
        $markerManager->setParams($this->params);
        $markers = $markerManager->getMapData();
        echo $markers;
        exit();
    }

    public function getinfoAction()
    {
        $markersEngine = new Warecorp_ICal_Event_Marker();
        $markersEngine->setParams($this->params);
        
        $objUser = Zend_Registry::get("User");
        $currentTimezone = ( null !== $objUser->getId() && null !== $objUser->getTimezone() ) ? $objUser->getTimezone() : 'UTC';
        $markersEngine->showBubbleCountry( !Warecorp::checkHttpContext('zccf') );
        $markersEngine->showBubbleState( !Warecorp::checkHttpContext('zccf') );
        $markersEngine->setUser( $objUser );
        $markersEngine->setTimezone( $currentTimezone );

        $markerManager = new Maps_Marker_Manager();
        $markerManager->setMarkerSource($markersEngine);
        $data = $markerManager->getMarkerInfoData($this->params['id']);
        echo $data;
        exit();
    }
}
