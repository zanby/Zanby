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

/**
 * Warecorp FRAMEWORK
 *
 * @package    Warecorp_Widget_Map
 * @copyright  Copyright (c) 2009
 * @author Alexander Komarovski
 */

class BaseWarecorp_Widget_Map extends Warecorp_Widget_Base 
{
	private $user = null;
	
	protected $groupContext = 0;
	protected $Group = null;
	protected $zoom = 3;
	protected $needDistrictLayer = 1;
	protected $gmapKey;
    protected $eventsDateRange = 0;
	
	protected $additionalControls = array();
	protected $mapType;
	protected $layers;
	protected $country;
	protected $latitude;
	protected $longitude;
	protected $zip;
	
	protected $kmlControl = 1;
	public $kmlControlExternalId;
	public $kmlControlInternalId;
	
	//Levels
	protected $listenZoomLevelChanges;
	protected $zoomLevelNational = 0; 
	protected $zoomLevelState = 0;
	protected $zoomLevelDistrict = 0;
	
	protected $districtLayer;//to show only district - NY21
	protected $stateLayer;//to show only state - NY
	
	//Callbacks
	protected $nationalCallback;
	protected $nationalChangeCallback;
	protected $nationalLessCallback;
	
	protected $zoomLevel = ''; //national / state / district
	protected $zoomlevelContextType; //user or group
    protected $zoomLevelConextId;
	
	protected $switchGEScenario = ''; //search (just redirect)
	
	protected $defaultDisplayType = 0; //0- groups, 1- events
	protected $displayRange = 0; // 0 - everywhere, 1 - just my district
	protected $eventsDisplayType = 0;//0 - all events 1- NDA and affilated events
	protected $eventToDisplayId = 0;// event id if $eventsDisplayType == 1
	
	protected $eventWhen; //events search page
	
    public $scriptsToAppendHeader = array();
    public $scriptsToAppendApplication = array();
    
    public $nationalCoordinates = null;
    public $stateCoordinates = null;
    public $districtCoordinates = null;
    protected $centerCoordinates = null;
    protected $stepOfClustering = 6;
    protected $maxMarkers = 20;
    
    
    public function __construct() {
        $this->user = Zend_Registry::get('User');   	
    }
    
    private function getCenterMapCoordinates(){
           return array('longitude' => Warecorp_Common_Functions :: getCentralCoordinateFrom2Longitudes($this->centerCoordinates['minlongitude'], $this->centerCoordinates['maxlongitude']), 'latitude' => ($this->centerCoordinates['maxlatitude']-$this->centerCoordinates['minlatitude'])/2+$this->centerCoordinates['minlatitude']);    
    }
    
    private function checkUserZoomLevels (){
    	//ANONYMOUS
    	if (!$this->user->getId()) {
            $_country = Warecorp_Location_Country::create(1);//USA
            $this->zoomLevelNational = $this->getCountryZoomLevel($_country);
            $this->zoomLevelState = 0;
            $this->zoomLevelDistrict = 0;  
              
    	//REGISTERED	
    	} else {
    		$this->zoomLevelNational = $this->getCountryZoomLevel($this->user->getCountry());
            $this->zoomLevelState = $this->getStateZoomLevel($this->user->getState());
            $this->zoomLevelDistrict = $this->getDistrictZoomLevel(); 
    	}
    }
            
    public function getCountryZoomLevel($country){
        $this->nationalCoordinates = $country->getMaxMinCoordinates(); 
        return Warecorp_Common_Functions::getMinZoomLevelForGMapByAreaCoordinatesAndRealSize($this->nationalCoordinates['maxlongitude'], $this->nationalCoordinates['minlongitude'], $this->nationalCoordinates['maxlatitude'], $this->nationalCoordinates['minlatitude'], $this->width, $this->height);
    }
    public function getStateZoomLevel($state){
       $this->stateCoordinates = $state->getMaxMinCoordinates();
       return Warecorp_Common_Functions::getMinZoomLevelForGMapByAreaCoordinatesAndRealSize($this->stateCoordinates['maxlongitude'], $this->stateCoordinates['minlongitude'], $this->stateCoordinates['maxlatitude'], $this->stateCoordinates['minlatitude'], $this->width, $this->height);
    }
    public function getDistrictZoomLevel(){
       $this->districtCoordinates = Z1SKY_Location_District :: getCoordinates(array($this->user->getProfile()->getDistrictNumber()), $this->user->getProfile()->getDistrictState());
       return Warecorp_Common_Functions::getMinZoomLevelForGMapByAreaCoordinatesAndRealSize($this->districtCoordinates['maxlongitude'], $this->districtCoordinates['minlongitude'], $this->districtCoordinates['maxlatitude'], $this->districtCoordinates['minlatitude'], $this->width, $this->height);    
    }
    
    public function loadParams($params) {
    	parent::loadParams($params);
    	$this->checkUserZoomLevels(); //width and height already defined or undefined
    	                   
        //PARAMS from CO
    	if (!empty($params['defaultDisplayType']) ) $this->defaultDisplayType = 1;
    	if (!empty($params['displayRange']) ) $this->displayRange = 1;
    	if (!empty($params['eventsDisplayType']) ) $this->eventsDisplayType = 1;
    	if (!empty($params['eventsDisplayType']) && !empty($params['eventToDisplayId']) ) $this->eventToDisplayId = intval($params['eventToDisplayId']);
    	if (!empty($params['groupContext'])) $this->groupContext = intval($params['groupContext']);

        if (!empty($params['eventsDateRange'])) $this->eventsDateRange = $params['eventsDateRange'];
    	
    	if (!empty($params['listenZoomLevelChanges']) ) $this->listenZoomLevelChanges = 1;
    	$this->centerCoordinates = $this->nationalCoordinates;
    	if (!empty($params['zoomLevel']) ) {
            $this->zoomLevel = $params['zoomLevel'];
           
            if (strpos($this->zoomLevel, 'District') === 0) {   
                $_state = 0;
                $_district = 0; 
                $parr = explode('_',str_replace('District', '', $this->zoomLevel));
                if (isset($parr[0])) $_district = intval($parr[0]);
                if (isset($parr[1])) $_state = intval($parr[1]);
               
                $state = Warecorp_Location_State::create($_state);
                if (!empty($state->id) && !empty($_district)) {
                     $this->districtLayer = $state->code.$_district;    
                     $this->centerCoordinates = Z1SKY_Location_District :: getCoordinates(array($_district), $state->code);
                }
                else {
                    $this->centerCoordinates = $state->getMaxMinCoordinates(); 
                }
            } elseif (strpos($this->zoomLevel, 'State') === 0) {
               $_state = intval(str_replace('State', '', $this->zoomLevel));  
               $state = Warecorp_Location_State::create($_state);
               if (!empty($state->id)) {
                    $this->stateLayer = $state->code;    
                    $this->centerCoordinates = $state->getMaxMinCoordinates(); 
               }
            }
    	}
        //var_dump($this->centerCoordinates);
        $this->zoom = Warecorp_Common_Functions::getMinZoomLevelForGMapByAreaCoordinatesAndRealSize($this->centerCoordinates['maxlongitude'], $this->centerCoordinates['minlongitude'], $this->centerCoordinates['maxlatitude'], $this->centerCoordinates['minlatitude'], $this->width, $this->height);
        
        //var_dump($this->zoom);
    	
    	//CALLBACKS
    	if (!empty($params['nationalCallback']) ) $this->nationalCallback = $params['nationalCallback'];
    	if (!empty($params['nationalChangeCallback']) ) $this->nationalChangeCallback = $params['nationalChangeCallback'];
    	if (!empty($params['nationalLessCallback']) ) $this->nationalLessCallback = $params['nationalLessCallback'];
    	
    	if (!empty($params['eventWhen']) ) $this->eventWhen = $params['eventWhen'];
    	//if (!empty($params['zoom']) ) $this->zoom = intval($params['zoom']);
    	
    	if (!empty($params['switchGEScenario']) ) $this->switchGEScenario = $params['switchGEScenario'];
    	
    	//GMap Key
        if (!empty($params['gmapKey'])) { 
            $this->gmapKey = $params['gmapKey'];
        } elseif (true) { //@TODO check if it is not external site) {
            $this->gmapKey = Z1SKY_GMap_Utils::getGMapKey();
        } else {
           $this->gmapKey ='';   
        }

        //!@todo not array - .... 
        if ((!isset($params['additionalControls'])) || (!in_array('GLargeMapControl',$params['additionalControls']))) {
		    $this->additionalControls[] = 'GSmallMapControl';
		} else {
		    $this->additionalControls[] = $params['additionalControls'];
		}     
                
        $this->mapType = (empty($params['mapType']) || !in_array($params['mapType'], array('G_NORMAL_MAP', 'G_HYBRID_MAP', 'G_SATELLITE_MAP', 'G_MAP_OVERLAY')) )?'G_MAP_OVERLAY':$params['mapType'];

        if (isset($this->districtLayer)) {
            $this->layers = Z1SKY_GMap_Utils::getWMSDistrictLayers($this->districtLayer);
        } elseif (!empty($this->stateLayer)) {
            $this->layers = Z1SKY_GMap_Utils::getWMSStateLayers($this->stateLayer);
        } else {
            $this->layers = Z1SKY_GMap_Utils::getWMSLayers();
        }
        

        if (!empty($params['country']) ) $this->country = $params['country'];
        
        
        $center = $this->getCenterMapCoordinates();
        
        if (!empty($params['latitude']) ) {$this->latitude = $params['latitude'];} else {$this->latitude = $center['latitude'];}
        if (!empty($params['longitude']) ) {$this->longitude = $params['longitude'];} else {$this->longitude = $center['longitude'];}
        if (!empty($params['zip']) ) $this->zip = $params['zip'];
        
        if (isset($params['kmlControl']) ) $this->kmlControl = $params['kmlControl'];
        if (isset($params['kmlControlExternalId']) ) $this->kmlControlExternalId = $params['kmlControlExternalId'];        
        if (isset($params['kmlControlInternalId']) ) $this->kmlControlInternalId = $params['kmlControlInternalId'];
          
                //????? $this->widgetParams['showGroupMarkers'] = (empty($this->params['showGroupMarkers']) )?0:1;
                //container
                //?????$this->output .= "document.write('<div id=\"".$this->widgetContainerId."_1\"></div><div id=\"".$this->widgetContainerId."\" style=\"width: ". $this->widgetParams['width']. "px; height: ". $this->widgetParams['height']. "px; \"></div>');";
                
        $this->scriptsToAppendHeader[] = 'http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key='.$this->getGmapKey();   

        
        //$this->scriptsToAppendHeader[] = JS_URL. '/markermanager.js?'.rand();  //!!!
        //$this->scriptsToAppendHeader[] = JS_URL.'/markermanager_packed.js';
        $this->scriptsToAppendHeader[] = JS_URL.'/json/json2.js';
        $this->scriptsToAppendHeader[] = JS_URL.'/markermanager.js';
        $this->scriptsToAppendHeader[] = JS_URL.'/labeledmarker.js';
        //$this->scriptsToAppendHeader[] = JS_URL. '/gmapMarkerUtils.js';
        
        $js = Z1SKY_GMap_Utils::getWMSJS();
        if ($js) {
        	$this->scriptsToAppendHeader[] = JS_URL.'/'.$js;
        }

        $AppTheme = Zend_Registry::get('AppTheme');
        //$this->scriptsToAppendApplication[] = $AppTheme->common->js.'/widgets/widgetMap.js?r='.rand();
        $this->scriptsToAppendApplication[] = $AppTheme->common->js.'/widgets/widgetMap_cluster.js';
                
    	return $this;
    }
        
    
    public function getWriteContainer() {
        return "document.write('<div id=\"".$this->containerId."_1\"></div><div id=\"".$this->containerId."\" style=\"width: ". $this->width. "px; height: ". $this->height. "px; \"></div>');";  
    }
    
    public function getInitFunction() {
        return 'wmObj'.$this->getCloneId().' = new WidgetMap(); wmObj'.$this->getCloneId().'.init('.Zend_Json::encode($this->getParamsAsArray()).');';
    }
    
    public function getParamsAsArray() {
        
    	parent :: getParamsAsArray();
    	
    	$this->paramsAsArray['groupContext'] = $this->getGroupContext();
    	$this->paramsAsArray['zoom'] = $this->getZoom();
        //var_dump($this->getZoom());
    	$this->paramsAsArray['needDistrictLayer'] = 1;
    	$this->paramsAsArray['gmapKey'] = $this->getGmapKey();
    	$this->paramsAsArray['additionalControls'] = $this->getAdditionalControls();
    	$this->paramsAsArray['mapType'] = $this->getMapType();
    	$this->paramsAsArray['layers'] = $this->getLayers();
    	$this->paramsAsArray['country'] = $this->getCountry();
    	$this->paramsAsArray['latitude'] = $this->getLatitude();
    	$this->paramsAsArray['longitude'] = $this->getLongitude();
    	$this->paramsAsArray['zip'] = $this->getZip();
    	
    	$this->paramsAsArray['defaultDisplayType'] = $this->getDefaultDisplayType();
    	$this->paramsAsArray['displayRange'] = $this->getDisplayRange();
    	$this->paramsAsArray['eventsDisplayType'] = $this->getEventsDisplayType();
    	$this->paramsAsArray['eventToDisplayId'] = $this->getEventToDisplayId();
    	
    	$this->paramsAsArray['eventWhen'] = $this->eventWhen;
    	
    	$this->paramsAsArray['switchGEScenario'] = $this->switchGEScenario;
    	
    	//LEVELS
    	$this->paramsAsArray['listenZoomLevelChanges'] = $this->listenZoomLevelChanges;
    	$this->paramsAsArray['zoomLevelNational'] = $this->zoomLevelNational;
    	$this->paramsAsArray['zoomLevelState'] = $this->zoomLevelState;
    	$this->paramsAsArray['zoomLevelDistrict'] = $this->zoomLevelDistrict;	
    	$this->paramsAsArray['zoomLevel'] = $this->zoomLevel;
    	//CALLBACKS
    	$this->paramsAsArray['nationalCallback'] = $this->nationalCallback;
    	$this->paramsAsArray['nationalChangeCallback'] = $this->nationalChangeCallback;
    	$this->paramsAsArray['nationalLessCallback'] = $this->nationalLessCallback;
    	
    	$this->paramsAsArray['districtLayer'] = $this->districtLayer;
    	$this->paramsAsArray['stateLayer'] = $this->stateLayer;
    	
    	$this->paramsAsArray['kmlControl'] = $this->kmlControl;
    	$this->paramsAsArray['kmlControlExternalId'] = $this->kmlControlExternalId;
    	$this->paramsAsArray['kmlControlInternalId'] = $this->kmlControlInternalId;
        $this->paramsAsArray['eventsDateRange'] = $this->eventsDateRange;
    	
    	//$this->paramsAsArray['r'] = rand();//hack
    	
    	return $this->paramsAsArray;
    }
    
    public function getParamsAsQueryString($excludeArray = array()) {
        
        $_params = $this->getParamsAsArray(); 
        $_paramString = '';
        
        $_first = true;
        foreach($_params as $paramKey => $paramValue) {
            if (!in_array($paramKey, $excludeArray) && !$paramValue == '' && !(is_array($paramValue)/* && empty($paramValue)*/ )  ) {
                if ($_first) {
                    $_paramString.='?';	
                } else {
                    $_paramString.='&';	
                }
            	$_paramString.= $paramKey.'='.$paramValue;	
            	$_first = false;
            }
           	
        }
        
        return $_paramString;
    }
    
    public function getGroupContext() {
        return $this->groupContext;  
    }
    
    public function getDefaultDisplayType() {
        return $this->defaultDisplayType;  
    }
    
    public function getDisplayRange() {
        return $this->displayRange;  
    }
    
    public function getEventsDisplayType() {
        return $this->eventsDisplayType;  
    }
    
    public function getEventToDisplayId() {
        return $this->eventToDisplayId;  
    }
    
    public function getZoom() {
        return $this->zoom;  
    }
    
    public function getGmapKey() {
        return $this->gmapKey;  
    }
    
    public function getAdditionalControls() {
        return $this->additionalControls;  
    }
    
    public function getMapType() {
        return $this->mapType;  
    }
    
    public function getLayers() {
        return $this->layers;  
    }
    
    public function getCountry() {
        return $this->country;  
    }
    
    public function getLatitude() {
        return $this->latitude;  
    }
    
    public function getLongitude() {
        return $this->longitude;  
    }
    
    public function getZip() {
        return $this->zip;  
    }
    
    public function getUser() {
        return $this->user;  
    }

}
