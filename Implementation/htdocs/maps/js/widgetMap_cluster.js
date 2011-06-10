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
    if (newLevel >=19) {
        objWM.map.setZoom(newLevel -1);
        return false;
    }
    
    objWM.mainTimer = setTimeout( function(){ objWM.changeZoomLevel(oldLevel, newLevel);  }, 1000);     
    return false;
}


WidgetMap.prototype.changeZoomLevel = function(oldLevel, newLevel) {
    
    var url = this.params.actionURL;
    url = url + this.getMapParams() + this.getAdditionalParams()
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
    this.icons = {};
    this.userInit();
    this.initGmap();

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
            var url = this.params.actionURL;
            url = url + this.getMapParams() + this.getAdditionalParams()

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
    
WidgetMap.prototype.initControls = function()
{
    if (this.params.additionalControls) { // GScaleControl
        for (i=0; i < this.params.additionalControls.length ; i++){           GLargeMapControl
            if (this.params.additionalControls[i] == 'GSmallMapControl') {
                this.map.addControl(new GSmallMapControl);
            } else if (this.params.additionalControls[i] == 'GLargeMapControl') {
                this.map.addControl(new GLargeMapControl);
            } else if (this.params.additionalControls[i] == 'GLargeMapControl3D') {
                this.map.addControl(new GLargeMapControl3D);
            } else {//@TODO
                alert(this.params.additionalControls[i]);
            }        
        }
    }
        
    this.controls = new Array();
    var wmapObj;
        
    /*KML Control*/
    /*
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
    */
    this.userInitControls();
}

WidgetMap.prototype.initLayers = function () {
    this.layers = new Array();

    cnt = 1;
    for (i=0; i< this.params.layers.length; i++) {    
        this.layers[cnt] = createWMSTileLayer(this.params.layers[i].url , this.params.layers[i].wmsLayer, null, 'image/gif', null, null, null, this.params.layers[i].opacity, this.params.layers[i].copyright, this.map);
        cnt++;
    }
        
    this.layers[0] = G_NORMAL_MAP.getTileLayers()[0];   
    if (this.layers[1]) {                          
        var G_MAP_OVERLAY = createWMSOverlayMapType(this.layers, 'Overlay');
        this.map.addMapType(G_MAP_OVERLAY);
    }
    this.map.setMapType(eval(this.params.mapType));
    this.userInitLayers();
}

WidgetMap.prototype.initPosition = function () { 
    if (this.params.lat && this.params.lng) {
        this.map.setCenter(new GLatLng(this.params.lat, this.params.lng), this.params.zoom);
    } else {
        this.needRefresh = 1;
    } 
    this.userInitPosition();   
}

WidgetMap.prototype.initListeners = function () {
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
    this.userInitListeners(); 
}

      
WidgetMap.prototype.initGmap = function () { //alert('start');
    if (GBrowserIsCompatible()) { 

        this.map = new GMap2(document.getElementById('widgetContainer'+this.params.cloneId), { mapTypes:[G_NORMAL_MAP] });
    		
    	this.initControls();
            	
        this.initLayers();
        
        this.initPosition();
        
        this.initListeners();
	        
	    //LISTENER FOR CHANGING ZOOM LEVEL
           
        //DISPLAY Group or Event markers
        /*
        if (!this.params.defaultDisplayType || this.params.defaultDisplayType == 0) {//groups or events
            if (this.params.switchGEScenario == 'search'){
                this.getSearchGroupMarkers();
            } else {
                this.getGroupMarkers();
            }
        } else {
            if (this.params.switchGEScenario == 'search'){
                this.getSearchEventMarkers();
            } else {
                if (this.params.switchGEScenario == 'nda'){
                    this.getNdaEventMarkers();
                } else {
                    this.getEventMarkers();
                }
            }
        }
        */
        this.getMyMarkers(); 
    } else {
// !GBrowserIsCompatible
    }
}

WidgetMap.prototype.getAdditionalParams = function () {
    responce = '';
    // skip

    for (i=2; i< this.params.additionalParams.length; i++) {
        if ( this.params.additionalParams[i].name == 'locale' ||
             !this.params.additionalParams[i].name
         ) continue;

        // alert(this.params.additionalParams[i].name + '/' + this.params.additionalParams[i].value);
        responce = responce + '/' + this.params.additionalParams[i].name + '/' + this.params.additionalParams[i].value;
    }
    //alert(responce);

    return responce;
} 

WidgetMap.prototype.getMapParams = function () {
    url = '';    
    if (this.params.width) {
        url = url + '/width/' + this.params.width;
    }
    if (this.params.height) {
        url = url + '/height/' + this.params.height;
    }
        
    return url;
} 

WidgetMap.prototype.getViewportParams = function () {
    url = '';    
        
    var southWest = this.map.getBounds().getSouthWest();
    url = url + '/sw/' + southWest.toUrlValue();
    
    var northEast = this.map.getBounds().getNorthEast();
    url = url + '/ne/' + northEast.toUrlValue();
    url = url + '/currentZoomLevel/' + this.map.getZoom();
    return url;
} 

WidgetMap.prototype.getMyMarkers = function () {
    var url = this.params.actionURL;
    url = url + this.getMapParams() + this.getAdditionalParams() + this.getViewportParams();

    //alert(url);
    
    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.loadViewport(data, true);
    }, 'json');
}
            
/*            
WidgetMap.prototype.getNdaEventMarkers = function () {
    var url='/en/widget/getNdaEventMarkers';
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
    var url='/en/widget/getEventMarkers';
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
    var url='/en/widget/getGroupMarkers';
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
    var url='/en/widget/getSearchGroupMarkers';
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
    alert('yes');
    var url='/en/widget/getSearchEventMarkers';
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
*/

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
        var url = this.params.actionURL;
        url = url + this.getMapParams() + this.getAdditionalParams()

        //var url = this.getURL();

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

    var url='/en/widget/getKML/r/'+Math.round(Math.random()*100000000)+'/';
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
        var url='/en/widget/getKML/r/'+Math.round(Math.random()*100000000)+'/';
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
    
WidgetMap.prototype.getIcon = function (image) {
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

WidgetMap.prototype.getDefaultIcon = function () {
   icon = new GIcon(G_DEFAULT_ICON);

   return icon;
   
    var path = '/maps/img/state.png';
    return icon = this.getIcon(path);
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
                    if (true || this.isMarkerLoaded(place.lat, place.lng, place.count, layer["zoom"][0]) == false){
                        var latlng = new GLatLng(place.lat, place.lng);
                        var mOptions = {};
                        var icon = {};
                        if (place.tooltip) {
                            mOptions.title = place.tooltip;
                        }
                        
                        if (place.icon) {
                            //alert(place.icon);
                            mOptions.icon = this.getIcon(place.icon);
                            icon = this.getIcon(place.icon);
                        }
                        else {
                            mOptions.icon = this.getDefaultIcon();
                            icon = this.getDefaultIcon();
                        }

                        var wmapObj = this;
                        if (place.type != 'cluster'){
                            marker = new GMarker(latlng, mOptions);             
                            marker.params = {id:place.id,type:place.type, addparams:this.getAdditionalParams(), map:this.map};
                            GEvent.addListener(marker, "click", loadDetails);   
                        }
                        else{
                            this.setMarkerLoaded(place.lat, place.lng, place.count, layer["zoom"][0]);
                            opts = {
                              "icon": icon,
                              "clickable": true,
                              "labelText": place.count,
                              "labelOffset": null
                            };
                            // alert(place.count);
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
            this.mgr.addMarkers( markersA, layer["zoom"][0], layer["zoom"][1] );
        }
    }
    this.mgr.refresh();
}

function loadDetails(){
        var url='/en/map/getinfo/id/'+ this.params.id + '/type/' + this.params.type + this.params.addparams;
        var markerD = this;
        $.post(url, {},function(data) {
                    var infoTab = new GInfoWindowTab("Location", getLocTabHtml(data));
                    if (data.desc || data.InfoTabHtml) {
                        var locTab = new GInfoWindowTab("Info", getInfoTabHtml(data));
                        markerD.params.map.openInfoWindowTabs(markerD.getLatLng(), [infoTab, locTab]);
                    } else {
                        markerD.params.map.openInfoWindowTabs(markerD.getLatLng(), [infoTab]);
                    } 
            }
        , 'json');
}

function getLocTabHtml(data) {
	if ( data.LocTabHtml != 'undefined' ) return data.LocTabHtml;
    var street = data.street;
    if (street == null) {street = '';} 
    return '<div id="infoTabLocation"><div class="titleMap">'+data.title+'</div><div class="addressMap">'+data.cntry + '<br/>' + data.city +'<br/>'+ street +'</div><div class="linkMap"><a target="' + (data.url_target ? data.url_target : '_parent') + '" href="'+data.url+'" >' + (data.url_label ? data.url_label : 'go to '+data.type)  + '</a></div>';
}

function getInfoTabHtml(data) {
	if ( data.InfoTabHtml ) return data.InfoTabHtml;
    description = data.desc;
    if(!description) { description = 'There is no description.'}
    return '<div id="infoTabInfo"><div class="addressMap">'+data.desc+'</div><div class="linkMap"><a target="' + (data.url_target ? data.url_target : '_parent') + '" href="'+data.url+'" >' + (data.url_label ? data.url_label : 'go to '+data.type) + '</a></div>';
}