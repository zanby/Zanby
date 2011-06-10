<?php
class Maps_Utils {
	private static $gmapKey;
    private static $gmapAddressErrorMessage;
    private static $gmapMarkerTTL;
    private static $gmapAddressLookupResultTTL;
    private static $gmapAddressLookupAcuracyLevel;
    private static $contentObjectGmapKey;
    private static $typeIcons;
    private static $gmapInited = false;
    private static $gmapConfig;
    private static $gmapClusteringZoomLevel = null;
	
	public static function getGmapConfig() {
		if (!self::$gmapConfig) {
			$cfgGmap = simplexml_load_file(APPLICATION_PATH."/configs/cfg.gmap.xml");
			if ($cfgGmap === false) throw new Exception("Can't parse cfg.gmap.xml");

			//	@author Artem Sukharev
			if ( class_exists('Warecorp_Config_Loader') ) {
				$cfgLoader = Warecorp_Config_Loader::getInstance();
				self::$gmapConfig = $cfgLoader->getAppConfig('cfg.gmap.xml');
			} else {
				self::$gmapConfig = $cfgGmap->gmap;
			}
		}
		return self::$gmapConfig;
	}

	/**
	 * Return lat of default center
	 * @return doublee
	 */
	public static function getDefaultCenterLat()
	{
	    $cfgGmap = self::getGmapConfig();
	    if ( isset($cfgGmap->default_center) && isset($cfgGmap->default_center->lat) ) return $cfgGmap->default_center->lat ? $cfgGmap->default_center->lat : null;
	    return null;
	}

    /**
     * Return lng of default center
     * @return doublee
     */
	public static function getDefaultCenterLng()
    {
        $cfgGmap = self::getGmapConfig();
        if ( isset($cfgGmap->default_center) && isset($cfgGmap->default_center->lng) ) return $cfgGmap->default_center->lng ? $cfgGmap->default_center->lng : null;
        return null;
    }
	
    /**
     * Return zoom of default center
     * @return int
     */
    public static function getDefaultCenterZoom()
    {
        $cfgGmap = self::getGmapConfig();
        if ( isset($cfgGmap->default_center) && isset($cfgGmap->default_center->zoom) ) return $cfgGmap->default_center->zoom ? (int) $cfgGmap->default_center->zoom : 3 ;
        return 3;
    }
    
    public static function getMapClusteringZoomLevel() {
        if (!self::$gmapClusteringZoomLevel ) {
            $cfgGmap = self::getGmapConfig();
            if (!isset($cfgGmap->map_clustering_zoom_level)) {
                self::$gmapMarkerTTL = null;//disabled by default
            } else {
                if ($cfgGmap->map_clustering_zoom_level < 1 ) {
                    self::$gmapMarkerTTL = 1;
                } else if ($cfgGmap->map_clustering_zoom_level > 16 ) {
                    self::$gmapMarkerTTL = 16;
                }else{
                    self::$gmapMarkerTTL = $cfgGmap->map_clustering_zoom_level;
                }
            }
        }
        return self::$gmapMarkerTTL;
    }
    
	public static function isGmapInited() {
		if (!self::$gmapInited) {
			self::$gmapInited = true;
			return false;
		}
		return true;
	}
	
	public static function getGMapKey() {
		if (!self::$gmapKey) {
			$cfgGmap = self::getGmapConfig();
			if (!isset($cfgGmap->google_map_key)) {
				throw new Exception('No Google Maps key');
			}
			self::$gmapKey = (string)$cfgGmap->google_map_key;
		}
		return self::$gmapKey;
	}

    /**
     * @author komarovski
     */
    public static function getGMapAddressErrorMessage() {
        if (!self::$gmapAddressErrorMessage) {
            $cfgGmap = self::getGmapConfig();
            if (!isset($cfgGmap->google_address_error)) {
                throw new Exception('There is no message copy!');
            }
            self::$gmapAddressErrorMessage = (string)$cfgGmap->google_address_error;
        }
        return self::$gmapAddressErrorMessage;
    }

    /**
     * @author komarovski
     */
    public static function getMapMarkerTTL() {
        if (!self::$gmapMarkerTTL) {
            $cfgGmap = self::getGmapConfig();
            if (!isset($cfgGmap->map_marker_ttl)) {
                self::$gmapMarkerTTL = 2;//2 minutes by default
            } else {
                self::$gmapMarkerTTL = (int)$cfgGmap->map_marker_ttl;
            }
        }
        return self::$gmapMarkerTTL;
    }
                                             
    
    /**
     * @author komarovski
     */
    public static function getAddressLookupResultTTL() {
        if (!self::$gmapAddressLookupResultTTL) {
            $cfgGmap = self::getGmapConfig();
            if (!isset($cfgGmap->address_lookup_result_ttl)) {
                self::$gmapAddressLookupResultTTL = 0;//0 seconds by default
            } else {
                self::$gmapAddressLookupResultTTL = (int)$cfgGmap->address_lookup_result_ttl;
            }
        }
        return self::$gmapAddressLookupResultTTL;
    }

    /**
     * @author komarovski
     */
    public static function getAddressLookupAcuracyLevel() {
        if (!self::$gmapAddressLookupAcuracyLevel) {
            $cfgGmap = self::getGmapConfig();
            if (!isset($cfgGmap->address_lookup_acuracy_level)) {
                self::$gmapAddressLookupAcuracyLevel = 1;//minimal acuracy level
            } else {
                self::$gmapAddressLookupAcuracyLevel = (int)$cfgGmap->address_lookup_acuracy_level;
            }
        }
        return self::$gmapAddressLookupAcuracyLevel;
    }
    
    public static function getCOGMapKey() {
        if (!self::$contentObjectGmapKey) {
            $cfgGmap = self::getGmapConfig();
            if (!isset($cfgGmap->zanbyserv_google_map_key)) {
                throw new Exception('No Content Object Google Map key');
            }
            self::$contentObjectGmapKey = (string)$cfgGmap->zanbyserv_google_map_key;
        }
        return self::$contentObjectGmapKey;
    }	
	
	public static function getIconByType($type) {
		if (self::$typeIcons === null) {
			$types = Z1SKY_GMap_MarkerTypeEnum::getAllTypes();
			$cfgGmap = self::getGmapConfig();
			
			self::$typeIcons = array();
			
			if (isset($cfgGmap->icons))
			foreach ($types as $t) {
				if (isset($cfgGmap->icons->$t) && (string)$cfgGmap->icons->$t != '') {
					self::$typeIcons[$t] = IMG_URL. '/'. (string)$cfgGmap->icons->$t;
				}
			}
		}
		
		if (isset(self::$typeIcons[$type])) return self::$typeIcons[$type];
		return null;
	}
	
	public static function getWMSLayers() {
		$cfgGmap = self::getGmapConfig();
		$layers = array();
		if (isset($cfgGmap->wms) && isset($cfgGmap->wms->layers) && isset($cfgGmap->wms->layers->layer)) {
			foreach ($cfgGmap->wms->layers->layer as $layerConfig) {
				$layer = array('url' => (string)$layerConfig->url, 'wmsLayer' => (string)$layerConfig->wmsLayer, 'opacity' => (string)$layerConfig->opacity, 'copyright' => (string)$layerConfig->copyright);
				$layers[] = $layer;
			}
		}
		return $layers;
	}

    /**
     * @author komarovski
     */
    public static function getWMSDistrictLayers($districtLayer) {
		$cfgGmap = self::getGmapConfig();
		$layers = array();
		if (isset($cfgGmap->wms) && isset($cfgGmap->wms->layers) && isset($cfgGmap->wms->layers->layer)) {
			foreach ($cfgGmap->wms->layers->layer as $layerConfig) {
				$layer = array('url' => (string)$layerConfig->url, 'wmsLayer' => (string)($layerConfig->wmsLayer.',district='.$districtLayer) , 'opacity' => (string)$layerConfig->opacity, 'copyright' => (string)$layerConfig->copyright);
				$layers[] = $layer;
			}
		}
		return $layers;
	}

    /**
     * @author komarovski
     */
    public static function getWMSStateLayers($stateLayer) {
		$cfgGmap = self::getGmapConfig();
		$layers = array();
		if (isset($cfgGmap->wms) && isset($cfgGmap->wms->layers) && isset($cfgGmap->wms->layers->layer)) {
			foreach ($cfgGmap->wms->layers->layer as $layerConfig) {
				$layer = array('url' => (string)$layerConfig->url, 'wmsLayer' => (string)($layerConfig->wmsLayer.',state='.$stateLayer), 'opacity' => (string)$layerConfig->opacity, 'copyright' => (string)$layerConfig->copyright);
				$layers[] = $layer;
			}
		}
		return $layers;
	}

    /**
     * @author komarovski // for hivemaps test
     */
    public static function getWMSUrl() {
        $cfgGmap = self::getGmapConfig();
        if (isset($cfgGmap->wms) && isset($cfgGmap->wms->layers) && isset($cfgGmap->wms->layers->layer)) {
            foreach ($cfgGmap->wms->layers->layer as $layerConfig) {
                return (string)$layerConfig->url;
            }
        }
        return '';
    }

	public static function getWMSJS() {
		$cfgGmap = self::getGmapConfig();
		if (isset($cfgGmap->wms) && isset($cfgGmap->wms->wmsJS)) {
			$js = (string)$cfgGmap->wms->wmsJS;
			if (empty($js)) return false;
			return $js;
		}
		return false;
	}

    public static function getZoomForCO($width, $heigth, $squareMiles) {
        if ($width>$heigth)
            $pixels = $width;
        else 
            $pixels = $heigth;
           
        $meters = $squareMiles*1.609344; //need meters
        
        for ($i=0;$i<=17;$i++) {  
            $mpp = pow(2,17-$i)/1000*$pixels; // meters in current window if zoom = $i
            $current = abs($meters - $mpp);
            if ($i == 0) {
                $lowest = $current;
                $zoom = $i;
            } elseif ($current<$lowest) {
                $lowest = $current; 
                $zoom = $i;      
            }
            //echo "i: $i, mpp: $mpp, current: $current, lowest: $lowest, zoom: $zoom |||\n";
        }
        
        return $zoom;
    }
}
