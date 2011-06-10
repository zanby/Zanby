function wmPointFunction(point, objWM){ 
    if (!point) {
        alert('address not found');
    } else {
        objWM.map.setCenter(point, objWM.params.zoom);
    }
}

function wmMoveEndFunction(center, objWM){
     objWM.checkMapMoving(center);  
}

function wmZoomEndFunction(oldLevel, newLevel, objWM){
    if (objWM.mainTimer != null) {
        clearTimeout(objWM.mainTimer);
        objWM.mainTimer = null;   
    }
    if (newLevel >=17) {
        objWM.map.setZoom(newLevel -1);
        return false;
    }
    
    objWM.mainTimer = setTimeout( function(){ objWM.changeZoomLevel(oldLevel, newLevel);  }, 1000);     
    return false;
}


WidgetMap.prototype.changeZoomLevel = function(oldLevel, newLevel) {
    var url = this.getURL();
    //additional code for sending basic viewport to server
    // from server will be returned prepeated viewport
    var southWest = this.map.getBounds().getSouthWest();
    url = url + '/sw/' + southWest.toUrlValue();
    
    var northEast = this.map.getBounds().getNorthEast();
    url = url + '/ne/' + northEast.toUrlValue();
    url = url + '/currentZoomLevel/' + newLevel;
                   
    var wmapObj = this;  

    $.post(url, {}, function(data) {
        wmapObj.clearMap();
        wmapObj.loadViewport(data, true);
    }, 'json');

}

WidgetMap.prototype.clearMap = function () {     
    this.mgr.clearMarkers();
    this.map.clearOverlays(); 
    this.zoneLoaded = [];
    this.clearMarkerLoaded(); 
    $(this.map.getPane(G_MAP_MARKER_PANE)).children().remove()
}

function setClusteringZoomLevel(objWM) {
    if (objWM.clusteringZoomLevel) {
        objWM.map.setZoom(objWM.clusteringZoomLevel+1);
    }
}
   
                      
function WidgetMap () {}
WidgetMap.prototype.init = function (paramsObj) {
		    
    this.params = paramsObj;
    this.map = null;
    this.layers = null;
    this.controls = null;                                             
    this.clusteringZoomLevel = null;
    this.prepearedViewPort = [];
    this.basicViewPort = [];
    this.zoneLoaded = new Array();
    this.mainTimer = null;
    this.debugViewport = false;
    this.currentURL = '';
		
    this.allMarkers = new Array();
    this.markerLoaded = [];
    this.mgr = null;
    this.initGmap();
    this.icons = {};

    this.params.entityType = this.params.defaultDisplayType;
}

WidgetMap.prototype.setMarkerLoaded = function (lat, lng, info, zoom) {
    var key = lat + '/'+ lng + '/' + info + '/' + zoom;
    this.markerLoaded[key] = true;
}

WidgetMap.prototype.clearMarkerLoaded = function () {
    this.markerLoaded = [];
}

WidgetMap.prototype.isMarkerLoaded = function (lat, lng, info, zoom) {
    var key = lat + '/'+ lng + '/' + info + '/' + zoom;
    if (this.markerLoaded[key] != null && this.markerLoaded[key] == true){
        return true;
    }
    return false;
}


WidgetMap.prototype.setViewPortIsLoaded = function(viewPort) {
    var key = viewPort.nelat + '/'+ viewPort.nelng + '/' +viewPort.swlat + '/' + viewPort.swlng + '/' + this.map.getZoom();
    this.zoneLoaded[key] = true;
}

WidgetMap.prototype.isViewPortIsLoaded = function(viewPort) {
    key = viewPort.nelat + '/'+ viewPort.nelng + '/' +viewPort.swlat + '/' + viewPort.swlng + '/' + this.map.getZoom();   
    if (this.zoneLoaded[key] != null && this.zoneLoaded[key] == true){
        return true;
    }
    return false;
}

	
WidgetMap.prototype.checkMarkerManager = function() {
    if (!this.mgr) {
        this.createMarkerManager();
    }
}

WidgetMap.prototype.prepeareViewportVariables = function() {
    this.deltaLat = Math.abs(this.prepearedViewPort.swlat - this.prepearedViewPort.nelat);
    this.deltaLng = Math.abs(this.prepearedViewPort.swlng - this.prepearedViewPort.nelng);
}

WidgetMap.prototype.checkMapMoving = function(center) {
    var result = new Array();
    result['x'] = 0;
    result['y'] = 0;
    var centerLat = center.lat();
    var centerLng = center.lng();
    
    // @ todo make functions for receiving read X Y 
    if (this.prepearedViewPort.nelat < center.lat()) { result['x'] = 1; }
    if (this.prepearedViewPort.swlat > center.lat()) { result['x'] = -1; }
    
    if (this.prepearedViewPort.nelng < center.lng()) { result['y'] = 1; }
    if (this.prepearedViewPort.swlng > center.lng()) { result['y'] = -1; }
    
    /*
    alert ('nelat - ' + this.prepearedViewPort['nelat'] + ' center - ' + centerLat + ' swlat - ' + this.prepearedViewPort['swlat'] + '\n'
            + 'nelng - ' + this.prepearedViewPort['nelng'] + ' center - '+ centerLng + ' swlng - ' + this.prepearedViewPort['swlng'] + '\n'
            + 'x - ' + result['x'] + ' y - ' + result['y']);
    */
    
    if (result['x'] != 0 || result['y'] != 0){
        this.prepearedViewPort = this.calculateViewportByRelatedIndex(this.prepearedViewPort, result['x'], result['y']);
        this.prepeareViewportVariables();
        if ( this.isViewPortIsLoaded( this.prepearedViewPort ) != true ){ 
            this.setViewPortIsLoaded( this.prepearedViewPort );  
            var url = this.getURL();

            url = url + '/sw/' + this.prepearedViewPort['swlat'] + ',' + this.prepearedViewPort['swlng'];
            url = url + '/ne/' + this.prepearedViewPort['nelat'] + ',' + this.prepearedViewPort['nelng'];
            url = url + '/currentZoomLevel/' + this.map.getZoom();
            
            var wmapObj = this;
            
            $.post(url, {}, function(data) {
                wmapObj.loadViewport(data, true);
            }, 'json');
           
        }
        else{
            this.loadViewportsAround(this.prepearedViewPort);
        }   
    }
}

	
WidgetMap.prototype.createMarkerManager = function() {
    this.mgr = new MarkerManager(this.map, {
        trackMarkers: true
    });
}
    
WidgetMap.prototype.getNationalZoomLevel = function() {
    if (this.params.zoomLevelNational) {
        return this.params.zoomLevelNational;
    }
    return 0;
}
WidgetMap.prototype.getStateZoomLevel = function() {
    if (this.params.zoomLevelState) {
        return this.params.zoomLevelState;
    }
    return 0;
}
WidgetMap.prototype.getDistrictZoomLevel = function() {
    if (this.params.zoomLevelDistrict) {
        return this.params.zoomLevelDistrict;
    }
    return 0;
}
	
WidgetMap.prototype.getZoomLevel = function(value) {
    if (value == 'district') {
        return this.getDistrictZoomLevel();
    } else if (value == 'national') {
        return this.getNationalZoomLevel();
    } else if (value == 'state') {
        return this.getStateZoomLevel();
    }
    return value;
}
    
WidgetMap.prototype.initGmap = function () { //alert('start');
    if (GBrowserIsCompatible()) { //alert('compatible');
        		
        this.map = new GMap2(document.getElementById('widgetContainer'+this.params.cloneId), {
            mapTypes:[G_NORMAL_MAP]
            });
    		
    		
        if (this.params.additionalControls) { // GScaleControl
            for (i=0; i < this.params.additionalControls.length ; i++){
                if (this.params.additionalControls[i] == 'GSmallMapControl') {
                    this.map.addControl(new GSmallMapControl);
                } else {//@TODO
                    alert(this.params.additionalControls[i]);
                }
    	            	  
            }
        }
            
        this.controls = new Array();
        var wmapObj;
        	
        /*KML Control*/
        if (this.params.kmlControl) {
            wmapObj = this;
            if (this.params.kmlControlExternalId || this.params.kmlControlInternalId) {
                if (this.params.kmlControlInternalId && document.getElementById(this.params.kmlControlInternalId)) {
                    GEvent.addDomListener(document.getElementById(this.params.kmlControlInternalId), "click", function() {
                        wmapObj.getKML();
                    });
                } else if (this.params.kmlControlExternalId && parent.document.getElementById(this.params.kmlControlExternalId)) {
                    GEvent.addDomListener(parent.document.getElementById(this.params.kmlControlExternalId), "click", function() {
                        wmapObj.getKML();
                    });
                }
        		
            } else {
                function GWKMLControl() {}
                GWKMLControl.prototype = new GControl();
                GWKMLControl.prototype.initialize = function(map) {
                    var container = document.createElement("div");
                    var controlDiv = document.createElement("div");
                    GWsetKMLButtonStyle_(controlDiv);
                    container.appendChild(controlDiv);
                    controlDiv.appendChild(document.createTextNode("KML"));
                    wmapObj.controls['kml'] = controlDiv;
                    GEvent.addDomListener(controlDiv, "click", function() {
                        wmapObj.getKML();
                    });
				    		  
                    map.getContainer().appendChild(container);
                    return container;
                }
	            
                GWKMLControl.prototype.getDefaultPosition = function() {
                    return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7, wmapObj.params.height-50));
                }
		        	
                GWsetKMLButtonStyle_ = function(button) {
                    button.className="WMKMLControl";
                }
		           
                this.map.addControl(new GWKMLControl());
            }      
        	
        }
        	
        	
        	
        /*Groups & Events Controls*/
        if (this.params.switchGEScenario && this.params.switchGEScenario == 'nda') {
        //no controls
        } else {
            wmapObj = this;
            //CUSTOM CONTROL
	            	
            function GWEventControl() {}
            function GWGroupControl() {}
	
            GWEventControl.prototype = new GControl();
            GWGroupControl.prototype = new GControl();
	        	
            GWEventControl.prototype.initialize = function(map) {
                var container = document.createElement("div");
                var controlDiv = document.createElement("div");
		            	  
                if (!wmapObj.params.defaultDisplayType || wmapObj.params.defaultDisplayType == 0) {
                    GWsetButtonStyle_(controlDiv);
                } else {
                    GWsetSelectedButtonStyle_(controlDiv);
                }
		            	  
                container.appendChild(controlDiv);
                controlDiv.appendChild(document.createTextNode("Events"));
		            	  
                wmapObj.controls['event'] = controlDiv;
		            	  
                if (wmapObj.params.switchGEScenario == 'search'){
                    GEvent.addDomListener(controlDiv, "click", function() {
                        parent.document.location = AppTheme.base_url+"/en/index/event/";
                    });
                } else {
                    GEvent.addDomListener(controlDiv, "click", function() {
                        GWsetSelectedButtonStyle_(wmapObj.controls['event']);
                        GWsetButtonStyle_(wmapObj.controls['group']);
                        wmapObj.clearMarkers();
                        wmapObj.getEventMarkers();
                    });
                }
		            	  
                map.getContainer().appendChild(container);
                return container;
            }
	
	            	
            GWGroupControl.prototype.initialize = function(map) {
                var container = document.createElement("div");
                var controlDiv = document.createElement("div");
	
                if (!wmapObj.params.defaultDisplayType || wmapObj.params.defaultDisplayType == 0) {
                    GWsetSelectedButtonStyle_(controlDiv);
                } else {
                    GWsetButtonStyle_(controlDiv);
                }
		        	  
                container.appendChild(controlDiv);
                controlDiv.appendChild(document.createTextNode("Groups"));
		        	 
                wmapObj.controls['group'] = controlDiv;
		        	  
                if (wmapObj.params.switchGEScenario == 'search'){
                    GEvent.addDomListener(controlDiv, "click", function() {
                        parent.document.location = AppTheme.base_url+"/en/index/groups/";
                    });
                } else {
                    GEvent.addDomListener(controlDiv, "click", function() {
                        GWsetSelectedButtonStyle_(wmapObj.controls['group']);
                        GWsetButtonStyle_(wmapObj.controls['event']);
                        wmapObj.clearMarkers();
                        wmapObj.getGroupMarkers();
                    });
                }
		
                map.getContainer().appendChild(container);
                return container;
            }
	            	
	            	  
            GWGroupControl.prototype.getDefaultPosition = function() {
                return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(87, 7));
            }
            GWEventControl.prototype.getDefaultPosition = function() {
                return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7, 7));
            }
	
	            	
            GWsetButtonStyle_ = function(button) {
                button.className="WMcustomControl";
            }
            GWsetSelectedButtonStyle_ = function(button) {
                button.className="WMcustomControlSelected";
            }
	            
            this.map.addControl(new GWEventControl());
            this.map.addControl(new GWGroupControl());
        //map.setCenter(new GLatLng(37.441944, -122.141944), 13);
        }
            
            	
        //WidgetMap.paramsArray['layers']  $layers = Z1SKY_GMap_Utils::getWMSLayers();
        this.layers = new Array();
        cnt = 0;
        for (i=0; i< this.params.layers.length; i++) {
            this.layers[cnt] = createWMSTileLayer(this.params.layers[i].url , this.params.layers[i].wmsLayer, null, 'image/gif', null, null, null, this.params.layers[i].opacity, this.params.layers[i].copyright, this.map);
            cnt++;
        }
	        
	        
        //@TODO
        // tdata = '';
        tdata=new Array();
        for (i = 0; i < cnt; i++) {
            //tdata = tdata + WidgetMap.layers[cloneId][i]; alert(WidgetMap.layers[cloneId][i]);
            tdata[tdata.length] = this.layers[i];
        //if (i != (cnt-1)) {tdata = tdata + ", "}
        }
        //Overlays
        var G_MAP_OVERLAY = createWMSOverlayMapType([G_NORMAL_MAP.getTileLayers()[0], this.layers[0], this.layers[1] ], 'Overlay');
        if (this.params.needDistrictLayer) {
            this.map.addMapType(G_MAP_OVERLAY);
        }
        this.map.setMapType(eval(this.params.mapType)); //G_MAP_OVERLAY
	        
        //country
        if (this.params.country) {
            //$myPointMustBeNull = true;
            geocoder = new GClientGeocoder();
            geocoder.objWM = this;
            geocoder.getLatLng(this.params.country, function(point){
                wmPointFunction(point, geocoder.objWM);
            });
            //alert('country');
        } else if (this.params.latitude && this.params.longitude) {
            this.map.setCenter(new GLatLng(this.params.latitude, this.params.longitude), this.params.zoom);
            //alert('lnlat');
        //myPoint = WidgetMap.paramsArray[cloneId].getCenter;
        } if (this.params.zip) {
            //$myPointMustBeNull = true;
            geocoder = new GClientGeocoder();
            geocoder.objWM = this;
            geocoder.getLatLng(this.params.zip + ', USA', function(point){
                wmPointFunction(point, geocoder.objWM);
            });
            //alert('zip');
        } else {
            //crutch
            //	geocoder = new GClientGeocoder();
            //    geocoder.objWM = this;
            //    geocoder.getLatLng('United States', function(point){wmPointFunction(point, geocoder.objWM);});
            this.needRefresh = 1;
            //alert('refresh'); 
        }
	        
	        
	    //LISTENER FOR CHANGING ZOOM LEVEL
        objWM = this;
        GEvent.addListener(this.map, 'zoomend', function(oldLevel, newLevel){
            wmZoomEndFunction(oldLevel, newLevel, objWM);
        })

        //Added listener for all kinds of maps. 
        
        objWM = this;
        GEvent.addListener(this.map, 'moveend', function(){
            var center = this.getCenter();
            wmMoveEndFunction(center, objWM);
        })   
	       
        //DISPLAY Group or Event markers
        if (!this.params.defaultDisplayType || this.params.defaultDisplayType == 0) {//groups or events
            if (this.params.switchGEScenario == 'search'){
                //objWM = this;
                //setTimeout("objWM.getSearchGroupMarkers()", 10000);
                this.getSearchGroupMarkers();
            } else {
                this.getGroupMarkers();
            }
        } else {
            if (this.params.switchGEScenario == 'search'){
                //objWM = this;
                //setTimeout("objWM.getSearchEventMarkers()", 10000);
                this.getSearchEventMarkers();
            } else {
                if (this.params.switchGEScenario == 'nda'){
                    //objWM = this;
                    //setTimeout("objWM.getNdaEventMarkers()", 0);
                    this.getNdaEventMarkers();
		    		   
                } else {
                    this.getEventMarkers();
                }
            }
        }
        
	       
    } else {
// !GBrowserIsCompatible
    }
}
            
            
WidgetMap.prototype.getNdaEventMarkers = function () {
    var url=AppTheme.base_url+'/en/widget/getNdaEventMarkers';
    this.params.entityType = 1;
    if (this.params.width) {
        url = url + '/width/' + this.params.width;
    }
    if (this.params.height) {
        url = url + '/height/' + this.params.height;
    }
    if (this.params.groupContext) {
        url = url + '/groupContext/' + this.params.groupContext;
    }
    if (this.params.displayRange) {
        url = url + '/displayRange/' + this.params.displayRange;
    }
    if (this.params.zoomLevel) {
        url = url + '/zoomLevel/' + this.params.zoomLevel;
    }
    if (this.params.eventToDisplayId) {
        url = url + '/eventToDisplayId/' + this.params.eventToDisplayId;
    }
    if (this.params.eventsDateRange) {
        url += '/eventsDateRange/' + this.params.eventsDateRange;
    }

    this.currentURL = url;
    //additional code for sending basic viewport to server
    // from server will be returned prepeated viewport
    var southWest = this.map.getBounds().getSouthWest();
    url = url + '/sw/' + southWest.toUrlValue();
    
    var northEast = this.map.getBounds().getNorthEast();
    url = url + '/ne/' + northEast.toUrlValue();
    url = url + '/currentZoomLevel/' + this.map.getZoom(); 
    
    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.loadViewport(data, true);
    }, 'json');
}
	
	
WidgetMap.prototype.getEventMarkers = function () {
    var url=AppTheme.base_url+'/en/widget/getEventMarkers';
    this.params.entityType = 1;
    if (this.params.groupContext) {
        url = url + '/groupContext/' + this.params.groupContext;
    }
    if (this.params.displayRange) {
        url = url + '/displayRange/' + this.params.displayRange;
    }
    if (this.params.eventsDisplayType) {
        url = url + '/eventsDisplayType/' + this.params.eventsDisplayType;
        if (this.params.eventToDisplayId) {
            url = url + '/eventToDisplayId/' + this.params.eventToDisplayId;
        }
    }
    
        
    this.currentURL = url;
    //additional code for sending basic viewport to server
    // from server will be returned prepeated viewport
    var southWest = this.map.getBounds().getSouthWest();
    url = url + '/sw/' + southWest.toUrlValue();
    
    var northEast = this.map.getBounds().getNorthEast();
    url = url + '/ne/' + northEast.toUrlValue();
    url = url + '/currentZoomLevel/' + this.map.getZoom(); 
    
    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.loadViewport(data, true);
    }, 'json');
}

            
            
WidgetMap.prototype.getGroupMarkers = function () {
    var url=AppTheme.base_url+'/en/widget/getGroupMarkers';
    this.params.entityType = 0;
    if (this.params.groupContext) {
        url = url + '/groupContext/' + this.params.groupContext;
    }
    if (this.params.displayRange) {
        url = url + '/displayRange/' + this.params.displayRange;
    }
    if (this.params.width) {
        url = url + '/width/' + this.params.width;
    }
    if (this.params.height) {
        url = url + '/height/' + this.params.height;
    }
    
        
    this.currentURL = url;
    //additional code for sending basic viewport to server
    // from server will be returned prepeated viewport
    var southWest = this.map.getBounds().getSouthWest();
    url = url + '/sw/' + southWest.toUrlValue();
    
    var northEast = this.map.getBounds().getNorthEast();
    url = url + '/ne/' + northEast.toUrlValue();
    url = url + '/currentZoomLevel/' + this.map.getZoom(); 

    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.loadViewport(data, true);
    }, 'json');
}
	
       
	
WidgetMap.prototype.getSearchGroupMarkers = function () {
    var url=AppTheme.base_url+'/en/widget/getSearchGroupMarkers';
    this.params.entityType = 0;
    if (this.params.width) {
        url = url + '/width/' + this.params.width;
    }
    if (this.params.height) {
        url = url + '/height/' + this.params.height;
    }
    if (this.params.groupContext) {
        url = url + '/groupContext/' + this.params.groupContext;
    }
    if (this.params.zoomLevel) {
        url = url + '/zoomLevel/' + this.params.zoomLevel;
    }    
        
    this.currentURL = url;
    //additional code for sending basic viewport to server
    // from server will be returned prepeated viewport
    var southWest = this.map.getBounds().getSouthWest();
    url = url + '/sw/' + southWest.toUrlValue();
    
    var northEast = this.map.getBounds().getNorthEast();
    url = url + '/ne/' + northEast.toUrlValue();
    url = url + '/currentZoomLevel/' + this.map.getZoom(); 
    
    
    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.loadViewport(data, true);
    }, 'json');
}
    
    
WidgetMap.prototype.getURL = function () {
     return this.currentURL;
}
    
    
WidgetMap.prototype.getSearchEventMarkers = function () {
    
    var url=AppTheme.base_url+'/en/widget/getSearchEventMarkers';
    this.params.entityType = 0;
    if (this.params.width) {
        url = url + '/width/' + this.params.width;
    }
    if (this.params.height) {
        url = url + '/height/' + this.params.height;
    }
    if (this.params.eventWhen) {
        url = url + '/eventWhen/' + this.params.eventWhen;
    }
    if (this.params.eventToDisplayId) {
        url = url + '/eventToDisplayId/' + this.params.eventToDisplayId;
    }
    if (this.params.zoomLevel) {
        url = url + '/zoomLevel/' + this.params.zoomLevel;
    }
    
    this.currentURL = url;
    //additional code for sending basic viewport to server
    // from server will be returned prepeated viewport
    var southWest = this.map.getBounds().getSouthWest();
    url = url + '/sw/' + southWest.toUrlValue();
    
    var northEast = this.map.getBounds().getNorthEast();
    url = url + '/ne/' + northEast.toUrlValue();
    url = url + '/currentZoomLevel/' + this.map.getZoom(); 
    
    if (this.debugViewport) {
        var polyline = new GPolyline([
              southWest,
              northEast,
            ], "#00ff00", 10);
        this.map.addOverlay(polyline);
    }
    
    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.loadViewport(data, true);
    }, 'json');
    
}

WidgetMap.prototype.loadViewport = function (data, loadArount){
    if (loadArount == true && this.mainTimer != null) {clearTimeout(this.mainTimer);}
    this.setViewPortIsLoaded(data.viewport);
    
    if (this.debugViewport) {
        var polyline = new GPolyline([
              new GLatLng(data.viewport['nelat'], data.viewport['nelng']),
              new GLatLng(data.viewport['swlat'], data.viewport['swlng']),
            ], "#ff0000", 10);
        this.map.addOverlay(polyline);
    }
    
    this.showMarkers(data, !loadArount);
    
    if (loadArount == true){
        this.prepearedViewPort = data.viewport;
        this.prepeareViewportVariables();    
        var Wgr = this;
        this.mainTimer = setTimeout( function(){ Wgr.loadViewportsAround(Wgr.prepearedViewPort) }, 2000)            
    }
}   

WidgetMap.prototype.loadViewportsAround = function (viewPort){
    if (this.map.getZoom() > 2){
        this.loadViewportByRelatedIndex(viewPort, 0, -1);
        this.loadViewportByRelatedIndex(viewPort, 0, 1);
        this.loadViewportByRelatedIndex(viewPort, 1, -1);
        this.loadViewportByRelatedIndex(viewPort, 1, 0);
        this.loadViewportByRelatedIndex(viewPort, 1, 1);
        this.loadViewportByRelatedIndex(viewPort, -1, -1);
        this.loadViewportByRelatedIndex(viewPort, -1, 0);
        this.loadViewportByRelatedIndex(viewPort, -1, 1);
    }
}

WidgetMap.prototype.calculateViewportByRelatedIndex = function (viewPort, x, y){
    var updatedViewport = new Array();
    updatedViewport['swlat'] = (viewPort['swlat']+(x*this.deltaLat));    
    updatedViewport['swlng'] = (viewPort['swlng']+(y*this.deltaLng));    
    updatedViewport['nelat'] = (viewPort['nelat']+(x*this.deltaLat)); 
    updatedViewport['nelng'] = (viewPort['nelng']+(y*this.deltaLng));
    return updatedViewport;
}


WidgetMap.prototype.loadViewportByRelatedIndex = function (viewPort, x, y){
    
    var updatedViewport = this.calculateViewportByRelatedIndex(viewPort, x, y); 
   // alert(this.isViewPortIsLoaded( updatedViewport ));     
    if ( this.isViewPortIsLoaded( updatedViewport ) != true ){  
        this.setViewPortIsLoaded(updatedViewport);  
        var url = this.getURL();

        url = url + '/sw/' + updatedViewport['swlat']+','+updatedViewport['swlng'];
        url = url + '/ne/' + updatedViewport['nelat']+','+updatedViewport['nelng'];
        url = url + '/currentZoomLevel/' + this.map.getZoom(); 
        
        var wmapObj = this;
        
        $.post(url, {}, function(data) {
            wmapObj.loadViewport(data, false);
        }, 'json');   
    }
}

	
WidgetMap.prototype.getKML = function () {
    var type = "";
    if ( this.params.entityType === 0 ) {       // groups
        if (this.params.switchGEScenario == 'search'){
            type = 'searchGroupMarkers';
        } else {
            type = 'groupMarkers';
        }
    } 
    else if ( this.params.entityType === 1 ) {  // events
        if (this.params.switchGEScenario == 'search'){
            type = 'searchEventsMarkers';
        } else {
            if (this.params.switchGEScenario == 'nda'){
                type = 'ndaEventMarkers';
            } else {
                type = 'eventMarkers';
            }
        }
    }

    var url=AppTheme.base_url+'/en/widget/getKML/r/'+Math.round(Math.random()*100000000)+'/';
    var wmapObj = this;
    $.post(
        url,
        {   //markers: JSON.toJSON(this.lastMarkersDataResponse),
            kmlType : type
        },
        function(data) { wmapObj.showKML(data); },
        'json'
    );
}
	
WidgetMap.prototype.showKML = function (data) {
    if (data == 200) {
        var url=AppTheme.base_url+'/en/widget/getKML/r/'+Math.round(Math.random()*100000000)+'/';
        document.location.href = url;
    }
}

	
WidgetMap.prototype.clearMarkers = function () {
    this.mgr.clearMarkers();
}
    
      
WidgetMap.prototype.showMarkers = function (markers, preloadMarkers) {
    this.lastMarkersDataResponse = markers;
    this.setGMapMarkers(markers.templates, markers.markersArray);
}
    
WidgetMap.prototype. getIcon = function (image) {
    var icon = null;
    if (image) {
        if (this.icons[image]) {
            icon = this.icons[image];
        } else {
            icon = new GIcon();
            icon.image = image;
            //icon.iconSize = new GSize(66, 66);
            icon.iconAnchor = new GPoint(20 >> 1, 20 >> 1);
            icon.infoWindowAnchor = new GPoint(20 >> 1, 20 >> 1);
            //icon.shadow = null;"images/" +       images[1] +      ".png";
            icon.shadowSize = new GSize(0, 0);
            this.icons[image] = icon;
        }
    }
    return icon;
}

WidgetMap.prototype.setGMapMarkers = function (templates, markers, autoPosition, maxMinCoordinates, zoom, customCenter, clusteringZoomLevel) {
       
    this.checkMarkerManager();
    var marker;
    	    	
    for (var i in markers) {
        if (markers.hasOwnProperty(i)) {
            var layer = markers[i];
            var markersA = new Array();

            for (var j in layer["places"]) {
                if (layer["places"].hasOwnProperty(j)) {
                    var place = layer["places"][j];
                    if (this.isMarkerLoaded(place.latitude, place.longitude, place.count, layer["zoom"][0]) == false){
                        var latlng = new GLatLng(place.latitude, place.longitude);
                        var mOptions = {};
                        var icon = {};
                        if (place.tooltip) {
                            mOptions.title = place.tooltip;
                        }
                        if (place.icon) {
                            mOptions.icon = this.getIcon(place.icon);
                            icon = this.getIcon(place.icon);
                            
                        }
                      //  if (place.html[])
                        
                        //marker.prototype.params = {};
                        var wmapObj = this;
                        if (place.type != 'cluster'){
                            marker = new GMarker(latlng, mOptions);     
                            //marker.bindInfoWindowHtml(renderFromTemplate(templates[place.html['TT']], place.html));
                            marker.params = {id:place.id,type:place.type, map:this.map};
                            GEvent.addListener(marker, "click", loadDetails);                        
                        }
                        else{
                            this.setMarkerLoaded(place.latitude, place.longitude, place.count, layer["zoom"][0]);
                            opts = {
                              "icon": icon,
                              "clickable": true,
                              "labelText": place.count,
                              "labelOffset": null
                            };
                            if (place.count > 99) {
                                opts.labelOffset = new GSize(9, 14); 
                            }else if (place.count > 9) {
                                opts.labelOffset = new GSize(9, 9); 
                            }else{
                                opts.labelOffset = new GSize(12, 7); 
                            }

                            marker = new LabeledMarker(latlng, opts);
                            GEvent.addListener(marker, "click", function() {
                                if (wmapObj.map.getZoom() <= 17){
                                  objWM.map.setCenter(this.getLatLng(), wmapObj.map.getZoom()+1);
                                }
                            });                    
                        }
                        markersA.push(marker);
                    }
                }
            }
            
            this.mgr.addMarkers(markersA, layer["zoom"][0], layer["zoom"][1]);
        }
          
    }
    this.mgr.refresh();
}

function loadDetails(){// locTab, infoTab) {
        var url=AppTheme.base_url+'/en/widget/getXMLDetails/id/'+ this.params.id + '/type/' + this.params.type;
        var markerD = this;
        $.post(url, {},function(data) {
                    var infoTab = new GInfoWindowTab("Location", getLocTabHtml(data));
                    if (data.desc) {
                        var locTab = new GInfoWindowTab("Info", getInfoTabHtml(data));
                        markerD.params.map.openInfoWindowTabs(markerD.getLatLng(), [infoTab, locTab]);
                    } else {
                        markerD.params.map.openInfoWindowTabs(markerD.getLatLng(), [infoTab]);
                    } 
            }
        , 'json');
}


/*function showInfo(map, marker, latlng, locTab, infoTab) {
    var locTab = new GInfoWindowTab("Info", infoTab);
    var infoTab = new GInfoWindowTab("Location", locTab);
    //setTimeout(function(){loadDetails(marker.data.id, locTab, infoTab);}, 0);
    map.openInfoWindowTabs(latlng, [infoTab]);
}
*/

function getLocTabHtml(data) {
    var street = data.street;
    if (street == null) {street = '';} 
    return '<div id="infoTabLocation"><div class="titleMap">'+data.title+'</div><div class="addressMap">'+data.cntry + '<br/>' + data.city +'<br/>'+ street +'</div><div class="linkMap"><a target="_parent" href="'+data.url+'" >go to '+data.type+'</a></div>';
    //return '<div id="infoTabLocation"><div class="title">'+data.title+'</div><div class="address">'+data.address+'</div><div class="nav"><a href="#" onclick="return CUSTOMMAP.mapZoom(14,'+data.lat+','+data.lon+');">'+gettext(strings[2])+'</a><a target="'+getLinkTarget()+'" href="'+getDetailsURL(data)+'">'+gettext(strings[3])+'</a></div></div>';
}
function getInfoTabHtml(data) {
    description = data.desc;
    if(!description) { description = 'There is no description.'}
    return '<div id="infoTabInfo"><div class="addressMap">'+data.desc+'</div><div class="linkMap"><a target="_parent" href="'+data.url+'" >go to '+data.type+'</a></div>';
    //return '<div id="infoTabInfo"><div class="desc">'+data.desc+'</div><div class="nav"><a target="'+getLinkTarget()+'" href="'+getDetailsURL(data)+'">'+gettext(strings[3])+'</a></div></div>';
}

function renderFromTemplate(template, params){
    var result = template;
    for(key in params) {
        if (key != 'TT') {
            var reg = new RegExp("\\[\\*"+key+"\\*\\]", "g");
            result = result.replace(reg, params[key]);
        }
    }
    return result;
}