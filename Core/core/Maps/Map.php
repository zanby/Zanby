<?php
/**
 * @package    Maps_Map
 * @copyright  Copyright (c) 2010
 * @author Michael Pianko
 */

 define('MAP_JS_PATH', '/maps/js');
 
class Maps_Map extends Maps_Base 
{
    protected $_mapParams = array('wtype', 'cloneId', 'containerId', 'containerType', 'width', 'height', 'zoom', 'gmapKey', 'additionalControls', 'mapType', 'layers', 'lat', 'lng', 'actionURL', 'controller', 'module', 'action', 'wdtype');
	protected $zoom;
	protected $gmapKey;
	
	protected $additionalControls = array();
	protected $mapType;
	protected $layers = array();
	protected $latitude;
	protected $longitude;
    
    protected $_params;
    protected $_additionalParams = array();
		
	protected $listenZoomLevelChanges;
		
	protected $zoomLevel = 1; 
    protected $action = null;
    
    protected $defaultSettings = array();
			
    public $scriptsToAppendHeader = array();
    public $scriptsToAppendApplication = array();
        
    
    public function setParams($params)
    {
        //  TODO: probably it must be $this->params = $params; ( $this->params vs $this->_params )
        //  @autor Artem Sukharev
        $this->_params = $params;
        return $this;
    }
    
    public function getParam($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }
        else {
            return false;
        }
    }
    
    private function setDefaultValues()
    {
        $this->defaultSettings['lat']   = 0.0001;
        $this->defaultSettings['lng']   = 0.0001;
        $this->defaultSettings['zoom']  = 2;
    }
    
    public function __construct() { 
        error_reporting(E_ALL);
        $this->setDefaultValues();
        $this->setGmapKey();
        $this->loadDefaultJS();
    }
    
    protected function setGmapKey($value = null)
    {
        if ($value !== null){
            $this->gmapKey = $value;
        }elseif ($this->getParam('gmapKey')) { 
            $this->gmapKey = $this->getParam('gmapKey');
        } elseif (true) { 
            $this->gmapKey = Maps_Utils::getGMapKey();
        } else {
           $this->gmapKey ='';   
        }
    }
    
    protected function loadDefaultJS()
    {
        $this->scriptsToAppendHeader[] = 'http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key='.$this->getGmapKey();   
        $this->scriptsToAppendHeader[] = MAP_JS_PATH.'/jquery/jquery-1.3.2.js';
        $this->scriptsToAppendHeader[] = MAP_JS_PATH.'/json/json2.js';
        $this->scriptsToAppendHeader[] = MAP_JS_PATH.'/markermanager.js';
        $this->scriptsToAppendHeader[] = MAP_JS_PATH.'/labeledmarker.js';         

        $this->scriptsToAppendApplication[] = MAP_JS_PATH.'/widgetMap_cluster.js?t='.time();        
        $this->scriptsToAppendApplication[] = MAP_JS_PATH.'/map-extentions.js';
                
    }
    
    public function addLayer($layer, $name = null)
    {
        if ( !($layer instanceof Maps_Layers_iLayer) ){
            throw new Exception('There is no message copy!'); 
        }
        
        if ($name === null) {
            $name = 'tmpLayer'.(string)(count($this->layers)+1);
        }
        $this->layers[$name] = $layer;
    }
    
    public function deleteLayer( $name )
    {
        if (isset($this->layers[$name])){
            unset($this->layers[$name]);
        }
    }
    
    private function setAdditionalParams( $params )
    {
        foreach ( $params as $key => $value ){
            if (!in_array($key, $this->_mapParams)){
                $this->_additionalParams[$key] = $value;
            }
        }
    }
                        
    public function loadParams($params) {
    	parent::loadParams($params);
        $this->setAdditionalParams($params);

        /*
         * comented by Artem Sukharev
         * array('GSmallMapControl', 'GLargeMapControl', 'GLargeMapControl3D')
         * 
        if (!$this->getParam('additionalControls') || (!in_array('GLargeMapControl',$this->getParam('additionalControls')))) {
            $this->additionalControls[] = 'GLargeMapControl';
        } else {
            $this->additionalControls[] = $params['additionalControls'];
        }
		
        $this->mapType = (!$this->getParam('mapType') || !in_array($this->getParam('mapType'), array('G_NORMAL_MAP', 'G_HYBRID_MAP', 'G_SATELLITE_MAP', 'G_MAP_OVERLAY')) )?'G_MAP_OVERLAY':$this->getParam('mapType');
        */
        $this->mapType = (!isset($params['mapType']) || !in_array($params['mapType'], array('G_NORMAL_MAP', 'G_HYBRID_MAP', 'G_SATELLITE_MAP', 'G_MAP_OVERLAY')) ) ? 'G_MAP_OVERLAY' : $params['mapType'];
        
        $default = $this->getCenterMapCoordinates();
        
        if (!empty($params['lat']) ) {$this->latitude = $params['lat'];} else {$this->latitude = $default['lat'];}
        if (!empty($params['lng']) ) {$this->longitude = $params['lng'];} else {$this->longitude = $default['lng'];}
        if (!empty($params['zoomLevel']) ) { $this->zoom = $params['zoomLevel']; } else { $this->zoom = $default['zoom'];}
        
    	return $this;
    }
    
    private function getCenterMapCoordinates(){
        return $this->defaultSettings;
    }
    
    public function setCenterMapCoordinates($lat, $lng, $zoom){
        $this->defaultSettings['lat']   = $lat;
        $this->defaultSettings['lng']   = $lng;
        $this->defaultSettings['zoom']  = $zoom;        
    }
    
    public function getWriteContainer() {
        return "document.write('<div id=\"".$this->containerId."_1\"></div><div id=\"".$this->containerId."\" style=\"width: ". $this->width. "px; height: ". $this->height. "px; \"></div>');";  
    }
    
    public function getInitFunction() {
        return 'wmObj'.$this->getCloneId().' = new WidgetMap(); wmObj'.$this->getCloneId().'.init('.Zend_Json::encode($this->getParamsAsArray()).');';
    }
    
    public function getParamsAsArray() {
        
    	parent :: getParamsAsArray();
    	
    	$this->paramsAsArray['zoom'] = $this->getZoom();
    	$this->paramsAsArray['gmapKey'] = $this->getGmapKey();
    	$this->paramsAsArray['additionalControls'] = $this->getAdditionalControls();
    	$this->paramsAsArray['mapType'] = $this->getMapType();
    	$this->paramsAsArray['layers'] = $this->getLayers();
    	$this->paramsAsArray['lat'] = $this->getLatitude();
        $this->paramsAsArray['lng'] = $this->getLongitude();
        $this->paramsAsArray['actionURL'] = $this->getActionUrl();
        $this->paramsAsArray['additionalParams'] = $this->getAssocAdditionalParams($this->_additionalParams);
        $this->paramsAsArray['mapsParams'] = $this->paramsAsArray;
        
    	
    	    	
    	return $this->paramsAsArray;
    }
    
    private function getAssocAdditionalParams($_params = null)
    {
        $result = array();
        if ($_params === null) $_params = $this->_additionalParams;
        foreach ( $_params as $key => $value ) {
            $result[] = array( 'name' => $key, 'value' => $value);
        }
        return $result;
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
        //var_dump($_paramString);
        foreach($this->_additionalParams as $paramKey => $paramValue) {
            if (!in_array($paramKey, $excludeArray) && !$paramValue == '' && (!is_array($paramValue)  ) ) {
                $_paramString.='&';    
                $_paramString.= $paramKey.'='.htmlentities($paramValue);    
            }
        }
        //var_dump($_paramString);
        //exit();        
        //var_dump($_paramString);
        //exit();
        return $_paramString;
    }
    
    public function getZoom() {
        return $this->zoom;  
    }
    
    public function getGmapKey() {
        return $this->gmapKey;  
    }
    
    public function addAdditionalControl( $type ) {
        $this->additionalControls[] = $type;
        return $this;
    }
    
    public function getAdditionalControls() {
        return $this->additionalControls;  
    }
    
    public function getMapType() {
        return $this->mapType;  
    }
        
    public function getLatitude() {
        return $this->latitude;  
    }
    
    public function getLongitude() {
        return $this->longitude;  
    }
    
    public function setActionURL($value) {
        $this->action = $value;
        return $this;  
    }
    
    public function getActionUrl() {
        return $this->action;
    }
    
    
    public function getLayers() {
        $result = array();
        foreach ( $this->layers as $layer ) {
            if ( $layer->getJSlinks() !== null ) {
                if (is_array($layer->getJSlinks())) {
                    $this->scriptsToAppendHeader = array_merge($this->scriptsToAppendHeader, $layer->getJSlinks());
                }else {
                    $this->scriptsToAppendHeader[] = $layer->getJSlinks(); 
                }
            }
            $result[] = $layer->getAsArray();
        }
        return $result;  
    }
    
    public function getMapHTML() 
    {
        if ($this->getContainerType() == 'iniframe') {
            $_paramsString = $this->getParamsAsQueryString(array('containerType'));
            $_addonContent='';
            header('Content-type: text/html');
            echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><link rel='stylesheet' type='text/css' href='/maps/css/".$this->getWType().".css' media='screen' />".$this->appendHeaderScripts()."</head><body style='padding:0px;margin:0px;'><script type='text/javascript'>function getwmObj(){return wmObj".$this->getCloneId().";} </script><script type='text/javascript' src='/en/map/".$_paramsString."'></script>".$_addonContent."</body></html>";
            exit;
        }
        
        $this->output = '';
        $this->output .= $this->getWriteContainer();
        $this->appendScripts();
        $this->output .= "document.write('<script type=\"text/javascript\"> $(function(){".$this->getInitFunction()."});</script>');";
        header('Content-type: text/javascript');
        echo $this->output;
    }
}